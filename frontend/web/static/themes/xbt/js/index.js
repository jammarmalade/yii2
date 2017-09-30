;(function(){
    //切换
    $('.switch-li').mouseover(function(){
        console.log('xx');
        var num = $(this).index();
        $('.bd-news').hide();
        $('.bd-news').eq(num).show();
        $('.cur').removeClass('cur');
        $(this).addClass('cur');
    });
})();
$("img.lazy").lazyload({
    effect: "fadeIn"
});