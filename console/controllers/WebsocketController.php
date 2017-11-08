<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use console\components\WebSocket;

/**
 * websocket controller
 */
class WebsocketController extends Controller {

    public function actionStart() {
        //引入websocket类文件并开启监听
        $server = new WebSocket();
        $server->start();
    }

}
