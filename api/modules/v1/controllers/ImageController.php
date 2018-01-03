<?php

namespace api\modules\v1\controllers;

use Yii;
use api\controllers\ApiactiveController;
use api\common\Functions;
use backend\models\Image as TableImage;

class ImageController extends ApiactiveController
{
    /**
     * 上传记录图片
     * @param type $rid     记录id
     */
    public function saveImage($rid){
        $this->isLogin();
        if(!$_FILES){
            return false;
        }
        $uploadController = new \backend\controllers\UploadController($this->id, $this->module);
        //待插入的图片数据
        $saveImageData = [];
        foreach($_FILES as $k=>$v){
            $imageInfo = $uploadController->saveImage($v,'record');
            if ($imageInfo['status']) {
                $tmpImageData = [
                    'uid' => $this->uid,
                    'sid' => $rid,
                    'username' => $this->username,
                    'filename' => $imageInfo['filename'],
                    'path' => $imageInfo['path'],
                    'type' => 1,//记录
                    'size' => $imageInfo['size'],
                    'width' => $imageInfo['width'],
                    'height' => $imageInfo['height'],
                    'thumb' => $imageInfo['thumb'],
                    'width_thumb' => $imageInfo['width_thumb'],
                    'height_thumb' => $imageInfo['height_thumb'],
                    'exif' => $imageInfo['exif'],
                    'status' => 1,
                    'time_create' => $this->formatTime,
                ];
                $saveImageData[] = $tmpImageData;
            }else{
                //保存图片失败
            }
        }
        if($saveImageData){
            Yii::$app->db->createCommand()->batchInsert(TableImage::tableName(), ['uid', 'sid', 'username', 'filename','path','type','size','width','height','thumb','width_thumb','height_thumb','exif','status','time_create'], $saveImageData)->execute();
        }
        return true;
    }

}