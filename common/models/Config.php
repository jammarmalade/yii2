<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%config}}".
 *
 * @property string $id
 * @property string $name
 * @property string $key
 * @property string $value
 * @property integer $type
 * @property string $remark
 * @property integer $status
 * @property string $order_number
 * @property string $time_create
 */
class Config extends \yii\db\ActiveRecord
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
        return '{{%config}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'key', 'value', 'type'], 'required'],
            ['key', 'unique', 'message' => 'key值已存在.'],
            [['type', 'status', 'order_number'], 'integer'],
            [['time_create'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['key'], 'string', 'max' => 20],
            [['value'], 'string', 'max' => 1000],
            [['remark'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称标识',
            'key' => '调用key',
            'value' => '值',
            'type' => '值类型',
            'remark' => '备注',
            'status' => '状态',
            'order_number' => '显示顺序',
            'time_create' => '创建时间',
        ];
    }
    /**
     * 值类型
     */
    public function typeArr(){
        return [
            1 => '字符串',
            2 => '图片',
        ];
    }
    /**
     * 状态
     */
    public function statusArr(){
        return [
            1 => '正常',
            2 => '删除',
        ];
    }
    /**
     * 获取配置
     * @param string  $key     key 值
     * @return string|key 
     */
    public static function getConfig($key = ''){
        $cache = Yii::$app->cache;
        if(Yii::$app->request->get('t')==1){
            $cache->delete('config');
        }
        $configList = $cache->getOrSet('config', function () {
            $configListRes = Config::find()->where(['status'=>1])->asArray()->all();
            $configList = [];
            foreach($configListRes as $k=>$v){
                $tmpValue = $v['value'];
                if(strpos($tmpValue, 'config')!==false){
                    $tmpValue = Yii::$app->params['imgDomain'].$tmpValue;
                }
                $configList[$v['key']] = $tmpValue;
            }
            return $configList;
        },86400);
        
        $returnData = $configList;
        if($key && isset($returnData[$key])){
            $returnData = $returnData[$key];
        }
        return $returnData;
    }
    /**
     * 删除配置缓存
     */
    public static function delConfigCache(){
        $cache = Yii::$app->cache;
        $cache->delete('config');
    }
}
