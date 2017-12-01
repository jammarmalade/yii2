<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'language' => 'zh-CN',
    'timeZone' => 'Asia/Shanghai',
    'aliases' => [
        '@uploads' => '@app/../uploads/',//上传目录
    ],
    'components' => [
        'user' => [
            'identityClass' => 'backend\models\UserBackend',
            'enableAutoLogin' => true,
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
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=jam00',
            'username' => 'root',
            'password' => '123456',
            'charset' => 'utf8',
            'tablePrefix' => 't_',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        //缓存配置
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false, //这句一定有，false发送邮件，true只是生成邮件在runtime文件夹下，不发邮件
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.163.com', //每种邮箱的host配置不一样
                'username' => 'jammarmalade@163.com',
                'password' => 'lxg0806',
                'port' => '994',
                'encryption' => 'ssl',
            ],
            'messageConfig' => [
                'charset' => 'UTF-8',
                'from' => ['jammarmalde@163.com' => 'jammarmalade']
            ],
        ],
    //配置主题
//        'view' => [
//            'theme' => [
//                'basePath' => '@webroot/static/themes/xbt',//资源目录
//                'baseUrl' => '@web/static/themes/xbt',//资源url
//                'pathMap' => [
//                    '@app/views' => [
//                        '@app/themes/xbt',
//                    ]
//                ],
//            ],
//        ],
    ],
    //多主题设置
    'as theme' => [
        'class' => 'frontend\components\ThemeController',
    ],
    'params' => $params,
];
