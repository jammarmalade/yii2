<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RecordSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

AppAsset::addScript($this, 'highcharts.min.js');
AppAsset::addScript($this, 'jquery-ui-1.10.3.full.min.js');

$this->title = '记录统计';
$this->params['breadcrumbs'][] = $this->title;
//https://www.hcharts.cn/docs/basic-tooltip
?>
<style type="text/css">
    .record-title{
        width:100%;
        padding: 10px auto;
        text-align:center;
    }
    .record-title div{
        display: inline-block;
    }
    .label-tag{
        background-color: #428BCA!important;
        padding: 8px 10px 7px 10px;
        height:28px;
    }
    .record-account-area{
        width:80%;
    }
    .choose-mouth-area{
        float: none;
        margin-left: -100px;
    }
    .li-active{
        background-color: #EFEFEF;
    }
    #record-table tbody{
        display: none;
    }
    //多级下拉菜单
    .dropdown-submenu {  
        position: relative;  
    }  
    .dropdown-submenu > .dropdown-menu {  
        top: 0;  
        left: 100%;  
        margin-top: -6px;  
        margin-left: -1px;  
        -webkit-border-radius: 0 6px 6px 6px;  
        -moz-border-radius: 0 6px 6px;  
        border-radius: 0 6px 6px 6px;  
    }
    .dropdown-submenu:hover > .dropdown-menu {  
        display: block;  
    }  
    .dropdown-submenu > a:after {  
        display: block;  
        content: " ";  
        float: right;  
        width: 0;  
        height: 0;  
        border-color: transparent;  
        border-style: solid;  
        border-width: 5px 0 5px 5px;  
        border-left-color: #ccc;  
        margin-top: 5px;  
        margin-right: 10px;
    }
    .dropdown-submenu:hover > a:after {  
        border-left-color: #fff;  
    }  
    .dropdown-submenu.pull-left {  
        float: none;  
    }  
    .dropdown-submenu.pull-left > .dropdown-menu {  
        left: -100%;  
        margin-left: 10px;  
        -webkit-border-radius: 6px 0 6px 6px;  
        -moz-border-radius: 6px 0 6px 6px;  
        border-radius: 6px 0 6px 6px;  
    }
    #map_area{
        margin: 20px 0px;
    }
</style>
<div class="record-statistics">
    <div class="record-title">
        <div class="widget-toolbar no-border choose-mouth-area">
            <button class="btn btn-minier btn-primary dropdown-toggle" data-toggle="dropdown">
                选择年/月份
                <i class="icon-angle-down icon-on-right bigger-110"></i>
            </button>
            <ul class="dropdown-menu pull-right dropdown-125 dropdown-lighter dropdown-caret">
                <?php foreach($yearArr as $v){?>
                    <li class="dropdown-submenu <?php if($v==$chooseYear){echo 'li-active';}?>">
                        <a href="#">
                            <i class="icon-caret-right bigger-110 invisible">&nbsp;</i>
                            <?=$v?> 年
                        </a>
                        <ul class="dropdown-menu pull-right">  
                        <?php for($i=1;$i<=12;$i++){?>
                            <li><a href="<?php echo Url::to(['record/statistics','date'=>$chooseYear.'-'.$i]);?>" class="<?php if($v==$chooseYear && $i==intval($chooseMouth)){echo 'li-active';}?>"><?=$i?>月</a></li>
                        <?php }?>
                        </ul>
                    </li>
                <?php }?>
            </ul>
        </div>
        <div class="record-account-area">
            <div class="infobox infobox-green2 infobox-small infobox-dark">
                <div class="infobox-data">
                    <div class="infobox-content">收入</div>
                    <div class="infobox-content">￥<?php echo $data['accountIn'];?></div>
                </div>
            </div>
            <div class="infobox infobox-red infobox-small infobox-dark">
                <div class="infobox-data">
                    <div class="infobox-content">支出</div>
                    <div class="infobox-content">￥<?php echo $data['accountOut'];?></div>
                </div>
            </div>
            <div class="infobox infobox-green infobox-small infobox-dark">
                <div class="infobox-data">
                    <div class="infobox-content">纯收入</div>
                    <div class="infobox-content">￥<?php echo $data['income'];?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="space-6"></div>
    <div id="container"></div>
    <div class="record-area">
        <table id="record-table" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th class="center">ID</th>
                    <th>标签</th>
                    <th>金额</th>
                    <th>类型</th>
                    <th>备注</th>
                </tr>
            </thead>
            <?php foreach($recordGroupData as $date=>$tmpData){?>
                <tbody id="record-day-item-<?=$date?>">
                    <?php foreach($tmpData as $v){?>
                    <tr>
                        <td><?php echo '<a href="'.Url::to(['record/index','RecordSearch[id]'=>$v['id']]).'" target="_blank">'.$v['id'].'</a>';?></td>
                        <td>
                            <?php foreach($v['tags'] as $tagname){?>
                                <span class="label label-sm label-tag"><?=$tagname?></span>
                            <?php }?>
                        </td>
                        <td><?=$v['account']?></td>
                        <td><?php if($v['type']==1){echo '<span style="color:#FF3030;">支出</span>';}else{echo '<span style="color:#9ACD32;">收入</span>';}?></td>
                        <td><?=$v['content']?></td>
                    </tr>
                    <?php }?>
                </tbody>
            <?php }?>
        </table>
        
    </div>
    <div id="map_area">
        地图区域
    </div>
</div>

<?php $this->beginBlock("index") ?>
jQuery(document).ready(function () {
    $("#choose-mouth").menu();
    var chart = new Highcharts.Chart('container', {
	credits:{
            enabled: false
        },
        title: {
            text: '<?php echo $data['startDate'].' 到 '.$data['endDate']?> 收支记录',
            x: -20
        },
        xAxis: {
            categories: [<?php echo "'" . join("','", $dateArr) . "'"; ?>]
        },
        yAxis: {
            title: {
                text: '金额（元）'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            formatter: function() {
                var content = '<b><span style="font-size: 14px;">日期：' + this.point.date + '</span></b><br/>';
                content += '<b><span style="font-size: 14px;">金额：' + this.y + ' 元</span></b><br/>';
                content += '<b><span style="font-size: 14px;">标签：</span></b>';
                if(this.point.tags.length > 0){
                    var tags = this.point.tags;
                    var dot = '';
                    for(var k in tags){
                        content += dot+'<span>'+tags[k]+'</span>';
                        dot = '、';
                    }
                }else{
                    content += '无';
                }
                content += '<br/> <br/>点击查看当日/当月详细记录';
                return content;
            }
        },
        //点击事件
        plotOptions: {
            line: {
                events: {
                    click: function () {
                        var domId = 'record-day-item-'+event.point.date;
                        $('#record-table tbody').hide();
                        $('#'+domId).show();
                    }
                }
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        series: [{
            name: '收入',
            color: '#B3EE3A',
            data: <?php echo json_encode($ydataIn); ?>
        },{
            name: '支出',
            color: '#FF3030',
            data: <?php echo json_encode($ydataOut); ?>
        }]
    });
});
<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks["index"], \yii\web\View::POS_END); ?>