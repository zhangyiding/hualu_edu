<?php
namespace Common\Controller;

use Common\Lib\RecordLog;
use GuzzleHttp\json_encode;
use Think\Controller;
use Common\Model\Admin\MemberLogModel;

import("Common.Lib.ApiResp", "", ".class.php");

class BaseController extends Controller
{

    protected $params = array();
    protected $_res = '';
    protected $error = '';
    protected $start_time = '';
    protected $end_time = '';
    protected $ip='';

    protected $subsite_id = '';//分站id
    protected $subsite_name = '';//分站名称
    protected $creator = '';//分站管理员id
    protected $subsite_host = '';//分站域名
    protected $admin_name = '';//管理员名称
    protected $su_type = '2';//管理员类型，1为主站管理员，2为分站管理员，3为培训管理员

    protected $page = '1';//页数
    protected $offset = '0';//偏移量
    protected $limit = '10';//每页数量

    public function __construct()
    {

        $this->start_time = microtime(1);
        //处理参数
        $this->getParams();

        //判断后台管理员是否登陆
        $this->checkLogin();

        parent::__construct();
    }

    public function getParams()
    {

        $m_base = new \Common\Model\BaseModel();
        //获取用户访问的二级域名地址
        preg_match("#(.*?)\.#", $_SERVER['SERVER_NAME'],$match);
        $this->subsite_host = $match[1];

        if($subsite_info = $m_base->getSubsiteId($this->subsite_host)){
        $this->subsite_id = $subsite_info['subsite_id'];
        $this->subsite_name = $subsite_info['name'];
        }

//        $this->subsite_id = '130301704';

        $this->ip = $_SERVER["REMOTE_ADDR"];

        $page = intval($_GET['page']);
        $this->page = $page ? $page : $this->page;

        $pagesize = intval($_GET['pagesize']);
        $this->limit = $pagesize ? $pagesize : $this->limit;
        $this->offset = ($this->page - 1) * $this->limit;

        if(session('?subsite_id')){
            $this->subsite_id = session('subsite_id');
            $this->creator = session('su_id');
            $this->admin_name = session('username');
            $this->su_type = session('su_type');
        }

        $this->params = $_REQUEST;

    }

    public function doLog()
    {
        $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER["REQUEST_URI"];
        $this->end_time = microtime(true);;
        RecordLog::add_client_api_log($url, $_SERVER['REQUEST_METHOD'], $_SERVER['HTTP_TRACEINFO'], $this->params,
            $this->_res, $this->start_time, $this->end_time);
    }

    public function __destruct()
    {

    }



    private function checkLogin()
    {
        $url = $_SERVER['REQUEST_URI'];
        //当访问后台时判断是否存在分站id
        if(strpos($url,'admin')){
            if (!session('?su_id')) {
                showMsg('尚未登录','/admin/login/index',1);
                return false;
            } else {
                return true;
            }
        }else{


        }


    }




    /**
     * 输出ajax格式结果
     * @param $data
     * @param int $error
     */
    public function ajaxReturn($data,$success=0){
        $result['error'] = $success == 0 ? 'fail': 'ok' ;
        $result['data'] = $data;
        exit(json_encode($result));
    }

    public function showMsg($message, $url = "", $iserror = 0) {
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
        exit;
    }

    /**
     * 返回接口数据
     * @param string $msg	提示信息
     * @param array $data	数据
     * @param int $error   0失败1成功
     */
    public  function apiReturn($msg,$data=array()){

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
    public function to_back($value, $msg = '')
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
//
//        if($this->_res->code == 10000 && strtolower($_SERVER['REQUEST_METHOD']) == 'post'){
//            MemberLogModel::addLog($this->user_id,$this->access,$this->params);
//        }

        header('Content-type: application/json; charset=utf-8');
        die(json_encode($this->_res));
    }


    public function isLogin()
    {

        if (!session('?student_id')) {
            return false;
        } else {

            return session('user_info');
        }

    }






}