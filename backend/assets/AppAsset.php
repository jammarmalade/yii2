<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\assets;

use yii;
use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
//        'static/css/site.css',
    ];
    public $js = [
//        'static/js/jquery.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    //view 层使用 AppAsset::addScript($this,Yii::$app->request->baseUrl."/css/main.js");
    //定义按需加载JS方法，注意加载顺序在最后  
    public static function addScript($view, $jsfile,$position = \yii\web\View::POS_END) {
        if(strpos($jsfile, 'http')===false){
            $jsfile = Yii::$app->request->baseUrl.'/static/js/'.$jsfile;
        }
        $view->registerJsFile($jsfile, [AppAsset::className(), "depends" => "backend\assets\AppAsset",'position'=>$position]);
    }

    //定义按需加载css方法，注意加载顺序在最后  
    public static function addCss($view, $cssfile,$fullPath = false) {
        if(strpos($cssfile, 'http')===false){
            $tmpPath = $fullPath ? '' : '/static/css/';
            $cssfile = Yii::$app->request->baseUrl.$tmpPath.$cssfile;
        }
        $view->registerCssFile($cssfile, [AppAsset::className(), "depends" => "backend\assets\AppAsset"]);
    }
}
