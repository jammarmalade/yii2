<?php

namespace console\components;

use yii;

//websocket 服务器端

class WebSocket {

    private $_serv;
    //加密key
    public $key = '#jam00#';
    // 用户信息，uid => username ,fd
    public $userInfo = [];

    public function __construct() {
        $this->_serv = new \swoole_websocket_server("192.168.31.200", 9501);
        $this->_serv->set([
            'worker_num' => 1,
            'heartbeat_check_interval' => 60,
            'heartbeat_idle_time' => 125,
        ]);
        $this->_serv->on('open', [$this, 'onOpen']);
        $this->_serv->on('message', [$this, 'onMessage']);
        $this->_serv->on('close', [$this, 'onClose']);
    }

    /**
     * @param $serv
     * @param $request
     * @return mixed
     */
    public function onOpen($serv, $request) {
        // 连接授权
        $accessResult = $this->checkAccess($serv, $request);
        if (!$accessResult) {
            return false;
        }
        // 始终把用户最新的fd跟uid映射在一起
        if (array_key_exists($request->get['uid'], $this->userInfo)) {
            $existFd = $this->userInfo[$request->get['uid']]['fd'];
            $this->close($existFd, 'uid exists.');
            $this->userInfo[$request->get['uid']]['fd'] = $request->fd;
        } else {
            $this->userInfo[$request->get['uid']]['fd'] = $request->fd;
        }
        $this->userInfo[$request->get['uid']]['username'] = urldecode($request->get['username']);
        $this->log($this->userInfo[$request->get['uid']]['username']." connected");
    }

    /**
     * @param $serv
     * @param $frame
     * @return mixed
     */
    public function onMessage($serv, $frame) {
        // 校验数据的有效性，我们认为数据被`json_decode`处理之后是数组并且数组的`event`项非空才是有效数据
        // 非有效数据，关闭该连接
        $data = $frame->data;
        $data = json_decode($data, true);
        if (!$data || !is_array($data) || empty($data['event'])) {
            $this->close($frame->fd, 'data format invalidate.');
            return false;
        }
        // 根据数据的`event`项，判断要做什么,`event`映射到当前类具体的某一个方法，方法不存在则关闭连接
        $method = $data['event'];
        if (!method_exists($this, $method)) {
            $this->close($frame->fd, 'event is not exists.');
            return false;
        }
        $this->$method($frame->fd, $data);
    }

    public function onClose($serv, $fd) {
        $this->log("client {$fd} closed.");
    }

    /**
     * 校验客户端连接的合法性,无效的连接不允许连接
     * @param type $serv
     * @param type $request
     * @return boolean
     */
    public function checkAccess($serv, $request) {
        // get不存在或者uid和token有一项不存在，关闭当前连接
        if (!isset($request->get) || !isset($request->get['uid']) || !isset($request->get['token'])) {
            $this->close($request->fd, 'access faild.');
            return false;
        }
        $uid = $request->get['uid'];
        $token = $request->get['token'];
        // 校验token是否正确,无效关闭连接
        if (md5(md5($uid) . $this->key) != $token) {
            $this->close($request->fd, 'token invalidate.');
            return false;
        }
        return true;
    }

    /**
     * 关闭$fd的连接，并删除该用户的映射
     * @param $fd
     * @param $message
     */
    public function close($fd, $message = '') {
        // 删除映射关系
        foreach ($this->userInfo as $uid=>$info){
            if ($info['fd']==$fd) {
                // 关闭连接
                $this->_serv->close($fd);
                unset($this->userInfo[$uid]);
            }
        }

    }

    public function sendMsgAll($fd, $data) {
        //群发
        if(!isset($this->userInfo[$data['uid']])){
            $this->close($fd, 'user not exists.');
        }
        $fromUser = $this->userInfo[$data['uid']];
        $now = date('Y-m-d H:i:s');
        $content = htmlentities($data['content']);
        foreach($this->userInfo as $uid=>$info){
            $this->log("send to ".$info['username']);
            $this->push($info['fd'], ['fromUserId'=>$data['uid'],'event' => 'sendMsgAll', 'message' => $content,'username'=>$fromUser['username'],'sendtime'=>$now]);
        }
    }

    /**
     * @param $fd
     * @param $message
     */
    public function push($fd, $message) {
        if (!is_array($message)) {
            $message = [$message];
        }
        $message = json_encode($message);
        // push失败，close
        if ($this->_serv->push($fd, $message) == false) {
            $this->close($fd);
        }
    }

    /**
     * 输出
     */
    public function log($msg){
        echo $msg."\n";
    }

    public function start() {
        $this->_serv->start();
    }

}
