<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\Article;
use backend\models\Tag;
use common\models\ArticleTag;
use backend\models\Image as TableImage;
use frontend\components\Functions as tools;
use common\models\FriendLink;

/**
 * Common controller
 * 一些通用的获取数据
 */
class CommonController extends Controller {

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
        $list = FriendLink::find()->where(['status' => 1])->select('id,url,name')->orderBy('order_number DESC')->asArray()->all();
        return $list;
    }
    
}
