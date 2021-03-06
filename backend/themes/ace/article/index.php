<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '文章列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    if(Yii::$app->getSession()->getFlash('success')){
        echo '<div class="alert alert-block alert-success">'.Yii::$app->getSession()->getFlash('success').'</div>';
    }
    if(Yii::$app->getSession()->getFlash('error')){
        echo '<div class="alert alert-block alert-danger">'.Yii::$app->getSession()->getFlash('error').'</div>';
    }
    ?>


    <p>
        <?= Html::a('新增文章', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
//            'uid',
            [
                'attribute' => 'username',
                'value' => function($model){
                    return Html::a($model->username,['user-backend/view','id'=>$model->uid] , ["target" => "_blank"]);
                },
                'format' => 'raw',
            ],
            'subject',
//            'content:ntext',
            'like',
            'view',
            'comment',
            // 'image_id',
            [
                'attribute' => 'status',
                'label' => '状态',
                'value' => function($model){
                   $state = [
                        '1' => '正常',
                        '2' => '删除',
                    ];
                    return $state[$model->status];
                },
                'headerOptions' => ['width' => '70'],
                'filter' => Html::activeDropDownList($searchModel,'status',['1'=>'正常','2'=>'删除'],['prompt'=>'全部'])
            ],
            [
                'attribute' => 'copyright',
                'label' => '版权',
                'value' => function($model){
                    $state = $model->copyrightArr();
                    return $state[$model->copyright];
                },
                'headerOptions' => ['width' => '70'],
                'filter' => Html::activeDropDownList($searchModel,'copyright',$searchModel->copyrightArr(),['prompt'=>'全部'])
            ],
            // 'time_update',
            // 'time_create',
            [
                'attribute' => 'time_create',
                'label' => '创建时间',
                'value' => function($model){
                    return  $model->time_create;
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
                'header' => '提交到百度',
                'template' => '{submit}',
                'buttons' => [
                    'submit' => function($url, $model, $key){
                        if($model->bdlink == 0){
                            return Html::a('推送数据到百度',['bdlink', 'id' => $key ,'type' => 0], ['class' => 'btn btn-sm btn-success','data' => ['confirm' => '确定推送吗？','method' => 'post']]);
                        }else{
                            return Html::a('更新数据到百度',['bdlink', 'id' => $key ,'type' => 1], ['class' => 'btn btn-sm btn-success','data' => ['confirm' => '确定更新吗？','method' => 'post']]);
                        }
                    },
                ],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{view} {update} {recommend} {delete} {copyright}',
                'buttons' => [
                    'view' => function($url, $model, $key){
                       return Html::a('查看',['view', 'id' => $key], ['class' => 'btn btn-sm btn-success',]
                       );
                    },
                    'update' => function($url, $model, $key){
                       return Html::a('修改',['create', 'id' => $key], ['class' => 'btn btn-sm btn-success',]
                       );
                    },
                    'recommend' => function($url, $model, $key){
                       if($model->recommend == 1){
                            return Html::a('取消推荐',['delete', 'id' => $key ,'status' => 0,'type'=>'recommend'], ['class' => 'btn btn-sm btn-success','data' => ['confirm' => '你确定要取消吗？','method' => 'post']]);
                        }else{
                            return Html::a('推荐',['delete', 'id' => $key ,'status' => 1,'type'=>'recommend'], ['class' => 'btn btn-sm btn-success','data' => ['confirm' => '确认推荐吗？','method' => 'post']]);
                        }
                    },
                    'delete' => function($url, $model, $key){
                        if($model->status == 2){
                            return Html::a('<i class="icon-reply"></i>恢复',['delete', 'id' => $key ,'status' => 1,'type'=>'status'], ['class' => 'btn btn-sm btn-success','data' => ['confirm' => '你确定要恢复吗？','method' => 'post']]);
                        }else{
                            return Html::a('<i class="icon-trash"></i>删除',['delete', 'id' => $key ,'status' => 2,'type'=>'status'], ['class' => 'btn btn-sm btn-danger','data' => ['confirm' => '你确定要删除吗？','method' => 'post']]);
                        }
                    },
                    'copyright' => function($url, $model, $key){
                       if($model->copyright == 1){
                            return Html::a('取消版权',['delete', 'id' => $key ,'status' => 0,'type'=>'copyright'], ['class' => 'btn btn-sm btn-success','data' => ['confirm' => '你确定要取消吗？','method' => 'post']]);
                        }else{
                            return Html::a('显示版权',['delete', 'id' => $key ,'status' => 1,'type'=>'copyright'], ['class' => 'btn btn-sm btn-success','data' => ['confirm' => '确认显示版权吗？','method' => 'post']]);
                        }
                    },
                ],
            ],
        ],
    ]); ?>

</div>

