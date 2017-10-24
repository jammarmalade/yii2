<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%comment}}".
 *
 * @property string $id
 * @property string $rid
 * @property string $ruid
 * @property string $username
 * @property string $aid
 * @property string $authorid
 * @property string $author
 * @property string $content
 * @property string $like
 * @property integer $type
 * @property integer $status
 * @property string $create_time
 */
class Comment extends \yii\db\ActiveRecord
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
        return '{{%comment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rid', 'ruid', 'aid', 'authorid', 'like', 'type', 'status'], 'integer'],
            [['author', 'content'], 'required'],
            [['content'], 'string'],
            [['create_time'], 'safe'],
            [['username', 'author'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'rid' => '回复ID',
            'ruid' => '回复的评论的用户id',
            'username' => '回复的评论的用户昵称',
            'aid' => '关联ID',
            'authorid' => '本评论的作者id',
            'author' => '本评论的作者昵称',
            'content' => '评论内容',
            'like' => '点赞数',
            'type' => '评论类型，1 文章',
            'status' => '1正常，2删除',
            'create_time' => '创建时间',
        ];
    }
    /**
     * 状态
     */
    public function statusArr(){
        return [
            1 => '正常',
            2 => '删除',
        ];
    }
    /**
     * 类型
     */
    public function typeArr(){
        return [
            1 => '文章',//Article
        ];
    }
    /**
     * 获取评论列表
     */
    public static function getList($aid,$page,$defaultHead){
        $cache = Yii::$app->cache;
        $skey = 'comment-list-'.$aid.'-'.$page;
    }
    /**
     * 获取评论列表内部
     */
    public static function _getList(){
        
    }
}
