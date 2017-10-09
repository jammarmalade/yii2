<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\assets\AppAsset;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Config */
/* @var $form yii\widgets\ActiveForm */

AppAsset::addScript($this, 'jquery.uploadify.js');
?>
<style type='text/css'>
    #upload_div{
        display: none;
        margin-bottom: 10px;
    }
</style>
<div class="config-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'key')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList($model->typeArr()) ?>
    <div id="upload_div">
        <label class="control-label">上传图片</label>
        <p>
            <button class="btn btn-sm btn-primary" id="img-ipt">上传图片</button>
            <img id="img-show" style="border-radius: 5px;border:1px solid silver;width:300px;margin-top:10px;" src=""/>
        </p>
    </div>
    <?= $form->field($model, 'value')->textarea(['rows' => 5]) ?>
    
    <?= $form->field($model, 'remark')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_number')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '新增' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php $this->beginBlock("_form") ?>
jQuery(document).ready(function () {
    $("#config-type").change(function(){
        var val = $(this).val();
        if(val == 2){
            $('#upload_div').show();
        }else{
            $('#upload_div').hide();
        }
    });
    //上传文章封面图
    $('#img-ipt').uploadify({
        'buttonText': '上传图片',
        'multi': false,
        'formData': {
            'session_id': '<?php echo session_id();?>',
        },
        'uploader': '<?php echo Url::to(['upload/index','type'=>'config','action'=>'image']);?>',
        'fileTypeExts': '*.jpg;*.gif;*.png',
        'onUploadSuccess': function (file, res) {
            res = eval("(" + res + ")");
            if(res['state']!='SUCCESS'){
                alert(res['state']);
                console.log(res);
                return false;
            }
            $('#img-show').attr('src', res['url']).show();
            $('#config-value').val(res['path']);
        }
    });
});
<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks["_form"], \yii\web\View::POS_END); ?>
