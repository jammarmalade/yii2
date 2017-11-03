<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\jui\DatePicker;
use backend\components\Functions;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ImageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '图片列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="image-index">
    
    <div class="tabbable">
        <ul class="nav nav-tabs">
            <li class="active">
                <a data-toggle="tab" href="#list">
                    <i class="icon-list"></i>
                    图片列表
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
                    <?= Html::a('上传图片', ['create'], ['class' => 'btn btn-success']) ?>
                </p>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
            //            ['class' => 'yii\grid\SerialColumn'],

                        'id',
                        [
                            'attribute' => 'username',
                            'value' => function($model){
                                return Html::a($model->username,['user-backend/view','id'=>$model->uid] , ["target" => "_blank"]);
                            },
                            'format' => 'raw',
                        ],
                        [
                            'attribute' => 'path',
                            'value' => function($model){
                                $imgUrl = $imgUrlThumb = \Yii::$app->params['imgDomain'].$model->path;
                                //若是有缩略图
                                if($model->thumb){
                                    $imgUrlThumb = $imgUrlThumb.'.thumb.jpg';
                                }
                                $imgStr = Html::img($imgUrlThumb,['class' => 'img-list']);
                                return '<ul class="ace-thumbnails"><li>'.Html::a($imgStr.'<div class="text"><div class="inner">点击查看</div></div>',$imgUrl, ["target" => "_blank",'data-rel'=>"colorbox",'class' => 'cboxElement']).'</li></ul>';
                            },
                            'format' => 'raw',
                            'filter' => ''
                        ],
                        [
                            'attribute' => 'type',
                            'label' => '使用位置',
                            'value' => function($model){
                               $state = $model->getTypeArr();
                                return $state[$model->type];   
                            },
                            'headerOptions' => ['width' => '70'],
                            'filter' => Html::activeDropDownList($searchModel,'type',$searchModel->getTypeArr(),['prompt'=>'全部'])
                        ],
                        [
                            'attribute' => 'size',
                            'value' => function($model){
                                return Functions::size_count($model->size);   
                            },
                            'filter' => ''
                        ],
                        // 'width',
                        // 'height',
                        // 'width_thumb',
                        // 'height_thumb',
                        // 'exif:ntext',
                        [
                            'attribute' => 'status',
                            'label' => '状态',
                            'value' => function($model){
                               $state = [
                                    '0' => '删除',
                                    '1' => '正常',
                                    '2' => '未使用',
                                ];
                                return $state[$model->status];   
                            },
                            'headerOptions' => ['width' => '70'],
                            'filter' => Html::activeDropDownList($searchModel,'status',['1'=>'正常','0'=>'删除','2' => '未使用'],['prompt'=>'全部'])
                        ],
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
                            'header' => '操作',
                            'template' => '{view} {delete} ',
                            'buttons' => [
                                'view' => function($url, $model, $key){
                                   return Html::a('查看',['view', 'id' => $key], ['class' => 'btn btn-sm btn-success',]
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
