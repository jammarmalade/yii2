<?php

namespace backend\models;

use yii\base\Model;
use yii\web\UploadedFile;
use yii\imagine\Image;
use backend\components\Functions;

//http://www.yiichina.com/doc/guide/2.0/tutorial-core-validators#file
class UploadForm extends Model {

    /**
     * 接收上传的文件实例
     * @var UploadedFile
     */
    public $imageFile;
    /**
     * 目录分类
     * @var type 
     */
    public $typeDir = 'image';
    /**
     * 图片旋转
     * @var type 
     */
    private $rotateAngleGd = [3 => '180', 6 => '360', 8 => '90'];
    private $rotateAngleIm = [3 => '180', 6 => '90', 8 => '270'];
    /**
     * 缩略图宽度
     * @return type
     */
    private $thumbWidth = 600;

    public function rules() {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg, gif', 'maxSize' => 10 * 1024 * 1024],
        ];
    }

    public function upload() {
        if ($this->validate()) {
            //保存原图
            $savePathSuffix = $this->getTargetDir(). $this->getTargetFilename() . '.jpg';
            $savePath = \Yii::getAlias('@uploads').$savePathSuffix;
            if ($this->imageFile->saveAs($savePath)) {
                $tmpImgInfo = getimagesize($savePath);
                $imageInfo['width'] = $tmpImgInfo[0];
                $imageInfo['height'] = $tmpImgInfo[1];
                //若是宽度超过600 的，生成缩略图
                if($imageInfo['width'] > 0){
                    $imageInfo['width_thumb'] = $this->thumbWidth;
                    //以宽为基准计算缩略图等比例高度
                    $imageInfo['height_thumb'] = ceil($imageInfo['height'] * ($this->thumbWidth / $imageInfo['width']));
                    //生成缩略图
                    Image::thumbnail($savePath, $imageInfo['width_thumb'], $imageInfo['height_thumb'])->save($savePath.'.thumb.jpg', ['quality' => 90]);
                }
                $imageInfo['path'] = $savePathSuffix;
                $imageInfo['filename'] = $this->imageFile->name;
                $imageInfo['size'] = $this->imageFile->size;
                //获取图片 exif 信息
                $exif = $this->getExif($savePath);
                if($exif){
                    $imageInfo['exif'] = json_encode($exif);
                }
                return $imageInfo;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    /**
     * 获取保存文件名
     */
    private function getTargetFilename(){
        return date('His') . strtolower(Functions::random(16));
    }
    /**
     * 获取保存路径
     * @return string
     */
    private function getTargetDir(){
        $subdir = $subdir1 = $subdir2 = '';
        $subdir1 = date('Ym');
        $subdir2 = date('d');
        $subdir = $subdir1 . '/' . $subdir2 . '/';
        $this->checkDirExists($subdir1, $subdir2);
        return $this->typeDir.'/'.$subdir;
    }
    /**
     * 检查文件夹目录是否存在，不存在则创建
     * @param type $sub1    第一级目录
     * @param type $sub2    第二级目录
     * @return type
     */
    private function checkDirExists($sub1 = '', $sub2 = '') {
        $basedir = \Yii::getAlias('@uploads');
        $typedir = $this->typeDir ? ($basedir . '/' . $this->typeDir) : '';
        $subdir1 = $this->typeDir && $sub1 !== '' ? ($typedir . '/' . $sub1) : '';
        $subdir2 = $sub1 && $sub2 !== '' ? ($subdir1 . '/' . $sub2) : '';

        $res = $subdir2 ? is_dir($subdir2) : ($subdir1 ? is_dir($subdir1) : is_dir($typedir));

        if (!$res) {
            $res = $typedir && $this->makeDir($typedir);
            $res && $subdir1 && ($res = $this->makeDir($subdir1));
            $res && $subdir1 && $subdir2 && ($res = $this->makeDir($subdir2));
        }
        return $res;
    }
    /**
     * 创建目录
     * @param type $dir
     * @param type $index 创建 index.html 文件
     * @return type
     */
    private function makeDir($dir, $index = true) {
        $res = true;
        if (!is_dir($dir)) {
            $res = @mkdir($dir, 0777);
            $index && touch($dir . '/index.html');
        }
        return $res;
    }
    /**
     * 获取图片exif信息
     * @param type $path 图片原图路径
     * @return type 
     */
    private function getExif($path){
        if (extension_loaded('exif') && extension_loaded('mbstring')) {
            return exif_read_data($path, "EXIF");
        }
        return ;
    }
}
