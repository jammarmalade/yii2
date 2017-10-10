<?php

namespace frontend\controllers;

use Yii;
use frontend\components\WebController;
use common\models\Article;
use backend\models\Tag;
use common\models\ArticleTag;
use backend\models\Image as TableImage;
use frontend\components\Functions as tools;

/**
 * Common controller
 * 一些通用的获取数据
 */
class CommonController extends WebController {

    //右侧信息
    public static function getRightInfo(){
        //点击排行
        $topList = Article::find()->select('id,subject,view')->where(['status'=>1])->limit(6)->orderBy('view DESC')->asArray()->all();
        //推荐
        $recommendList = Article::find()->select('id,subject,view')->where(['recommend'=>1])->limit(6)->orderBy('time_create DESC')->asArray()->all();
        
        $resData = [
            'topList' => $topList,
            'recommendList' => $recommendList,
        ];
        return $resData;
    }
    //友链
    public static function getFriendLink(){
        $list = [
            ['url' => 'https://www.baidu.com/', 'name' => '百度'],
            ['url' => 'http://www.yiichina.com/doc/guide/2.0', 'name' => 'Yii2.0权威指南'],
            ['url' => 'https://www.aliyun.com/', 'name' => '阿里云'],
            ['url' => 'http://www.bootcss.com/', 'name' => 'Bootstrap'],
            ['url' => 'http://layer.layui.com/', 'name' => 'layer弹层'],
        ];
        return $list;
    }
    
}
