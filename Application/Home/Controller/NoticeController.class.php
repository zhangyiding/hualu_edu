<?php

/*
 * 学员须知
 *
 */
namespace Home\Controller;
use Common\Controller\BaseController;
use Think\Controller;
class NoticeController extends BaseController {
    public function index(){
        $this->display();
    }

    /*
     * 先学习后付款
     */
    public function study(){
        $this->display();
    }


    /*
     * 学校地址
     */
    public function address(){
        $this->display();
    }


    /*
     * 学院环境
     */
    public function environment(){
        $this->display();
    }


    
}