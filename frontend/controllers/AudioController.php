<?php

namespace frontend\controllers;

use Yii;
use frontend\components\WebController;
use common\models\Audio;
use backend\controllers\UploadController;
use frontend\components\Functions as tools;
use frontend\components\Audio\AipSpeech;

/**
 * audio controller
 */
class AudioController extends WebController {

    public function actionIndex() {

        return $this->render('index', [

        ]);
    }

    public function actionCreate(){
        if(!$this->uid){
            return $this->ajaxReturn('', '本功能暂时只支持登录用户使用！');
        }
        $content = $this->input('post.content', '');
        $content = strip_tags($content);
        if(trim($content)==''){
            return $this->ajaxReturn('', '请输入合成的文本内容!');
        }
        $spd = $this->input('post.spd', 4);
        if($spd < 0 || $spd > 9){
            return $this->ajaxReturn('', '语速选择错误！');
        }
        $pit = $this->input('post.pit', 5);
        if($pit < 0 || $pit > 9){
            return $this->ajaxReturn('', '音调选择错误！');
        }
        $vol = $this->input('post.vol', 5);
        if($vol < 0 || $vol > 15){
            return $this->ajaxReturn('', '音量选择错误！');
        }
        $per = $this->input('post.per', 0);
        if(!in_array($per, [0,1,3,4])){
            return $this->ajaxReturn('', '发音人选择错误');
        }
        //保存路径
        $pathArr = tools::getSavePath('audio','mp3');

        $appId = '10465622';
        $appKey = 'HpTkIGxXyCM666GNBoV8o4Ya';
        $appSecret = 'PaRhijyOmtML7asqdzPvLdZg1xQ3PeMi';

        $client = new AipSpeech($appId, $appKey, $appSecret);
        $result = $client->synthesis($content, 'zh', 1, [
            'spd' => $spd,//语速，取值0-9，默认为5中语速
            'pit' => $pit,//音调，取值0-9，默认为5中语调
            'vol' => $vol,//音量，取值0-15，默认为5中音量
            'per' => $per,//发音人选择, 0为女声，1为男声，3为情感合成-度逍遥，4为情感合成-度丫丫，默认为普通女
        ]);
        if(!is_array($result)){
            file_put_contents($pathArr['localPath'], $result);
            $audioModel = new Audio();
            $audioModel->uid = $this->uid;
            $audioModel->content = $content;
            $audioModel->spd = $spd;
            $audioModel->pit = $pit;
            $audioModel->vol = $vol;
            $audioModel->per = $per;
            $audioModel->path = $pathArr['savePath'];
            $audioModel->time_create = $this->formatTime;
            $audioModel->id = $audioModel->save(false);
            if($audioModel->id){
                $audioUrl = Yii::$app->params['staticDomain'].$pathArr['savePath'];
                return $this->ajaxReturn([
                    'id' => $audioModel->id,
                ], $audioUrl,true);
            }else{
                return $this->ajaxReturn('', '保存失败');
            }
        }else{
            $err = [
                500 => '不支持输入',
                501 => '输入参数不正确',
                502 => 'token验证失败',
                503 => '合成后端错误',
            ];
            return $this->ajaxReturn('', '合成失败！[ '.$err[$result['err_no']].' ]');
        }

    }

}
