<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%tag}}".
 *
 * @property string $id
 * @property string $name
 * @property string $uid
 * @property string $time_create
 * @property string $content
 * @property string $img
 * @property string $time_update
 * @property integer $status
 */
class Tag extends \yii\db\ActiveRecord
{
    //注册时期搜索
    public $time_create_from;
    //注册时期搜索
    public $time_create_to;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tag}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'uid', 'username','time_create'], 'required'],
            [['uid', 'status'], 'integer'],
            [['time_create', 'time_update'], 'safe'],
            [['content'], 'string'],
            [['name','username'], 'string', 'max' => 15],
            [['img'], 'string', 'max' => 200],
            ['name', 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '标签ID',
            'name' => '标签名称',
            'uid' => '用户id',
            'username' => '用户名',
            'time_create' => '创建时间',
            'content' => '描述',
            'img' => '标签图标',
            'time_update' => '更新时间',
            'status' => '标签状态，0删除，1正常',
        ];
    }
}
