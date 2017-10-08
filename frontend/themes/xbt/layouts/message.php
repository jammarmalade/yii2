<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $title;
?>
<div class="site-error box">

    <h3><?= Html::encode($this->title) ?></h3>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($msg)) ?>
    </div>

</div>
