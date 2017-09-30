<?php

use frontend\assets\AppAsset;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use frontend\components\Functions as tools;

//延迟加载
AppAsset::addScript($this, 'lazyload.min.js');
AppAsset::addCss($this, 'index.css',false, yii\web\View::POS_BEGIN);
AppAsset::addScript($this, 'index.js');

/* @var $this yii\web\View */
$this->title = '首页';
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
                            <h3><a href="javascript:;"><?=$article['subject']?></a></h3>
                            <p class="article-description"><?=$article['description']?></p>
                            <p class="autor">
                                <span class="time"><span class="glyphicon glyphicon-time" style="color: rgb(109, 160, 255);" aria-hidden="true"></span><?=$article['date']?></span>
                                <span><span class="glyphicon glyphicon-eye-open" aria-hidden="true" ></span>浏览（<a href="javascript:;"><?=$article['view']?></a>）</span>
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
                        <li class="switch-li"><a href="javascript:;">推荐文章</a></li>
                    </ul>
                </div>
                <div class="ms-main" id="ms-main">
                    <div style="display: block;" class="bd bd-news" >
                        <ul>
                            <li><span>1</span><a href="javascript:;" target="_blank">住在手机里的朋友</a></li>
                            <li><span>2</span><a href="javascript:;" target="_blank">教你怎样用欠费手机拨打电话</a></li>
                            <li><span>3</span><a href="javascript:;" target="_blank">原来以为，一个人的勇敢是，删掉他的手机号码...</a></li>
                            <li><span>4</span><a href="javascript:;" target="_blank">手机的16个惊人小秘密，据说99.999%的人都不知</a></li>
                            <li><span>5</span><a href="javascript:;" target="_blank">你面对的是生活而不是手机</a></li>
                            <li><span>6</span><a href="javascript:;" target="_blank">豪雅手机正式发布! 在法国全手工打造的奢侈品</a></li>
                        </ul>
                    </div>
                    <div  class="bd bd-news">
                        <ul>
                            <li><span>1</span><a href="javascript:;" target="_blank">豪雅手机正式发布! 在法国全手工打造的奢侈品</a></li>
                            <li><span>2</span><a href="javascript:;" target="_blank">原来以为，一个人的勇敢是，删掉他的手机号码...</a></li>
                            <li><span>3</span><a href="javascript:;" target="_blank">教你怎样用欠费手机拨打电话</a></li>
                            <li><span>4</span><a href="javascript:;" target="_blank">手机的16个惊人小秘密，据说99.999%的人都不知</a></li>
                            <li><span>5</span><a href="javascript:;" target="_blank">你面对的是生活而不是手机</a></li>
                            <li><span>6</span><a href="javascript:;" target="_blank">住在手机里的朋友</a></li>
                        </ul>
                    </div>
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
                    <li><a href="https://www.baidu.com/">百度</a></li>
                    <li><a href="http://www.yiichina.com/doc/guide/2.0">Yii2.0权威指南</a></li>
                    <li><a href="https://www.aliyun.com/">阿里云</a></li>
                    <li><a href="http://www.bootcss.com/">Bootstrap</a></li>
                    <li><a href="http://layer.layui.com/">layer弹层</a></li>
                </ul>
            </div>
        </div>
        <!--r_box end --> 
    </article>
</div>


