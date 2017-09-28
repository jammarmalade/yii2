<?php
$params = array_merge(
        require(__DIR__ . '/../../common/config/params.php'), 
        require(__DIR__ . '/../../common/config/params-local.php'), 
        require(__DIR__ . '/params.php'), 
        require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'language' => 'zh-CN',
    'modules' => [
        'admin' => [
            'class' => 'mdm\admin\Module',
//            'layout' => 'left-menu',
            'mainLayout' => '@app/themes/ace/layouts/main.php',
        ],
    ],
    'timeZone' => 'Asia/Shanghai',
    'aliases' => [
        '@mdm/admin' => '@app/modules/yii2-admin',
        '@uploads' => '@app/../uploads/',//上传目录
    ],
    'components' => [
        'user' => [
            'identityClass' => 'backend\models\UserBackend',
            'enableAutoLogin' => true,
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=jam00',
            'username' => 'root',
            'password' => '123456',
            'charset' => 'utf8',
            'tablePrefix' => 't_',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest'],
        ],
//        'urlManager' => [
//            'enablePrettyUrl' => true,
//            'showScriptName' => false,
//            'enableStrictParsing' => false,
//            'suffix' => '.html',
//            'rules' => [
//                '<controller:\w+>/<action:\w+>/<page:\d+>' => '<controller>/<action>',
//                "<controller:\w+>/<action:\w+>" => "<controller>/<action>",
//            ],
//        ],
        //配置主题
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@app/views' => [
                        '@app/themes/ace',
                    ]
                ],
            ],
        ],
    ],
    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
            //这里是允许访问的action
            //controller/action
//            '*',//测试时开启
            'site/logout',
            'upload/*',
        ],
    ],
    'params' => $params,
];
