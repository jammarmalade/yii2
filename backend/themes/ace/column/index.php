<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\jui\DatePicker;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ColumnSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '栏目列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="column-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('新增栏目', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'id',
                'headerOptions' => ['width' => '80'],
            ],
            'name',
            'url:url',
            'order_number',
            'class',
            [
                'attribute' => 'pid',
                'value' => function($model){
                    if($model->pid ==0){
                        return '一级导航';
                    }else{
                        $list = $model->columnDropList();
                        return Html::a(str_replace('　', '', $list[$model->pid]), Url::to(['column/index','ColumnSearch[id]'=>$model->pid]));
                    }
                },
                'format' => 'raw',
                'filter' => Html::activeDropDownList($searchModel,'pid',$searchModel->columnDropList(),['prompt'=>'全部'])
            ],
            'remark',
            [
                'attribute' => 'status',
                'label' => '状态',
                'value' => function($model){
                    $state = $model->statusArr();
                    return $state[$model->status];   
                },
                'headerOptions' => ['width' => '70'],
                'filter' => Html::activeDropDownList($searchModel,'status',$searchModel->statusArr(),['prompt'=>'全部'])
            ],
            [
                'attribute' => 'create_time',
                'label' => '创建时间',
                'value' => function($model){
                    return  $model->create_time;   
                },
                'headerOptions' => ['width' => '200'],
                'filter' => DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'time_create_from',
                        'language' => 'zh-CN',
                        'dateFormat' => 'yyyy-MM-dd',
                        'value' =>  function($model){
                            return  $model->time_create_from;   
                        },
                        'options' => ['class' => 'col-xs-6','id' => 'time_create_from','title'=> '选择开始日期'],
                    ]).DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'time_create_to',
                        'language' => 'zh-CN',
                        'dateFormat' => 'yyyy-MM-dd',
                        'value' =>  function($model){
                            return  $model->time_create_to;   
                        },
                        'options' => ['class' => 'col-xs-6','id' => 'time_create_to','title'=> '选择结束日期'],
                    ]),
            ],
            //['class' => 'yii\grid\ActionColumn'],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{view} {update} {delete} ',
                'buttons' => [
                    'view' => function($url, $model, $key){
                       return Html::a('查看',['view', 'id' => $key], ['class' => 'btn btn-sm btn-success',]
                       );
                    },
                    'update' => function($url, $model, $key){
                       return Html::a('修改',['update', 'id' => $key], ['class' => 'btn btn-sm btn-success',]
                       );
                    },
                    'delete' => function($url, $model, $key){
                        if($model->status == 2){
                            return Html::a('<i class="icon-reply"></i>恢复',['delete', 'id' => $key ,'status' => 1], ['class' => 'btn btn-sm btn-success','data' => ['confirm' => '你确定要恢复吗？','method' => 'post']]);
                        }else{
                            return Html::a('<i class="icon-trash"></i>删除',['delete', 'id' => $key ,'status' => 2], ['class' => 'btn btn-sm btn-danger','data' => ['confirm' => '你确定要删除吗？','method' => 'post']]);
                        }
                    },
                ],
            ],
        ],
    ]); ?>

</div>

