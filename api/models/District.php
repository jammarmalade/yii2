<?php

namespace api\modules\v1\models;
namespace app\models;

use Yii;

/**
 * This is the model class for table "district".
 *
 * @property string $id
 * @property string $name
 * @property string $alias
 * @property string $pinyin
 * @property integer $level
 * @property string $upid
 * @property string $yh_code
 * @property string $zh_code
 * @property double $latitude
 * @property double $longitude
 */
class District extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'district';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['level', 'upid', 'yh_code', 'zh_code'], 'integer'],
            [['latitude', 'longitude'], 'number'],
            [['name', 'alias', 'pinyin'], 'string', 'max' => 50]
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
            'alias' => 'Alias',
            'pinyin' => 'Pinyin',
            'level' => 'Level',
            'upid' => 'Upid',
            'yh_code' => 'Yh Code',
            'zh_code' => 'Zh Code',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
        ];
    }
}
