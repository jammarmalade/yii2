<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%district}}".
 *
 * @property string $id
 * @property string $name
 * @property string $alias
 * @property string $pinyin
 * @property integer $level
 * @property string $upid
 * @property string $yh_code
 * @property string $zh_code
 * @property string $hf_code
 * @property integer $order_number
 * @property double $latitude
 * @property double $longitude
 */
class District extends \yii\db\ActiveRecord
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
        return '{{%district}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['level', 'upid', 'yh_code', 'zh_code', 'order_number'], 'integer'],
            [['latitude', 'longitude'], 'number'],
            [['name', 'alias', 'pinyin'], 'string', 'max' => 50],
            [['hf_code'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'alias' => '别名',
            'pinyin' => 'Pinyin',
            'level' => 'Level',
            'upid' => 'Upid',
            'yh_code' => '雅虎城市代码',
            'zh_code' => '中国天气网城市代码',
            'hf_code' => '和风天气代码',
            'order_number' => '值越大越靠前',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
        ];
    }
}
