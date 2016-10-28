<?php
namespace Admin\Controller;

use Common\Controller\BaseController;
use Think\Controller;
class IndexController extends BaseController {
    public function index(){

        if(session('?user_id')){
            $username = session('username');
            $this->assign('username',$username);
            $this->display();
        }else{
            $this->showMsg('尚未登录','login/index',1);
        }


    }


}