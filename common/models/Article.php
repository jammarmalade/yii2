<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "t_article".
 *
 * @property string $id
 * @property string $sid
 * @property string $uid
 * @property string $username
 * @property string $subject
 * @property string $description
 * @property string $content
 * @property string $like
 * @property string $view
 * @property string $comment
 * @property string $view_auth
 * @property string $image_id
 * @property string $copyright
 * @property integer $status
 * @property string $time_update
 * @property string $time_create
 */
class Article extends \yii\db\ActiveRecord
{
    //时期搜索（开始）
    public $time_create_from;
    //时期搜索（结束）
    public $time_create_to;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid'], 'required'],
            [['uid', 'like', 'view', 'comment', 'image_id', 'status'], 'integer'],
            [['content'], 'string'],
            [['time_update', 'time_create'], 'safe'],
            [['username'], 'string', 'max' => 15],
            [['subject'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sid' => '加密ID',
            'uid' => '用户id',
            'username' => '用户名',
            'subject' => '文章标题',
            'description' => '文章摘要',
            'content' => '文章内容',
            'like' => '点赞数',
            'view' => '查看数',
            'comment' => '评论数',
            'view_auth' => '查看密码',
            'image_id' => '内容第一张图片的id',
            'copyright' => '显示版权',
            'status' => '状态，1正常，2删除',
            'time_update' => '更新时间',
            'time_create' => '创建时间',
        ];
    }
    /**
     * 版权标记
     */
    public function copyrightArr(){
        return [
            0 => '不显示',
            1 => '显示',
        ];
    }
}
