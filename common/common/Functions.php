<?php

namespace commom\common;

class Functions {

    public static function printarr($arr) {
        echo '<pre>';
        print_r($arr);
        echo '</pre>';
    }

    public static function fput($msg, $arr = 0, $ext = '') {
        $time = date('Y-m-d H:i:s', time());
        $path = YII_DEBUG ? 'E:\wamp\www\log.txt' : INDEX_ROOT . '/log.txt';
        if ($arr) {
            file_put_contents($path, var_export($msg, true) . ' - ' . $ext . ' - ' . $time . PHP_EOL, FILE_APPEND);
        } else {
            file_put_contents($path, $msg . ' - ' . $ext . ' - ' . $time . PHP_EOL, FILE_APPEND);
        }
    }

}
