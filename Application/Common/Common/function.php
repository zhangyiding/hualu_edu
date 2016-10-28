<?php
use Common\Lib\Crypt3Des;

/**
 * get方式获取接口数据
 * $url 请求地址
 * $formate 返回格式 xml|json
 * $timeout 超时时间
 */
function get_remote_data($url, $formate = 'xml', $timeout = 1)
{
    $result = '';
    $opts = array(
        'http' => array(
            'timeout' => $timeout,
            'method' => "GET",
            'header' => "Content-Type: text/html; charset=utf-8",
        )
    );
    $result = file_get_contents($url, false, stream_context_create($opts));
    if (empty($result)) {
        return $result;
    }
    return $formate == 'xml' ? (array)simplexml_load_string($result) : $result;
}

/**
 * web页面请求接口数据时生成签名字符串，用于向接口发送请求时签名
 * @param int $sign_time
 * @param string $key
 * @return string
 */
function create_sign($sign_time, $key = 'tuanche1234abcd1234')
{
    if (empty($sign_time)) {
        $sign_time = time();
    }
    $sign = md5($sign_time . $key);
    return $sign;
}

/**
 * 接口请求参数加密
 * @param str $data
 * @return Ambigous <boolean, mixed>
 */
function encrypt_data($data, $key = '')
{
    if (empty($key)) {
        $key = C('PARAM_KEY');
    }
    $crypt = new Crypt3Des($key);
    $result = $crypt->encrypt($data);
    return $result;
}

/**
 * 接口返回数据解密
 * @param str $data
 * @return Ambigous <boolean, mixed>
 */
function decrypt_data($data, $dejson = true, $key = '')
{
    if (empty($key)) {
        $key = C('PARAM_KEY');
    }
    $crypt = new Crypt3Des($key);
    $result = $crypt->decrypt($data);
    if ($dejson) {
        $res_data = array();
        if ($result) {
            $res_data = json_decode($result, true);
        }
    } else {
        $res_data = $result;
    }
    return $res_data;
}

function create_token($deviceid = '', $user)
{
    $secrt = md5($deviceid . C('SIGN_KEY'));
    $identity = encrypt_data($user);
    return $identity . '_' . $secrt;
}

/**
 * 中文截取
 * @param str $str 需要截取的文字
 * @param int $len 截取长度
 * @param str $encoding 编码
 * @param bool $CUT_LEFT 是否从左侧开始截取
 */
function cut_str($str, $len, $encoding = 'UTF-8', $CUT_LEFT = '')
{
    if (mb_strlen($str, $encoding) > $len) {
        if ($CUT_LEFT) {
            return '…' . mb_substr($str, -$len, $len, $encoding);
        }
        return mb_substr($str, 0, $len, $encoding) . '…';
    } else {
        return $str;
    }
}

function checkUserAgent()
{
    $os_agent = $_SERVER['HTTP_USER_AGENT'];
    $iPad = (bool)stripos($os_agent, 'ipad');
    $iPhone = (bool)stripos($os_agent, 'iphone');
    $macOs = (bool)stripos($os_agent, 'mac');
    $Android = (bool)stripos($os_agent, 'android');
    $os = 'Android';
    if ($Android) {
        $os = 'Android';
    } elseif ($iPad) {
        $os = 'iPhone';
    } elseif ($iPad) {
        $os = 'iPhone';
    } elseif ($macOs) {
        $os = 'iPhone';
    }
    return $os;
}

function checkWxbrowser()
{
    $os_agent = $_SERVER['HTTP_USER_AGENT'];
    $wx_browser = (bool)stripos($os_agent, 'MicroMessenger');
    $is_wx = 0;
    if ($wx_browser) {
        $is_wx = 1;
    }
    return $is_wx;
}

/**
 *  屏蔽用户名部分词
 * @param $str
 * @return mixed
 */
function toSafeUserName($str)
{
    $_length = mb_strlen($str, 'UTF8');
    //取中间字
    if ($_length == 2) {
        $_mid_str = mb_substr($str, 0, 1, 'UTF8');
    } else {
        //大于2个字
        $_mid_str = mb_substr($str, 1, $_length - 2, 'UTF8');
    }
    //$_mid_length = mb_strlen($_mid_str, 'UTF8');
    //$_replaceStr = str_repeat('*', $_mid_length);
    return str_replace($_mid_str, '***', $str);
}

/**
 *
 * @param  $mobile
 *
 * 手机号验证
 */
function check_mobile($mobile, $pattern = false)
{

    if (!$pattern) {
        $pattern = '/(^1[34578]\d{9}$)/';
    }
    $result = preg_match($pattern, $mobile, $match);

    if (empty($match)) {
        return false;
    }
    return true;
}

function hide_mobile($mobile)
{
    return preg_replace("/^(\d{3})(\d{4})(\d{4})/", "\\1****\\3", $mobile);
}

function randomNum($c = 8)
{
    $m = min(mt_getrandmax(), pow(10, $c));
    return str_pad(mt_rand(0, $m - 1), $c, '0', STR_PAD_LEFT);
}

function randomStr($c = 4)
{
    $rchar = '';
    $str = "abcdefghijklmnopqrstuvwxyz";
    for ($i = 0; $i < $c; $i++) {
        $r = rand(0, 25);
        $rchar .= substr($str, $r, 1);
    }
    return $rchar;
}

function getfirstchar($s0)
{
    $fchar = ord($s0{0});
    if ($fchar >= ord("A") and $fchar <= ord("z")) {
        return strtoupper($s0{0});
    }
    $s1 = iconv("UTF-8", "gb2312", $s0);
    $s2 = iconv("gb2312", "UTF-8", $s1);
    if ($s2 == $s0) {
        $s = $s1;
    } else {
        $s = $s0;
    }
    $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
    if ($asc == -5736) {
        return "A";
    }
    if ($asc >= -20319 and $asc <= -20284) {
        return "A";
    }
    if ($asc >= -20283 and $asc <= -19776) {
        return "B";
    }
    if ($asc >= -19775 and $asc <= -19219) {
        return "C";
    }
    if ($asc >= -19218 and $asc <= -18711) {
        return "D";
    }
    if ($asc >= -18710 and $asc <= -18527) {
        return "E";
    }
    if ($asc >= -18526 and $asc <= -18240) {
        return "F";
    }
    if ($asc >= -18239 and $asc <= -17923) {
        return "G";
    }
    if ($asc >= -17922 and $asc <= -17418) {
        return "I";
    }
    if ($asc >= -17417 and $asc <= -16475) {
        return "J";
    }
    if ($asc >= -16474 and $asc <= -16213) {
        return "K";
    }
    if ($asc >= -16212 and $asc <= -15641) {
        return "L";
    }
    if ($asc >= -15640 and $asc <= -15166) {
        return "M";
    }
    if ($asc >= -15165 and $asc <= -14923) {
        return "N";
    }
    if ($asc >= -14922 and $asc <= -14915) {
        return "O";
    }
    if ($asc >= -14914 and $asc <= -14631) {
        return "P";
    }
    if ($asc >= -14630 and $asc <= -14150) {
        return "Q";
    }
    if ($asc >= -14149 and $asc <= -14091) {
        return "R";
    }
    if ($asc >= -14090 and $asc <= -13319) {
        return "S";
    }
    if ($asc >= -13318 and $asc <= -12839) {
        return "T";
    }
    if ($asc >= -12838 and $asc <= -12557) {
        return "W";
    }
    if ($asc >= -12556 and $asc <= -11848) {
        return "X";
    }
    if ($asc >= -11847 and $asc <= -11056) {
        return "Y";
    }
    if ($asc >= -11055 and $asc <= -10247) {
        return "Z";
    }
    return null;
}

function msubstr($str, $start = 0, $length, $charset = "utf-8", $suffix = true)
{
    if (function_exists("mb_substr")) {
        if ($suffix) {
            return mb_substr($str, $start, $length, $charset) . "...";
        } else {
            return mb_substr($str, $start, $length, $charset);
        }
    } elseif (function_exists('iconv_substr')) {
        if ($suffix) {
            return iconv_substr($str, $start, $length, $charset) . "...";
        } else {
            return iconv_substr($str, $start, $length, $charset);
        }
    }
    $re['utf-8'] = "/[x01-x7f]|[xc2-xdf][x80-xbf]|[xe0-xef]
                      [x80-xbf]{2}|[xf0-xff][x80-xbf]{3}/";
    $re['gb2312'] = "/[x01-x7f]|[xb0-xf7][xa0-xfe]/";
    $re['gbk'] = "/[x01-x7f]|[x81-xfe][x40-xfe]/";
    $re['big5'] = "/[x01-x7f]|[x81-xfe]([x40-x7e]|xa1-xfe])/";
    preg_match_all($re[$charset], $str, $match);
    $slice = join("", array_slice($match[0], $start, $length));
    if ($suffix) {
        return $slice . "…";
    }
    return $slice;
}

/**
 * @param 时间戳 $time
 * @return string
 */
function getWeekDay($time)
{
    $week = array('0' => '日', '1' => '一', '2' => '二', '3' => '三', '4' => '四', '5' => '五', '6' => '六');
    $tmpweek_day = date("w", $time);
    $week_day = $week[$tmpweek_day];
    return $week_day;
}

/*
 * 验证IP地址有效性
 */

function filter_valid_ip($ip)
{
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 |
            FILTER_FLAG_IPV6 |
            FILTER_FLAG_NO_PRIV_RANGE |
            FILTER_FLAG_NO_RES_RANGE) === false
    ) {
        return false;
    }
    return true;
}

function get_client_ipaddr()
{
    if (!empty($_SERVER ['HTTP_CLIENT_IP']) && filter_valid_ip($_SERVER ['HTTP_CLIENT_IP'])) {
        return $_SERVER ['HTTP_CLIENT_IP'];
    }
    if (!empty($_SERVER ['HTTP_X_FORWARDED_FOR'])) {
        $iplist = explode(',', $_SERVER ['HTTP_X_FORWARDED_FOR']);
        foreach ($iplist as $ip) {
            if (filter_valid_ip($ip)) {
                return $ip;
            }
        }
    }
    if (!empty($_SERVER ['HTTP_X_FORWARDED']) && filter_valid_ip($_SERVER ['HTTP_X_FORWARDED'])) {
        return $_SERVER ['HTTP_X_FORWARDED'];
    }
    if (!empty($_SERVER ['HTTP_X_CLUSTER_CLIENT_IP']) && filter_valid_ip($_SERVER ['HTTP_X_CLUSTER_CLIENT_IP'])) {
        return $_SERVER ['HTTP_X_CLUSTER_CLIENT_IP'];
    }
    if (!empty($_SERVER ['HTTP_FORWARDED_FOR']) && filter_valid_ip($_SERVER ['HTTP_FORWARDED_FOR'])) {
        return $_SERVER ['HTTP_FORWARDED_FOR'];
    }
    if (!empty($_SERVER ['HTTP_FORWARDED']) && filter_valid_ip($_SERVER ['HTTP_FORWARDED'])) {
        return $_SERVER ['HTTP_FORWARDED'];
    }
    return $_SERVER ['REMOTE_ADDR'];
}

//写文件操作
function writeToFile($file, $content, $pattern = 'ab')
{
    //$file_dir = dirname($file);
    if (!$fp = fopen($file, $pattern)) {
        return false;
    }
    fwrite($fp, $content);
    fclose($fp);
    return true;
}

/**
 * 此方法用于替换一个字符串中指定的值
 * +------------------------------------------------------
 * @param $replace 数组  key 为查找字符串$str中将被替换的字符串 ,value 替换的值
 * @param $str 基准字符串
 * @param $left
 * @param $right
 *
 */
function replace($replace, $str, $left = "{", $right = "}")
{
    $key = is_array($keys = array_keys($replace)) ? $keys[0] : "";
    $search = $key ? $left . $key . $right : $key;
    return ($search && $replace[$key]) ? str_replace($search, $replace[$key], $str) : false;
}

function _addslashes($value)
{
    $value = is_array($value) ? array_map('_addslashes', $value) : addslashes($value);
    return $value;
}

/*
 * 转换特殊字符（特殊字符处理）
 */

function specialchars_reverse($str)
{
    $return_str = '';
    $specArr = array(
        "\r" => "\n",
        "<br/>" => "\n",
        "<p>" => "\n",
        "<br>" => "\n",
        "+" => "＆",
        "＆amp;" => "＆",
        " " => '',
    );
    $str = str_replace(array_keys($specArr), array_values($specArr), $str);
    $str = strip_tags($str);
    $str = htmlspecialchars($str, ENT_COMPAT);
    $str = preg_replace("#\n\s*\n#", "\n", $str); // 把多个换行替换为一个换行
    $specArr = array(
        "~" => "～",
        "`" => "｀",
        "!" => "！",
        "$" => "＄",
        "<" => "＜",
        ">" => "＞",
        "\t" => "",
        "\'" => "'",
        '\"' => '"',
        "'" => "＇",
        '"' => "＂",
        "&" => "＆",
        "%" => "％",
        "/" => "／",
        "\\\\" => "\\",
        "\\" => "＼",
        "," => "，",
    );
    $str = str_replace(array_keys($specArr), array_values($specArr), $str);
    for ($i = 0; $i < strlen($str); $i++) {
        $char = $str{$i};
        if (ord($char) != 10) {
            $return_str .= (32 <= ord($char)) ? $char : "";
        }
    }
    unset($specArr, $str);
    return $return_str;
}

function gen_request_sign($sign_time)
{
    $key = C('SIGN_KEY');
    $sign = md5($sign_time . $key);
    return array('time' => $sign_time, 'sign' => $sign);
}

function check_pwd($pwd)
{
    $pwdLength = strlen($pwd);
    if ($pwdLength < 6) {
        return 12;
    }
    //密码强度
    $score = 0;
    //匹配数字
    if (preg_match("/[0-9]+/", $pwd)) {
        $score++;
    }
    //匹配小写
    if (preg_match("/[a-z]+/", $pwd)) {
        $score++;
    }
    //匹配大写
    if (preg_match("/[A-Z]+/", $pwd)) {
        $score++;
    }
    //匹配特殊符号
    if (preg_match("/[_|\-|+|=|*|!|@|#|$|%|^|&|(|)|'|\"|`]+/", $pwd)) {
        $score++;
    }
    if ($score < 2) {
        return 13;
    }
    return 0;
}

//中文字符检测
function str_has_chinese($str)
{
    if (is_string($str)) {
        if (mb_strlen($str, 'utf8') == strlen($str)) {
            return false;
        } else {
            return true;
        }
    }
    return false;
}

/**
 * 处理用户名称信息
 * @param string $str
 * @return string
 * @author liubin 2013-10-17
 */
function bodyShowAsterisk($str)
{
    $len = strlen($str);
    if ($len < 3) {
        return $str;
    }
    $strStart = mb_substr($str, 0, 1, "utf8");
    $strEnd = mb_substr($str, -1, 1, "utf8");
    $sterisk = '';

    for ($i = 0; $i < 3; $i++) {
        $sterisk .= "*";
    }
    return $strStart . $sterisk . $strEnd;
}

/**
 * 根据返回数据中某个字段进行升序或降序排列
 * @param  $data
 * @param  $orderBy 返回数据中某字段
 * @param  $sortTag (传入0是降序 1是升序)
 * @return array
 * @author liubin 2013-11-22
 */
function sortResult($data, $orderBy, $sortTag)
{
    $sortTag = (int)$sortTag;
    if (!is_array($data)) {
        return $data;
    }
    if (sizeof($data) <= 1) {
        return $data;
    }
    if (empty($orderBy)) {
        return $data;
    }
    //$sortTag:SORT_ASC ,SORT_DESC
    if ($sortTag <= 0) {
        $sortTag = SORT_DESC;
    } else {
        $sortTag = SORT_ASC;
    }
    // 取得列的列表
    foreach ($data as $key => $row) {
        $sortcol[$key] = $row[$orderBy];
    }
    //把 $data 作为最后一个参数，以通用键排序
    array_multisort($sortcol, $sortTag, $data);
    return $data;
}

function addslashes_deep($value)
{
    if (get_magic_quotes_gpc()) {
        return $value;
    }
    $value = is_array($value) ? array_map('addslashes_deep', $value) : addslashes($value);
    return $value;
}

/**
 * 过滤掉一些特殊字符
 */
function filter_special_chars($str)
{

    $special_chars = array('&', '~', '`', '＆', '％', '$', "@");
    $str = str_replace($special_chars, '', $str);
    return $str;
}


function urlsafe_b64encode($string)
{
    $data = base64_encode($string);
    $data = str_replace(array('+', '/', '='), array('-', '_', '.'), $data);
    return $data;
}

function urlsafe_b64decode($string)
{
    $data = str_replace(array('-', '_', '.'), array('+', '/', '='), $string);
    $mod4 = strlen($data) % 4;
    if ($mod4) {
        $data .= substr('====', $mod4);
    }
    $d = base64_decode($data);
    return $d;
}

function rad($d)
{
    return $d * 0.017453292519943; //$d * 3.1415926535898 / 180.0;
}

function mile($dist)
{
    return $dist * 0.0006214;
}

/**
 * @param 起点维度 $lat1
 * @param 起点经度 $lng1
 * @param 终点维度 $lat2
 * @param 终点经度 $lng2
 * @param 1米 2英里 $type
 * @return number
 */
function geo_distance($lat1, $lng1, $lat2, $lng2, $type = 1)
{
    $r = 6378137; // m 为单位
    $radLat1 = rad($lat1);
    $radLat2 = rad($lat2);
    $a = $radLat1 - $radLat2;
    $b = rad($lng1) - rad($lng2);
    $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));
    $s = $s * $r;
    $distance = round($s * 10000) / 10000;
    if ($type == 2) {
        $distance = mile($distance);
    }
    return $distance;
}

function verify_mobile($mobile)
{
    $pattern = "/^(\d{5,16})$/";
    if (!preg_match($pattern, $mobile, $matches)) {
        return false;
    }
    return true;
}

function createSmsCaptcha($lenght = 4)
{
    $str = "0123456789";
    $password = "";
    for ($i = 0; $i < $lenght; ++$i) {
        $n = rand(0, 9);
        $password .= $str[$n];
    }
    return $password;
}

function createUserLoginCaptcha()
{
    $str = "0123456789abcdefghijqklmnopqrstuvwxyz";
    $captcha = '';
    for ($i = 0; $i < 4; ++$i) {
        $n = rand(0, 35);
        $captcha .= $str[$n];
    }
    return $captcha;
}

function getClientId($clientname)
{
    $client_arr = C('CLIENT_NAME_ARR');
    $source_client = strtolower($clientname);
    return $client_arr[$source_client];
}

function check_email($email)
{
    $pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
    if (preg_match($pattern, $email)) {
        return true;
    } else {
        return false;
    }
}


/**
 * 本地化时间
 * @param $timestamp    时间戳
 * @param string $timezone 用户时区
 * @return bool|string
 */
function localizationDate($timestamp, $timezone = '')
{
    if (isset($timezone)) {
        date_default_timezone_set($timezone);
    }
    $timestamp = intval($timestamp);
    $t = time() - $timestamp;

    switch ($t) {
        case $t > 0 && $t < 1800:
            $date = floor($t / 60) . '分钟前';
            break;
        case $t > 1800 && $t < 3600:
            $date = '1小时以前';
            break;
        case $t > 3600 && $t < 24 * 3600:
            $date = floor($t / 3600) . '小时以前';
            break;
        case $t > 24 * 3600 && $t < 24 * 3600 * 7:
            $date = floor($t / (3600 * 24)) . '天以前';
            break;
        case $t > 24 * 3600 * 7 && $t < 24 * 3600 * 7 * 4:
            $date = floor($t / (3600 * 24 * 7)) . '周以前';
            break;
        case date('Y', $timestamp) < date('Y'):
            $date = date('Y.m.d', $timestamp);
            break;
        case date('m', $timestamp) < date('m'):
        case date('d', $timestamp) < date('d'):
            $date = date('m/d H:i', $timestamp);
            break;

        default :
            $date = date('m/d/Y H:i', $timestamp);
    }
    return $date;
}


/**
 * 格式化时间
 * @param $timestamp    时间戳
 * @param string $timezone 用户时区
 * @return bool|string
 */
function formatTimestamp($timestamp,$city_id){
    $m_base = new \Admin\Model\CityModel();
    $city_info = $m_base->getCityInfoByCityId($city_id);
    date_default_timezone_set($city_info['timezone']);
    $date = date('m-d H:i',$timestamp);
    return $date;
}



/**
 * 格式化时间(评论界面)
 * @param $timestamp    时间戳
 * @param string $timezone 用户时区
 * @return bool|string
 */
function formatTimeForComment($timestamp, $timezone = '', $city_id)
{
    if (isset($timezone)) {
        date_default_timezone_set($timezone);
    }
    $timestamp = intval($timestamp);
    $t = time() - $timestamp;
    $type = 'm/d H:i';
    if (!empty($city_id)) {
        $type = ($city_id == '214') ? 'm/d H:i' : 'd/m H:i';
    }
    switch ($t) {
        case $t > 0 && $t < 60:
            $date = '刚刚';
            break;
        case $t > 60 && $t < 3600:
            $date = floor($t / 60) . '分钟';
            break;
        case $t > 3600 && $t < 24 * 3600:
            $date = floor($t / 3600) . '小时';
            break;
        case $t > 24 * 3600 && $t < 24 * 3600 * 7:
            $date = floor($t / (3600 * 24)) . '天';
            break;
        case $t > 24 * 3600 * 7 && $t < 24 * 3600 * 28:
            $date = floor($t / 3600 * 24 * 7) . '周';
            break;
        case $t > 24 * 3600 * 28:
            $date = date($type, $timestamp);
            break;
        default :
            $date = date($type, $timestamp);
    }
    return $date;
}

/**
 * 本地化距离
 * @param $distance 距离
 * @param $city_id  目标用户城市id
 * @param $user_city_id 当前用户城市id
 * @param string $unit 本地化距离单位
 * @return float|string
 */
function localizationDistance($distance, $target_local_info,$source_local_info)
{
    $distance = floor($distance);
    $unit = $target_local_info['mileage_unit'];

    $d = '';
    switch ($distance) {
        case $distance > 0 && $unit == 'km' && $distance < 1000 :
            $d = '<1 km';
            break;
        case $distance > 0 && $unit == 'mi' && convert_distance($distance, 'mi') < 1 :
            $d = '<1 mi';
            break;
        case $distance >= 1000 && $distance < 100 * 1000:
            $d = '<100 km';
            break;
        case convert_distance($distance, 'mi') >= 1 && convert_distance($distance, 'mi') < 100:
            $d = '<100 mi';
            break;
        case $distance > 1000 * 100 && $target_local_info['city_id'] == $source_local_info['city_id']:
            $d = $source_local_info['city_name'];
            break;
        case $distance > 1000 * 100 && $target_local_info['country_id'] == $source_local_info['country_id']:
            $d = $source_local_info['country_name'];
            break;
        default :
            $d = round($distance / 1000, 1) .' km';
    }
    return $d;
}

/**
 *本地化价格
 * @param $price    当前金额
 * @param $taget    目标币种
 * @param string $source 源币种
 * @return float
 */
function localizationPrice($price, $taget, $source = 'USD')
{
    $redis = new Common\Lib\EtacarRedis();
    $redis->connect('db1', '1');
    $cache_key = 'exchange_rate:' . md5($taget . $source);

    if (!$rate = $redis->get($cache_key)) {
        $url = C('ETA_SERVICE_URL') . "/basedata/tool/converterCurrency?amount=1&from_currency=$source&to_currency=$taget";
        $curl = new Common\Lib\Curl();
        $curl->get($url, $result);
        $result = json_decode($result, true);
        if (!$result || $result['code'] !== 10000) {
            return false;
        }

        $rate = $result['result']['amount'];
        $redis->set($cache_key, $rate, 600);
    }

    return round($price * $rate, 2);
}

/**
 * 距离单位换算
 * @param $distance 距离
 * @param string $target 目标单位
 * @param string $source 源单位
 * @return bool|float|string
 */
function convert_distance($distance, $target = 'mi', $source = 'm')
{
    $source = strtolower($source);
    $target = strtolower($target);
    $unit = array(
        'm' => '1',
        'mi' => '1609.344',
        'km' => '1000',
    );

    if (!array_key_exists($source, $unit) || !array_key_exists($target, $unit)) {
        return false;
    }

    //转成基本m单位
    $source_unit = $unit[$source];
    $distance = $distance / $source_unit;

    //转换成目标单位
    $target_unit = $unit[$target];
    $distance = (String)round($distance / $target_unit, 2);
    return $distance;
}

function generateTradeno($goods_id)
{
    $trade_no = str_pad($goods_id, 6, "0") . uniqid('');
//     $trade_no = str_pad($goods_id, 6, "0").(randomID(8)).(str_pad(time()%10000, 4, "0"));
    return $trade_no;

}

function randomID($c = 16)
{
    $id = '';
    $str = "0123456789abcdef";
    for ($i = 0; $i < $c; $i++) {
        $r = rand(0, 15);
        $id .= substr($str, $r, 1);
    }
    return $id;
}


function objarray_to_array($obj)
{
    $ret = array();
    foreach ($obj as $key => $value) {
        if (gettype($value) == "array" || gettype($value) == "object") {
            $ret[$key] = objarray_to_array($value);
        } else {
            $ret[$key] = $value;
        }
    }
    return $ret;
}

/**
 * 获取数组指定索引集合
 * @param array $array
 * @param string $index
 * @param int $recursion
 * @return array
 */
function array_values_by_key(array $array, $index = 'id', $recursion_level = 1)
{
    $_array = array();
    $_recursion_level = 0;
    foreach ($array as $key => $value) {
        if (is_array($value) && $_recursion_level <= $_recursion_level) {
            $_array = array_merge($_array, array_values_by_key($value, $index, $recursion_level));
            continue;
        }

        if ($key == $index) {
            $_array[] = $value;
        }

        $_recursion_level++;
    }
    return $_array;
}
/**
 * @desc 随机生成一定长度的字符串
 */
function getRandChar($length,$type=1){
	$str = null;
	$strPol = "abcdefghijklmnopqrstuvwxyz";
	$strTol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$strNum = "0123456789";
	switch ($type){
		case '1':
			$strRet = $strPol.$strTol.$strNum;
			break;
		case '2':
			$strRet = $strPol;
			break;
		case '3':
			$strRet = $strTol;
			break;
		case '4':
			$strRet = $strNum;
			break;
		case '5':
			$strRet = $strPol.$strTol;
			break;
		case '6':
			$strRet = $strPol.$strNum;
			break;
		case '7':
			$strRet = $strTol.$strNum;
			break;
	}
	$max = strlen($strRet)-1;

	for($i=0;$i<$length;$i++){
		$str.=$strRet[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
	}

	return $str;
}

/**
 * 处理接口输出集合中的null字符串
 * @param $object
 * @return string
 */
function processOutput($object)
{
    $out_put = '';
    if (is_array($object) && !empty($object)) {
        foreach ($object as $key => $value) {
            if (is_array($value)) {
                $out_put[$key] = processOutput($value);
                continue;
            }
            $out_put[$key] = toString($value);
        }
    }elseif (is_array($object) && empty($object)){
        $out_put = $object;
    } elseif (is_object($object)){
        $object = objarray_to_array($object);
        foreach ($object as $key => $value) {
            if (is_array($value)) {
                $out_put[$key] = processOutput($value);
                continue;
            }
            $out_put[$key] = toString($value);
        }
    }else{
        $out_put = toString($object);
    }

    return $out_put;
}


/**
 * 转换null|int为字符串
 * @param $value
 * @return string
 */
function toString(&$value){
//    if(is_null($value)){
//        $value = '';
//    }

    if(is_null($value) || is_numeric($value) || is_int($value)){
        $value = (String) $value;
    }

    return $value;
}

/**
 * @param string $image 图片地址
 * @param unknown $type 1商品 2
 */
function handleImageSize($image,$type=1,$need_size=''){
    if($type ==1){
        $size = C('IMAGE_GOODS_SIZE');        
    }else{
        $size = C('IMAGE_USER_SIZE');        
    }
    $image_info = pathinfo($image);
    $image_host = str_replace('source','small',$image_info['dirname']).'/';
    $image_name = $image_info['filename'];
    $all_image = array();
    foreach ($size as $v){
        $size_image = $image_host.$image_name.'_'.$v.'.png';
        $all_image[$v]= array('size'=>$v,'path'=>$size_image);
    }

    if(isset($all_image[$need_size])){
        return $all_image[$need_size];
    }
    $all_image[]=array('size'=>"",'image'=>$image);
    return $all_image;
}
/**
 * 
 * @param string $type   goods/user
 * @param string $img_name  图片名称
 * @return string
 */
function getImageBaseUrl($img){
	if(empty($img)){
        $img_url = C('IMG_URL') . '/' . 'no_img.jpg';

    }else{
        $img_url = C('IMG_URL') . '/' . $img;
    }
    return $img_url;
}

/**
 * @todo 判断合法坐标
 * @param $coordinate
 * @return bool
 */
function checkCoordinate($coordinate){
    $lng_min = -180;
    $lng_max = 180;
    $lat_min = -90;
    $lat_max = 90;
    if(strpos($coordinate,',') !== FALSE){
        list($lat,$lng) = explode(',',$coordinate);
        $lat = floatval($lat);
        $lng = floatval($lng);

        if($lat == 0 && $lng==0){
            return FALSE;
        }

        if($lat < $lat_min || $lat > $lat_max || $lng < $lng_min || $lng > $lng_max){
            return FALSE;
        }

        return TRUE;
    }
    return FALSE;
}

/**
 * 后台列表分页
 *
 * @param int $count
 * @param int $page
 * @param int $pagesize
 * @return array()
 */
function listPage($count, $page, $pagesize=10){
	$page = $page<=0 ? 1 : $page ;
	$data['totalpage'] = $count == 0 ? 0 : (($count%$pagesize) ? intval($count/$pagesize) +1 : ($count/$pagesize));
	$data['page'] = $page;
	$data['pagesize'] = $pagesize;
	$data['offset'] = ($offset = ($page-1)*$pagesize) > $count ? $count : $offset;
	return $data;
}
