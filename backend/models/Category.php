<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%category}}".
 *
 * @property integer $id
 * @property string $name
 */
class Category extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => '栏目ID',
            'name' => '栏目名',
        ];
    }

    /**
     * 获取栏目的枚举值，
     * key=>value的形式组合:key表示栏目ID,value表示栏目名称
     */
    public static function dropDownList() {
        $query = static::find();
        $enums = $query->all();
        return $enums ? ArrayHelper::map($enums, 'id', 'name') : [];
    }

}
