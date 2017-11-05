<?php

/**
 * 资源
 */

namespace api\modules\v1\controllers;

use api\controllers\ApiactiveController;
use api\common\Functions;
use api\common\simple_html_dom;
use backend\models\Source;
use backend\models\SourceImage;

class SourceController extends ApiactiveController {

    public $nameZfl = 'yxpjw.me';

    /**
     * 获取列表
     * http：//127.0.0.1/advanced/api/web/index.php/v1/source/zfl
     * http://zhaofuli.biz/page/1.html
     */
    public function actionZfl() {
        //查询出最新的sid
        $maxSid = Source::find()->where(['name' => $this->nameZfl, 'type' => 0])->max('sid');
        $this->deepGetList($maxSid, 1);
    }

    /**
     * 递归调用
     */
    public function deepGetList($maxSid, $page) {
        static $deepCount;
        $html = $this->getList($page);
        $count = $this->saveList($html, $page, $maxSid);
        if ($count == 10 && $deepCount < 10) {
            $deepCount++;
            $this->deepGetList($maxSid, ++$page);
        } else {
            return true;
        }
    }

    /**
     * 获取列表数据
     */
    private function getList($page) {
        return Functions::curlZFL('http://'.$this->nameZfl.'/page/' . $page . '.html','http://'.$this->nameZfl.'/',$this->nameZfl);
        //缓存key
        $sKey = 'zfl-list' . $page;
        //获取缓存
        $cacheData = \yii::$app->cache->get($sKey);
        if ($cacheData) {
            return $cacheData['data'];
        }
        $html = Functions::curlZFL('http://'.$this->nameZfl.'/page/' . $page . '.html');
        $cacheData['data'] = $html;

        \yii::$app->cache->set($sKey, $cacheData, 3000); //缓存一个小时
        return $cacheData['data'];
    }

    /**
     * 将文件列表存入数据库
     */
    private function saveList($html, $page, $maxSid) {
        include_once \Yii::getAlias("@app") . '/common/simple_html_dom.php';
        $count = 0;
        $dom = new \simple_html_dom();
        $dom->load($html);
        $list = $dom->find('article[class^=excerpt]');
        $insertData = array();
        $formatTime = date('Y-m-d H:i:s', time());
        foreach ($list as $k => $item) {
            $tmp = [];
            $tmp['name'] = $this->nameZfl;
            $url = $item->find('h2 a')[0]->attr['href'];
            $subject = $item->find('h2 a')[0]->text();
            //获取sid
            preg_match('#/(\d+)\.html#', $url, $m);
            if ($maxSid && $maxSid == $m[1]) {
                break;
            }
            $count++;
            $tmp['sid'] = $m[1];
            $tmp['surl'] = 'http://'.$this->nameZfl . $url;
            $tmp['subject'] = iconv('gb2312', 'utf-8//IGNORE', $subject);
            $tmp['content'] = '';
            $tmp['type'] = 0;
            $tmp['page'] = $page;
            $tmp['time_create'] = $formatTime;
            $insertData[] = $tmp;
        }
        if ($insertData) {
            \Yii::$app->db->createCommand()->batchInsert(Source::tableName(), ['name', 'sid', 'surl', 'subject', 'content', 'type', 'page', 'time_create'], $insertData)->execute();
        }
        return $count;
    }

    /**
     * 获取内容页和图片
     */
    public function actionInfo() {
//        $info = Source::find()->where(['id' => 183])->one();
//        $getUrl = 'http://zhaofuli.biz//luyilu/2016/1219/2735_2.html';
//        $html = $this->getInfo($getUrl);
//        $nextUrl = $this->saveInfo($html, $info, 2);
//        echo $nextUrl;
//        exit();
        
        
        //获取最新的内容
        $info = Source::find()->where(['type' => 0, 'status' => 1])->one();
        if (!$info || $info['status'] != 1) {
            Functions::printarr($info);
            exit();
        }
        set_time_limit(0);
        $this->deepGetInfo($info, $info['surl'], 1);
    }

    /**
     * 递归调用 获取详情
     */
    public function deepGetInfo($info, $getUrl, $page) {
        static $deepCount;
        $html = $this->getInfo($getUrl);
        $nextUrl = $this->saveInfo($html, $info, $page);
        if ($nextUrl && $deepCount < 100) {
            $deepCount++;
            $this->deepGetInfo($info, $nextUrl, ++$page);
        } else {
            return true;
        }
    }

    /**
     * 将详情页内容存入数据库
     */
    private function saveInfo($html, $info, $page) {
        include_once \Yii::getAlias("@app") . '/common/simple_html_dom.php';

        $dom = new \simple_html_dom();
        $dom->load($html);
        $list = $dom->find('article[class=article-content] p');
        $insertData = array();
        $formatTime = date('Y-m-d H:i:s', time());
        $content = '';
        //更改为正在获取内容
        Source::updateAll(['status' => 2, 'page' => $page, 'exe_time' => $formatTime], 'id=' . $info['id']);
        foreach ($list as $k => $item) {
            if ($page == 1 && strpos($item->innertext, 'img') === false) {
                $content = iconv('gb2312', 'utf-8//IGNORE', $item->innertext);
//                $content = $item->innertext;
                continue;
            }
            $imgDom = $item->find('img');
            if(!$imgDom){
                continue;
            }
            $src = $imgDom[0]->attr['src'];
            if (!$src) {
                continue;
            }
            $m = [];
            preg_match('#/(\d+)/([\w\-]+)\.(jpg|png|gif)#i', $src, $m);
            if(!isset($m[2])){
                continue;
            }
            $tmp = [];
            $tmp['name'] = $this->nameZfl;
            $tmp['sid'] = $m[2];
            $tmp['surl'] = $src;
            $path = $this->getImage($src, $this->nameZfl . '/' . $m[1] . '/' . $info['id'] . '/' . md5($src));
            if ($path == -1) {
                $tmp = [];
                continue;
            }
            list($locaPath, $savePath) = $path;
            $tmp['path'] = $locaPath ? $locaPath : '';
            $tmp['psid'] = $info['id'];
            $tmp['page'] = $page;
            $tmp['status'] = file_exists($savePath) ? 3 : 1;
            $tmp['exe_time'] = $formatTime;
            $tmp['time_create'] = $formatTime;
            $insertData[] = $tmp;
        }
        //插入图片数据
        if ($insertData) {
            \Yii::$app->db->createCommand()->batchInsert(SourceImage::tableName(), ['name', 'sid', 'surl', 'path', 'psid', 'page', 'status', 'exe_time', 'time_create'], $insertData)->execute();
        }
        if ($page == 1) {
            //获取标签
            $tagsDom = $dom->find('div[class=article-tags] a');
            $tags = [];
            //获取标题
            $subjectList = $dom->find('h1[class=article-title]');
            $subjectDom = array_shift($subjectList);
            
            $subject = iconv('gb2312', 'utf-8//IGNORE', $subjectDom->innertext);
            foreach ($tagsDom as $k => $v) {
                $tags[] = iconv('gb2312', 'utf-8//IGNORE', $v->innertext);
            }
            $content = $content ? $content : $info['subject'];
            Source::updateAll(['tags' => join(',', $tags), 'content' => $content,'subject' => $subject], 'id=' . $info['id']);
        }
        //获取是否有下一页
        $nextUrl = '';
        $pageLi = $dom->find('div[class^=pagination] li', -1);
        if($pageLi){
            $haveA = $pageLi->find('a');
            if ($haveA) {
                $nextUrl = $haveA[0]->attr['href'];
            }
        }
        if ($nextUrl) {
            preg_match('#(.+?)/\d+\.html#', $info['surl'], $preUrl);
            $nextUrl = $preUrl[1] . '/' . $nextUrl;
        } else {
            //标记结束
            $countImg = SourceImage::find()->where(['psid' => $info['id']])->count();
            Source::updateAll(['status' => 3, 'count' => $countImg], 'id=' . $info['id']);
            
        }
        
        return $nextUrl;
    }

    /**
     * 下载图片
     */
    private function getImage($url, $preDir) {
        $preSavePath = \Yii::getAlias('@uploads');
        $locaPath = $preDir . '.jpg';
        $savePath = $preSavePath . $locaPath;
        if (file_exists($savePath) && is_readable($savePath)) {
            return -1;
        }
        $saveDir = dirname($savePath);
        if (!is_dir($saveDir)) {
            mkdir($saveDir, 0777, true);
        }
        Functions::curlGetImage($url, $savePath, 'http://'.$this->nameZfl.'/', 'images.zhaofulipic.com:8818');
        return [$locaPath, $savePath];
    }

    /**
     * 获取列表数据
     */
    private function getInfo($url) {
        //缓存key
        $sKey = 'zfl-list' . md5($url);
        //获取缓存
//        $cacheData = \yii::$app->cache->get($sKey);
//        if ($cacheData && $cacheData['data']) {
//            return $cacheData['data'];
//        }
        $html = Functions::curlZFL($url,'http://'.$this->nameZfl.'/',$this->nameZfl);
        if (!$html) {
            sleep(1);
            $html = Functions::curlZFL($url,'http://'.$this->nameZfl.'/',$this->nameZfl);
        }
        if (!$html) {
            sleep(1);
            $html = Functions::curlZFL($url,'http://'.$this->nameZfl.'/',$this->nameZfl);
        }
        if (!$html) {
            sleep(1);
            $html = Functions::curlZFL($url,'http://'.$this->nameZfl.'/',$this->nameZfl);
        }
        $cacheData['data'] = $html;

        \yii::$app->cache->set($sKey, $cacheData, 3000); //缓存一个小时
        return $cacheData['data'];
    }

    /**
     * 检查图片是否存在
     */
    public function actionCheck(){
        set_time_limit(0);
        $imgList = SourceImage::find()->where(['check'=>0])->asArray()->limit(50)->all();
        if(!$imgList){
            exit();
        }
        $ids = array_column($imgList, 'id');
        SourceImage::updateAll(['check' => 9],['in','id',$ids]);
        foreach($imgList as $k=>$v){
            $path = $this->getImage($v['surl'], substr($v['path'],0,-4));
            if ($path == -1) {
                SourceImage::updateAll(['status' => 3,'check' => 1],['id' => $v['id']]);
                continue;
            }
            list($locaPath, $savePath) = $path;
            if(file_exists($savePath)){
                SourceImage::updateAll(['status' => 3,'check' => 1],['id' => $v['id']]);
            }
        }
    }
    
    /**
     * 整页添加及验证
     */
    public function actionAddpage(){
        include_once \Yii::getAlias("@app") . '/common/simple_html_dom.php';
        
        $url = 'http://yxpjw.me/page/8.html';
        $html = Functions::curlZFL($url,'http://'.$this->nameZfl.'/',$this->nameZfl);
        $dom = new \simple_html_dom();
        $dom->load($html);
        $list = $dom->find('article[class^=excerpt]');
        $insertData = array();
        $formatTime = date('Y-m-d H:i:s', time());
        foreach ($list as $k => $item) {
            $tmp = [];
            $tmp['name'] = $this->nameZfl;
            $url = $item->find('h2 a')[0]->attr['href'];
            //获取sid
            preg_match('#/(\d+)\.html#', $url, $m);
            $tmp['sid'] = $m[1];
            //判断是否存在
            $count = Source::find()->where(['sid'=>$tmp['sid']])->count();
            if($count){
                echo $count.'<br>';
                continue; 
            }
            $subject = $item->find('h2 a')[0]->text();
            
            $tmp['surl'] = 'http://'.$this->nameZfl . $url;
            $tmp['subject'] = iconv('gb2312', 'utf-8//IGNORE', $subject);
            $tmp['time_create'] = $formatTime;
            $insertData[] = $tmp;
        }
        
        if ($insertData) {
            \Yii::$app->db->createCommand()->batchInsert(Source::tableName(), ['name', 'sid', 'surl', 'subject', 'time_create'], $insertData)->execute();
        }
        Functions::printarr($insertData,1);
    }
    
    public function actionTest(){
        include_once \Yii::getAlias("@app") . '/common/simple_html_dom.php';
        
        $html = $this->getInfo('http://zhaofuli.biz/luyilu/2017/0204/2951.html');
        $dom = new \simple_html_dom();
        $dom->load($html);
        
        exit();
    }
}
