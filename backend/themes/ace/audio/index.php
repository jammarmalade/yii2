<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\AudioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '语音合成列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="audio-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<!--    <p>
        <?= Html::a('Create Audio', ['create'], ['class' => 'btn btn-success']) ?>
    </p>-->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            [
                'attribute' => 'username',
                'value' => function($model){
                    return Html::a($model->username,['user-backend/view','id'=>$model->uid] , ["target" => "_blank"]);
                },
                'headerOptions' => ['width' => '100px'],
                'format' => 'raw',
            ],
            [
                'attribute' => 'content',
                'label' => '合成内容',
                'value' => function($model){
                    return mb_substr($model->content, 0,300).'...';
                },
                'headerOptions' => ['width' => '400px'],
            ],
            [
                'label' => '语速/音调/音量',
                'value' => function($model){
                    return $model->spd.' / '.$model->pit.' / '.$model->vol;
                },
                'headerOptions' => ['width' => '120px'],
            ],
            [
                'attribute' => 'per',
                'label' => '发声人',
                'value' => function($model){
                    $perArr = $model->perArr();
                    return $perArr[$model->per];
                },
                'headerOptions' => ['width' => '100px'],
            ],
            [
                'attribute' => 'path',
                'label' => '合成语音',
                'value' => function($model){
                    $staticDomain = Yii::$app->params['staticDomain'];
                    return '<audio src="'.$staticDomain.$model->path.'" controls="controls">您的浏览器不支持 audio 标签。</audio>';
                },
                'format' => 'raw',
            ],
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
                'attribute' => 'time_create',
                'label' => '创建时间',
                'value' => function($model){
                    return  $model->time_create;
                },
                'headerOptions' => ['width' => '200'],
                //'format' => ['datetime','php:Y-m-d H:i:s'],
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

