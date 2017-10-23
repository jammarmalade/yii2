<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%image}}".
 *
 * @property string $id
 * @property string $uid
 * @property string $sid
 * @property string $username
 * @property string $path
 * @property integer $type
 * @property string $size
 * @property integer $width
 * @property integer $height
 * @property integer $thumb
 * @property integer $width_thumb
 * @property integer $height_thumb
 * @property string $exif
 * @property integer $status
 * @property string $time_create
 */
class Image extends \yii\db\ActiveRecord
{
    //时期搜索（开始）
    public $time_create_from;
    //时期搜索（结束）
    public $time_create_to;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%image}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid'], 'required'],
            [['uid', 'type', 'size', 'width', 'height', 'width_thumb', 'height_thumb', 'status','thumb'], 'integer'],
            [['exif'], 'string'],
            [['time_create'], 'safe'],
            [['username'], 'string', 'max' => 15],
            [['path','filename'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => '用户id',
            'sid' => '使用id，如文章id，记录id...',
            'username' => '用户名',
            'filename' => '文件名',
            'path' => '相对存放路径',
            'type' => '图片类型，0文章，1收支记录',
            'size' => '图片大小',
            'width' => '图片宽度',
            'height' => '图片高度',
            'thumb' => '是否有缩略图',
            'width_thumb' => '缩略图宽度',
            'height_thumb' => '缩略图高度',
            'exif' => '图片的exif信息',
            'status' => '标签状态，0删除，1正常，2未使用',
            'time_create' => '上传时间',
        ];
    }
    protected $typeArr = [
            'article' => 0,
            'record' => 1,
            'config' => 2,
        ];
    /**
     * 获取分类id
     * @param type $table 表名称
     */
    public function getTypeId($table){
        return $this->typeArr[$table];
    }
    /**
     * 获取分类表名
     * @param type $table 表名称
     */
    public function getTypeTable($typeId){
        $tmp = array_flip($this->typeArr);
        return $tmp[$typeId];
    }
    public function getTypeArr(){
        $tmp = array_flip($this->typeArr);
        return $tmp;
    }
    /**
     * 替换图片bbcode为百度编辑器的图片代码
     * @param type $articleInfo     文章信息
     */
    public static function replaceImgCode($articleInfo,$type='ueditor'){
        //所有图片信息
        $imageList = Image::find()->where(['sid'=>$articleInfo['id'],'uid'=>$articleInfo['uid']])->asArray()->all();
        $searchArr = $replaceArr = [];
        $imgDomain = Yii::$app->params['imgDomain'];
        //占位图
        if(Yii::$app->request->serverName == 'admin.jam00.com'){
            $tmpPath = '/static';
        }else{
            $tmpPath = Yii::$app->view->theme->baseUrl;
        }
        $zwImage = Yii::$app->request->hostInfo.$tmpPath.'/images/l.gif';
        $imgList = $tmpImgList = [];
        //手机端显示
        $mobileReplaceArr = [];
        foreach($imageList as $k=>$v){
            $searchArr[] = '[img]'.$v['id'].'[/img]';
            $imUrl = $originalUrl = $imgDomain.$v['path'];
            $tmpImgList[$v['id']] = $imUrl;
            if($v['thumb']){
                $imUrl .= '.thumb.jpg';
            }
            $filename = substr($v['path'],strrpos($v['path'],'/')+1);
            $title = $v['id'].'_'.$filename;
            if($type=='ueditor'){
                $replaceArr[] = '<img src="'.$imUrl.'" _src="'.$imUrl.'" title="'.$title.'" alt="'.$filename.'">';
            }elseif($type=='show'){
                $filename = substr($v['filename'],0,strrpos($v['filename'],'.'));
                $replaceArr[] = '<img src="'.$zwImage.'" title="'.$filename.'" class="lazy view-img" data-original="'.$imUrl.'" data-big="'.$originalUrl.'">';
                $mobileReplaceArr[] = '<img width="100%" src="'.$zwImage.'" title="'.$filename.'" class="lazy view-img" data-original="'.$imUrl.'" data-big="'.$imUrl.'">';
            }
        }
        $articleInfo['content'] = preg_replace('#src="http://(api\.map|img).baidu.com([^"]+?)"#','src="https://$1.baidu.com$2"',$articleInfo['content']);
        preg_match_all('#\[img\](\d+)\[/img\]#i', $articleInfo['content'], $m);
        $tmpIds = $m[1];
        $tmpContent = str_replace($searchArr, $replaceArr, $articleInfo['content']);
        if($type=='ueditor'){
            return $tmpContent;
        }elseif($type=='show'){
            $mobileContent = $articleInfo['content'];
            
            if($mobileReplaceArr){
                $mobileContent = str_replace($searchArr, $mobileReplaceArr, $mobileContent);
            }
            $mobileContent = self::replaceDynamicMap($mobileContent,true);
            $tmpContent = self::replaceDynamicMap($tmpContent);
            foreach($tmpIds as $k=>$tmpId){
                $imgList[] = $tmpImgList[$tmpId];
            }
            $res['mobileContent'] = $mobileContent;
            $res['content'] = $tmpContent;
            $res['imgList'] = $imgList;
            return $res;
        }
    }
    /**
     * 替换百度动态地图链接地址
     */
    public static function replaceDynamicMap($content,$mobile = false){
        return preg_replace_callback('#<iframe src="http://([^/]+?)/static/js/ueditor/dialogs/map/show.html([^"]+?)"([^>]+?)>#',function($m) use($mobile){
            $serverName = Yii::$app->request->serverName;
            if($serverName!='admin.jam00.com'){
                $path = Yii::$app->view->theme->baseUrl.'/js/ueditor/dialogs/map/show.html';
            }else{
                $path = 'http://admin.jam00.com/static/js/ueditor/dialogs/map/show.html';
            }
            $fragment = $m[2];
            $attrs = $m[3];
            if($mobile){
                $fragment = str_replace('width=800', 'width=100%', $fragment);
                $attrs = str_replace('width="804"', 'width="100%"', $attrs);
            }
            $src = $path.$fragment;
            return '<iframe src="'.$src.'"'.$attrs.'>';
        },$content);
    }
}
