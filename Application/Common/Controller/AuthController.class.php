<?php
namespace Common\Controller;
use Think\Controller;


class AuthController extends Controller {
    private $user_id;

    public function __construct()
    {
        // if (!$this->auth()) {
        //     die('un auth');
        // }
    }

    /**
     *
     * @return bool
     */
    private function auth(){
        $rule_name= MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;

        if(in_array(MODULE_NAME.'/'.CONTROLLER_NAME,C('PUBLIC_CONTROLLER'))){
            return true;
        }

        if(!$this->user_id = session('user_id')){
            return false;
        }

        return $this->checkAuth($rule_name);
    }

    /**
     *
     * @param $rule_name
     * @param $user_id
     * @return bool
     */
    private function checkAuth($rule_name,$user_id){
        $rule_name = strtolower($rule_name);
        //$user_id 根据user_id获取用户权限
        $allow_rule_name = $this->getAllowRules();
        return in_array($rule_name,$allow_rule_name);
    }

    /**
     *
     */
    private function getAllowRules(){
        $allow_rule_name = ['user/login/index','home/index/index'];
        $this->user_id;
        //从redis取权限列表
    }



}