<?php
namespace Home\Controller;
use Common\Controller\BaseController;
use Home\Model\UserModel;
use Common\Lib\Verify;
use Think\Controller;
class UserController extends BaseController {

    protected $username = '';
    protected $password;
    protected $user_info;
    protected $student_id;


    /*
     * 用户登录接口
     */
    public function login(){

        $this->username = $this->params['username'];
        $this->password = $this->params['password'];

        if($this->doLogin()){
            $this->to_back($this->user_info);
        }else{
            $this->to_back('11005');
        }
    }





    /**
     * 退出登录
     */
    public function logout()
    {
        $this->destroyStatus();
        $this->to_back('成功');
    }

    /**
     * 退出登录
     */
    private function destroyStatus()
    {
        session_destroy();
    }



    /*
     * 根据用户名来判断使用哪种方式登录
     */

    private function doLogin()
    {


        if (strripos($this->username, '@') != false) {
            return $this->loginByEmail();
        } elseif (intval($this->username) > 0 && strlen($this->username) > 10) {
            return $this->loginByMobile();
        }else{
            return false;
        }

    }



    /**
     * 根据email获取用户信息
     * @param $email
     */
    private function loginByEmail()
    {
        $m_user = new UserModel();
        $where['email'] = $this->username;
        $where['status'] = 0;
        $where['password'] =  encryptpass($this->password);
        if($this->user_info = $m_user->getUserInfo($where)){
            $this->storeStatus();
            return true;
        }else{
            return false;
        }
    }



    /**
     * 根据mobile获取用户信息
     * @param $mobile
     */
    private function loginByMobile()
    {
        $m_user = new UserModel();
        $where['mobile'] = $this->username;
        $where['status'] = 0;
        $where['password'] =  encryptpass($this->password);
        if($this->user_info = $m_user->getUserInfo($where)){
            $this->storeStatus();
            return true;
        }else{
            return false;
        }
    }


    /**
     * @todo 生成图片验证码
     *
     * @return image
     */
    public function createVerify(){
        session_start();
        $session_id = session_id();
        $verify = new Verify();
        $verify->entry($session_id);
    }


    /*
     * 用户注册接口
     *
     */
    public function region(){

        $m_user = new UserModel();
        $verify = new Verify();

        $map['email'] = $this->params['email'];
        $map['subsite_id']  = $this->params['subsite_id'];
        $map['gender']  = $this->params['gender'];
        $map['unit']  = $this->params['unit'];
        $map['name']  = $this->params['name'];
        $map['password']  = encryptpass($this->params['password']);

        $map['mobile']  = $this->params['mobile'];
        $map['comefrom'] = C('region_type')['user'];


        if(!check_mobile($this->params['mobile'])){
            $this->to_back('11002');
        }

        if(!$verify->check($this->params['code'],session_id())){
            $this->to_back('11007');
        }

        if($this->params['password'] !== $this->params['confirm_pwd']){
            $this->to_back('11009');
        }

        if(!check_email($this->params['email'])){
            $this->to_back('11002');
        }

        if($m_user->checkUname($this->params['email'])){
            $this->to_back('11001');
        }

        if($this->student_id = $m_user->region($map)){

            $where['student_id'] = $this->student_id;
            $where['status'] = 0;
            $this->user_info = $m_user->getUserInfo($where);
            $this->storeStatus();

            $this->to_back($this->user_info);
        }else{
            $this->to_back('11004');
        }





        $this->display();
    }


    /**
     * 缓存登录信息
     */
    private function storeStatus()
    {
        $user_info = $this->user_info;

        session_start();
        session('subsite_id', $user_info['subsite_id']);
        session('student_id', $user_info['student_id']);
        session('name', $user_info['name']);
        session('user_info', $user_info);
    }


    /*
     * 用户找回密码接口
     */
    public function findPwd(){



        $this->display();
    }



    /*
     * 用户修改密码接口
     */
    public function changePwd(){



        $this->display();
    }



    /*
     * 判断用户是否登录
     */
    public function userIsLogin(){

        if (!session('?student_id')) {
          $this->to_back('10001');
        } else {
            $user_info = session('user_info');
            $this->to_back($user_info);
        }

    }





}