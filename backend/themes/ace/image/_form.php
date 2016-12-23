<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Image */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="image-form">
    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
        'fieldConfig' => [  
            'template' => '{input}<a class="btn btn-app btn-purple btn-sm btn-upload"><i class="icon-cloud-upload bigger-200"></i>选择文件</a>{hint}{error}'
        ],
        
    ]) ?>

    <?php echo $form->field($model, 'imageFile')->fileInput() ?>
    
    <button class="btn btn-lg btn-success">点击上传</button>

    <?php ActiveForm::end() ?>

    


</div>
