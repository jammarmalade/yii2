<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RecordSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

AppAsset::addScript($this, 'highcharts.min.js');

$this->title = '记录统计';
$this->params['breadcrumbs'][] = $this->title;
//https://www.hcharts.cn/docs/basic-tooltip
?>

<div class="record-statistics">
    统计
    <div id="container"></div>
</div>

<?php $this->beginBlock("index") ?>
jQuery(document).ready(function () {
    var chart = new Highcharts.Chart('container', {
    credits:{
        enabled: false
    },
    title: {
        text: '本月消费记录',
        x: -20
    },
    xAxis: {
        categories: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月']
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
        style: { 
            color: "#ff0000",
            fontSize: "12px",
            fontWeight: "blod",
            fontFamily: "Courir new"
        }
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle',
        borderWidth: 0
    },
    series: [{
		name: '本月',
        data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
    }]
});
});
<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks["index"], \yii\web\View::POS_END); ?>