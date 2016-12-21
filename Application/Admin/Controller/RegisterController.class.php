<?php
namespace Admin\Controller;

use Think\Controller;
use Common\Controller\BaseController;
class RegisterController extends BaseController {

    protected $reg_status = array(
        '0'=>'待审核',
        '1'=>'审核通过',
        '2'=>'审核不通过'
    );

    public function index(){
        $m_register = new \Admin\Model\RegisterModel();
        $m_base = new \Common\Model\BaseModel();
        //当分站管理员访问时只能查看所属分站的数据
        if($this->su_type = C('SUBSITE_USER')){
            $where['subsite_id'] = $this->subsite_id;
        }else{
            $where['scm_id'] = array('egt',0);
        }



        $data = array();
        $page_arr = array();
       if($count = $m_register->getRegisterCount($where)){

           //获取分页总数进一取整
           $page_count = ceil($count/$this->limit);
           for($i=1;$i<=$page_count;$i++){
               $page_arr[] = $i;
           }

           $data = $m_register->getRegisterList($where,$this->offset,$this->limit);

           $m_student = new \Admin\Model\StudentModel();
           $m_course = new \Admin\Model\CourseModel();
           foreach($data as $k=>$v){
               //获取学员信息
               $student_info = $m_student->getStudentInfo($v['student_id']);
               $data[$k]['student_name'] = $student_info['name'];

               //获取课程信息
               $course_info = $m_course->getCourseInfo($v['course_id']);
               $data[$k]['course_name'] = $course_info['name'];
               $data[$k]['price'] = $course_info['price'];

               //获取分站信息
               $subsite_info = $m_base->getSubsiteInfo($this->subsite_id);
               $data[$k]['subsite_name'] = $subsite_info['name'];
           }
       }

        $this->assign('reg_list',$data);
        $this->assign('reg_status',$this->reg_status);
        $this->assign('page_arr',$page_arr);
        $this->display();
    }


    /*
     * 修改审核状态
     */
    public function changeStatus(){

        $data['status'] = $this->params['status'];
        $where['scm_id'] = $this->params['scm_id'];

        $m_register = new \Admin\Model\RegisterModel();

        if($m_register->changeStatus($where,$data)!== false){
            $this->showMsg('修改成功','index',1);
        }else{
            $this->showMsg('修改失败，请重试');
        }
    }




    /*
    * 报名信息汇总
    */
    public function count(){
        $m_register = new \Admin\Model\RegisterModel();
        $m_base = new \Common\Model\BaseModel();
        //当分站管理员访问时只能查看所属分站的数据
        if($this->su_type = C('SUBSITE_USER')){
            $where['scm.subsite_id'] = $this->subsite_id;
        }else{
            $where['scm.scm_id'] = array('egt',0);
        }


        $data = array();
        $page_arr = array();
        if($count = $m_register->getCseRegCount($where)){

            //获取分页总数进一取整
            $page_count = ceil($count/$this->limit);
            for($i=1;$i<=$page_count;$i++){
                $page_arr[] = $i;
            }

            $data = $m_register->getCseRegCountList($where,$this->offset,$this->limit);


            foreach($data as $k=>$v){

                $data[$k]['price'] = round($v['price']*$v['scm_count']);

                //获取分站信息
                $subsite_info = $m_base->getSubsiteInfo($this->subsite_id);
                $data[$k]['subsite_name'] = $subsite_info['name'];
            }
        }

        $this->assign('reg_list',$data);
        $this->assign('page_arr',$page_arr);
        $this->display();
    }





}