SyntaxHighlighter.all();
//图片查看
var viewer = new Viewer(document.getElementById('content'), {
    url: 'data-big',
    title: false,
    navbar: false
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
                cancleReply();
                simplemde.value('');
                //将评论的内容插入到 comment_list
            }else{
                showMsg(d.msg);
                return false;
            }
        },'json')
    })
    $('#cancel_reply').click(function(e){
        cancleReply();
    })
    function cancleReply(){
        $('#reply_area').hide();
        $('#reply_username').text('');
        $('#comment_btn').attr('data-rid','');
    }
});

