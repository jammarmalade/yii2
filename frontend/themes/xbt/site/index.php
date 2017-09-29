<?php

use frontend\assets\AppAsset;
use yii\helpers\Url;

AppAsset::addCss($this, 'index.css',false, yii\web\View::POS_BEGIN);
AppAsset::addScript($this, 'index.js');

/* @var $this yii\web\View */
$this->title = '博客首页';
$imageUrl = Yii::$app->view->theme->baseUrl.'/images';
?>
<div class="site-index">
    <article>
        <div class="l_box f_l box">
            <!-- banner代码 结束 -->
            <div class="topnews">
                <h2><b>最新发布</b></h2>
                <div class="blogs">
                    <figure><img src="<?=$imageUrl?>/01.jpg"></figure>
                    <ul>
                        <h3><a href="javascript:;">住在手机里的朋友</a></h3>
                        <p>通信时代，无论是初次相见还是老友重逢，交换联系方式，常常是彼此交换名片，然后郑重或是出于礼貌用手机记下对方的电话号码。在快节奏的生活里，我们不知不觉中就成为住在别人手机里的朋友。又因某些意外，变成了别人手机里匆忙的过客，这种快餐式的友谊 ...</p>
                        <p class="autor"><span class="lm f_l"><a href="javascript:;">个人博客</a></span><span class="dtime f_l">2014-02-19</span><span class="viewnum f_r">浏览（<a href="javascript:;">459</a>）</span><span class="pingl f_r">评论（<a href="javascript:;">30</a>）</span></p>
                    </ul>
                </div>
                <div class="blogs">
                    <figure><img src="<?=$imageUrl?>/02.jpg"></figure>
                    <ul>
                        <h3><a href="javascript:;">教你怎样用欠费手机拨打电话</a></h3>
                        <p>初次相识的喜悦，让你觉得似乎找到了知音。于是，对于投缘的人，开始了较频繁的交往。渐渐地，初识的喜悦退尽，接下来就是仅仅保持着联系，平淡到偶尔在节假曰发短信互致问候...</p>
                        <p class="autor"><span class="lm f_l"><a href="javascript:;">个人博客</a></span><span class="dtime f_l">2014-02-19</span><span class="viewnum f_r">浏览（<a href="javascript:;">459</a>）</span><span class="pingl f_r">评论（<a href="javascript:;">30</a>）</span></p>
                    </ul>
                </div>
                <div class="blogs">
                    <figure><img src="<?=$imageUrl?>/03.jpg"></figure>
                    <ul>
                        <h3><a href="javascript:;">原来以为，一个人的勇敢是，删掉他的手机号码...</a></h3>
                        <p>原来以为，一个人的勇敢是，删掉他的手机号码、QQ号码等等一切，努力和他保持距离。等着有一天，习惯不想念他，习惯他不在身边,习惯时间把他在我记忆里的身影磨蚀干净... </p>
                        <p class="autor"><span class="lm f_l"><a href="javascript:;">个人博客</a></span><span class="dtime f_l">2014-02-19</span><span class="viewnum f_r">浏览（<a href="javascript:;">459</a>）</span><span class="pingl f_r">评论（<a href="javascript:;">30</a>）</span></p>
                    </ul>
                </div>
                <div class="blogs">
                    <figure><img src="<?=$imageUrl?>/04.jpg"></figure>
                    <ul>
                        <h3><a href="javascript:;">手机的16个惊人小秘密，据说99.999%的人都不知</a></h3>
                        <p>引导语：知道么，手机有备用电池，手机拨号码12593+电话号码=陷阱……手机具有很多你不知道的小秘密，说出来一定很惊奇！不信的话就来看看吧！...</p>
                        <p class="autor"><span class="lm f_l"><a href="javascript:;">个人博客</a></span><span class="dtime f_l">2014-02-19</span><span class="viewnum f_r">浏览（<a href="javascript:;">459</a>）</span><span class="pingl f_r">评论（<a href="javascript:;">30</a>）</span></p>
                    </ul>
                </div>
                <div class="blogs">
                    <figure><img src="<?=$imageUrl?>/05.jpg"></figure>
                    <ul>
                        <h3><a href="javascript:;">你面对的是生活而不是手机</a></h3>
                        <p>每一次与别人吃饭，总会有人会拿出手机。以为他们在打电话或者有紧急的短信，但用余光瞟了一眼之后发现无非就两件事：1、看小说，2、上人人或者QQ...</p>
                        <p class="autor"><span class="lm f_l"><a href="javascript:;">个人博客</a></span><span class="dtime f_l">2014-02-19</span><span class="viewnum f_r">浏览（<a href="javascript:;">459</a>）</span><span class="pingl f_r">评论（<a href="javascript:;">30</a>）</span></p>
                    </ul>
                </div>
                <div class="blogs">
                    <figure><img src="<?=$imageUrl?>/06.jpg"></figure>
                    <ul>
                        <h3><a href="javascript:;">豪雅手机正式发布! 在法国全手工打造的奢侈品</a></h3>
                        <p>现在跨界联姻，时尚、汽车以及运动品牌联合手机制造商联合发布手机产品在行业里已经不再新鲜，上周我们给大家报道过著名手表制造商瑞士泰格·豪雅（Tag Heuer） 联合法国的手机制造商Modelabs发布的一款奢华手机的部分谍照，而近日该手机终于被正式发布了...</p>
                        <p class="autor"><span class="lm f_l"><a href="javascript:;">个人博客</a></span><span class="dtime f_l">2014-02-19</span><span class="viewnum f_r">浏览（<a href="javascript:;">459</a>）</span><span class="pingl f_r">评论（<a href="javascript:;">30</a>）</span></p>
                    </ul>
                </div>
                <div class="blogs">
                    <figure><img src="<?=$imageUrl?>/04.jpg"></figure>
                    <ul>
                        <h3><a href="javascript:;">手机的16个惊人小秘密，据说99.999%的人都不知</a></h3>
                        <p>引导语：知道么，手机有备用电池，手机拨号码12593+电话号码=陷阱……手机具有很多你不知道的小秘密，说出来一定很惊奇！不信的话就来看看吧！...</p>
                        <p class="autor"><span class="lm f_l"><a href="javascript:;">个人博客</a></span><span class="dtime f_l">2014-02-19</span><span class="viewnum f_r">浏览（<a href="javascript:;">459</a>）</span><span class="pingl f_r">评论（<a href="javascript:;">30</a>）</span></p>
                    </ul>
                </div>
            </div>
            <div class="div-page">
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <li>
                            <a href="#" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <li class="active"><a href="#">1</a></li>
                        <li><a href="#">2</a></li>
                        <li><a href="#">3</a></li>
                        <li><a href="#">4</a></li>
                        <li><a href="#">5</a></li>
                        <li>
                            <a href="#" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <div class="r_box f_r">
            <!--tit01 end-->
            <!--<div class="ad300x100"> </div>-->
            <div class="moreSelect box" id="lp_right_select"> 
                <div class="ms-top">
                    <ul class="hd" id="tab">
                        <li class="switch-li cur"><a href="javascript:;">点击排行</a></li>
                        <li class="switch-li"><a href="javascript:;">最新文章</a></li>
                        <li class="switch-li"><a href="javascript:;">站长推荐</a></li>
                    </ul>
                </div>
                <div class="ms-main" id="ms-main">
                    <div style="display: block;" class="bd bd-news" >
                        <ul>
                            <li><span class="label-1">1</span><a href="javascript:;" target="_blank">住在手机里的朋友</a></li>
                            <li><span class="label-2">2</span><a href="javascript:;" target="_blank">教你怎样用欠费手机拨打电话</a></li>
                            <li><span class="label-3">3</span><a href="javascript:;" target="_blank">原来以为，一个人的勇敢是，删掉他的手机号码...</a></li>
                            <li><span>4</span><a href="javascript:;" target="_blank">手机的16个惊人小秘密，据说99.999%的人都不知</a></li>
                            <li><span>5</span><a href="javascript:;" target="_blank">你面对的是生活而不是手机</a></li>
                            <li><span>6</span><a href="javascript:;" target="_blank">豪雅手机正式发布! 在法国全手工打造的奢侈品</a></li>
                        </ul>
                    </div>
                    <div  class="bd bd-news">
                        <ul>
                            <li><span class="label-1">1</span><a href="javascript:;" target="_blank">豪雅手机正式发布! 在法国全手工打造的奢侈品</a></li>
                            <li><span class="label-2">2</span><a href="javascript:;" target="_blank">原来以为，一个人的勇敢是，删掉他的手机号码...</a></li>
                            <li><span class="label-3">3</span><a href="javascript:;" target="_blank">教你怎样用欠费手机拨打电话</a></li>
                            <li><span>4</span><a href="javascript:;" target="_blank">手机的16个惊人小秘密，据说99.999%的人都不知</a></li>
                            <li><span>5</span><a href="javascript:;" target="_blank">你面对的是生活而不是手机</a></li>
                            <li><span>6</span><a href="javascript:;" target="_blank">住在手机里的朋友</a></li>
                        </ul>
                    </div>
                    <div class="bd bd-news">
                        <ul>
                            <li><span class="label-1">1</span><a href="javascript:;" target="_blank">手机的16个惊人小秘密，据说99.999%的人都不知</a></li>
                            <li><span class="label-2">2</span><a href="javascript:;" target="_blank">你面对的是生活而不是手机</a></li>
                            <li><span class="label-3">3</span><a href="javascript:;" target="_blank">原来以为，一个人的勇敢是，删掉他的手机号码...</a></li>
                            <li><span>4</span><a href="javascript:;" target="_blank">住在手机里的朋友</a></li>
                            <li><span>5</span><a href="javascript:;" target="_blank">教你怎样用欠费手机拨打电话</a></li>
                            <li><span>6</span><a href="javascript:;" target="_blank">豪雅手机正式发布! 在法国全手工打造的奢侈品</a></li>
                        </ul>
                    </div>
                </div>
                <!--ms-main end --> 
            </div>
            <!--切换卡 moreSelect end -->

            <div class="cloud box">
                <h3>标签云</h3>
                <ul>
                    <li><a href="javascript:;">个人博客</a></li>
                    <li><a href="javascript:;">web开发</a></li>
                    <li><a href="javascript:;">前端设计</a></li>
                    <li><a href="javascript:;">Html</a></li>
                    <li><a href="javascript:;">CSS3</a></li>
                    <li><a href="javascript:;">Html5+css3</a></li>
                    <li><a href="javascript:;">百度</a></li>
                    <li><a href="javascript:;">Javasript</a></li>
                    <li><a href="javascript:;">web开发</a></li>
                    <li><a href="javascript:;">前端设计</a></li>
                    <li><a href="javascript:;">Html</a></li>
                    <li><a href="javascript:;">CSS3</a></li>
                    <li><a href="javascript:;">Html5+css3</a></li>
                    <li><a href="javascript:;">百度</a></li>
                </ul>
            </div>
            <div class="tuwen box">
                <h3>图文推荐</h3>
                <ul>
                    <li><a href="javascript:;"><img src="<?=$imageUrl?>/01.jpg"><b>住在手机里的朋友</b></a>
                        <p><span class="tulanmu"><a href="javascript:;">手机配件</a></span><span class="tutime">2015-02-15</span></p>
                    </li>
                    <li><a href="javascript:;"><img src="<?=$imageUrl?>/02.jpg"><b>教你怎样用欠费手机拨打电话</b></a>
                        <p><span class="tulanmu"><a href="javascript:;">手机配件</a></span><span class="tutime">2015-02-15</span></p>
                    </li>
                    <li><a href="javascript:;" title="手机的16个惊人小秘密，据说99.999%的人都不知"><img src="<?=$imageUrl?>/03.jpg"><b>手机的16个惊人小秘密，据说...</b></a>
                        <p><span class="tulanmu"><a href="javascript:;">手机配件</a></span><span class="tutime">2015-02-15</span></p>
                    </li>
                    <li><a href="javascript:;"><img src="<?=$imageUrl?>/06.jpg"><b>住在手机里的朋友</b></a>
                        <p><span class="tulanmu"><a href="javascript:;">手机配件</a></span><span class="tutime">2015-02-15</span></p>
                    </li>
                    <li><a href="javascript:;"><img src="<?=$imageUrl?>/04.jpg"><b>教你怎样用欠费手机拨打电话</b></a>
                        <p><span class="tulanmu"><a href="javascript:;">手机配件</a></span><span class="tutime">2015-02-15</span></p>
                    </li>
                </ul>
            </div>
            <!--<div class="ad"> </div>-->
            <div class="links box" >
                <h3><span>[<a href="javascript:;">申请友情链接</a>]</span>友情链接</h3>
                <ul>
                    <li><a href="javascript:;">杨青个人博客</a></li>
                    <li><a href="javascript:;">web开发</a></li>
                    <li><a href="javascript:;">前端设计</a></li>
                    <li><a href="javascript:;">Html</a></li>
                    <li><a href="javascript:;">CSS3</a></li>
                    <li><a href="javascript:;">Html5+css3</a></li>
                    <li><a href="javascript:;">百度</a></li>
                </ul>
            </div>
        </div>
        <!--r_box end --> 
    </article>
</div>
