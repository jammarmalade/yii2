<?php

namespace frontend\controllers;

use Yii;
use frontend\components\WebController;
use common\models\Article;
use backend\models\Tag;
use common\models\ArticleTag;
use backend\models\Image as TableImage;
use frontend\components\Functions as tools;
use common\models\Comment;

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
        //若是ajax，则是获取评论
        if($this->ajax){
            //查询评论列表
            $commentskey = 'comment-list-'.$aid.'-'.$this->input('page', 0);
            $defaultHead = $this->defaultHeadImg;
            $commentData = $cache->getOrSet($commentskey, function () use($aid,$defaultHead) {
                return Comment::getList($aid, $defaultHead);
            },300);
            $commentHtml = $this->renderPartial('../comment/_commentList', ['pages' => $commentData['pages'],'dataList'=>$commentData['list']]);
            return $this->ajaxReturn($commentHtml, '', true);
        }
        $skey = 'article-' . $aid;
        //测试删除
        if($this->input('t', '')){
            $cache->flush();
        }
        //getOrSet yii2.0.11版本 才有，我是直接覆盖了caching文件夹
        $articleInfo = $cache->getOrSet($skey, function () use($aid) {
            return $this->getArticleInfo($aid);
        },3600);
        //若是手机端，就替换为手机端的内容
        if($this->mobile && isset($articleInfo['mobileContent'])){
            $articleInfo['content'] = $articleInfo['mobileContent'];
        }
        //是否删除
        if($articleInfo['status']!=1){
            if(Yii::$app->user->isGuest){
                return $this->message(['msg'=>'该内容不存在']);
            }
            if(Yii::$app->user->identity->id != 1){
                return $this->message(['msg'=>'该内容已被删除']);
            }
        }
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
            tools::setCookie($viewCounter, $aid, 120);
        }
        //查询评论列表
        $commentskey = 'comment-list-'.$aid.'-'.$this->input('page', 0);
        $defaultHead = $this->defaultHeadImg;
        $commentData = $cache->getOrSet($commentskey, function () use($aid,$defaultHead) {
            return Comment::getList($aid, $defaultHead);
        },300);

        return $this->render('index', [
            'articleInfo' => $articleInfo,
            'selfUrl' => Yii::$app->request->hostInfo.Yii::$app->request->url,
            'commentData' => $commentData,
        ]);
    }
    private function getArticleInfo($aid){
        $articleInfo = Article::find()->where(['id'=>$aid])->asArray()->one();
        $articleInfo['date'] = substr($articleInfo['time_create'], 0, 16);
        //标签
        $articleInfo['tagList'] = ArticleTag::find()->from(ArticleTag::tableName() . ' as at')
                ->join('LEFT JOIN', Tag::tableName() . ' as t', 't.id = at.tid')
                ->where(['at.aid'=>$aid])->select('at.id,at.tid,at.aid,t.name as tagname')->asArray()->all();
        //图片列表
        $articleInfo['imgList'] =[];
        if($articleInfo['image_id']){
            $tmpArr = TableImage::replaceImgCode($articleInfo,'show');
            $articleInfo['mobileContent'] = $tmpArr['mobileContent'];
            $articleInfo['content'] = $tmpArr['content'];
            $articleInfo['imgList'] = $tmpArr['imgList'];
        }else{
            //替换百度编辑的内容为https
            $articleInfo['content'] = preg_replace('#src="http://img.baidu.com([^"]+?)"#','src="https://img.baidu.com$1"',$articleInfo['content']);
        }

        return $articleInfo;
    }
    //提交链接到百度
    public function actionBdlink(){
        if (!$this->uid) {
            return $this->ajaxReturn('', '请先登录');
        }
        $id = $this->input('post.id', 0);
        if (!$id) {
            return $this->ajaxReturn('', '数据错误 id');
        }
        $type = $this->input('get.type', 0);
        if ($type == 0) {
            $apiType = 'urls';
        } else {
            $apiType = 'update';
        }
        $articleUrl = 'https://blog.jam00.com/article/info/' . $id . '.html';
        $apiUrl = 'http://data.zz.baidu.com/'.$apiType.'?site=https://blog.jam00.com&token=oCdtFTedue07WkJ3';
        $urls = [$articleUrl];
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $apiUrl,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => implode("\n", $urls),
            CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
        );
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        $resArr = json_decode($result, true);
        if(isset($resArr['error'])){
            return $this->ajaxReturn('', '提交链接到百度失败：【'.$resArr['error'].'】'.$resArr['message']);
        }else{
            if($type == 0) {
                Article::updateAll(['bdlink' => 1], ['id' => $id]);
            }
            return $this->ajaxReturn('', '提交链接到百度成功，当天剩余推送条数 【'.$resArr['remain'].'】');
        }
    }
}
