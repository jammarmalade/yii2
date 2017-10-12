<?php

namespace common\models;

use Yii;
use frontend\components\Functions as tools;

/**
 * This is the model class for table "{{%column}}".
 *
 * @property string $id
 * @property string $name
 * @property string $url
 * @property integer $status
 * @property string $order_number
 * @property string $create_time
 * @property string $class
 * @property string $pid
 * @property string $remark
 */
class Column extends \yii\db\ActiveRecord
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
        return '{{%column}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['status', 'order_number', 'pid'], 'integer'],
            [['create_time'], 'safe'],
            [['name'], 'string', 'max' => 10],
            [['url', 'remark'], 'string', 'max' => 300],
            [['class'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '导航名称',
            'url' => '导航链接',
            'status' => '状态',
            'order_number' => '排序',
            'create_time' => '创建时间',
            'class' => '栏目样式',
            'pid' => '上级导航id',
            'remark' => '备注',
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
     * 获取格式化的导航（上下关联）
     */
    public function getColumnList($flush = false){
        $cache = Yii::$app->cache;
        if(Yii::$app->request->get('t')==1 || $flush){
            $cache->delete('column-list');
        }
        $cacheData = $cache->getOrSet('column-list', function () {
            return $this->_getColumnList();
        },86400);
        return $cacheData;
    }
    private function _getColumnList(){
        $navList = Column::find()->where(['status'=>1])->asArray()->all();
        if(!$navList){
            return [];
        }
        $group = array();
        //按上一级pid分组
        foreach ($navList as $k => $v) {
            $group[$v['pid']][] = $v;
        }
        //根据order_number排序
        foreach ($group as $k => $v) {
            $group[$k] = tools::multiArraySort($v, 'order_number', SORT_DESC);
        }
        //格式化导航（一维和多维），$formatNavIds 层级id
        $formatNav = $formatNavMul = $formatNavIds = array();
        $tmpMul = [];
        foreach ($group[0] as $k => $v) {
            $formatNav[$v['id']] = $v['name'];
            $formatNavIds[1][] = $v['id']; //第一级导航id
            unset($group[0][$k]);
            if (isset($group[$v['id']])) {
                foreach ($group[$v['id']] as $k1 => $v1) {
                    $formatNav[$v1['id']] = '　' . $v1['name'];
                    $v1['url'] = $v1['url'] ? $v1['url'] : 'javascript:;';
                    $formatNavIds[2][] = $v1['id']; //第二级导航id
                    unset($group[$v['id']][$k1]);
                    if (!isset($group[$v['id']][$k1 - 1])) {
                        unset($group[$v['id']]);
                    }
                    $tmpMul[$v1['id']] = $v1;
                }
            }
            if (!isset($group[0][$k - 1])) {
                unset($group[0]);
            }
            $v['cnav'] = $tmpMul;
            $v['url'] = $v['url'] ? $v['url'] : 'javascript:;';
            $formatNavMul[$v['id']] = $v;
            $tmpMul = array();
        }
        //若还有，则表明有第三级
        if ($group) {
            $returnNav = array();
            foreach ($formatNav as $k => $v) {
                $returnNav[$k] = $v;
                if (isset($group[$k])) {
                    foreach ($group[$k] as $k1 => $v1) {
                        $returnNav[$v1['id']] = '　　' . $v1['name'];
                        $v1['url'] = $v1['url'] ? $v1['url'] : 'javascript:;';
                        $tmpMul[$v1['id']] = $v1;
                        $formatNavIds[3][] = $v1['id']; //第三级导航id
                    }
                    //多维
                    foreach ($formatNavMul as $k2 => $v2) {
                        if ($formatNavMul[$k2]['cnav']) {
                            foreach ($formatNavMul[$k2]['cnav'] as $k3 => $v3) {
                                //找到二级对应的栏目
                                if ($k3 == $k) {
                                    $formatNavMul[$k2]['cnav'][$k3]['cnav'] = $tmpMul;
                                    $tmpMul = array();
                                }
                            }
                        }
                    }
                }
            }
        } else {
            $returnNav = $formatNav;
        }
        //一维 ， 多维 , 层级导航id（二维，层级只有三级）
        $return = array('formatNav' => $returnNav, 'formatNavMul' => $formatNavMul, 'formatNavIds' => $formatNavIds);
        
        return $return;
    }
    /**
     * 栏目列表(用于下拉框)
     */
    public function columnDropList(){
        $list = $this->getColumnList(true);
        return $list['formatNav'];
    }
    
}
