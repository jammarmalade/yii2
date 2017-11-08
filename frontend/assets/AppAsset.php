<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;
use Yii;
/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/common.min.css',
    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    //跟随 theme 主题配置而改变
    public function init(){
        parent::init();
        $this->basePath = Yii::$app->view->theme->basePath;
        $this->baseUrl = Yii::$app->view->theme->baseUrl;
    }

    //view 层使用 AppAsset::addScript($this,Yii::$app->request->baseUrl."/css/main.js");
    //定义按需加载JS方法，注意加载顺序在最后
    public static function addScript($view, $jsfile,$position = \yii\web\View::POS_END) {
        $baseUrl = Yii::$app->view->theme->baseUrl;
        if(strpos($jsfile, 'http')===false){
            $jsfile = $baseUrl.'/js/'.$jsfile;
        }
        $view->registerJsFile($jsfile, [AppAsset::className(), "depends" => "frontend\assets\AppAsset",'position'=>$position]);
    }

    //定义按需加载css方法，注意加载顺序在最后
    public static function addCss($view, $cssfile,$fullPath = false,$position = \yii\web\View::POS_HEAD) {
        $baseUrl = Yii::$app->view->theme->baseUrl;
        if(strpos($cssfile, 'http')===false){
            $tmpPath = $fullPath ? '' : '/css/';
            $cssfile = $baseUrl.$tmpPath.$cssfile;
        }
        $view->registerCssFile($cssfile, [AppAsset::className(), "depends" => "frontend\assets\AppAsset",'position'=>$position]);
    }
}
