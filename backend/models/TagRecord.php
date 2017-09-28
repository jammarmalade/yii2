<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%tag_record}}".
 *
 * @property string $id
 * @property string $uid
 * @property string $tid
 * @property string $rid
 * @property string $create_time
 */
class TagRecord extends \yii\db\ActiveRecord
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
        return '{{%tag_record}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','uid' ,'tid', 'rid'], 'required'],
            [['id','uid', 'tid', 'rid'], 'integer']
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
            'tid' => '标签id',
            'rid' => '记录id',
            'create_time' => '添加时间',
        ];
    }
}
