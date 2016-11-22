<?php
namespace Home\Controller;
use Common\Controller\BaseController;
use Home\Model\UserModel;
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




    /*
     * 用户注册接口
     *
     */
    public function region(){

        $m_user = new UserModel();
        if(!check_email($this->params['email'])){
            $this->to_back('11002');
        }
        if($m_user->checkUname($this->params['email'])){
            $this->to_back('11001');
        }
        $map['email'] = $this->params['email'];
        $map['cu_id']  = $this->params['cu_id'];
        $map['gender']  = $this->params['gender'];
        $map['unit']  = $this->params['unit'];
        $map['name']  = $this->params['name'];
        $map['password']  = encryptpass($this->params['password']);
        if(!check_mobile($this->params['mobile'])){
            $this->to_back('11002');
        }
        $map['mobile']  = $this->params['mobile'];
        $map['subsite_id'] = $this->subsite_id;
        $map['comefrom'] = C('region_type')['user'];

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



}