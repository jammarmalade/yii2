<?php
<<<<<<< HEAD
namespace api\common;

class Functions {
    public static function printarr($arr){
=======

namespace api\common;

class Functions {

    public static function printarr($arr) {
>>>>>>> f59ee97123803bb24cfdf87180f035b4694c234a
        echo '<pre>';
        print_r($arr);
        echo '</pre>';
    }
<<<<<<< HEAD
}


=======
    //curl 获取数据
    public static function myCurl($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.125 Safari/537.36");
        $header = array(
            "Accept:application/xml",
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

}
>>>>>>> f59ee97123803bb24cfdf87180f035b4694c234a
