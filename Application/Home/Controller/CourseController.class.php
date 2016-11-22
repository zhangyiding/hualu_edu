<?php
namespace Home\Controller;
use Common\Controller\BaseController;
use Home\Model\CourseModel;
use Home\Model\NewsModel;
use Home\Model\UserModel;
use Think\Controller;
class CourseController extends BaseController {


    /*
     * 选课首页
     */
    public function index(){
        $course_type = $this->params['course_type'];//课程分类
        $open_status = $this->params['open_status'];//课程类型，公开或者内训
        $provider = $this->params['provider'];//提供方

        $where['status'] = 0;
        $m_course = new CourseModel();
        if(!empty($course_type)){
            $ct_arr = array_filter(explode(',',$course_type));
            $cse_id = $m_course->getCseByType($ct_arr);

            $where['course_id'] = array('in',$cse_id);

        }

        if(!empty($open_status)){
            $where['open_status'] = $open_status;
        }

        if(!empty($provider)){
            $where['subsite_id'] = ($provider ==1)? 0:$this->subsite_id;
        }else{
            $where['subsite_id'] = array('in',array(0,$this->subsite_id));
        }


        if($count = $m_course->getCourseCount($where)){
            //获取分页总数进一取整
            $page_arr = listPage($count,$this->limit);

            $data = $m_course->getCourseList($where,$this->offset,$this->limit);

            $this->assign('course_list',$data);
            $this->assign('page_arr',$page_arr);
        }

        $this->display();




    }




}