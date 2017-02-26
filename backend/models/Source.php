<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%source}}".
 *
 * @property string $id
 * @property string $name
 * @property string $sid
 * @property string $surl
 * @property string $subject
 * @property string $content
 * @property integer $type
 * @property string $path
 * @property string $psid
 * @property integer $get
 * @property string $time_create
 */
class Source extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%source}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'sid', 'subject', 'content'], 'required'],
            [['content', 'tags'], 'string'],
            [['type', 'get', 'page'], 'integer'],
            [['time_create'], 'safe'],
            [['name'], 'string', 'max' => 15],
            [['sid', 'psid'], 'string', 'max' => 32],
            [['surl', 'path'], 'string', 'max' => 300],
            [['subject'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'sid' => 'Sid',
            'surl' => 'Surl',
            'subject' => 'Subject',
            'content' => 'Content',
            'tags' => 'Tags',
            'type' => 'Type',
            'path' => 'Path',
            'psid' => 'Psid',
            'get' => 'Get',
            'page' => 'Page',
            'time_create' => 'Time Create',
        ];
    }

}
