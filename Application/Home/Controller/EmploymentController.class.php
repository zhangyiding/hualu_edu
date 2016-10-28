<?php

/*
 * 就业体系
 *
 */
namespace Home\Controller;
use Common\Controller\BaseController;
use Think\Controller;
class EmploymentController extends BaseController {
    public function index(){
        $this->display();
    }

    /*
     * 人才输送
     */
    public function deliver(){
        $this->display();
    }


    /*
     * 就业服务
     */
    public function service(){
        $this->display();
    }


    /*
     * 人才合作
     */
    public function cooperate(){
        $this->display();
    }


   /*
    * start
    */
    public function star(){
        $this->display();
    }

    
}