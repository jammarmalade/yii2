<?php

use yii\helpers\Html;
use backend\assets\AppAsset;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Article */

AppAsset::addScript($this, 'ueditor/ueditor.config.js');
AppAsset::addScript($this, 'ueditor/ueditor.all.min.js');
AppAsset::addScript($this, 'jquery-ui.min.js');

$this->title = '新增文章';
$this->params['breadcrumbs'][] = ['label' => '文章列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
    #content_area,#tag_area{
        margin-top: 10px;
    }
    #tag_show_area{
        display: inline-block;
    }
    .boot-input {
        display: block;
        width: 100%;
        height: 40px;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 4px;
        -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
        box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
        -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
        -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
        transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    }
    #search_tag_input{
        width:100%;
        border:0;
    }
    .input-icon{
        margin-bottom: 5px;
    }
</style>
<div class="article-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <input type="text" class="form-control" id="subject" placeholder="文章标题">
    <div id="content_area">
        <div id="editor"></div>
    </div>
    <div id="tag_area" class="boot-input">
        <div id="tag_show_area">
            <span class="tag"><m>上班</m><button type="button" class="close delete-tag" data-tagid="51">×</button></span>
        </div>
        <span class="input-icon">
            <input type="text" placeholder="搜索标签" class="nav-search-input" id="search_tag_input" autocomplete="off">
            <i class="icon-search nav-search-icon"></i>
        </span>
    </div>
</div>

<?php $this->beginBlock("index") ?>
jQuery(document).ready(function () {
    //var ue = UE.getEditor('editor');
    //绑定搜索
    $('#search_tag_input').autocomplete({
        minLength: 1,
        source: '<?php echo Url::to(['tag/search']);?>',
        focus: function() {
            // 防止在获得焦点时插入值
            return false;
        },
        select: function(e, ui) {
            event.preventDefault(); 
            
            var tagid = ui.item.value;
            var tagname = ui.item.label;

            if (tagid == -1 || tagid == -2) {
                return false;
            }
            //若是新增标签
            if(tagid==0){
                var arr = tagname.split(' ');
                if(arr[0] =='创建' && arr[(arr.length - 1)]=='标签'){
                    arr.splice(0,1);
                    arr.splice((arr.length - 1),1);
                }
                tagname = arr.join(' ')
            }
            var length = mb_strlen(tagname);
            if (length > 30) {
                alert('标签长度只能是20个字符或10个汉字');
                return false;
            }
            var showTagArea = $('#tag_show_area');
            var errortitle = '';
            showTagArea.find('m').each(function () {
                if (tagname == $(this).text()) {
                    errortitle = '该标签已存在';
                    return false;
                }
            });
            showTagArea.find('button').each(function () {
                if (tagid == $(this).attr('data-tagid')) {
                    errortitle = '该标签已存在';
                    return false;
                }
            });
            if (errortitle != '') {
                alert(errortitle);
                return false;
            }
            $.post('<?php echo Url::to(['tag/add']);?>',{'tagname': tagname},function(d){
                if(d.status){
                    tagid = d.data;
                    var html = '<span class="tag"><m>'+tagname+'</m><button type="button" class="close delete-tag" data-tagid="'+tagid+'">×</button></span>';
                    showTagArea.append(html);
                    //绑定删除
                    $(".delete-tag").unbind('click').on('click', function(e) {
                        deleteTag($(this),e);
                    });
                }else{
                    alert(d.msg);
                }
            },'json')
            this.value = '';
            return false;
        }
    }).data("ui-autocomplete")._renderItem = function( ul, item ) {
        console.log(item.value);
        var html = '<span>' + item.label + '</span>';
        if (item.value == -1) {
            html = '<span>' + item.label + '　正在审核</span>';
        }
        if (item.value == -2) {
            html =  '<span>请使用已存在的标签</span>';
        }
        return $("<li>").append(html).appendTo( ul );
    };
    $(".delete-tag").unbind('click').on('click', function(e) {
        deleteTag($(this),e);
    });
    function deleteTag(_this,e){
        e.preventDefault();
        _this.parent().remove();
    }
});
<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks["index"], \yii\web\View::POS_END); ?>
