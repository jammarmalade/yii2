<?php

namespace api\common;

class Functions {

    public static function printarr($arr, $exit = 0) {
        echo '<pre>';
        print_r($arr);
        echo '</pre>';
        if ($exit) {
            exit();
        }
    }

    //curl 获取数据
    public static function curlHeader($url) {
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

    /**
     * http://fuli.asia/ curl
     */
    public static function curlZFL($url ,$referer ,$host) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.157 Safari/537.36");
        $header = array(
            "Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
            "Referer:$referer",
            "Host:$host",
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

    public static function curlGetImage($url = "", $filename = "", $referer = '', $host = '') {
        if (is_dir(basename($filename))) {
            echo "The Dir was not exits";
            return false;
        }
        $hander = curl_init();

        curl_setopt($hander, CURLOPT_URL, $url);
        curl_setopt($hander, CURLOPT_TIMEOUT, 60);
        curl_setopt($hander, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($hander, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($hander, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36");
//        $header[] = "Accept:image/webp,*/*;q=0.8";
//        $header[] = "Accept-Encoding:gzip, deflate, sdch";
//        $header[] = "Accept-Language:zh-CN,zh;q=0.8";
//        $header[] = "Cache-Control:no-cache";
//        $header[] = "Pragma:no-cache";
        $header[] = "Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";
        $header[] = "Accept-Encoding:gzip, deflate, sdch";
        $header[] = "Accept-Language:zh-CN,zh;q=0.8";
        $header[] = "Cache-Control:no-cache";
        $header[] = "Connection:keep-alive";
        $header[] = "Pragma:no-cache";
        $header[] = "Upgrade-Insecure-Requests:1";
        $header[] = "User-Agent:Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36";
        if ($referer) {
            $header[] = "Referer:$referer";
        }
        if ($host) {
            $header[] = "Host:$host";
        }
        if ($header) {
            curl_setopt($hander, CURLOPT_HTTPHEADER, $header);
        }
        $img = curl_exec($hander);
        curl_close($hander);

        if ($img) {
            $fp = fopen($filename, 'wb');
            fwrite($fp, $img);
            fclose($fp);
            return true;
        } else {
            return false;
        }
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
    /**
     * 加密解密字符串
     * @param type $string          加密字符串
     * @param type $operation       操作 DECODE / ENCODE
     * @param type $key             加密密钥
     * @param type $expiry          过期时间
     * @return string
     */
    public static function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
        $ckey_length = 4;
        $key = md5($key != '' ? $key : '123456');
        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';

        $cryptkey = $keya . md5($keya . $keyc);
        $key_length = strlen($cryptkey);

        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
        $string_length = strlen($string);

        $result = '';
        $box = range(0, 255);

        $rndkey = array();
        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }

        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        if ($operation == 'DECODE') {
            if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            return $keyc . str_replace('=', '', base64_encode($result));
        }
    }

}
