<?php

namespace frontend\components;

use yii;
use yii\web\Cookie;

class Functions {

    /**
     * 打印数组
     * @param type $arr
     */
    public static function printarr($arr, $ext = 0) {
        echo '<pre>';
        print_r($arr);
        echo '</pre>';
        if ($ext) {
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
        $path = YII_DEBUG ? \Yii::getAlias("@webroot") . '/log.txt' : INDEX_ROOT . '/log.txt';
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

    /**
     * 返回两个日期间的所有日期
     * @param type $start   开始时间 2017-08-25
     * @param type $end     结束时间 2017-08-25
     * @param type $format  日期格式 Y-m-d
     * @return array
     */
    public static function rangDate($start, $end, $format = 'n-d') {
        $dt_start = strtotime($start);
        $dt_end = strtotime($end);
        $date = [];
        while ($dt_start <= $dt_end) {
            $date[] = date($format, $dt_start);
            $dt_start = strtotime('+1 day', $dt_start);
        }
        return $date;
    }

    /**
     * 二维数组排序，指定key值
     * @param array $multi_array    要排序的数组
     * @param string $sort_key      按照哪一个key值排序
     * @param const $sort           排序规则 SORT_ASC，SORT_DESC
     * @return array                排序后的数组
     */
    public static function multiArraySort($multi_array, $sort_key, $sort = SORT_ASC) {
        if (is_array($multi_array)) {
            foreach ($multi_array as $row_array) {
                if (is_array($row_array)) {
                    $key_array[] = $row_array[$sort_key];
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
        array_multisort($key_array, $sort, $multi_array);
        return $multi_array;
    }

    /**
     * 关闭yii 的toolbar
     */
    public static function DebugToolbarOff() {
        if (class_exists('\yii\debug\Module')) {
            Yii::$app->view->off(\yii\web\View::EVENT_END_BODY, [\yii\debug\Module::getInstance(), 'renderToolbar']);
        }
    }

    /**
     * 随机返回颜色十六进制值
     */
    public static function randomColor() {
//        mt_srand((double) microtime() * 1000000);
//        $c = '#';
//        while (strlen($c) < 6) {
//            $c .= sprintf("%02X", mt_rand(0, 255));
//        }
//        return $c;
        //第二种
        $color = ['#8A9B0F', '#EB6841', '#3FB8AF', '#FE4365', '#FC9D9A', '#EDC951', '#C8C8A9', '#83AF9B', '#036564', '#3299BB', '#428BCA'];
        return $color[array_rand($color)];
    }

    /**
     * 设置cookie
     */
    public static function setCookie($key, $value, $expire = 3600) {
//        $viewCookies = Yii::$app->response->cookies;
//        $viewCookies->add(new Cookie([
//            'name' => $key,
//            'expire' => $expire,
//            'value' => $value
//        ]));
        $time = time();
        $expire = $expire > 0 ? $time + $expire : ($expire < 0 ? $time - 600 : 0);
        setcookie($key, $value, $expire);
    }

    /**
     * 获取cookie
     */
    public static function getCookie($key, $defaultValue = '') {
//        $cookies = Yii::$app->request->cookies;
//        self::printarr($cookies);
//        if (isset($cookies[$key])) {
//            return $cookies[$key]->value;
//        }else{
//            return $defaultValue;
//        }
        return isset($_COOKIE[$key]) ? $_COOKIE[$key] : $defaultValue;
    }

    /**
     * 是否存在cookie
     */
    public static function hasCookie($skey) {
        $cookies = Yii::$app->request->cookies;
        return $cookies->has($skey);
    }

    /**
     * textarea 转 br
     */
    public static function textarea2br($cotent) {
        return preg_replace('#\r\n|\r|\n#', '<br />', $cotent);
    }

    /**
     * 格式化时间
     * @param string $time 时间戳
     * @param int $ago 是否显示为 几秒/几分/几小时...的相对时间
     * @param string $format  显示的日期格式
     * @return string
     */
    public static function formatTime($time, $ago = '', $format = '') {
        static $year;
        $time = strtotime($time);
        $timestamp = time();
        $year = date('Y', $timestamp);
        if ($ago) {
            $dur = $timestamp - $time;
            if ($dur < 0) {
                return date('Y-m-d H:i:s', $time);
            } else {
                if ($dur < 60) {
                    return $dur . ' 秒前';
                } else {
                    if ($dur < 3600) {
                        return floor($dur / 60) . ' 分钟前';
                    } else {
                        if ($dur < 86400) {
                            return floor($dur / 3600) . ' 小时前';
                        } else {
                            if ($dur < 604800) {
                                $day = floor($dur / 86400);
                                if ($day == 1) {
                                    return '昨天 ' . date('H:i', $time);
                                } elseif ($day == 2) {
                                    return '前天 ' . date('H:i', $time);
                                } else {
                                    return $day . ' 天前';
                                }
                            } else {
                                if ($year != date('Y', $time)) {
                                    return date('Y-m-d H:i', $time);
                                } else {
                                    return date('n-j H:i', $time);
                                }
                            }
                        }
                    }
                }
            }
        } else {
            return date($format ? $format : ('Y-m-d H:i:s'), $time);
        }
    }

    public static function ip2city($ip) {
        if (preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/", $ip)) {
            $iparray = explode('.', $ip);
            if ($iparray[0] == 10 || $iparray[0] == 127 || ($iparray[0] == 192 && $iparray[1] == 168) || ($iparray[0] == 172 && ($iparray[1] >= 16 && $iparray[1] <= 31))) {
                return '- LAN';
            } elseif ($iparray[0] > 255 || $iparray[1] > 255 || $iparray[2] > 255 || $iparray[3] > 255) {
                return '- Invalid IP Address';
            }
        } else {
            return '';
        }
        $ipdatafile = \Yii::getAlias('@webroot').'/../components/wry.dat';
        if (!$fd = @fopen($ipdatafile, 'rb')) {
            return '- Invalid IP data file';
        }

        $ip = explode('.', $ip);
        $ipNum = $ip[0] * 16777216 + $ip[1] * 65536 + $ip[2] * 256 + $ip[3];

        if (!($DataBegin = fread($fd, 4)) || !($DataEnd = fread($fd, 4)))
            return;
        @$ipbegin = implode('', unpack('L', $DataBegin));
        if ($ipbegin < 0)
            $ipbegin += pow(2, 32);
        @$ipend = implode('', unpack('L', $DataEnd));
        if ($ipend < 0)
            $ipend += pow(2, 32);
        $ipAllNum = ($ipend - $ipbegin) / 7 + 1;

        $BeginNum = $ip2num = $ip1num = 0;
        $ipAddr1 = $ipAddr2 = '';
        $EndNum = $ipAllNum;

        while ($ip1num > $ipNum || $ip2num < $ipNum) {
            $Middle = intval(($EndNum + $BeginNum) / 2);

            fseek($fd, $ipbegin + 7 * $Middle);
            $ipData1 = fread($fd, 4);
            if (strlen($ipData1) < 4) {
                fclose($fd);
                return '- System Error';
            }
            $ip1num = implode('', unpack('L', $ipData1));
            if ($ip1num < 0)
                $ip1num += pow(2, 32);

            if ($ip1num > $ipNum) {
                $EndNum = $Middle;
                continue;
            }

            $DataSeek = fread($fd, 3);
            if (strlen($DataSeek) < 3) {
                fclose($fd);
                return '- System Error';
            }
            $DataSeek = implode('', unpack('L', $DataSeek . chr(0)));
            fseek($fd, $DataSeek);
            $ipData2 = fread($fd, 4);
            if (strlen($ipData2) < 4) {
                fclose($fd);
                return '- System Error';
            }
            $ip2num = implode('', unpack('L', $ipData2));
            if ($ip2num < 0)
                $ip2num += pow(2, 32);

            if ($ip2num < $ipNum) {
                if ($Middle == $BeginNum) {
                    fclose($fd);
                    return '- Unknown';
                }
                $BeginNum = $Middle;
            }
        }

        $ipFlag = fread($fd, 1);
        if ($ipFlag == chr(1)) {
            $ipSeek = fread($fd, 3);
            if (strlen($ipSeek) < 3) {
                fclose($fd);
                return '- System Error';
            }
            $ipSeek = implode('', unpack('L', $ipSeek . chr(0)));
            fseek($fd, $ipSeek);
            $ipFlag = fread($fd, 1);
        }

        if ($ipFlag == chr(2)) {
            $AddrSeek = fread($fd, 3);
            if (strlen($AddrSeek) < 3) {
                fclose($fd);
                return '- System Error';
            }
            $ipFlag = fread($fd, 1);
            if ($ipFlag == chr(2)) {
                $AddrSeek2 = fread($fd, 3);
                if (strlen($AddrSeek2) < 3) {
                    fclose($fd);
                    return '- System Error';
                }
                $AddrSeek2 = implode('', unpack('L', $AddrSeek2 . chr(0)));
                fseek($fd, $AddrSeek2);
            } else {
                fseek($fd, -1, SEEK_CUR);
            }

            while (($char = fread($fd, 1)) != chr(0))
                $ipAddr2 .= $char;

            $AddrSeek = implode('', unpack('L', $AddrSeek . chr(0)));
            fseek($fd, $AddrSeek);

            while (($char = fread($fd, 1)) != chr(0))
                $ipAddr1 .= $char;
        } else {
            fseek($fd, -1, SEEK_CUR);
            while (($char = fread($fd, 1)) != chr(0))
                $ipAddr1 .= $char;

            $ipFlag = fread($fd, 1);
            if ($ipFlag == chr(2)) {
                $AddrSeek2 = fread($fd, 3);
                if (strlen($AddrSeek2) < 3) {
                    fclose($fd);
                    return '- System Error';
                }
                $AddrSeek2 = implode('', unpack('L', $AddrSeek2 . chr(0)));
                fseek($fd, $AddrSeek2);
            } else {
                fseek($fd, -1, SEEK_CUR);
            }
            while (($char = fread($fd, 1)) != chr(0))
                $ipAddr2 .= $char;
        }
        fclose($fd);

        if (preg_match('/http/i', $ipAddr2)) {
            $ipAddr2 = '';
        }
        $ipaddr = "$ipAddr1 $ipAddr2";
        $ipaddr = preg_replace('/CZ88\.NET/is', '', $ipaddr);
        $ipaddr = preg_replace('/^\s*/is', '', $ipaddr);
        $ipaddr = preg_replace('/\s*$/is', '', $ipaddr);
        if (preg_match('/http/i', $ipaddr) || $ipaddr == '') {
            $ipaddr = '- Unknown';
        }
        return '- ' . iconv('gbk','utf-8',$ipaddr);
    }
}
