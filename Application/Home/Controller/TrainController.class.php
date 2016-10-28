<?php

/*
 * 高端培训
 *
 */
namespace Home\Controller;
use Common\Controller\BaseController;
use Think\Controller;
class TrainController extends BaseController {
    public function index(){
        $this->display();
    }

    /*
     * 企业定制
     */
    public function customEnterprise(){
        $this->display();
    }


    /*
     * 文化扶贫
     */
    public function help(){
        $this->display();
    }


    /*
     * 信息化
     */
    public function information(){
        $this->display();
    }




    
}