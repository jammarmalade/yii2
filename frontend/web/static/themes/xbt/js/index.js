;(function(){
    //切换
    $('.switch-li').mouseover(function(){
        var num = $(this).index();
        $('.bd-news').hide();
        $('.bd-news').eq(num).show();
        $('.ms-top .cur').removeClass('cur');
        $(this).addClass('cur');
    });
})();
$("img.lazy").lazyload({
    effect: "fadeIn",
});