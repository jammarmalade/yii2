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
            return $this->getArticleList();
        },3600);
        //标签云
        $tagList = $cache->getOrSet('tag-list-cloud', function(){
            return $this->getTagCloudList();
        }, 7200);
        //随机打乱
        shuffle($tagList);
        //右侧内容
        $rightInfo = $cache->getOrSet('right-info', function(){
            return $this->getRightInfo();
        }, 14400);
        //友链
        $friendLinkList = $cache->getOrSet('friend-link', function(){
            return $this->getFriendLink();
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
    //友链
    private function getFriendLink(){
        $list = [
            ['url' => 'https://www.baidu.com/', 'name' => '百度'],
            ['url' => 'http://www.yiichina.com/doc/guide/2.0', 'name' => 'Yii2.0权威指南'],
            ['url' => 'https://www.aliyun.com/', 'name' => '阿里云'],
            ['url' => 'http://www.bootcss.com/', 'name' => 'Bootstrap'],
            ['url' => 'http://layer.layui.com/', 'name' => 'layer弹层'],
        ];
        return $list;
    }
    //右侧信息
    private function getRightInfo(){
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
    //标签云
    public function getTagCloudList(){
        $limit = 100;
        $tableName = ArticleTag::tableName();
        $sql = 'SELECT tid FROM '.$tableName.' WHERE id >= ((SELECT MAX(id) FROM '.$tableName.')-(SELECT MIN(id) FROM '.$tableName.')) * RAND() + (SELECT MIN(id) FROM '.$tableName.') LIMIT '.$limit;
        $tagIds = Yii::$app->db->createCommand($sql)->queryColumn();
        $tagList = Tag::find()->select('id,name')->where(['in','id', array_values(array_unique($tagIds))])->asArray()->all();
        return $tagList;
    }
    //获取文章列表
    private function getArticleList() {
        $limit = 10;
        //文章列表
        $articleQuery = Article::find()->where(['status' => 1]);
        $count = $articleQuery->count();
        $pages = new Pagination(['totalCount' => $count, 'pageSize' => $limit]);
        $field = 'id,uid,username,subject,description,view_auth,image_id,time_create,like,view,comment';
        $articleList = $articleQuery->select($field)->orderBy('time_create DESC')->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        $imgList = $imageIds = $aids = $groupTagList = [];
        if (is_array($articleList)) {
            $imageIds = array_filter(array_column($articleList, 'image_id'));
            $aids = array_filter(array_column($articleList, 'id'));
        }
        if ($imageIds) {
            //查询文章图片
            $imgList = TableImage::find()->select('sid,path,thumb')->where(['in', 'id', $imageIds])->asArray()->indexBy('sid')->all();
        }
        //标签
        if ($aids) {
            $tagList = ArticleTag::find()->from(ArticleTag::tableName() . ' as at')
                            ->join('LEFT JOIN', Tag::tableName() . ' as t', 't.id = at.tid')
                            ->where(['in', 'at.aid', $aids])->select('at.id,at.tid,at.aid,t.name as tagname')->asArray()->all();
            //按照aid 分组
            foreach ($tagList as $k => $v) {
                $groupTagList[$v['aid']][] = $v;
            }
        }
        if (is_array($articleList)) {
            foreach ($articleList as $k => $v) {
                $v['date'] = substr($v['time_create'], 0, 10);
                $v['faceUrl'] = $this->defaultArticlItemImg;
                $v['description'] = tools::textarea2br($v['description']);
                if (isset($imgList[$v['id']])) {
                    $tmpImage = $imgList[$v['id']];
                    $v['faceUrl'] = $this->imageUrl . $tmpImage['path'];
                    if ($tmpImage['thumb']) {
                        $v['faceUrl'] .= '.thumb.jpg';
                    }
                }
                $v['tagList'] = [];
                if (isset($groupTagList[$v['id']])) {
                    $v['tagList'] = $groupTagList[$v['id']];
                }
                $articleList[$k] = $v;
            }
        }
        $cacheData['articleList'] = $articleList;
        $cacheData['pages'] = $pages;
        return $cacheData;
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
