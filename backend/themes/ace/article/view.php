<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\assets\AppAsset;

/* @var $this yii\web\View */
/* @var $model common\models\Article */

//引入代码高亮文件
AppAsset::addCss($this, 'ueditor/third-party/SyntaxHighlighter/shCoreDefault.css',true);
AppAsset::addScript($this, 'ueditor/third-party/SyntaxHighlighter/shCore.min.js', yii\web\View::POS_HEAD);
AppAsset::addScript($this, 'lazyload.min.js', yii\web\View::POS_HEAD);

$this->title = $articleInfo->subject;
$this->params['breadcrumbs'][] = ['label' => '文章列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('修改', ['create', 'id' => $articleInfo->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('新增', ['create'], ['class' => 'btn btn-success']) ?>
        <?php
        if($articleInfo['status']==1){
            Html::a('删除', ['delete', 'id' => $articleInfo->id,'status'=>2], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => '确认删除吗?',
                    'method' => 'post',
                ],
            ]);
        }else{
            Html::a('恢复', ['delete', 'id' => $articleInfo->id,'status'=>1], [
                'class' => 'btn btn-success',
                'data' => [
                    'confirm' => '确认恢复',
                    'method' => 'post',
                ],
            ]);
        } 
        ?>
    </p>
    <?= DetailView::widget([
        'model' => $articleInfo,
        'attributes' => [
            'id',
            'uid',
            'username',
            'subject',
            'description',
            'like',
            'view',
            'comment',
            'image_id',
            'status',
            'time_update',
            'time_create',
        ],
    ]) ?>
    <h1>内容详情</h1>
    <div id="show_content">
        <?= $articleInfo->content ?>
    </div>
</div>

<?php $this->beginBlock("code") ?>
SyntaxHighlighter.all();
$("img.lazy").lazyload({
    effect: "fadeIn"
});
<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks["code"], \yii\web\View::POS_END); ?>
