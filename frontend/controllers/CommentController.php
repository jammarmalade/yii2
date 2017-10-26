<?php

namespace frontend\controllers;

use Yii;
use frontend\components\WebController;
use common\models\Comment;
use common\models\Article;
/**
 * Comment controller
 * 评论
 */
class CommentController extends WebController {

    public function actionAdd(){
        if(!$this->uid){
            return $this->ajaxReturn('', '登陆后才能评论哦~~！');
        }
        $aid = $this->input('post.aid', 0);
        if(!$aid){
            return $this->ajaxReturn('', '缺少关联id！');
        }
        $rid = $this->input('post.rid', 0);
        $content = $this->input('post.content', '');
        //若是有rid，先查出回复的用户信息
        $replyCommentInfo = Comment::find()->select('id,rid,authorid,author')->where(['id'=>$rid])->asArray()->one();
        $ruid = 0;
        $username = '';
        if($replyCommentInfo){
            $ruid = $replyCommentInfo['authorid'];
            $username = $replyCommentInfo['author'];
            if($replyCommentInfo['rid'] > 0){
                $rid = $replyCommentInfo['rid'];
            }
        }
        $commentModel = new Comment();
        $commentModel->rid = $rid;
        $commentModel->ruid = $ruid;
        $commentModel->username = $username;
        $commentModel->aid = $aid;
        $commentModel->authorid = $this->uid;
        $commentModel->author = $this->username;
        $commentModel->content = $content;
        $commentModel->type = $this->input('post.type', 1);
        $commentModel->status = 1;
        $commentModel->create_time = $this->formatTime;
        
        $commentModel->id = $commentModel->save(false);
        $insertData = $commentModel->attributes;
        if($insertData['id']){
            $insertData['head'] = $this->defaultHeadImg;
            $insertData['showTime'] = '刚刚';
            $insertData['showDate'] = substr($insertData['create_time'], 0, 16);
            $commentHtml = $this->renderPartial('../comment/_commentItem', ['comment' => $insertData]);
            //评论成功，增加文章评论数
            $articleModel = new Article();
            $articleModel->increase($aid, 'comment');
            return $this->ajaxReturn($insertData, $commentHtml,true);
        }else{
            return $this->ajaxReturn('', '评论失败！');
        }
    }
}
