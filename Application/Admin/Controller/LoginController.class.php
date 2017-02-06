<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Controller\BaseController;
class LoginController extends Controller {
    public function index(){

        $this->display();
    }

    /**
     * 用户登录操作
     */
    public function doLogin(){

        $user = $_REQUEST['username'];
        $pwd = md5($_REQUEST['password']);
        $login_model = new \Admin\Model\LoginModel();
        if (!$user_info = $login_model->login($user, $pwd)) {
            showMsg('登录失败');
        }

        session_start();
        session(array('name'=>'session_id','expire'=>3600));
        session('subsite_id',$user_info['subsite_id']);
        session('username',$user_info['name']);
        session('su_id',$user_info['su_id']);
        session('su_type',$user_info['type']);


        Header("Location: /admin");
        $this->display('Index/index');
    }


    /**
     * 用户退出操作
     */
    public function doLogout(){
        $login_model = new \Admin\Model\LoginModel();
        $login_model->logout();
        showMsg('退出成功','index',1);
    }


    /*
     * 修改密码
     */

}