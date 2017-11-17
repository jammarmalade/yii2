<?php

namespace console\components;

use yii;

//websocket 服务器端

class WebSocket {

    private $_server;
    //加密key
    public $key = '';
    // 用户信息，uid => username ,fd
    public $userInfo = [];

    public function __construct($config) {
        $this->_server = new \swoole_websocket_server($config['host'], $config['port']);
        $this->key = $config['key'];
        $this->_server->set([
            'worker_num' => 1,
            'heartbeat_check_interval' => 30,
            'heartbeat_idle_time' => 65,
        ]);
        $this->_server->on('open', [$this, 'onOpen']);
        $this->_server->on('message', [$this, 'onMessage']);
        $this->_server->on('close', [$this, 'onClose']);
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
        $notice = false;
        // 始终把用户最新的fd跟uid映射在一起
        if (array_key_exists($request->get['uid'], $this->userInfo)) {
            $existFd = $this->userInfo[$request->get['uid']]['fd'];
            //前端有心跳检测，防止新开页面和旧页面之间反复重连，通知旧页面停止心跳检测
            $this->push($existFd, [
                'event' => 'uncheck',
            ]);
            $this->close($existFd, 'uid exists.');
            $this->userInfo[$request->get['uid']]['fd'] = $request->fd;
        } else {
            $this->userInfo[$request->get['uid']]['fd'] = $request->fd;
            //更新所有用户的成员列表
            $notice = true;
        }
        //用户名
        $this->userInfo[$request->get['uid']]['username'] = urldecode($request->get['username']);
        //城市
        $this->userInfo[$request->get['uid']]['city'] = urldecode($request->get['city']);
        if($notice){
            $this->noticeAll($request->get['uid']);
        }
        $this->log($this->userInfo[$request->get['uid']]['username']." connected");
    }
    //通知所有在线用户，此用户上线
    public function noticeAll($uid){
        $tmpUserInfo = $this->userInfo[$uid];
        foreach($this->userInfo as $tmpuid=>$info){
            if($tmpuid!=$uid){
                $this->push($info['fd'], [
                    'event' => 'addMember',
                    'uid' => $uid,
                    'username' => $tmpUserInfo['username'],
                    'city' => $tmpUserInfo['city']
                ]);
            }
        }
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
        //删除用户信息
        $tmpUid = '';
        foreach ($this->userInfo as $uid=>$info){
            if ($info['fd']==$fd) {
                $tmpUid = $uid;
                unset($this->userInfo[$uid]);
            }
        }
        if($tmpUid==''){
            return false;
        }
        //通知所有用户刷新成员列表
        foreach ($this->userInfo as $uid => $info) {
            $this->push($info['fd'], [
                'uid' => $tmpUid,
                'event' => 'deleteMember',
            ]);
        }
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
     * 关闭链接 - 内部调用
     * @param type $fd
     */
    public function close($fd) {
        // 关闭连接
        $this->_server->close($fd);
    }
    /**
     * 发送群体消息
     * @param type $fd      当前用户的标识
     * @param type $data    接收到的内容
     */
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
            $this->push($info['fd'], [
                'fromUserId'=>$data['uid'],
                'event' => 'sendMsgAll',
                'message' => $content,
                'username'=>$fromUser['username'],
                'sendtime'=>$now
            ]);
        }
    }
    //获取成员列表
    public function getMemberList($fd, $data){
        $memberList = [];
        foreach($this->userInfo as $uid=>$info){
            $tmp = [];
            $tmp['uid'] = $uid;
            $tmp['username'] = $info['username'];
            $tmp['city'] = $info['city'];
            $memberList[] = $tmp;
        }
        $this->push($fd, [
            'event' => 'getMemberList',
            'memberList' => $memberList,
        ]);
    }
    //保持在线
    public function keepalive($fd,$data){
        $this->push($fd, [
            'event' => 'keepalive',
            'data' => '',
        ]);
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
        if ($this->_server->push($fd, $message) == false) {
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
        $this->_server->start();
    }

}
