<?php

namespace frontend\controllers;

use Yii;
use frontend\models\ContactForm;
use frontend\components\WebController;
use common\models\Article;
use backend\models\Tag;
use common\models\ArticleTag;
use yii\data\Pagination;
use backend\models\Image as TableImage;
use frontend\components\Functions as tools;
use frontend\controllers\CommonController;

/**
 * Site controller
 */
class SiteController extends WebController {

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

    public function actionIndex() {
        $cache = Yii::$app->cache;
        $page = $this->input('page', 0);
        $skey = 'article-list-' . $page;
        //测试删除
        if($this->input('t', '')){
            $cache->flush();
        }
        //getOrSet yii2.0.11版本 才有，我是直接覆盖了caching文件夹
        $cacheData = $cache->getOrSet($skey, function () {
            return Article::getArticleList();
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
            'defaultArticlItemImg' => $this->defaultArticlItemImg,
            'pages' => $cacheData['pages'],
            'dataList' => $cacheData['articleList'],
            'tagList' => $tagList,
            'topList' => $rightInfo['topList'],
            'recommendList' => $rightInfo['recommendList'],
            'friendLinkList' => $friendLinkList,
        ]);
    }
    

    public function actionContact() {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                        'model' => $model,
            ]);
        }
    }

    public function actionAbout() {
        return $this->render('about');
    }

}
