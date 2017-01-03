<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RecordSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '收支记录';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="record-index">
    <div class="tabbable">
        <ul class="nav nav-tabs">
            <li class="active">
                <a data-toggle="tab" href="#list">
                    <i class="icon-list"></i>
                    记录列表
                </a>
            </li>
            <li>
                <a data-toggle="tab" href="#search">
                    <i class="icon-search nav-search-icon"></i>
                    查询
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <div id="list" class="tab-pane in active">
                <p>
                    <?= Html::a('新增收支记录', ['create'], ['class' => 'btn btn-success']) ?>
                </p>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
            //            ['class' => 'yii\grid\SerialColumn'],

                        'id',
                        [
                            'attribute' => 'username',
            //                'header' => '用户名',
                            'value' => function($model){
                                return Html::a($model->username,['user-backend/view','id'=>$model->uid] , ["target" => "_blank"]);
                            },
                            'format' => 'raw',
                        ],
                        'account',
                        'content:ntext',
            //             'imgstatus',
            //             'longitude',
            //             'latitude',
            //             'weather',
            //             'remark',
                         [
                            'attribute' => 'time_create',
                            'label' => '创建时间',
                            'value' => function($model){
                                return  $model->time_create;   
                            },
                            'headerOptions' => ['width' => '200'],
                            'format' => ['datetime','php:Y-m-d H:i:s'],
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
                        [
                            'attribute' => 'type',
                            'label' => '记录类型',
                            'value' => function($model){
                               $state = [
                                    '1' => '支出',
                                    '2' => '收入',
                                ];
                                return $state[$model->type];   
                            },
                            'headerOptions' => ['width' => '70'],
                            'filter' => Html::activeDropDownList($searchModel,'type',['1' => '支出','2' => '收入'],['prompt'=>'全部'])
                        ],
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
                                    if($model->status == 0){
                                        return Html::a('<i class="icon-reply"></i>恢复',['delete', 'id' => $key ,'status' => 1], ['class' => 'btn btn-sm btn-success','data' => ['confirm' => '你确定要恢复吗？']]);
                                    }else{
                                        return Html::a('<i class="icon-trash"></i>删除',['delete', 'id' => $key ,'status' => 0], ['class' => 'btn btn-sm btn-danger','data' => ['confirm' => '你确定要删除吗？']]);
                                    }
                                },
                            ],
                        ],
                    ],
                ]); ?>
            </div>

            <div id="search" class="tab-pane">
                <?php echo $this->render('_search', ['model' => $searchModel]); ?>
            </div>
        </div>
    </div>
    

</div>
<?php $this->beginBlock("index") ?>
jQuery(document).ready(function () {
    $("#time_create_from,#time_create_to").tooltip({
        hide: {
            effect: "explode",
            delay: 250
        }
    });
});
<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks["index"], \yii\web\View::POS_END); ?>