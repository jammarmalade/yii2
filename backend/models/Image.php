<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%image}}".
 *
 * @property string $id
 * @property string $uid
 * @property string $username
 * @property string $path
 * @property integer $type
 * @property string $size
 * @property integer $width
 * @property integer $height
 * @property integer $width_thumb
 * @property integer $height_thumb
 * @property string $exif
 * @property integer $status
 * @property string $time_create
 */
class Image extends \yii\db\ActiveRecord
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
        return '{{%image}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid'], 'required'],
            [['uid', 'type', 'size', 'width', 'height', 'width_thumb', 'height_thumb', 'status'], 'integer'],
            [['exif'], 'string'],
            [['time_create'], 'safe'],
            [['username'], 'string', 'max' => 15],
            [['path','filename'], 'string', 'max' => 255]
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
            'filename' => '文件名',
            'path' => '相对存放路径',
            'type' => '图片类型，0未使用，1收支记录',
            'size' => '图片大小',
            'width' => '图片宽度',
            'height' => '图片高度',
            'width_thumb' => '缩略图宽度',
            'height_thumb' => '缩略图高度',
            'exif' => '图片的exif信息',
            'status' => '标签状态，0删除，1正常，2未使用',
            'time_create' => '上传时间',
        ];
    }
}
