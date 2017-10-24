<?php
use frontend\assets\AppAsset;
use yii\helpers\Url;
use frontend\components\Functions as tools;

//引入代码高亮文件
AppAsset::addCss($this, '/js/ueditor/third-party/SyntaxHighlighter/shCoreDefault.css',true);
AppAsset::addScript($this, 'ueditor/third-party/SyntaxHighlighter/shCore.min.js');
//延迟加载
AppAsset::addScript($this, 'lazyload.min.js');
//图片查看
AppAsset::addCss($this, 'viewer.min.css');
AppAsset::addScript($this, 'viewer.min.js');
AppAsset::addCss($this, 'index.min.css',false, yii\web\View::POS_BEGIN);
AppAsset::addCss($this, 'article.css',false, yii\web\View::POS_BEGIN);
AppAsset::addScript($this, 'index.js');
//markdown编辑器
AppAsset::addCss($this, 'simplemde.css');
AppAsset::addScript($this, 'simplemde.js');

//本页js
AppAsset::addScript($this, 'article.js');
/* @var $this yii\web\View */
$this->title = $articleInfo['subject'];
$tagNames = join(',',array_column($articleInfo['tagList'], 'tagname'));
$this->metaTags[]="<meta name='keywords' content='$tagNames'/>";
$this->metaTags[]="<meta name='description' content='".$articleInfo['description']."'/>";
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
                <span class="time" title="<?=$articleInfo['date']?>"><span class="glyphicon glyphicon-time"aria-hidden="true"></span><?= tools::formatTime($articleInfo['time_create'], 1, 'Y-m-d')?></span>
                <span><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>浏览（<a href="javascript:;"><?=$articleInfo['view']?></a>）</span>
                <?php if($articleInfo['time_update']!=$articleInfo['time_create']){?>
                <span title="<?=$articleInfo['time_update']?>">更新于 <?= tools::formatTime($articleInfo['time_update'], 1, 'Y-m-d')?></span>
                <?php }?>
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
    <div id="comment_area">
        <div id="comment_title">评论<span class="count">（<?=$articleInfo['comment']?>）</span></div>
        <div id="comment_list">
            
            <div class="media">
                <div class="media-left">
                    <a href="#">
                        <img class="media-object" src="<?=$this->params['defaultHeadImg'];?>" >
                    </a>
                </div>
                <div class="media-body">
                    <h4 class="media-heading">Media heading</h4>
                    <div class="media-body-content">测试一下贝恩嫩嗯嗯嗯呢</div>
                    <div class="media-body-opt">
                        <span class="opt-time"><span class="glyphicon glyphicon-time" aria-hidden="true"></span> 2017-10-24 17:18</span>
                        <span class="opt-like"><span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> <m>10</m></span>
                        <span class="opt-reply"><span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span> 回复</span>
                    </div>
                    <div class="reply-list">
                        <div class="media">
                            <div class="media-left">
                                <a href="#">
                                    <img class="media-object" src="<?=$this->params['defaultHeadImg'];?>" >
                                </a>
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading">User11</h4>
                                <div class="media-body-content">测试一下贝恩嫩嗯嗯嗯呢</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <div id="comment_add_area">
        <div id="comment_add_title">发布评论</div>
        <div id="comment_editor">
            <textarea name="comment_content" id="comment_content"></textarea>
        </div>
        <div id="comment_opt" class="clearfix">
            <div id="reply_area">回复<span id="reply_username">admin</span><a href="javascript:;" id="cancel_reply" title="取消回复">X</a></div>
            <button type="button" class="btn btn-default" id="comment_btn" data-rid="" data-aid="<?=$articleInfo['id']?>">提交</button>
        </div>
    </div>
</div>
<?php $this->beginBlock("links") ?>
window.URL_COMMENT_ADD = '<?php echo Url::to(['comment/add']);?>';
<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks["links"], \yii\web\View::POS_HEAD); ?>


