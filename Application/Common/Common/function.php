<?php
use Common\Lib\Crypt3Des;
use Common\Lib\Upimg;
use Common\Lib\Page;
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
 * 密码加密
 * @param str $pass
 */
 function encryptpass($pass) {
    if (!$pass)
        return false;

    return md5($pass);
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

function check_email($email)
{
    $pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
    if (preg_match($pattern, $email)) {
        return true;
    } else {
        return false;
    }
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
 * 
 * @param string $type   goods/user
 * @param string $img_name  图片名称
 * @return string
 */
function getImageBaseUrl($img){

	if(empty($img)){
        $img_url = C('RESOURCE_URL') . '/' . 'Public/no_img.jpg';
    }else{
        if(!strstr($img,'http:')){
            if(strstr($img,'showimage')){
                $img_url = C('TCS_URL').$img;
            }else{
                $img_url = C('RESOURCE_URL') . '/' . $img;
            }

        }else{
            $img_url = $img;
        }
    }
    return $img_url;

}

/**
 * TODO 基础分页的相同代码封装，使前台的代码更少
 * @param $m 模型，引用传递
 * @param $where 查询条件
 * @param int $pagesize 每页查询条数
 * @return
 */
function listPage($count,$pagesize=10){
    $p=new Page($count,$pagesize);

    $p->url = $_SERVER['PHP_SELF'];


    $p->lastSuffix=false;

    return $p->show();
}



/**
 *
 * @param string $type   goods/user
 * @param string $img_name  图片名称
 * @return string
 */
function getFileBaseUrl($path){

    if(empty($path)){
        return '';
    }else{
        if(!strstr($path,'http:')){
            $file_url = C('RESOURCE_URL') . '/' . $path;
        }else{
            $file_url = $path;
        }
    }
    return $file_url;

}


function getFileCoverByExt($ext)
{
    if (empty($ext)) {
       $file_cover = '/public/home/image/file_cover.png';
        return $file_cover;
    }

    if(in_array($ext,array('pdf'))){
        $file_cover = '/public/home/image/pdf.png';
    }elseif(in_array($ext,array('doc','docx'))){
        $file_cover = '/public/home/image/doc.png';
    }else{
        $file_cover = '/public/home/image/file_cover.png';
    }
    return $file_cover;
}


/**
 * 按照指定长度裁剪字符串
 *
 * @param str $str
 * @return str
 */
function cutStr($str,$len){
   if(empty($str)){
       return false;
   }
    $str_len = abslength($str);


    if( $str_len > $len){
        $c_str = mb_substr($str , 0 , $len,'utf-8');
        return $c_str . '..';
    }else{
        return $str;
    }

}


/**
 * 可以统计中文字符串长度的函数
 * @param $str 要计算长度的字符串
 * @param $type 计算长度类型，0(默认)表示一个中文算一个字符，1表示一个中文算两个字符
 *
 */
function abslength($str)
{
    if(empty($str)){
        return 0;
    }
    if(function_exists('mb_strlen')){
        return mb_strlen($str,'utf-8');
    }
    else {
        preg_match_all("/./u", $str, $ar);
        return count($ar[0]);
    }
}



/**
 * @tudo 格式化秒数
 *
 * @param int $seconds
 * @return str
 */
function changeTimeType($seconds){
    if ($seconds>3600){
        $hours = intval($seconds/3600);
        $minutes = $seconds%3600;
        $time = $hours.":".gmstrftime('%M:%S', $minutes);
    }else{
        $time = gmstrftime('%H:%M:%S', $seconds);
    }
    return $time;
}





/**
 * @tudo 格式化时间戳
 *
 * @param int $time //时间戳
 * @param int $op //操作类型
 * @return str
 */
function formatTime($time,$op=''){

    if($op){
        if(empty($time)){
            return '0';
        }
        $data = round($time/60);

    }else{
        if(empty($time)){
            return '00-00-00';
        }
        $data = date('Y-m-d',$time);
    }


    return $data;
}






function getFileInfo($file,$unit = 'm'){

    if( $arw_size = filesize(iconv('UTF-8','GB2312',$file))){

        //获取文件大小
        $unit = (empty($unit)) ? 'm' : $unit; //默认单位为m
            switch ($unit) {
                case 'b':
                    $size = $arw_size;
                    break;
                case 'kb':
                    $size = round($arw_size / 1000);
                    break;
                case 'm':
                    $size = round($arw_size / 1000 / 1000);
                    break;
                default:
                    $size = round($arw_size / 1000);
            }


            //获取视频名称以及扩展
            $v_info = path_info($file);

            return array('vtime' => 0,
                'duration' => 0,
                'size' => $size,
                'name' => $v_info['filename'],
                'ext'=>$v_info['extension']
            );
        }
}


/**
 * 获取路径文件信息
 *
 * @param str $filepath //文件路径
 * @return array()
 */

function path_info($filepath)
{
    $path_parts = array();
    $path_parts ['dirname'] = rtrim(substr($filepath, 0, strrpos($filepath, '/')),"/")."/";
    $path_parts ['basename'] = ltrim(substr($filepath, strrpos($filepath, '/')),"/");
    $path_parts ['extension'] = substr(strrchr($filepath, '.'), 1);
    $path_parts ['filename'] = ltrim(substr($path_parts ['basename'], 0, strrpos($path_parts ['basename'], '.')),"/");
    return $path_parts;
}




/**
 * @todo 上传文件
 */
 function uploadFile($type='3') {

     $upimgObj = new Upimg($_FILES['uploadFile']);

     if($type == C('COURSE_IMG')){
         $upload_dir = C('IMG_DIR') ;
     }elseif($type == C('COURSE_VE')){
         $upload_dir = C('VIDEO_DIR') ;
         $upimgObj->SetSaveMod(0);
     }elseif($type == C('COURSE_FILE')){
         $upload_dir = C('FILE_DIR') ;
         $upimgObj->SetSaveMod(0);
     }else{
         return false;
     }
    //文件上传路径
    if(!is_dir($upload_dir)){
        mkdir($upload_dir);
    }

    if(!is_writeable($upload_dir)) {
        showMsg("上传目录不可写");
    }

    if ($upimgObj->Save($upload_dir,false)) {
        $imgUrl = $upimgObj->GetSavePath();
        return $imgUrl;
    } else {
        return false;
    }
}

/**
 * @todo 视频上传
 */
function uploadVideo($upload_dir) {

    //文件上传路径
    if(!is_dir($upload_dir)){
        mkdir($upload_dir);
    }

    if(!is_writeable($upload_dir)) {
        $this->showMsg("上传目录不可写");
    }


    $upimgObj = new Upimg($_FILES['uploadFile']);
    if ($upimgObj->Save($upload_dir,false)) {
        $imgUrl = $upimgObj->GetSavePath();
        return $imgUrl;
    } else {
        return false;
    }
}

/**
 * 输出ajax格式结果
 * @param $data
 * @param int $error
 */
 function ajaxReturn($data,$success=0){
    $result['error'] = $success == 0 ? 'fail': 'ok' ;
    $result['data'] = $data;
    exit(json_encode($result));
}

 function showMsg($message, $url = "", $iserror = 0) {
    $code = mb_detect_encoding($message, "UTF-8, GBK, CP936");
    $tocode = "UTF-8";
    if ($code != $tocode) {
        $message = mb_convert_encoding($message, $tocode, $code);
    }

    if (!$iserror) //错误提示信息
        $redirect = "history.go(-1);\r\n";
    else
        $redirect = "location.href='" . $url . "';\r\n";

    printf("<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"\r\n
			\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\r\n
			<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n
			<head>\r\n
			<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf8\" />\r\n
			</head>\r\n
			<body>\r\n
			<script type='text/javascript'>\r\n
alert('%s');\r\n
%s
		</script>\r\n
		</body>\r\n
		</html>", $message, $redirect);
     die();
}

/**
 * 返回接口数据
 * @param string $msg	提示信息
 * @param array $data	数据
 * @param int $error   0失败1成功
 */
  function apiReturn($msg,$data=array()){

    $result['message'] = $msg;
    if(!empty($data)) {
        $result['data'] = $data;
    }
    header('Content-type: application/json;charset=utf-8');
    die(json_encode($result));
}
/**
 * 输出json数据
 * @param $value    数据|错误代码
 * @param string $msg 前端提示信息
 */
 function to_back($value, $msg = '')
{
    $this->_res = new \ApiResp();
    //处理对象
    if (is_object($value)) {
        $value = objarray_to_array($value);
    }

    if (!is_array($value) && intval($value) >= 10000) {
        $this->_res->code = $value;
    }


    if (is_array($value)) {
        $this->_res->result = $value;
    }

    $this->_res->msg = $msg;
    if (!$this->_res->msg) {
        $errorinfo = C('errorinfo');
        $this->_res->msg = L($errorinfo[$this->_res->code]);
    }

    if($this->_res->code == 10000 && strtolower($_SERVER['REQUEST_METHOD']) == 'post'){
        MemberLogModel::addLog($this->user_id,$this->access,$this->params);
    }

    header('Content-type: application/json; charset=utf-8');
    die(json_encode($this->_res));
}