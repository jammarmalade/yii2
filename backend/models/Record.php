<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%record}}".
 *
 * @property string $id
 * @property string $uid
 * @property string $username
 * @property string $account
 * @property integer $type
 * @property string $content
 * @property integer $imgstatus
 * @property double $longitude
 * @property double $latitude
 * @property string $weather
 * @property string $remark
 * @property string $time_create
 */
class Record extends \yii\db\ActiveRecord
{
    //日期搜索
    public $time_create_from;
    //日期搜索
    public $time_create_to;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%record}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'username', 'account', 'type'], 'required'],
            [['uid', 'type', 'imgstatus','status'], 'integer'],
            [['account', 'longitude', 'latitude'], 'number'],
            [['content'], 'string'],
            [['time_create'], 'safe'],
            [['username'], 'string', 'max' => 15],
            [['weather'], 'string', 'max' => 100],
            [['remark'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => '用户id',
            'username' => '用户名',
            'account' => '金额',
            'type' => '金额类型',
            'content' => '描述',
            'imgstatus' => '是否有图片',
            'longitude' => '经度',
            'latitude' => '纬度',
            'weather' => '天气',
            'remark' => '备注',
            'time_create' => '创建时间',
            'status' => '标签状态，0删除，1正常',
        ];
    }
}
