<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use console\components\WebSocket;
use common\models\Config;

/**
 * websocket controller
 */
class WebsocketController extends Controller {

    public function actionStart() {
        //引入websocket类文件并开启监听
        $config = [
            'host' => Config::getConfig('ws_host','console'),
            'port' => Config::getConfig('ws_port','console'),
            'key' => Config::getConfig('ws_key','console'),
        ];
        $server = new WebSocket($config);
        $server->start();
    }

}
