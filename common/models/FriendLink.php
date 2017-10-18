<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%friend_link}}".
 *
 * @property string $id
 * @property string $name
 * @property string $url
 * @property string $email
 * @property integer $status
 * @property string $order_number
 * @property string $create_time
 * @property string $remark
 */
class FriendLink extends \yii\db\ActiveRecord
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
        return '{{%friend_link}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'url'], 'required'],
            [['status', 'order_number'], 'integer'],
            [['create_time'], 'safe'],
            [['name'], 'string', 'max' => 10],
            [['url', 'remark'], 'string', 'max' => 300],
            [['email'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '显示名称',
            'url' => '链接',
            'email' => '邮箱',
            'status' => '状态',
            'order_number' => '排序',
            'create_time' => '创建时间',
            'remark' => '备注',
        ];
    }
    /**
     * 状态
     */
    public function statusArr(){
        return [
            1 => '正常',
            2 => '删除',
            3 => '待审核',
        ];
    }
}
