<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%tag}}".
 *
 * @property string $id
 * @property string $name
 * @property string $uid
 * @property string $time_create
 * @property string $content
 * @property string $img
 * @property string $time_update
 * @property integer $status
 */
class Tag extends \yii\db\ActiveRecord
{
    //注册时期搜索
    public $time_create_from;
    //注册时期搜索
    public $time_create_to;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tag}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'uid', 'username','time_create'], 'required'],
            [['uid', 'status','classify'], 'integer'],
            [['time_create', 'time_update'], 'safe'],
            [['content'], 'string'],
            [['name','username'], 'string', 'max' => 15],
            [['img'], 'string', 'max' => 200],
            ['name', 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '标签ID',
            'name' => '标签名称',
            'uid' => '用户id',
            'username' => '用户名',
            'time_create' => '创建时间',
            'content' => '描述',
            'img' => '标签图标',
            'classify' => '大分类，0其它，1衣，2食，3住，4行',//暂未使用
            'time_update' => '更新时间',
            'status' => '标签状态，0删除，1正常',
        ];
    }
    /**
     * 大分类
     */
    public function getClassify(){
        return [
            '0' => '其它',
            '1' => '衣',
            '2' => '食',
            '3' => '住',
            '4' => '行',
        ];
    }
    //模糊搜索标签
    public static function searchTag($q){
        $rows = Tag::find()
            ->where("status=:status and `name` like :keyword")
            ->addParams([':status'=>1,':keyword'=>"%$q%"])
            ->asArray()
            ->select('id,name,img')
            ->limit(10)
            ->all();
        return $rows;
    }
    //推荐标签
    public static function getRecommendTag($count = 10){
        $sKey = 'recommendTag';
        $data = Yii::$app->cache->get($sKey);
        if(!$data){
            $res = Tag::find()->orderBy('record_count DESC')->limit(100)->select('id')->asArray()->indexBy('id')->all();
            $data = array_column($res, 'id');
            Yii::$app->cache->set($sKey, $data, 86400);
        }
        shuffle($data);
        return array_slice($data, 0, $count);
    }
}
