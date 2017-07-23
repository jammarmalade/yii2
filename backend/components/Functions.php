<?php

namespace backend\components;

class Functions {

    /**
     * 打印数组
     * @param type $arr
     */
    public static function printarr($arr,$ext = 0) {
        echo '<pre>';
        print_r($arr);
        echo '</pre>';
        if($ext){
            exit();
        }
    }

    /**
     * 输出数据到文件
     * @param type $msg 
     * @param type $arr 是否是数组 1为数组
     * @param type $ext 其他信息
     */
    public static function fput($msg, $arr = 0, $ext = '') {
        $time = date('Y-m-d H:i:s', time());
        $path = YII_DEBUG ? \Yii::getAlias("@webroot").'/log.txt' : INDEX_ROOT . '/log.txt';
        if ($arr) {
            file_put_contents($path, var_export($msg, true) . ' - ' . $ext . ' - ' . $time . PHP_EOL, FILE_APPEND);
        } else {
            file_put_contents($path, $msg . ' - ' . $ext . ' - ' . $time . PHP_EOL, FILE_APPEND);
        }
    }

    /**
     * 字节换算
     * @param type $size
     * @return type
     */
    public static function size_count($size) {
        $unit = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');
        return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
    }

    /**
     * 随机数 
     * @param type $length 长度
     * @param type $numeric 纯数字
     * @return type
     */
    public static function random($length, $numeric = 0) {
        $seed = base_convert(md5(microtime()), 16, $numeric ? 10 : 35);
        $seed = $numeric ? (str_replace('0', '', $seed) . '012340567890') : ($seed . 'zZ' . strtoupper($seed));
        if ($numeric) {
            $hash = '';
        } else {
            $hash = chr(rand(1, 26) + rand(0, 1) * 32 + 64);
            $length--;
        }
        $max = strlen($seed) - 1;
        for ($i = 0; $i < $length; $i++) {
            $hash .= $seed{mt_rand(0, $max)};
        }
        return $hash;
    }

}
