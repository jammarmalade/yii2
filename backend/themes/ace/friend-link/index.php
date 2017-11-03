<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\FriendLinkSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '友链列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="friend-link-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('新增友链', ['create'], ['class' => 'btn btn-success']) ?>
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
            'email:email',
            'order_number',
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
                            $html = '';
                            if($model->status == 3){
                                $html .= Html::a('通过审核',['delete', 'id' => $key ,'status' => 1], ['class' => 'btn btn-sm btn-success','data' => ['confirm' => '确认通过审核？','method' => 'post']]);
                            }
                            $html .= Html::a('<i class="icon-trash"></i>删除',['delete', 'id' => $key ,'status' => 2], ['class' => 'btn btn-sm btn-danger','data' => ['confirm' => '你确定要删除吗？','method' => 'post']]);
                            return $html;
                        }
                    },
                ],
            ],
        ],
    ]); ?>

</div>

