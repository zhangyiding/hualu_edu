<?php

/*
 * 关于我们
 */
namespace Home\Controller;
use Common\Controller\BaseController;
use Think\Controller;
class AboutController extends BaseController {
    public function index(){
        $this->display();
    }

    /*
     * VR教育
     */
    public function vrEdu(){
        $this->display();
    }


    /*
     * 发展优势
     */
    public function advantages(){
        $this->display();
    }


    /*
     * 成长历史
     */
    public function history(){
        $this->display();
    }


   /*
    * 合作伙伴
    */
    public function cooperative(){
        $this->display();
    }


}