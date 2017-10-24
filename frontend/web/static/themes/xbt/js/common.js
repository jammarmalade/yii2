;
(function () {
    function footerPosition() {
        $("footer").removeClass("fixed-bottom");
        var contentHeight = document.body.scrollHeight, //网页正文全文高度
                winHeight = window.innerHeight;//可视窗口高度，不包括浏览器顶部工具栏
        if (!(contentHeight > winHeight)) {
            //当网页正文高度小于可视窗口高度时，为footer添加类fixed-bottom
            $("footer").addClass("fixed-bottom");
        } else {
            $("footer").removeClass("fixed-bottom");
        }
    }
    footerPosition();
    $(window).resize(footerPosition);
    
    //dropdown
    $('.dropdown').unbind("mouseover").mouseover(function () {
        $(this).addClass('open');
    })
    $('.dropdown').unbind("mouseout").mouseout(function () {
        $(this).removeClass('open');
    })
})();
var layer;
layui.use('layer', function () {
    layer = layui.layer;
})
/**
 * 弹出层
 */
function showDialog(d){
    var yesFun = d['yes'];
    var params = {
        type: 1,
        id: d['id'],
        shade: d['shade'] >=0 ? d['shade'] : 0 ,//遮罩层 默认0.3，0为不显示
        area: d['area'] ? d['area'] : 'auto',//宽高 ['500px',500px]
        scrollbar : d['scrollbar']==true ? true : false,//隐藏滚动条 true / false
        closeBtn: d['closeBtn'] >=0 ? d['closeBtn'] : 1,//关闭按钮
        title: d['title'] ? d['title'] : '信息',
        shadeClose: true, //点击遮罩关闭
        content: d['content'],
        btn: d['btn'] ? d['btn'] : '确认',
        fixed: true,
        yes: function(){
            if( typeof yesFun == 'function' ){
                return yesFun(this);
            }else{
                layer.close(layer.index);
            }
        },
        zIndex: layer.zIndex,
        success: function(layero, index){
            layer.setTop(layero);
        }
    };
    //弹出位置：t 顶部， r 右边，b 底部，l 左边
    if(d['offset']){
        params['offset'] = d['offset'];
    }
    layer.open(params);
}
function showMsg(msg,icon){
    //1 勾 ，7 感叹号
    icon = icon ? icon : 7;
    layer.alert(msg, {icon: icon,skin: 'layer-ext-moon'});
}
function showConfirm(msg , funcOk ,funcClose,okMsg,cancelMsg){
    if( typeof funcClose != 'function' ){
        funcClose = function(){
            layer.close();
        }
    }
    okMsg = okMsg ? okMsg : '确定';
    cancelMsg = cancelMsg ? cancelMsg : '取消';
    layer.confirm(msg, {
        btn: [okMsg, cancelMsg] //按钮
    },funcOk, funcClose);
}