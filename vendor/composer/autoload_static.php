<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb9d80fc47738ad15694c6cb62354a8d2
{
    public static $files = array (
        '2cffec82183ee1cea088009cef9a6fc3' => __DIR__ . '/..' . '/ezyang/htmlpurifier/library/HTMLPurifier.composer.php',
        '2c102faa651ef8ea5874edb585946bce' => __DIR__ . '/..' . '/swiftmailer/swiftmailer/lib/swift_required.php',
    );

    public static $prefixLengthsPsr4 = array (
        'y' => 
        array (
            'yii\\swiftmailer\\' => 16,
            'yii\\gii\\' => 8,
            'yii\\faker\\' => 10,
            'yii\\debug\\' => 10,
            'yii\\composer\\' => 13,
            'yii\\codeception\\' => 16,
            'yii\\bootstrap\\' => 14,
            'yii\\' => 4,
        ),
        'v' => 
        array (
            'vary\\' => 5,
        ),
        'c' => 
        array (
            'cebe\\markdown\\' => 14,
        ),
        'S' => 
        array (
            'Swoole\\' => 7,
        ),
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'yii\\swiftmailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/yiisoft/yii2-swiftmailer',
        ),
        'yii\\gii\\' => 
        array (
            0 => __DIR__ . '/..' . '/yiisoft/yii2-gii',
        ),
        'yii\\faker\\' => 
        array (
            0 => __DIR__ . '/..' . '/yiisoft/yii2-faker',
        ),
        'yii\\debug\\' => 
        array (
            0 => __DIR__ . '/..' . '/yiisoft/yii2-debug',
        ),
        'yii\\composer\\' => 
        array (
            0 => __DIR__ . '/..' . '/yiisoft/yii2-composer',
        ),
        'yii\\codeception\\' => 
        array (
            0 => __DIR__ . '/..' . '/yiisoft/yii2-codeception',
        ),
        'yii\\bootstrap\\' => 
        array (
            0 => __DIR__ . '/..' . '/yiisoft/yii2-bootstrap',
        ),
        'yii\\' => 
        array (
            0 => __DIR__ . '/..' . '/yiisoft/yii2',
        ),
        'vary\\' => 
        array (
            0 => __DIR__ . '/..' . '/vary',
        ),
        'cebe\\markdown\\' => 
        array (
            0 => __DIR__ . '/..' . '/cebe/markdown',
        ),
        'Swoole\\' => 
        array (
            0 => __DIR__ . '/..' . '/Swoole',
        ),
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static $prefixesPsr0 = array (
        'H' => 
        array (
            'HTMLPurifier' => 
            array (
                0 => __DIR__ . '/..' . '/ezyang/htmlpurifier/library',
            ),
        ),
        'F' => 
        array (
            'Faker\\PHPUnit' => 
            array (
                0 => __DIR__ . '/..' . '/fzaninotto/faker/test',
            ),
            'Faker' => 
            array (
                0 => __DIR__ . '/..' . '/fzaninotto/faker/src',
            ),
        ),
        'D' => 
        array (
            'Diff' => 
            array (
                0 => __DIR__ . '/..' . '/phpspec/php-diff/lib',
            ),
        ),
    );

    public static $classMap = array (
        'source\\helpers\\DateTimeHelper' => __DIR__ . '/../..' . '/source/Helpers/DateTimeHelper.php',
        'yii\\helpers\\ArrayHelper' => __DIR__ . '/../..' . '/source/Helpers/ArrayHelper.php',
        'yii\\helpers\\Console' => __DIR__ . '/../..' . '/source/Helpers/Console.php',
        'yii\\helpers\\FileHelper' => __DIR__ . '/../..' . '/source/Helpers/FileHelper.php',
        'yii\\helpers\\FormatConverter' => __DIR__ . '/../..' . '/source/Helpers/FormatConverter.php',
        'yii\\helpers\\Html' => __DIR__ . '/../..' . '/source/Helpers/Html.php',
        'yii\\helpers\\HtmlPurifier' => __DIR__ . '/../..' . '/source/Helpers/HtmlPurifier.php',
        'yii\\helpers\\Inflector' => __DIR__ . '/../..' . '/source/Helpers/Inflector.php',
        'yii\\helpers\\Json' => __DIR__ . '/../..' . '/source/Helpers/Json.php',
        'yii\\helpers\\Markdown' => __DIR__ . '/../..' . '/source/Helpers/Markdown.php',
        'yii\\helpers\\StringHelper' => __DIR__ . '/../..' . '/source/Helpers/StringHelper.php',
        'yii\\helpers\\Url' => __DIR__ . '/../..' . '/source/Helpers/Url.php',
        'yii\\helpers\\VarDumper' => __DIR__ . '/../..' . '/source/Helpers/VarDumper.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb9d80fc47738ad15694c6cb62354a8d2::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb9d80fc47738ad15694c6cb62354a8d2::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitb9d80fc47738ad15694c6cb62354a8d2::$prefixesPsr0;
            $loader->classMap = ComposerStaticInitb9d80fc47738ad15694c6cb62354a8d2::$classMap;

        }, null, ClassLoader::class);
    }
}
