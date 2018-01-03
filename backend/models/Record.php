<?php

namespace backend\models;

use Yii;
use backend\models\TagRecord;
use backend\models\Tag;

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
 * @property string $country
 * @property string $province
 * @property string $city
 * @property string $area
 * @property string $address
 * @property double $longitude
 * @property double $latitude
 * @property string $weather
 * @property string $remark
 * @property string $date
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
            [['content','address','country','province','city','area'], 'string'],
            [['time_create','date'], 'safe'],
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
            'type' => '金额类型，0没有消费，1支出，2收入',
            'content' => '描述',
            'imgstatus' => '是否有图片',
            'country' => '国家',
            'province' => '省份',
            'city' => '城市',
            'area' => '区域',
            'address' => '街道',
            'longitude' => '经度',
            'latitude' => '纬度',
            'weather' => '天气',
            'remark' => '备注',
            'time_create' => '创建时间',
            'status' => '记录状态，0删除，1正常',
        ];
    }
    /**
     * 记录支出类型
     */
    public function recordType() {
        return [
            0 => '记录',
            1 => '支出',
            2 => '收入',
        ];
    }
    /**
     * 获取标签
     */
    public function getTag($rid){
        $resTagList = TagRecord::findBySql('SELECT t.id,t.`name` FROM '.TagRecord::tableName().' tr,'.Tag::tableName().' t WHERE tr.rid='.$rid.' AND tr.tid = t.id')->asArray()->all();
        return $resTagList;
    }
}
