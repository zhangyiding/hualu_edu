<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Controller\BaseController;
class CouresController extends BaseController {

    /*
     * 课程信息
     */
    public function index(){

        $m_course = new \Admin\Model\CouresModel();
        $data = $m_course->getCourseList();
        $area_arr = $m_course->getCourseAreaArr();
        $this->assign('cue_list',$data);
        $this->assign('area_list',$area_arr);

        $this->display();
    }


    /*
     * 新增课程
     */
    public function addCourse(){
        //当type为1时执行更新操作，为2时插入操作
        $m_course = new \Admin\Model\CouresModel();
        $type = $this->params['type'];
        if($type == 1){
            $id = $this->params['id'];
            $data = $m_course->getCourseInfo($id);
            $this->assign('data',$data);
        }

        $area_arr = $m_course->getCourseAreaArr();
        $course_type = C('course_type');
        $course_status = C('course_status');

        $this->assign('area_list',$area_arr);
        $this->assign('status_list',$course_status);
        $this->assign('course_type',$course_type);

        $this->display();
    }


    /*
     * 新增课程动作
     */
    public function doAddCourse(){
        $where['name'] = $this->params['name'];
        $where['begin_time'] = strtotime($this->params['begin_time']);
        $where['teacher'] = $this->params['teacher'];
        $where['area'] = $this->params['area'];
        $where['status'] = $this->params['status'];
        $where['course_tag'] = $this->params['type'];
        $m_coures = new \Admin\Model\CouresModel();
        if($m_coures->doAddCoures($where) !== false){
            $this->showMsg('添加成功','index',1);

        }else{
            $this->showMsg('添加失败，请重试');
        }
    }

    /*
     * 删除课程动作
     */
    public function delCourse(){
        $where['id'] = $this->params['id'];
        $m_coures = new \Admin\Model\CouresModel();
        if($m_coures->delCourse($where) !== false){
            $this->showMsg('删除成功','index',1);
        }else{
            $this->showMsg('删除失败，请重试');
        }
    }


    //校区信息
    public function areaManage(){
        $m_course = new \Admin\Model\CouresModel();
        $data = $m_course->getCourseAreaList();

        $this->assign('area_list',$data);
        $this->display();

    }


    /*
    * 新增校区
    */
    public function addArea(){
        //当type为1时执行更新操作，为2时插入操作
        $m_course = new \Admin\Model\CouresModel();
        $type = $this->params['type'];
        if($type == 1){
            $id = $this->params['id'];
            $data = $m_course->getAreInfo($id);
            $this->assign('data',$data);
        }

        $this->display();
    }



    /*
     * 新增课程动作
     */
    public function doAddArea(){
        $where['area_name'] = $this->params['name'];
        //添加时默认为开通
        $where['status'] = 1;
        $where['add_time'] = time();
        $m_coures = new \Admin\Model\CouresModel();
        if($m_coures->doAddArea($where) !== false){
            $this->showMsg('添加成功','areaManage',1);

        }else{
            $this->showMsg('添加失败，请重试');
        }
    }
}