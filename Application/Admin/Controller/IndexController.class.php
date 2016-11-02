<?php
namespace Admin\Controller;

use Common\Controller\BaseController;
use Think\Controller;
class IndexController extends BaseController {
    public function index(){
        $this->assign('username',$this->user_name);
        $this->display();

    }


}