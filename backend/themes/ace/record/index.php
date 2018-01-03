<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\jui\DatePicker;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RecordSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

if($tagInfo){
    $this->title = '收支记录 - '.$tagInfo['name'];
}else{
    $this->title = '收支记录';
}
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
    .add-tag{
        cursor: pointer;
    }
    .tag-search{
        position: static;
        display: none;
    }
    .close-search-area{
        margin-left: 10px;
        font-size: 14px;
    }
    #top_search_div{
        position: static;
        width:200px;
        margin-bottom: 10px;
    }
    #top_search_input{
        width:200px;
    }
    .tag a,.tag a:hover,.tag a:focus {
        color: #fff;
        text-decoration: none;
    }
</style>
<div class="record-index">
    <div class="tabbable">
        <ul class="nav nav-tabs">
            <li class="active">
                <a data-toggle="tab" href="#list">
                    <i class="icon-list"></i>
                    记录列表
                </a>
            </li>
            <li>
                <a data-toggle="tab" href="#search">
                    <i class="icon-search nav-search-icon"></i>
                    查询
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <div id="list" class="tab-pane in active">
                <p>
                    <?= Html::a('新增收支记录', ['create'], ['class' => 'btn btn-success']) ?>
                    <?php
                    if($tagInfo){
                        echo Html::a('查看所有', ['index'], ['class' => 'btn btn-success']);
                    }
                    ?>
                </p>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
            //            ['class' => 'yii\grid\SerialColumn'],

                        'id',
                        [
                            'attribute' => 'username',
            //                'header' => '用户名',
                            'value' => function($model){
                                return Html::a($model->username,['user-backend/view','id'=>$model->uid] , ["target" => "_blank"]);
                            },
                            'format' => 'raw',
                        ],
                        'account',
                        'content:ntext',
                        [
                            'attribute' => 'address',
                            'label' => '记录地址',
                            'value' => function($model){
                                $address = $model->country.$model->province;
                                if($model->province != $model->city){
                                    $address .= $model->city;
                                }
                                $address .= $model->area.'<br>'.$model->address;
                                return $address;
                            },
                            'format' => 'raw',
                        ],
                         [
                            'attribute' => 'time_create',
                            'label' => '创建时间',
                            'value' => function($model){
                                return  $model->time_create;
                            },
                            'headerOptions' => ['width' => '200'],
                            'filter' => DatePicker::widget([
                                    'model' => $searchModel,
                                    'attribute' => 'time_create_from',
                                    'language' => 'zh-CN',
                                    'dateFormat' => 'yyyy-MM-dd',
                                    'value' =>  function($model){
                                        return  $model->time_create_from;
                                    },
                                    'options' => ['class' => 'col-xs-6','id' => 'time_create_from','title'=> '选择开始日期'],
                                ]).DatePicker::widget([
                                    'model' => $searchModel,
                                    'attribute' => 'time_create_to',
                                    'language' => 'zh-CN',
                                    'dateFormat' => 'yyyy-MM-dd',
                                    'value' =>  function($model){
                                        return  $model->time_create_to;
                                    },
                                    'options' => ['class' => 'col-xs-6','id' => 'time_create_to','title'=> '选择结束日期'],
                                ]),
                        ],
                        [
                            'attribute' => 'type',
                            'label' => '记录类型',
                            'value' => function($model){
                                $state = $model->recordType();
                                //是否有图片
                                $imgTitle = $model->imgstatus == 1 ? '（有图）' : '';
                                return $state[$model->type].$imgTitle;
                            },
                            'headerOptions' => ['width' => '70'],
                            'filter' => Html::activeDropDownList($searchModel,'type',$searchModel->recordType(),['prompt'=>'全部'])
                        ],
                        [
                            'label' => '标签',
                            'format' => 'raw',
                            'value' => function($model){
                                $res = $model->getTag($model->id);
                                $html = '<div class="show-tag-area" data-rid="'.$model->id.'">';
                                foreach($res as $k=>$v){
                                    $tmpUrl = Url::to(['record/ajaxdeletetag']);
                                    $html .= '<span class="tag"><m>'.Html::a($v['name'],['index','tid'=>$v['id']] , ["class" => "tag-link"]).'</m><button type="button" class="close delete-tag" data-rid="'.$model->id.'" data-tagid="'.$v['id'].'" data-href="'.$tmpUrl.'">×</button></span>';
                                }
                                $html .= '</div>';
                                //新增
                                $html .= '<span class="label label-lg label-pink arrowed-right add-tag">新增</span>';
                                //输入域
                                $html .= '<div class="nav-search tag-search"><span class="tags-search-area input-icon"><input type="text" class="nav-search-input input-search-tag" placeholder="搜索标签" autocomplete="off"><i class="icon-search nav-search-icon"></i></span><a class="close-search-area label label-lg label-pink arrowed-right" href="javascript:;">完成</a></div>';
                                return $html;
                            },
                            'filter' => '<div class="nav-search" id="top_search_div">
                                        <span class="tags-search-area input-icon">
                                            <input type="text" class="nav-search-input" id="top_search_input" placeholder="搜索标签" autocomplete="off">
                                            <i class="icon-search nav-search-icon"></i>
                                        </span>
                                    </div>'
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'header' => '操作',
                            'template' => '{view} {update} {delete} ',
                            'buttons' => [
                                'view' => function($url, $model, $key){
                                   return Html::a('查看',['view', 'id' => $key], ['class' => 'btn btn-sm btn-success',]
                                   );
                                },
                                'update' => function($url, $model, $key){
                                   return Html::a('修改',['update', 'id' => $key], ['class' => 'btn btn-sm btn-success',]
                                   );
                                },
                                'delete' => function($url, $model, $key){
                                    if($model->status == 0){
                                        return Html::a('<i class="icon-reply"></i>恢复',['delete', 'id' => $key ,'status' => 1], ['class' => 'btn btn-sm btn-success','data' => ['confirm' => '你确定要恢复吗？']]);
                                    }else{
                                        return Html::a('<i class="icon-trash"></i>删除',['delete', 'id' => $key ,'status' => 0], ['class' => 'btn btn-sm btn-danger','data' => ['confirm' => '你确定要删除吗？']]);
                                    }
                                },
                            ],
                        ],
                    ],
                ]); ?>
            </div>

            <div id="search" class="tab-pane">
                <?php echo $this->render('_search', ['model' => $searchModel]); ?>
            </div>
        </div>
    </div>


</div>
<div id="dialog-confirm" class="hide">
    <p class="bigger-110 bolder center grey">
        确认去掉标签？
    </p>
</div>
<?php $this->beginBlock("index") ?>
jQuery(document).ready(function () {
    $("#time_create_from,#time_create_to").tooltip({
        hide: {
            effect: "explode",
            delay: 250
        }
    });
    $.widget("ui.dialog", $.extend({}, $.ui.dialog.prototype, {
        _title: function(title) {
            var $title = this.options.title || '&nbsp;'
            if( ("title_html" in this.options) && this.options.title_html == true )
                title.html($title);
            else title.text($title);
        }
    }));
    $(".delete-tag").on('click', function(e) {
        deleteTag($(this),e);
    });
    function deleteTag(_this,e){
        e.preventDefault();
        var tagid = _this.attr('data-tagid');
        if(tagid==0){
            alert('请刷新再试');
            return false;
        }
        var recordid = _this.attr('data-rid');
        var url = _this.attr('data-href');
        var tmpdot = '?';
        if(url.indexOf('?')!=-1){
            tmpdot = '&';
        }
        url += tmpdot+'id='+recordid+'&tagid='+tagid;
        $( "#dialog-confirm" ).removeClass('hide').dialog({
            resizable: false,
            modal: true,
            title: "<div class='widget-header widget-header-small'><h4 class='smaller'>提示</h4></div>",
            title_html: true,
            buttons: [
                {
                    html: "<i class='icon-remove bigger-110'></i>&nbsp; 取消",
                    class : "btn btn-xs",
                    click: function() {
                        $( this ).dialog( "close" );
                    }
                },{
                    html: "<i class='icon-trash bigger-110'></i>&nbsp; 删除",
                    class: "btn btn-danger btn-xs",
                    click: function() {
                        var dialogDom = $( this );
                        $.get(url,function(d){
                            if(d.status){
                                _this.parents('span').remove();
                                dialogDom.dialog( "close" );
                            }else{
                                alert(d.msg);
                            }
                        },'json')
                    }
                }
            ]
        });
    }
    //显示搜索域
    $('.add-tag').on('click',function(){
        $(this).next().show();
        $(this).hide();
    })
    //显示增加按钮
    $('.close-search-area').on('click',function(){
        $(this).parent('div').prev().show();
        $(this).parent('div').hide();
    })
    //若有数据
    if($('.input-search-tag').length){
        //绑定搜索
        $('.input-search-tag').autocomplete({
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
                var showTagArea = $(this).parents('div').prev().prev();
                var rid = showTagArea.attr('data-rid');
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

                //添加关系
                $.post('<?php echo Url::to(['record/addrelation']);?>',{'rid': rid,'tagid': tagid,'tagname': tagname},function(d){
                    if(d.status){
                        tagid = d.data;
                        var url = '<?php echo Url::to(['record/ajaxdeletetag']);?>';
                        var taghtml = '<span class="tag"><m>'+tagname+'</m><button type="button" class="close delete-tag" data-rid="'+rid+'" data-tagid="'+tagid+'" data-href="'+url+'">×</button></span>';
                        showTagArea.append(taghtml);
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
    }
    //绑定顶部搜索
    $('#top_search_input').autocomplete({
        minLength: 1,
        source: '<?php echo Url::to(['tag/search']);?>',
        focus: function() {
          // 防止在获得焦点时插入值
          return false;
        },
        select: function(e, ui) {
            event.preventDefault();

            var tagid = ui.item.value;
            this.value = '';
            if(tagid > 0){
                window.location.href = '<?php echo Url::to(['record/index']);?>'+'&tid='+tagid;
            }
            return false;
        }
    }).data("ui-autocomplete")._renderItem = function( ul, item ) {
        console.log(item);
        var html = '<span>' + item.label + '</span>';
        if (item.value == 0) {
            html =  '<span>没有该标签</span>';
        }
        return $("<li>").append(html).appendTo( ul );
    };
});
<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks["index"], \yii\web\View::POS_END); ?>
