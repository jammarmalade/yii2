<?php

use frontend\assets\AppAsset;
use yii\helpers\Url;
use frontend\components\Functions as tools;


//引入代码高亮文件
AppAsset::addCss($this, '/js/ueditor/third-party/SyntaxHighlighter/shCoreDefault.css',true);
AppAsset::addScript($this, 'ueditor/third-party/SyntaxHighlighter/shCore.min.js');
//延迟加载
AppAsset::addScript($this, 'lazyload.min.js');
AppAsset::addCss($this, 'index.css',false, yii\web\View::POS_BEGIN);
AppAsset::addScript($this, 'index.js');
//图片查看
AppAsset::addCss($this, 'viewer.min.css');
AppAsset::addScript($this, 'viewer.min.js');
/* @var $this yii\web\View */
$this->title = $articleInfo['subject'];
?>

<div class="site-article box">
    <div id="article-head">
        <div class="item-tag">
            <?php foreach($articleInfo['tagList'] as $tag){?>
                <a href="<?=Url::to(['tag/index','id'=>$tag['tid']])?>"><?=$tag['tagname']?></a>
            <?php }?>
        </div>
        <span id="subject"><?=$articleInfo['subject']?></span>
        <div class="autor article-autor">
            <div>
                <span class="time"><span class="glyphicon glyphicon-time"aria-hidden="true"></span><?=$articleInfo['date']?></span>
                <span><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>浏览（<a href="javascript:;"><?=$articleInfo['view']?></a>）</span>
            </div>
        </div>
    </div>
    <div id="content">
        <?=$articleInfo['content']?>
    </div>
    <?php if($articleInfo['copyright']){?>
    <div id="copyright">
        本内容为博主原创，转载请注明出处。本文链接 <a href="<?=$selfUrl?>"><?=$selfUrl?></a>
    </div>
    <?php }?>
</div>
<?php $this->beginBlock("article") ?>
SyntaxHighlighter.all();
var viewer = new Viewer(document.getElementById('content'), {
    url: 'data-big',
    title: false,
});
<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks["article"], \yii\web\View::POS_END); ?>


