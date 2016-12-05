<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%blog}}".
 *
 * @property string $id
 * @property string $title
 * @property string $content
 * @property string $views
 * @property integer $status
 * @property string $create_time
 * @property string $update_time
 */
class Blog extends \yii\db\ActiveRecord
{
    public $category;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%blog}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content', 'create_time', 'update_time'], 'required'],
            [['content'], 'string'],
            [['views', 'status'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['title'], 'string', 'max' => 100],
            [['title', 'content', 'category'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'content' => '内容',
            'views' => '点击量',
            'status' => '状态 1未删除 2已删除',
            'create_time' => '添加时间',
            'update_time' => '更新时间',
        ];
    }
}
