SyntaxHighlighter.all();
//图片查看
var viewer = new Viewer(document.getElementById('content'), {
    url: 'data-big',
    title: false,
    navbar: false
});
$("img.lazy").lazyload({
    effect: "fadeIn",
    threshold : $(window).height()
});
//markdown编辑器
var simplemde = new SimpleMDE({
    element: document.getElementById("comment_content"),//textarea的DOM对象
    //autoDownloadFontAwesome: false,//自动下载FontAwesome
    status: false//编辑器底部的状态栏
});
jQuery(document).ready(function () {
    //提交评论
    $('#comment_btn').click(function(){
        var htmlMarkdown = simplemde.markdown(simplemde.value());
        if($.trim(htmlMarkdown)==''){
            showMsg('评论内容不能为空');
            return false;
        }
        var aid = $(this).attr('data-aid');
        var rid = $(this).attr('data-rid');
        //提交
        $.post(window.URL_COMMENT_ADD,{'content':htmlMarkdown,'rid': rid,'type': 1,'aid': aid},function(d){
            if(d.status){
                var data = d.data;
                var commentHtml = d.msg;
                cancleReply();
                simplemde.value('');
                //将评论的内容插入到 comment_list
                var insertDom;
                if(data.rid){
                    insertDom = $('#reply_list_'+data.rid);
                    insertDom.append(commentHtml);
                }else{
                    insertDom = $('#list_data');
                    insertDom.prepend(commentHtml);
                }
                //跳转到插入位置
                $(document.body).animate({scrollTop: insertDom.offset().top - 100}, 200);
                //重新绑定点赞和回复事件
                $('.opt-reply').unbind('click').bind('click',function(){
                    clickReply($(this));
                })
            }else{
                showMsg(d.msg);
                return false;
            }
        },'json')
    })
    $('#cancel_reply').click(function(e){
        cancleReply();
    })
    //点击回复
    $('.opt-reply').click(function(){
        clickReply($(this));
    })
    function clickReply(_this){
        var bodyDom = _this.parent('div').parent('div');
        var rid = bodyDom.attr('data-rid');
        var username = bodyDom.find('.media-heading').text();
        $('#reply_area').show();
        $('#reply_username').text(username);
        $('#comment_btn').attr('data-rid',rid);
    }
    function cancleReply(){
        $('#reply_area').hide();
        $('#reply_username').text('');
        $('#comment_btn').attr('data-rid','');
    }
    //ajax 获取评论列表
    $('.pagination a').click(function(e){
        getCommentList($(this),e);
    })
    function getCommentList(_this,e){
        e.preventDefault();
        var url = _this.attr('href');
        $.get(url+'&do=getComment',function(d){
            console.log(d.data);
            $('#comment_list').html(d.data);
            $('.pagination a').click(function(e){
                getCommentList($(this),e);
            })
        },'json');
    }
});

