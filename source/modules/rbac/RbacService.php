<?php
namespace source\modules\rbac;

use source\modules\menu\models\Menu;
use source\modules\rbac\models\Role;
use source\modules\rbac\models\Assignment;
use source\modules\rbac\models\Permission;
use source\modules\rbac\models\Relation;
use Yii;
use yii\db\Query;
use source\modules\rbac\models\Category;
use source\LuLu;
use source\core\front\FrontApplication;
use source\models\User;
use source\libs\Utility;

class RbacService extends \source\core\modularity\ModuleService
{

    const CachePrefix = 'rbac_';

    private $assignmentTable;

    private $roleTable;

    private $permissionTable;

    private $relationTable;

    private $ruleNamespace = '\source\modules\rbac\rules\\';

    public function init()
    {
        parent::init();
        
        $this->assignmentTable = Assignment::tableName();
        $this->roleTable = Role::tableName();
        $this->permissionTable = Permission::tableName();
        $this->relationTable = Relation::tableName();
    }

    public function getServiceId()
    {
        return 'rbacService';
    }

    public function getRolesByUser($username)
    {
        if($username===LuLu::getIdentity()->username)
        {
        
            $role = LuLu::getIdentity()->role;
        }
        else
        {
            $user = User::findOne(['username'=>$username]);
            $role=$user->role;
        }
        return $role;
        
//         $query = new Query();
//         $query->select([
//             'r.id', 
//             'r.category', 
//             'r.name', 
//             'r.description', 
//             'r.is_system', 
//             'r.sort_num'
//         ]);
//         $query->from([
//             'r' => $this->roleTable, 
//             'a' => $this->assignmentTable
//         ]);
//         $query->where('r.id=a.role');
//         $query->andWhere([
//             'a.user' => $username
//         ]);
//         $rows = $query->indexBy('id')->all();
//         return $rows;
    }

    public function getParentMenuByRole($role){
        $all=Relation::find()->where(['role'=>$role])->select('menu_id')->andWhere(['!=','menu_id',0])->groupBy('menu_id')->asArray()->all();
        $menu_id=array_column($all,'menu_id');
        $menus=Menu::find()->where(['id'=>$menu_id])->orderBy("sort_num asc")->asArray()->all();
        $type=LuLu::getIdentity()->type;
        if ($type==2){
            $permission=json_decode(Permission::getPermissionsByString(LuLu::getIdentity()->permission),true);
            $need=[];
            foreach ($menus as $menu) {
                $url=(Menu::find()->where(['parent_id'=>$menu['id']])->select("group_concat(url) as url")->asArray()->one())['url'];
                foreach ($permission as $key=>$p) {
                    $true1=strpos($key,$url);
                    $true2=strpos($url,$key);
                    if(($true1 ||$true2) && !in_array($menu,$need)){
                        $need[]=$menu;
                    }
                }


            }
           $menus=$need;
        }
        return $menus;
    }

    public function getPermissionsByUser($username = null)
    {
        $role = $this->getRolesByUser($username);
       
        return $this->getPermissionsByRole($role);
        
//        //for assignmentTable
//         $query = new Query();
//         $query->select([
//             'p.id', 
//             'p.category', 
//             'p.name', 
//             'p.description', 
//             'p.form', 
//             'p.default_value', 
//             'p.rule', 
//             'p.sort_num', 
//             'r.role', 
//             'r.value'
//         ]);
//         $query->from([
//             'p' => $this->permissionTable, 
//             'r' => $this->relationTable, 
//             'a' => $this->assignmentTable
//         ]);
//         $query->where('p.id=r.permission and r.role=a.role');
//         $query->andWhere([
//             'a.user' => $user
//         ]);
//         $rows = $query->all();
//         return $this->convertPermissionValue($rows);
    }

    public function getPermissionsByRole($role, $fromCache = true)
    {
        $cacheKey = self::CachePrefix . $role;
        
        $value = $fromCache ? LuLu::getCache($cacheKey) : false;
        if ($value === false)
        {
            $query = new Query();
            $query->select([
                'p.id', 
                'p.category', 
                'p.name', 
                'p.description', 
                'p.form', 
                'p.default_value', 
                'p.rule', 
                'p.sort_num', 
                'r.role', 
                'r.value'
            ]);
            $query->from([
                'p' => $this->permissionTable, 
                'r' => $this->relationTable
            ]);
            $query->where('r.permission=p.id');
            $query->andWhere([
                'r.role' => $role
            ]);
            $rows = $query->all();
            $value = $this->convertPermissionValue($rows);
            
            LuLu::setCache($cacheKey, $value);
        }
        return $value;
    }

    private function convertPermissionValue($rows)
    {
        $ret = [];
        if ($rows === null)
        {
            return $ret;
        }
        foreach ($rows as $row)
        {
            $form = intval($row['form']);
            if ($form === Permission::Form_Boolean)
            {
                $v = Utility::isTrue($row['value']);
            }
            else if ($form === Permission::Form_CheckboxList)
            {
                $v = explode(',', $row['value']);
            }
            else
            {
                $v = $row['value'];
            }
            $row['value'] = $v;
            $ret[$row['id']][] = $row;
        }
        return $ret;
    }

    public function checkPermission($permission = null, $params = [], $username = null)
    {
        if (empty($permission))
        {
            $permission = LuLu::getApp()->controller->uniqueId;
        }
        if (empty($username))
        {
            $username = LuLu::getIdentity()->username;
        }
        $rows = $this->getPermissionsByUser($username);

        if (! isset($rows[$permission]))
        {
            return false;
        }

        return $this->executeRule($rows[$permission], $params,$username);
    }

    public function checkHomePermission($permission = null, $params = [], $user = null)
    {
        if ($user === null)
        {
            $user = LuLu::getIdentity()->username;
        }
        if ($permission === null)
        {
            $permission = LuLu::getApp()->controller->uniqueId;
        }
        $permission = 'home_' . $permission;
        
        $rows = $this->getPermissionsByUser($user);
        
        if (! isset($rows[$permission]))
        {
            return false;
        }
        
        return $this->executeRule($rows[$permission], $params, $user);
    }

    private function executeRule($permission, $params = [], $user)
    {
        if (is_array($permission))
        {
            foreach ($permission as $p)
            {
                if (empty($p['rule']))
                {
                    return true;
                }
                $ruleClass = $this->ruleNamespace . $p['rule'];
                
                $ruleInstance = new $ruleClass();
                $ret = $ruleInstance->execute($p, $params, $user);
                if ($ret === true)
                {
                    return true;
                }
            }
            return false;
        }
        else
        {
            if (empty($permission['rule']))
            {
                return true;
            }
            
            $ruleClass = $this->ruleNamespace . $permission['rule'];
            
            $ruleInstance = new $ruleClass();
            return $ruleInstance->execute($permission, $params, $user);
        }
    }

    public function getAllRoles()
    {
        return Role::buildOptions();
    }

    public function checkMinPermission()
    {
        $permission=LuLu::getIdentity()->permission;
        $permission=json_decode(Permission::getPermissionsByString($permission),true);
        $route=LuLu::getApp()->requestedRoute;
        $action=LuLu::getApp()->requestedAction->id;
//        var_dump($route,$action,$permission);
        foreach ($permission as $key=>$per) {
            if(strpos($key,$route)!==false || strpos($route,$key)!==false ){
                $method = LuLu::getApp()->request->method;
                $method = strtolower($method);
                if(in_array($action,$per) || in_array($action . ':' . $method, $per) ){
                    return true;
                }
            }

        }
        return false;
    }
}
