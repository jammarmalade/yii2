<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%audio}}".
 *
 * @property string $id
 * @property string $uid
 * @property string $content
 * @property integer $spd
 * @property integer $pit
 * @property integer $vol
 * @property integer $per
 * @property string $path
 * @property integer $status
 * @property string $time_create
 */
class Audio extends \yii\db\ActiveRecord
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
        return '{{%audio}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'content'], 'required'],
            [['uid', 'spd', 'pit', 'vol', 'per', 'status'], 'integer'],
            [['content'], 'string'],
            [['time_create'], 'safe'],
            [['path'], 'string', 'max' => 300]
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
            'content' => '合成文本',
            'spd' => '语速，取值0-9，默认为5中语速',
            'pit' => '音调，取值0-9，默认为5中语调',
            'vol' => '音量，取值0-15，默认为5中音量',
            'per' => '发音人选择, 0为女声，1为男声，3为情感合成-度逍遥，4为情感合成-度丫丫，默认为普通女',
            'path' => '音频保存路径',
            'status' => '状态，1正常，2删除',
            'time_create' => '生成时间',
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

    public function perArr(){
        return [
            0 => '普通女声',
            1 => '普通男声',
            3 => '情感合成-男声',
            4 => '情感合成-女声',
        ];
    }
    
}
