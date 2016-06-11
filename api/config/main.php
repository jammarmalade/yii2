<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => ['log'],
    'defaultRoute' => 'v1/weather',//默认控制器
    'layout' => false,//禁用布局
    //模块
    'modules' => [
        'v1' => [
            'class' => 'api\modules\v1\Module',
            'basePath' => dirname(__DIR__).'/modules/v1/',
        ],
        'v2' => [
            'class' => 'api\modules\v2\Module',
            'basePath' => dirname(__DIR__).'/modules/v2/',
        ],
    ],
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=test',
            'username' => 'root',
            'password' => '123456',
            'charset' => 'utf8',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'directoryLevel'=>'2',   //缓存文件的目录深度
        ],
        'request' => [
            'enableCookieValidation' => true,
            //必须配置
            'cookieValidationKey' => 'jam123456',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true, // 启用美化URL
            'enableStrictParsing' => true, // 是否执行严格的url解析
//            'suffix' => '.html',//后缀
            'showScriptName' => false, //隐藏index.php  
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => [
                        'v1/weather',
                    ],
//                    'tokens' => [
//                        '{id}' => '<id:\\d[\\d,]*>',
//                    ],
                    'patterns' => [
                        'GET province' => 'province',//获取省
                        'GET city/<id>' => 'city',//获取城市 省id
                        'GET area/<id>' => 'area',//获取区县 城市id
                        'GET weather/<id>' => 'weather',//获取天气情况 城市天气代号id
                    ],
                    //扩展操作
                    'extraPatterns' => [
                        
                    ],
                    'pluralize' => false,//不启用复数形式访问 weathers
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => [
                        'v2/weather',
                    ]
                ],
//                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
            ]
        ],
        
        //测试
        
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false, // API change to false  
            'enableSession' => false,  // API ++  
            'loginUrl' => null // API ++  
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
            'errorAction' => 'apiactive/error',
//            'errorAction' => 'site/error',
        ],
    ],
    'params' => $params,
];
