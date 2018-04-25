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
 * @property string $tags
 * @property integer $type
 * @property string $path
 * @property string $psid
 * @property integer $count
 * @property string $time_create
 */
class Source extends \yii\db\ActiveRecord {

    //时期搜索
    public $time_create_from;
    //期搜索
    public $time_create_to;

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
            ['surl', 'unique', 'targetClass' => '\backend\models\Source', 'message' => '此页已收录。'],//唯一性验证
            [['name', 'sid', 'surl'], 'required'],
            [['content', 'tags'], 'string'],
            [['type', 'page', 'count'], 'integer'],
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
            'surl' => '源链接（本站必须支持）',
            'subject' => 'Subject',
            'content' => 'Content',
            'tags' => 'Tags',
            'type' => 'Type',
            'path' => 'Path',
            'psid' => 'Psid',
            'page' => 'Page',
            'status' => 'Status',
            'digest' => 'Digest',
            'count' => 'Count',
            'time_create' => 'Time Create',
        ];
    }

    /**
     * 站点名称
     */
    public function siteName() {
        return [
            'zhaofuli.biz' => '宅福利',
            'fuli.asia' => '宅福利2',
            'zhaofuli.in' => '宅福利3',
            'zhaofuli.mobi' => '宅福利4',
            'zhaifuli.xyz' => '宅福利5',
            'yxpjw.vip' => '宅福利6',
            'yxpjw.me' => '宅福利7',
            'yxpjw.club' => '宅福利8',
            'yxpjwnet.com' => '宅福利9',
        ];
    }

    /**
     * 状态
     */
    public function statusType() {
        return [
            0 => '删除',
            1 => '未获取内容',
            2 => '正在获取内容',
            3 => '已获取内容',
            4 => '已使用此内容',
            5 => '已查看此内容',
        ];
    }
    /**
     * 精华
     */
    public function digestList() {
        return [
            0 => '无',
            1 => '精华',
        ];
    }

}
