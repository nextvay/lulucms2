<?php

namespace source\modules\user\admin\controllers;

use source\modules\rbac\models\Permission;
use source\modules\rbac\models\Relation;
use Yii;
use source\models\User;
use source\models\search\UserSearch;
use source\core\back\BackController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use source\libs\Constants;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends BackController
{

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (Yii::$app->request->isAjax){
            $data=Yii::$app->request->get();
            $role=$data['role'];
            $data=User::getPermissionByRole($role);
            return json_encode($data,320);
        }

        $model = new User();
        $model->scenario='create';
        $model->status = Constants::Status_Enable;
        $model->type = 1;

        if ($model->load(Yii::$app->request->post())) {
            if($model->type==2){
                $Permission=Yii::$app->request->post()['Permission'];
                $need='';
                foreach ($Permission as $key=>$value) {
                    $need.=$key.'_'.implode(',',$value)."|";
                }
                $need=rtrim($need,'|');
                $model->permission=$need;
            }
            if($model->save()){
                 return $this->redirect(['index']);
            }else{
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario='update';
        $model->permission=Permission::getPermissionsByString($model->permission);

        if ($model->load(Yii::$app->request->post())) {
            if($model->type==2){
                $Permission=Yii::$app->request->post()['Permission'];
                $need='';
                foreach ($Permission as $key=>$value) {
                    $need.=$key.'_'.implode(',',$value)."|";
                }
                $need=rtrim($need,'|');
                $model->permission=$need;
            }else{
                $model->permission='';
            }
            if($model->save()){
                return $this->redirect(['index']);
            }else{
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
