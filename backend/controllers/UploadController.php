<?php

use Yii;
use yii\web\Controller;
use backend\models\UploadForm;
use yii\web\UploadedFile;

class UploadController extends Controller
{
    public function actionUpload()
    {
        $model = new UploadForm();

        if (Yii::$app->request->isPost) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ($model->upload()) {
                
                return;
            }
        }

        return $this->render('upload', ['model' => $model]);
    }
}
