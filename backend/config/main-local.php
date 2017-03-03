<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'jFs0ZR2OL1NCVQyweqohPJJtYNtB3nrA',
        ],
    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1'], 
        'generators' => [ 
            'crud' => [ //生成器名称 
                'class' => 'yii\gii\generators\crud\Generator', 
                'templates' => [ //设置我们自己的模板 
                    //模板名 => 模板路径 
                    'myCrud' => '@app/components/gii-custom/crud/mycurd', 
                ] 
            ],
            'model' => [ //生成器名称 
                'class' => 'yii\gii\generators\model\Generator', 
                'templates' => [
                    //模板名 => 模板路径 
                    'mymodel' => '@app/components/gii-custom/model/mymodel', 
                ] 
            ],
        ], 
    ];
}

return $config;
