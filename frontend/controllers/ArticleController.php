<?php

namespace frontend\controllers;

use Yii;
use frontend\components\WebController;
use common\models\Article;
use backend\models\Tag;
use common\models\ArticleTag;
use backend\models\Image as TableImage;
use frontend\components\Functions as tools;
use yii\web\Cookie;

/**
 * Article controller
 */
class ArticleController extends WebController {

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionInfo() {
        $cache = Yii::$app->cache;
        $aid = $this->input('id', 0);
        $skey = 'article-' . $aid;
        //测试删除
        $cache->flush();
        //getOrSet yii2.0.11版本 才有，我是直接覆盖了caching文件夹
        $articleInfo = $cache->getOrSet($skey, function () use($aid) {
            return $this->getArticleInfo($aid);
        },3600);
        //若是存在密码
        if ($articleInfo['view_auth']) {
            $cookieKey = 'auth-'.$aid;
            //读取cookie
            $cookieValue = tools::getCookie($cookieKey, '');
            if($cookieValue){
                $inputAuth = $cookieValue;
            }else{
               $inputAuth = $this->input('post.auth', '');
                if($inputAuth==''){
                    $inputAuth = $this->input('get.auth', '');
                } 
            }
            if ($inputAuth != $articleInfo['view_auth']) {
                $msg = $inputAuth != '' ? '密码错误' : '';
                return $this->render('articlAuth', ['msg' => $msg]);
            }
            if($inputAuth){
                //保存cookie
                tools::setCookie($cookieKey, $inputAuth, 86400);
            }
        }
        //查看计数
        $viewCounter = 'view-'.$aid;
        if(tools::getCookie($viewCounter,NULL) == NULL){
            //添加查看数
            Article::updateAll(['view' => $articleInfo['view'] + 1], "id = $aid");
            tools::setCookie($viewCounter, $aid, 300);
        }

        return $this->render('index', [
            'articleInfo' => $articleInfo,
        ]);
    }
    private function getArticleInfo($aid){
        $articleInfo = Article::find()->where(['id'=>$aid])->asArray()->one();
        $articleInfo['date'] = substr($articleInfo['time_create'], 0, 10);
        //标签
        $articleInfo['tagList'] = ArticleTag::find()->from(ArticleTag::tableName() . ' as at')
                ->join('LEFT JOIN', Tag::tableName() . ' as t', 't.id = at.tid')
                ->where(['at.aid'=>$aid])->select('at.id,at.tid,at.aid,t.name as tagname')->asArray()->all();
        //图片列表
        $articleInfo['imgList'] =[]; 
        if($articleInfo['image_id']){
            $tmpArr = TableImage::replaceImgCode($articleInfo,'show');
            $articleInfo['content'] = $tmpArr['content'];
            $articleInfo['imgList'] = $tmpArr['imgList'];
        }
        return $articleInfo;
    }
    
}
