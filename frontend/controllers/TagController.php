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
 * Tag controller
 */
class TagController extends WebController {

    public function actionIndex() {
        $cache = Yii::$app->cache;
        $tagId = $this->input('id', 0);
        if(!$tagId){
            return $this->message(['msg' => '没有标签id']);
        }
        //标签信息
        $tagInfo = Tag::find()->where(['id' => $tagId])->one();
        if(!$tagInfo || $tagInfo['status']!=1){
            return $this->message(['msg' => '标签不存在']);
        }
        //查看该标签下是否在 artic tag 表中
        $count = ArticleTag::find()->where(['tid' => $tagId])->count();
        if(!$count){
            return $this->message(['msg' => '该标签下没有内容']);
        }
        $page = $this->input('page', 0);
        $skey = 'article-list-'.$tagId.'-'. $page;
        //测试删除
        if($this->input('t', '')){
            $cache->flush();
        }
        //getOrSet yii2.0.11版本 才有，我是直接覆盖了caching文件夹
        $cacheData = $cache->getOrSet($skey, function () use($tagId) {
            return Article::getArticleList($tagId);
        },3600);
        //标签云
        $tagList = $cache->getOrSet('tag-list-cloud', function(){
            return Tag::getTagCloudList();
        }, 7200);
        //随机打乱
        shuffle($tagList);
        //右侧内容
        $rightInfo = $cache->getOrSet('right-info', function(){
            return CommonController::getRightInfo();
        }, 14400);
        //友链
        $friendLinkList = $cache->getOrSet('friend-link', function(){
            return CommonController::getFriendLink();
        }, 28800);

        return $this->render('index', [
            'tagInfo' => $tagInfo,
            'defaultArticlItemImg' => $this->defaultArticlItemImg,
            'pages' => $cacheData['pages'],
            'dataList' => $cacheData['articleList'],
            'tagList' => $tagList,
            'topList' => $rightInfo['topList'],
            'recommendList' => $rightInfo['recommendList'],
            'friendLinkList' => $friendLinkList,
        ]);
    }
    
}
