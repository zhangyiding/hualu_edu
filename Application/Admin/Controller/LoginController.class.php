<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Controller\BaseController;
class LoginController extends BaseController {
    public function index(){
        $this->display();
    }

    /**
     * 用户登录操作
     */
    public function doLogin(){
        $user = $this->params['username'];
        $pwd = md5($this->params['password']);
        $login_model = new \Admin\Model\LoginModel();
        if (!$user_info = $login_model->login($user, $pwd, $this->params)) {
            $this->showMsg('登录失败');
        }
        session_start();
        session(array('name'=>'session_id','expire'=>3600));
        session('user_id',$user_info['id']);
        session('username',$user_info['user']);

        Header("Location: /admin");

        $this->display('/index/index');
    }


    /**
     * 用户退出操作
     */
    public function doLogout(){
        session(null);
        $this->showMsg('退出成功','index',1);
    }

}