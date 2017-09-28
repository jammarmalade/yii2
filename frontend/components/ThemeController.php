<?php

namespace frontend\components;

use Yii;
use yii\base\Object;
/**
 * 主题控制器
 */
class ThemeController extends \yii\base\ActionFilter
{
    public function init ()
    {
        $theme = 'xbt';

        Yii::$app->view->theme = Yii::createObject([
            'class' => 'yii\base\Theme',
            'basePath' => "@app/web/static/themes/$theme",//资源目录
            'baseUrl' => "/static/themes/$theme",//资源url
            'pathMap' => [
                '@app/views' => [ 
                    "@app/themes/$theme",
                ]
            ],
        ]);
    }
}