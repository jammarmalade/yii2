<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserBackendSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '用户管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-backend-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
//            'id',
            [
                'attribute' => 'id',
                'headerOptions' => ['width' => '70'],
            ],
            'username',
            'email:email',
            'time_login',
            'records',
//            'time_register',
            [
                'attribute' => 'time_register',
                'label' => '注册时间',
                'value' => function($model){
                    return  $model->time_register;   
                },
                'headerOptions' => ['width' => '200'],
                'format' => ['datetime','php:Y-m-d H:i:s'],
                'filter' => DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'time_register_form',
                        'language' => 'zh-CN',
                        'dateFormat' => 'yyyy-MM-dd',
                        'value' =>  function($model){
                            return  $model->time_register_form;   
                        },
                        'options' => ['class' => 'col-xs-6','id' => 'time_register_form','title'=> '选择开始日期'],
                    ]).DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'time_register_to',
                        'language' => 'zh-CN',
                        'dateFormat' => 'yyyy-MM-dd',
                        'value' =>  function($model){
                            return  $model->time_register_to;   
                        },
                        'options' => ['class' => 'col-xs-6','id' => 'time_register_to','title'=> '选择结束日期'],
                    ]),
            ],
            [
                'attribute' => 'status',
                'label' => '状态',
                'value' => function($model){
                   $state = [
                        '0' => '删除',
                        '1' => '正常',
                    ];
                    return $state[$model->status];   
                },
                'headerOptions' => ['width' => '70'],
                //在搜索条件（过滤条件）中使用下拉框来搜索
//                'filter' => ['1'=>'正常','0'=>'删除'],
                //or
                'filter' => Html::activeDropDownList($searchModel,'status',['1'=>'正常','0'=>'删除'],['prompt'=>'全部'])
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{view} {update} {delete} ',//可自行添加，如 {mybtn},下面 buttons 要有一个对应的 mybtn 选项
//                'headerOptions' => ['width' => '100'],
                'buttons' => [
                    'view' => function($url, $model, $key){
                       return Html::a('查看',['view', 'id' => $key], ['class' => 'btn btn-sm btn-success',]);
                    },
                    'update' => function($url, $model, $key){
                       return Html::a('修改',['update', 'id' => $key], ['class' => 'btn btn-sm btn-success',]);
                    },
                    'delete' => function($url, $model, $key){
                        if($model->status == 0){
                            return Html::a('<i class="icon-reply"></i>恢复',['delete', 'id' => $key ,'status' => 1], ['class' => 'btn btn-sm btn-success','data' => ['confirm' => '你确定要恢复该用户吗？']]);
                        }else{
                            return Html::a('<i class="icon-trash"></i>删除',['delete', 'id' => $key ,'status' => 0], ['class' => 'btn btn-sm btn-danger','data' => ['confirm' => '你确定要删除该用户吗？']]);
                        }
                    },
                ],
            ],
        ],
    ]); ?>

</div>
<?php $this->beginBlock("index") ?>
jQuery(document).ready(function () {
    $("#time_register_form,#time_register_to").tooltip({
        hide: {
            effect: "explode",
            delay: 250
        }
    });
});
<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks["index"], \yii\web\View::POS_END); ?>
