<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\helpers\Json;
use yii\imagine\Image;
use backend\components\Functions as tools;
use yii\helpers\Url;
use backend\models\Image as TableImage;

class UploadController extends Controller {
    //取消csrf验证
    public $enableCsrfValidation = false;
    //分类文件夹
    private $typeDir = '';
    //文件后缀
    private $ext = '';
    /**
     * 缩略图宽度
     * @return type
     */
    private $thumbWidth = 800;
    //文件域名
    private $imgDomain = '';

    public function actionIndex() {
        $this->imgDomain = Yii::$app->params['imgDomain'];
        $action = Yii::$app->request->get('action');
        switch ($action){
            case 'config':
                $configArr = $this->jsonConfig();
                $configArr['imageDelUrl'] = Url::to(['upload/index','action'=>'deleteImage']);
                return json_encode($configArr);
                break;
            case 'image':
                return $this->image();
                break;
            case 'listimage':
                return $this->listImage();
                break;
            case 'deleteImage':
                return $this->deleteImage();
        }
    }
    private function deleteImage(){
        $uid = Yii::$app->user->id;
        $id = Yii::$app->request->post('id');
        $info = TableImage::find()->where(['id'=>$id,'uid'=>$uid])->one();
        $res = $info->delete();
        if($res){
            //删除文件
            $imgPath = Yii::getAlias('@uploads').$info['path'];
            @unlink($imgPath);
            if($info['thumb']){
                @unlink($imgPath.'.thumb.jpg');
            }
            return $this->ajaxReturn('','success', false);
        }else{
            return $this->ajaxReturn('','删除失败', false);
        }
    }
    //在线图片管理
    private function listImage(){
        $sid = Yii::$app->request->get('aid');
        $uid = Yii::$app->user->id;
        //查询用户没有使用的图片
        if($sid){
            $where = ['or',['uid'=>$uid,'status'=>2],['sid'=>$sid]];
        }else{
            $where = ['uid'=>$uid,'status'=>2];
        }
        $imageList = TableImage::find()->select('id,sid,thumb,path')->where($where)->orderBy('time_create DESC')->asArray()->all();
        
        if(!$imageList){
            return Json::encode([
                "state" => "no match file",
                "list" => [],
                "start" => 0,
                "total" => 0
            ]);
        }
        $resList = $usedList = [];
        foreach($imageList as $k=>$v){
            $tmpUrl = $this->imgDomain.$v['path'];
            if($v['thumb']){
                $tmpUrl .= '.thumb.jpg';
            }
            $tmp['id'] = $v['id'];
            $tmp['sid'] = $v['sid'];
            $tmp['url'] = $tmpUrl;
            $tmp['title'] = $v['id'].'_'.substr($v['path'],strrpos($v['path'],'/')+1);
            if($v['sid']){
                $usedList[] = $tmp;
            } else {
                $resList[] = $tmp;
            }
        }
        if($usedList){
            $resList = array_merge($usedList, $resList);
        }
        
        return Json::encode([
                "state" => "SUCCESS",
                "list" => $resList,
                "start" => 0,
                "total" => count($resList)
            ]);
    }
    private function image() {
        if ($_FILES) {
            $imageInfo = $this->save();
            if ($imageInfo['status']) {
                $imageModel = new TableImage();
                $imageModel->load($imageInfo, '');

                $imageModel->uid = Yii::$app->user->id;
                $imageModel->username = Yii::$app->user->identity->username;
                $imageModel->time_create = date('Y-m-d H:i:s');
                $imageModel->type = $imageModel->getTypeId($this->typeDir);
                $imageModel->status = 2;
                $imageModel->save(false);
                $imageInfo['url'] = $this->imgDomain.$imageInfo['path'];
                $imageInfo['id'] = $imageModel->id;
                if($imageInfo['thumb']){
                    $imageInfo['url'] .= '.thumb.jpg';
                }
                return $this->ajaxReturn($imageInfo, '', true);
            }else{
                return $imageInfo;
            }
        }else{
            return $this->ajaxReturn('', '请先上传图片', false);
        }
    }
    
    private function save(){
        if ($_FILES['imageFile']) {
            $imgSource = $_FILES['imageFile']; //图片资源
        } else {
            $imgSource = $_FILES['Filedata'];
        }
        $type = $this->typeDir = Yii::$app->request->get('type');
        //允许上传的类型
        $allowType = ['article', 'record'];
        if (!in_array($type, $allowType)) {
            return $this->ajaxReturn('','不允许上传', false);
        }
        //判断大小和图片类型
        if (!$this->isImage($imgSource['name'])) {
            return $this->ajaxReturn('','不允许上传', false);
        } else {
            if ($imgSource['size'] > 10 * 1024 * 1024) {
                return $this->ajaxReturn('','上传的图片文件不能大于10M', false);
            }
        }
        $tmpPath = $imgSource['tmp_name'];
        //保存原图
        $saveFileName = $this->getTargetFilename() . '.jpg';
        $savePathSuffix = $this->getTargetDir(). $saveFileName;
        $savePath = Yii::getAlias('@uploads').$savePathSuffix;
        if (copy($tmpPath, $savePath) || move_uploaded_file($tmpPath, $savePath)) {
            $tmpImgInfo = getimagesize($savePath);
            $imageInfo['width'] = $tmpImgInfo[0];
            $imageInfo['height'] = $tmpImgInfo[1];
            //若是宽度超过600 的，生成缩略图
            $imageInfo['thumb'] = 0;
            if($imageInfo['width'] > $this->thumbWidth){
                $imageInfo['width_thumb'] = $this->thumbWidth;
                //以宽为基准计算缩略图等比例高度
                $imageInfo['height_thumb'] = ceil($imageInfo['height'] * ($this->thumbWidth / $imageInfo['width']));
                //生成缩略图
                Image::thumbnail($savePath, $imageInfo['width_thumb'], $imageInfo['height_thumb'])->save($savePath.'.thumb.jpg', ['quality' => 90]);
                $imageInfo['thumb'] = 1;
            }
            $imageInfo['path'] = $savePathSuffix;
            $imageInfo['filename'] = $imgSource['name'];
            $imageInfo['size'] = $imgSource['size'];
            $imageInfo['newFilename'] = $saveFileName;
            //若是图片大于4M ，就压缩图片
            if($imgSource['size'] >= 2 * 1024 * 1024){
                //有exif数据，去掉原图的exif数据
                Image::thumbnail($savePath, $imageInfo['width'], $imageInfo['height'])->save($savePath, ['quality' => 85]);
                $imageInfo['size'] = filesize($savePath);
            }
            //获取图片 exif 信息
            $exif = $this->getExif($savePath);
            if($exif){
                $imageInfo['exif'] = json_encode($exif);
            }
            $imageInfo['status'] = true;
            return $imageInfo;
        } else {
            //资源不可读
            if (!is_readable($tmpPath)) {
                $errorMsg = '该图片不能读取';
            } elseif (!is_writable($imgPath)) {
                //目录不可写
                $errorMsg = '目标路径不可写 - ' . $imgPath;
            } else {
                $errorMsg = '保存失败';
            }
            return $this->ajaxReturn('',$errorMsg, false);
        }
    }
    
    private function ajaxReturn($data , $msg = '', $status = false){
        $state = $status ? 'SUCCESS' : $msg;
        if(!$status){
           return Json::encode(['state'=>$state]); 
        }
        $resData = [
            'state' => $state,
            'url' => $data['url'],
            'title' => $data['id'].'_'.$data['newFilename'],
            'original' => $data['filename'],
            'type' => $this->ext,
            'size' => $data['size'],
        ];
        return Json::encode($resData);
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
            getimagesize($path,$tempInfo);
            if(function_exists('exif_read_data') && isset($tempInfo['APP1']) && substr($tempInfo['APP1'], 0, 4)=='Exif'){
                $arr = exif_read_data($path, "EXIF");
                //删除不能识别和多于的数据
                unset(
                    $arr['MakerNote'],
                    $arr['ModeArray'],
                    $arr['GPSVersion'],
                    $arr['Undefinedtag:0x0002'],
                    $arr['Undefinedtag:0x0003'],
                    $arr['Undefinedtag:0x0019'],
                    $arr['UndefinedTag:0x0026'],
                    $arr['UndefinedTag:0x000D'],
                    $arr['UndefinedTag:0x0038'],
                    $arr['UndefinedTag:0x0035'],
                    $arr['UndefinedTag:0x0093'],
                    $arr['UndefinedTag:0x0097'],
                    $arr['UndefinedTag:0x0098'],
                    $arr['UndefinedTag:0x0099'],
                    $arr['UndefinedTag:0x009A'],
                    $arr['UndefinedTag:0x00A0'],
                    $arr['UndefinedTag:0x00E0'],
                    $arr['UndefinedTag:0x4001'],
                    $arr['UndefinedTag:0x4008'],
                    $arr['UndefinedTag:0x4009'],
                    $arr['UndefinedTag:0x4013'],
                    $arr['UndefinedTag:0x4015'],
                    $arr['UndefinedTag:0x4016'],
                    $arr['UndefinedTag:0x4018'],
                    $arr['UndefinedTag:0x4024'],
                    $arr['UndefinedTag:0x4025'],
                    $arr['UndefinedTag:0x4027']
		);
                return $arr;
            }
        }
        return ;
    }
    //判断是否是图片
    private function isImage($fileName) {
        $tmpArr = explode('.', $fileName);
        $this->ext = strtolower(end($tmpArr));
        static $imgext = array('jpg', 'jpeg', 'gif', 'png', 'bmp');
        return in_array($this->ext, $imgext) ? 1 : 0;
    }
    /**
     * 获取保存文件名
     */
    private function getTargetFilename(){
        return date('His') . strtolower(tools::random(16));
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

    private function jsonConfig() {
        $text = <<<'TEXT'
/* 前后端通信相关的配置,注释只允许使用多行方式 */
{
    /* 上传图片配置项 */
    "imageActionName": "image", /* 执行上传图片的action名称 */
    "imageFieldName": "imageFile", /* 提交的图片表单名称 */
    "imageMaxSize": 10240000, /* 上传大小限制，单位B */
    "imageAllowFiles": [".png", ".jpg", ".jpeg", ".gif", ".bmp"], /* 上传图片格式显示 */
    "imageCompressEnable": false, /* 是否压缩图片,默认是true */
    "imageCompressBorder": 1600, /* 图片压缩最长边限制 */
    "imageInsertAlign": "none", /* 插入的图片浮动方式 */
    "imageUrlPrefix": "", /* 图片访问路径前缀 */
    "imagePathFormat": "/ueditor/php/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
                                /* {filename} 会替换成原文件名,配置这项需要注意中文乱码问题 */
                                /* {rand:6} 会替换成随机数,后面的数字是随机数的位数 */
                                /* {time} 会替换成时间戳 */
                                /* {yyyy} 会替换成四位年份 */
                                /* {yy} 会替换成两位年份 */
                                /* {mm} 会替换成两位月份 */
                                /* {dd} 会替换成两位日期 */
                                /* {hh} 会替换成两位小时 */
                                /* {ii} 会替换成两位分钟 */
                                /* {ss} 会替换成两位秒 */
                                /* 非法字符 \ : * ? " < > | */
                                /* 具请体看线上文档: fex.baidu.com/ueditor/#use-format_upload_filename */

    /* 涂鸦图片上传配置项 */
    "scrawlActionName": "image", /* 执行上传涂鸦的action名称 */
    "scrawlFieldName": "imageFile", /* 提交的图片表单名称 */
    "scrawlPathFormat": "/ueditor/php/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
    "scrawlMaxSize": 10240000, /* 上传大小限制，单位B */
    "scrawlUrlPrefix": "", /* 图片访问路径前缀 */
    "scrawlInsertAlign": "none",

    /* 截图工具上传 */
    "snapscreenActionName": "uploadimage", /* 执行上传截图的action名称 */
    "snapscreenPathFormat": "/ueditor/php/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
    "snapscreenUrlPrefix": "", /* 图片访问路径前缀 */
    "snapscreenInsertAlign": "none", /* 插入的图片浮动方式 */

    /* 抓取远程图片配置 */
    "catcherLocalDomain": ["127.0.0.1", "localhost", "img.baidu.com"],
    "catcherActionName": "catchimage", /* 执行抓取远程图片的action名称 */
    "catcherFieldName": "source", /* 提交的图片列表表单名称 */
    "catcherPathFormat": "/ueditor/php/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
    "catcherUrlPrefix": "", /* 图片访问路径前缀 */
    "catcherMaxSize": 2048000, /* 上传大小限制，单位B */
    "catcherAllowFiles": [".png", ".jpg", ".jpeg", ".gif", ".bmp"], /* 抓取图片格式显示 */

    /* 上传视频配置 */
    "videoActionName": "uploadvideo", /* 执行上传视频的action名称 */
    "videoFieldName": "upfile", /* 提交的视频表单名称 */
    "videoPathFormat": "/ueditor/php/upload/video/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
    "videoUrlPrefix": "", /* 视频访问路径前缀 */
    "videoMaxSize": 102400000, /* 上传大小限制，单位B，默认100MB */
    "videoAllowFiles": [
        ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
        ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid"], /* 上传视频格式显示 */

    /* 上传文件配置 */
    "fileActionName": "uploadfile", /* controller里,执行上传视频的action名称 */
    "fileFieldName": "upfile", /* 提交的文件表单名称 */
    "filePathFormat": "/ueditor/php/upload/file/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
    "fileUrlPrefix": "", /* 文件访问路径前缀 */
    "fileMaxSize": 51200000, /* 上传大小限制，单位B，默认50MB */
    "fileAllowFiles": [
        ".png", ".jpg", ".jpeg", ".gif", ".bmp",
        ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
        ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid",
        ".rar", ".zip", ".tar", ".gz", ".7z", ".bz2", ".cab", ".iso",
        ".doc", ".docx", ".xls", ".xlsx", ".ppt", ".pptx", ".pdf", ".txt", ".md", ".xml"
    ], /* 上传文件格式显示 */

    /* 列出指定目录下的图片 */
    "imageManagerActionName": "listimage", /* 执行图片管理的action名称 */
    "imageManagerListPath": "/ueditor/php/upload/image/", /* 指定要列出图片的目录 */
    "imageManagerListSize": 20, /* 每次列出文件数量 */
    "imageManagerUrlPrefix": "", /* 图片访问路径前缀 */
    "imageManagerInsertAlign": "none", /* 插入的图片浮动方式 */
    "imageManagerAllowFiles": [".png", ".jpg", ".jpeg", ".gif", ".bmp"], /* 列出的文件类型 */

    /* 列出指定目录下的文件 */
    "fileManagerActionName": "listfile", /* 执行文件管理的action名称 */
    "fileManagerListPath": "/ueditor/php/upload/file/", /* 指定要列出文件的目录 */
    "fileManagerUrlPrefix": "", /* 文件访问路径前缀 */
    "fileManagerListSize": 20, /* 每次列出文件数量 */
    "fileManagerAllowFiles": [
        ".png", ".jpg", ".jpeg", ".gif", ".bmp",
        ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
        ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid",
        ".rar", ".zip", ".tar", ".gz", ".7z", ".bz2", ".cab", ".iso",
        ".doc", ".docx", ".xls", ".xlsx", ".ppt", ".pptx", ".pdf", ".txt", ".md", ".xml"
    ] /* 列出的文件类型 */

}
TEXT;
         return json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", $text), true);
    }
}
