<?php

use frontend\assets\AppAsset;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use frontend\components\Functions as tools;

//延迟加载
AppAsset::addScript($this, 'lazyload.min.js');
AppAsset::addCss($this, 'index.min.css',false, yii\web\View::POS_BEGIN);
AppAsset::addScript($this, 'index.js');

/* @var $this yii\web\View */
$confg = $this->params['config'];
$this->title = $confg['siteName'];
?>
<div class="site-index">
    <article>
        <div class="l_box f_l box">
            <!-- banner代码 结束 -->
            <div class="topnews">
                <h2><b>最新发布</b></h2>
                <?php foreach($dataList as $k=>$article){ ?>
                    <div class="blogs">
                        <figure><img src="" class="lazy" data-original="<?=$article['faceUrl']?>"></figure>
                        <ul>
                            <h3><a href="<?=Url::to(['article/info','id'=>$article['id']])?>"><?=$article['subject']?></a></h3>
                            <p class="article-description"><?=$article['description']?></p>
                            <p class="autor">
                                <span class="time" title="<?=$article['date']?>"><span class="glyphicon glyphicon-time" style="color: rgb(109, 160, 255);" aria-hidden="true"></span><?= tools::formatTime($article['time_create'], 1, 'Y-m-d')?></span>
                                <span><span class="glyphicon glyphicon-eye-open" aria-hidden="true" ></span>浏览（<?=$article['view']?>）</span>
                                <br>
                                <span class="item-tag">
                                    <span class="glyphicon glyphicon-tag" aria-hidden="true"></span>
                                    <?php foreach($article['tagList'] as $tag){?>
                                        <a href="<?=Url::to(['tag/index','id'=>$tag['tid']])?>"><?=$tag['tagname']?></a>
                                    <?php }?>
                                </span>
                            </p>
                        </ul>
                    </div>
                <?php } ?>
            </div>
            <div class="div-page">
                <?= LinkPager::widget([
                    'pagination' => $pages,
                    'maxButtonCount'=>5,
                    'nextPageLabel' => '下一页',
                    'prevPageLabel' => '上一页',
                    ]); ?>
            </div>
        </div>
        <div class="r_box f_r">
            <!--<div class="ad300x100"> </div>-->
            <div class="moreSelect box" id="lp_right_select">
                <div class="ms-top">
                    <ul class="hd" id="tab">
                        <li class="switch-li cur"><a href="javascript:;">点击排行</a></li>
                        <?php if($recommendList){?>
                        <li class="switch-li"><a href="javascript:;">推荐文章</a></li>
                        <?php }?>
                    </ul>
                </div>
                <div class="ms-main" id="ms-main">
                    <div style="display: block;" class="bd bd-news" >
                        <ul>
                            <?php foreach($topList as $k=>$v){?>
                                <li><span><?php echo ++$k;?></span><a href="<?=Url::to(['article/info','id'=>$v['id']])?>" target="_blank" title="<?=$v['subject']?>"><?=$v['subject']?></a></li>
                            <?php }?>
                        </ul>
                    </div>
                    <?php if($recommendList){?>
                    <div  class="bd bd-news">
                        <ul>
                            <?php foreach($recommendList as $k=>$v){?>
                            <li><span><?php echo ++$k;?></span><a href="<?=Url::to(['article/info','id'=>$v['id']])?>" target="_blank" title="<?=$v['subject']?>"><?=$v['subject']?></a></li>
                            <?php }?>
                        </ul>
                    </div>
                    <?php }?>
                </div>
                <!--ms-main end -->
            </div>
            <!--切换卡 moreSelect end -->

            <div class="cloud box">
                <h3>标签云</h3>
                <ul>
                    <?php foreach($tagList as $k=>$tag){?>
                    <li style="background-color: <?=tools::randomColor()?>;"><a href="<?=Url::to(['tag/index','id'=>$tag['id']])?>"><?=$tag['name']?></a></li>
                    <?php }?>
                </ul>
            </div>
<!--            <div class="tuwen box">
                <h3>图文推荐</h3>
                <ul>
                    <li><a href="javascript:;"><img src="<?=$this->params['staticImgUrl']?>/01.jpg"><b>住在手机里的朋友</b></a>
                        <p><span class="tulanmu"><a href="javascript:;">手机配件</a></span><span class="tutime">2015-02-15</span></p>
                    </li>
                </ul>
            </div>-->
            <!--<div class="ad"> </div>-->
            <div class="links box" >
                <h3><span>[<a href="javascript:;">申请友情链接</a>]</span>友情链接</h3>
                <ul>
                    <?php foreach($friendLinkList as $k=>$v){?>
                        <li><a href="<?=$v['url']?>" target="_blank"><?=$v['name']?></a></li>
                    <?php }?>
                </ul>
            </div>
        </div>
        <!--r_box end -->
    </article>
</div>

