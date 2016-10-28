<?php
namespace Common\Controller;

use Common\Lib\RecordLog;
use GuzzleHttp\json_encode;
use Think\Controller;
use Common\Model\Admin\MemberLogModel;

import("Common.Lib.ApiResp", "", ".class.php");

class BaseController extends Controller
{
    protected $is_login = 0;
    protected $params = array();
    protected $userinfo = array();
    protected $user_id = 0;
    protected $access = '';
    protected $access_list = array();
    protected $_res = '';
    protected $error = '';
    protected $start_time = '';
    protected $end_time = '';
    protected $page = '1';
    protected $offset = '0';
    protected $pagesize = '20';

    public function __construct()
    {
        $this->start_time = microtime(1);
        //处理参数
        $this->getParams();
        //判断是否登陆
//        $this->checkLogin();
        parent::__construct();
    }

    public function getParams()
    {
        $params = $_REQUEST;

        if (IS_POST) {
            $input = json_decode(file_get_contents('php://input'), true);
            if (is_array($input)) {
                $params = array_merge($params, $input);
            }
        }

        $page = intval($_GET['page']);
        $this->page = $page ? $page : $this->page;

        $pagesize = intval($_GET['pagesize']);
        $this->pagesize = $pagesize ? $pagesize : (C('PAGE_SIZE') ? C('PAGE_SIZE') : $this->pagesize);
        $this->offset = ($this->page - 1) * $this->pagesize;
        $this->params = $params;

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

    /**
     * 校验表单数据
     * @param $rules
     * @return bool
     */
    public function validate($rules)
    {
        $model = M();
        if (!$model->validate($rules)->create($this->params)) {
            // 如果创建失败 表示验证没有通过 输出错误提示信息
            $this->error = $model->getError();
            return false;
        }

        return true;
    }

    private function checkAccess()
    {
        //是否开放方法
        if ($this->checkUnAuth()) {
            return true;
        }
        //是否登录
        $this->checkLogin();

        //是否超级管理员
        if ($this->userinfo['is_supper'] || $this->user_id == 1) {
            return true;
        }

        //匹配权限
        if (!in_array($this->access, $this->getAccessList())) {
            $this->to_back(10002);
        }

        return true;
    }

    private function checkUnAuth()
    {
        $c = strtolower(MODULE_NAME . '/' . CONTROLLER_NAME);
        $this->access = strtolower(MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME);

        //CONTROLLER
        if (in_array($c, array_map('strtolower', C('UN_AUTH_CONTROLLER')))) {
            return true;
        }

        //ACTION
        if (in_array($this->access, array_map('strtolower', C('UN_AUTH_ACTION')))) {
            return true;
        }
        return false;
    }

    private function checkLogin()
    {
        if(session('?user_id')){
            $username = session('username');
            $this->assign('username',$username);
            $this->display();
        }else{
            $this->showMsg('尚未登录');

        }


//        if(isset($this->params['token'])){
//            session_id($this->params['token']);
//        }
//        session_start();
//
//        $this->user_id = session('user_id');
//
//        if (!$this->user_id) {
//            $this->to_back(10001);
//        }
//
//        return true;
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

        if($this->_res->code == 10000 && strtolower($_SERVER['REQUEST_METHOD']) == 'post'){
            MemberLogModel::addLog($this->user_id,$this->access,$this->params);
        }

        header('Content-type: application/json; charset=utf-8');
        die(json_encode($this->_res));
    }

    private function getAccessList()
    {
        return $this->access_list = session('permissions');
    }

    protected function getAccessToken(){
        $token = session_id();
        return $token;
    }

    protected function checkoutAccessToken(){

    }




}