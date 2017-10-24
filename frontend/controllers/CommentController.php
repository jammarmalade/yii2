<?php

namespace frontend\controllers;

use Yii;
use frontend\components\WebController;
use common\models\Comment;
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
        $insertData = [
            'rid' => $rid,
            'ruid' => $ruid,
            'username' => $username,
            'aid' => $aid,
            'authorid' => $this->uid,
            'author' => $this->username,
            'content' => $content,
            'like' => 0,
            'type' => $this->input('post.type', 1),
            'status' => 1,
            'create_time' => $this->formatTime,
        ];
        $commentModel = new Comment();
        $insertData['id'] = $commentModel->save($insertData);
        if($insertData['id']){
            return $this->ajaxReturn($insertData, '评论成功！',true);
        }else{
            return $this->ajaxReturn('', '评论失败！');
        }
    }
}
