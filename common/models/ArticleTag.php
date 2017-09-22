<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%article_tag}}".
 *
 * @property string $id
 * @property string $uid
 * @property string $tid
 * @property string $aid
 * @property string $create_time
 */
class ArticleTag extends \yii\db\ActiveRecord
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
        return '{{%article_tag}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'tid', 'aid'], 'integer'],
            [['create_time'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'tid' => 'Tid',
            'aid' => 'Aid',
            'create_time' => '创建时间',
        ];
    }
}
