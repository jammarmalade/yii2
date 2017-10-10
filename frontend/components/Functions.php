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
    public static function multi_array_sort($multi_array, $sort_key, $sort = SORT_ASC) {
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

}
