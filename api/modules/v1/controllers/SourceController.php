<?php

/**
 * 资源
 */

namespace api\modules\v1\controllers;

use api\controllers\ApiactiveController;
use api\common\Functions;
use api\common\simple_html_dom;
use backend\models\Source;

class SourceController extends ApiactiveController {

    /**
     * 获取列表
     * http：//127.0.0.1/advanced/api/web/index.php/v1/source/zfl
     * http://zhaofuli.biz/page/1.html
     */
    public function actionZfl() {
        $name = 'zhaofuli.biz';
        //查询出最新的sid
        $info = Source::find()->where(['name' => $name])->orderBy('time_create DESC')->max();

        exit();

        $page = 1;
        $html = $this->getList($page);
        $this->saveList($html, $page, $info);
    }

    /**
     * 获取列表数据
     */
    private function getList($page) {
        //缓存key
        $sKey = 'zfl-list' . $page;
        //获取缓存
        $cacheData = \yii::$app->cache->get($sKey);
        if ($cacheData) {
            return $cacheData['data'];
        }
        $html = Functions::curlZFL('http://zhaofuli.biz/page/' . $page . '.html');
        $cacheData['data'] = $html;

        \yii::$app->cache->set($sKey, $cacheData, 3600); //缓存一个小时
        return $cacheData['data'];
    }

    /**
     * 将文件列表存入数据库
     */
    private function saveList($html, $page, $info) {
        include_once \Yii::getAlias("@app") . '/common/simple_html_dom.php';

        $dom = new \simple_html_dom();
        $dom->load($html);
        $list = $dom->find('article[class^=excerpt]');
        $insertData = array();
        $formatTime = date('Y-m-d H:i:s', time());
        foreach ($list as $k => $item) {
            $tmp = [];
            $tmp['name'] = 'zhaofuli.biz';
            $url = $item->find('h2 a')[0]->attr['href'];
            $subject = $item->find('h2 a')[0]->text();
            //获取sid
            preg_match('#/(\d+)\.html#', $url, $m);
            if ($info && $info['sid'] == $m[1]) {
                break;
            }
            $tmp['sid'] = $m[1];
            $tmp['surl'] = 'http://zhaofuli.biz/' . $url;
            $tmp['subject'] = iconv('gb2312', 'utf-8//IGNORE', $subject);
            $tmp['content'] = '';
            $tmp['type'] = 0;
            $tmp['get'] = 0;
            $tmp['page'] = $page;
            $tmp['time_create'] = $formatTime;
            $insertData[] = $tmp;
        }
        if ($insertData) {
            \Yii::$app->db->createCommand()->batchInsert(Source::tableName(), ['name', 'sid', 'surl', 'subject', 'content', 'type', 'get', 'page', 'time_create'], $insertData)->execute();
        }
    }

}
