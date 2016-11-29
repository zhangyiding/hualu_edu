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

            if($data = $m_course->getCourseList($where,$this->offset,$this->limit)){

                $data = $this->formatCourse($data);
            }

            $this->assign('course_list',$data);
            $this->assign('page_arr',$page_arr);
        }


        $index = new IndexController();
        $time = $index->getTime();
        $this->assign('time',$time);
        if($weather = $index->getWeather()){
            $this->assign('weather',$weather);
        }

        $this->display();


    }


    /*
     * 处理课程列表部分数据
     * $param array $course_list 课程列表数据
     * return array
     */
    public function formatCourse($course_list){
        $m_course = new CourseModel();
        if(!empty($course_list) && is_array($course_list)){
            foreach($course_list as $k=>$v){
                $open_status = ($v['open_status'] == 1)?'最新上线':'即将结课';

                $course_num = $m_course->getCourseNum($v['course_id']);
                $course_list[$k]['os_cn'] = $open_status .' (共' .$course_num .'节课)';

                $course_list[$k]['name'] = cutStr($v['name'],12);

                $course_list[$k]['cover'] = getImageBaseUrl($v['cover']);

            }
            return $course_list;
        }else{
            return  false;
        }
    }





    /*
   * 选课首页
   */
    public function courseList()
    {
        $course_type = $this->params['course_type'];//课程分类
        $is_pub = $this->params['is_pub'];//课程状态1公共|2内训|3定向
        $number = $this->params['number'];
        $is_master = $this->params['is_master'];
        $course_dir = $this->params['course_dir'];

        if($is_master == 1){
            $where['subsite_id'] = 0;
        }elseif($is_master ==2){
            $where['subsite_id'] = $this->subsite_id;
        }



        $where['status'] = 0;
        $m_course = new CourseModel();
        if (!empty($course_type) || !empty($course_dir)) {
            $cse_d_id = array();
            $cse_t_id = array();
            if(!empty($course_type)){
                $ct_arr = explode(',', $course_type);
                $cse_t_id = $m_course->getCseByType($ct_arr,$opt=1);

            }

            if(!empty($course_dir)){
                $cd_arr = explode(',', $course_dir);
                $cse_d_id = $m_course->getCseByType($cd_arr,$opt=2);

            }
            $cse_arr = array_merge($cse_t_id,$cse_d_id);


            $where['course_id'] = array('in', $cse_arr);

        }


        if(!empty($is_pub)){
            $where['is_pub'] = $is_pub;
        }

        if ($count = $m_course->getCourseCount($where)) {

            $where['subsite_id'] = $this->subsite_id;

            if ($data = $m_course->getCourseList($where, 0, $number)) {
                $data = $this->formatCourse($data);
            } else {
                $where['subsite_id'] = 0;
                if ($data1 = $m_course->getCourseList($where, 0, $number)) {
                    $data = $this->formatCourse($data1);
                } else {
                    $this->to_back('11006');
                }
            }


            $this->assign('course_list', $data);

            $this->display();


        }
    }

}