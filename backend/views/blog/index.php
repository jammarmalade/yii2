<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\BlogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

Modal::begin([
    'id' => 'operate-modal',
    'header' => '<h4 class="modal-title"></h4>',
]); 
Modal::end();

$requestCreateUrl = Url::toRoute('create');
$js = <<<JS
// 创建操作
$('#create').on('click', function () {
    $('.modal-title').html('创建栏目');
    $.get('{$requestCreateUrl}',
        function (data) {
      // 弹窗的主题渲染页面
            $('.modal-body').html(data);
        }  
    );
});
JS;
$this->registerJs($js);

$this->title = 'Blogs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blog-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Blog', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            'content:ntext',
            'views',
            'status',
            // 'create_time',
            // 'update_time',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<!--创建一个按钮，并为其设置一个id,且设置属性data-toggle=moal && data-target属性指向刚刚创建的modal的id.-->
    <?= 
        Html::a('创建blog', ['create'], [
            'class' => 'btn btn-success',
            'id' => 'create', // 按钮的id随意
            'data-toggle' => 'modal', // 固定写法
            'data-target' => '#operate-modal', // 等于4.1begin中设定的参数id值
        ]) 
    ?>
</div>
