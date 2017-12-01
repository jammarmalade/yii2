<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use yii\jui\DatePicker;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SourceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '数据列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="source-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'sid',
            [
                'attribute' => 'name',
                'label' => '站点域名',
                'value' => function($model){
                    $sites = $model->siteName();
                    return $sites[$model->name];   
                },
                'headerOptions' => ['width' => '100'],
                'filter' => Html::activeDropDownList($searchModel,'name',$searchModel->siteName(),['prompt'=>'全部'])
            ],
            [
                'attribute' => 'subject',
                'label' => '标题',
                'value' => function($model){
                    return Html::a($model->subject,$model->surl , ["target" => "_blank"]);
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'path',
                'label' => '图片',
                'value' => function($model) use($imageList){
                    if(isset($imageList[$model->id])){
                        $url = \Yii::$app->params['SERVER_IMG'].$imageList[$model->id]->path;
                        $defaultUrl = \Yii::$app->params['SERVER_IMG'].'static/image/404.png';
                        $imgStr = Html::img($url,['class' => 'img-list','onerror'=>'this.src=\''.$defaultUrl.'\'']).'<br>';
                        return '<ul class="ace-thumbnails"><li>'.$imgStr.'</li></ul>';
                    }else{
                        return '暂未获取';
                    }
                },
                'format' => 'raw',
                'visible' => !in_array(Yii::$app->request->get('SourceSearch')['status'], [1]) ? true : false
            ],
            [
                'attribute' => 'time_create',
                'label' => '创建时间',
                'value' => function($model){
                    return  $model->time_create;   
                },
                'headerOptions' => ['width' => '200'],
//                'format' => ['datetime','php:Y-m-d H:i:s'],
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
            'content:html',
            [
                'attribute' => 'status',
                'label' => '状态',
                'value' => function($model){
                    $state = $model->statusType();
                    return $state[$model->status];   
                },
                'headerOptions' => ['width' => '70'],
                'filter' => Html::activeDropDownList($searchModel,'status',$searchModel->statusType(),['prompt'=>'全部'])
            ],
            [
                'attribute' => 'digest',
                'label' => '精华',
                'visible' => !in_array(Yii::$app->request->get('SourceSearch')['status'], [2]) ? true : false,
                'value' => function($model){
                    $state = $model->digestList();
                    return $state[$model->digest];   
                },
                'filter' => Html::activeDropDownList($searchModel,'digest',$searchModel->digestList(),['prompt'=>'全部'])
            ],
            [
                'attribute' => 'tags',
                'label' => '标签',
                'visible' => !in_array(Yii::$app->request->get('SourceSearch')['status'], [2]) ? true : false
            ],
            [
                'attribute' => 'exe_time',
                'label' => '最后执行时间',
//                'format' => ['datetime','php:Y-m-d H:i:s'],
                'headerOptions' => ['width' => '150'],
                'visible' => Yii::$app->request->get('SourceSearch')['status'] == 2
            ],
            [
                'attribute' => 'page',
                'label' => '第几页',
                'visible' => Yii::$app->request->get('SourceSearch')['status'] == 2
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '<p>{view}</p><p>{used}</p><p>{viewed}</p><p>{unget}</p><p>{digest}</p>',
                'buttons' => [
                    'view' => function($url, $model, $key){
                       return Html::a('查看',['view', 'id' => $key], ['class' => 'btn btn-sm btn-primary','target'=>'_blank']);
                    },
                    'used' => function($url, $model, $key){
                        if($model->status != 4){
                            return Html::a('置为已使用','javascript:;', ['class' => 'btn btn-sm btn-success ajax','data-href' => Url::to(['source/ajaxupdate','id'=>$key,'status'=>4])]);
                        }
                    },
                    'viewed' => function($url, $model, $key){
                        if($model->status != 5){
                            return Html::a('置为已查看','javascript:;', ['class' => 'btn btn-sm ajax','data-href' => Url::to(['source/ajaxupdate','id'=>$key,'status'=>5])]);
                        }
                    },
                    'unget' => function($url, $model, $key){
                        return Html::a('置为未获取','javascript:;', ['class' => 'btn btn-sm btn-danger ajax','data-href' => Url::to(['source/ajaxupdate','id'=>$key,'status'=>1])]);
                    },
                    'digest' => function($url, $model, $key){
                        if($model->digest == 0){
                            return Html::a('置为精华','javascript:;', ['class' => 'btn btn-sm btn-pink ajax','data-href' => Url::to(['source/ajaxdigest','id'=>$key,'digest'=>1])]);
                        }else{
                            return Html::a('取消精华','javascript:;', ['class' => 'btn btn-sm btn-pink ajax','data-href' => Url::to(['source/ajaxdigest','id'=>$key,'digest'=>0])]);
                        }
                    },
                ],
            ],
        ],
    ]); ?>

</div>
<?php $this->beginBlock("index") ?>
jQuery(document).ready(function () {
    $("#time_create_from,#time_create_to").tooltip({
        hide: {
            effect: "explode",
            delay: 250
        }
    });
    $('.ajax').on('click',function(){
        var _this = $(this);
        var url = _this.attr('data-href');
        if(!confirm('确认操作？')){
            return false;
        }
        $.ajax({
            url: url,
            type: 'get',
            dataType:'json',
            success: function (res) {
                if(res.status){
                    //alert('操作成功');
                    _this.remove();
                }
            }
        });
    })
});
<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks["index"], \yii\web\View::POS_END); ?>
