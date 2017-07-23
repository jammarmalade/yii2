<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%source_image}}".
 *
 * @property string $id
 * @property string $name
 * @property string $sid
 * @property string $surl
 * @property string $subject
 * @property string $tags
 * @property string $path
 * @property string $psid
 * @property string $page
 * @property integer $status
 * @property string $count
 * @property string $exe_time
 * @property string $remark
 * @property string $time_create
 */
class SourceImage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%source_image}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'sid', 'subject'], 'required'],
            [['page', 'status', 'count'], 'integer'],
            [['exe_time', 'time_create'], 'safe'],
            [['name'], 'string', 'max' => 15],
            [['sid', 'psid'], 'string', 'max' => 32],
            [['surl', 'path', 'remark'], 'string', 'max' => 300],
            [['subject'], 'string', 'max' => 200],
            [['tags'], 'string', 'max' => 100]
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
            'sid' => 'Sid',
            'surl' => 'Surl',
            'subject' => 'Subject',
            'tags' => 'Tags',
            'path' => 'Path',
            'psid' => 'Psid',
            'page' => 'Page',
            'status' => 'Status',
            'count' => 'Count',
            'exe_time' => 'Exe Time',
            'remark' => 'Remark',
            'time_create' => 'Time Create',
        ];
    }
}
