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
        $is_pub = $this->params['is_pub'];//课程状态1公共|2内训|3定向
        $is_master = $this->params['is_master'];
        $course_dir = $this->params['course_dir'];

        if($is_master == 1){
            $where['subsite_id'] = 0;
        }elseif($is_master ==2){
            $where['subsite_id'] = $this->subsite_id;
        }

        $where['status'] = 0;
        $m_course = new CourseModel();

        //获取课程方向列表
        $cd_list = $m_course->getCourseDir();
        $this->assign('cd_list',$cd_list);

        //根据课程方向获取课程分类列表

        if(!empty($course_dir)){
            $ct_list = $m_course->getCseTypeByDir($course_dir);
        }else{
            //默认加载第一条学习方向分类
            if(!empty($course_type)){
                $course_dir = $m_course->getCseDirByType($course_type);
                $ct_list = $m_course->getCseTypeByDir($course_dir);
            }else{
                $ct_list = $m_course->getCseTypeByDir('1');
            }
            }


        if(empty($ct_list)){
            $this->showMsg('暂无相关课程');
        }
        $this->assign('ct_list',$ct_list);

        $cse_t_list = array();
        $cse_d_list = array();
        if (!empty($course_type)) {
            $cse_t_list = explode(',',$course_type);
            }

        if (!empty($course_dir)) {
            $cse_type = $m_course->getCseTypeByDir($course_dir);

            foreach($cse_type as $k=>$v){
                $cse_t_list[] = $v['ct_id'];
            }

        }
        if($cse_list = array_merge($cse_t_list,$cse_d_list)){

            if($cse_d_id = $m_course->getCseByType($cse_list)){
                $where['course_id'] = array('in',$cse_d_id);
            }else{
                $this->showMsg('暂无相关课程');
            }
        }

        if(!empty($is_pub)){
            $where['is_pub'] = $is_pub;
        }

        if ($count = $m_course->getCourseCount($where)) {
            $page_arr = listPage($count,12,$this->page);

            $this->assign('page_arr',$page_arr);
            $this->assign('count',$count);

            $data = $m_course->getCourseList($where, $this->offset, 12);

            $data = $this->formatCourse($data);
            $this->assign('course_list',$data);
            }else{
            $this->showMsg('暂无相关课程');
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
        $is_recommend = $this->params['is_recommend'];//课程类型，2热门或者1推荐

        $where['status'] = 0;
        $m_course = new CourseModel();

        if (!empty($course_type)) {
            $ct_arr = explode(',', $course_type);
           if($cse_id = $m_course->getCseByType($ct_arr)){
               $where['course_id'] = array('in', $cse_id);
           }
        }



        if (!empty($is_recommend)) {
            $where['is_recommend'] = $is_recommend;
        }

        $where['subsite_id'] = $this->subsite_id;
        if ($data_sub = $m_course->getCourseList($where, 0, 8)) {
            $data = $this->formatCourse($data_sub);
        } else {
            $where['subsite_id'] = 0;
            if ($data_mas = $m_course->getCourseList($where, 0, 8)) {
                $data = $this->formatCourse($data_mas);
            } else {
                $this->to_back('11006');
            }
            $this->assign('course_list', $data);

            $this->display();


        }
    }



    public function Recommend(){

        $this->to_back($this->params);
        $data = array(
            array(
                'name'=>'中国共产党问责条例解毒',
                'id'=>1
            ),
            array(
                'name'=>'中国共产党问责条例解毒',
                'id'=>1
            ),
            array(
                'name'=>'中国共产党问责条例解毒',
                'id'=>1
            ),
            array(
                'name'=>'中国共产党问责条例解毒',
                'id'=>1
            ),
            array(
                'name'=>'中国共产党问责条例解毒',
                'id'=>1
            ),
            array(
                'name'=>'中国共产党问责条例解毒',
                'id'=>1
            ),
            array(
                'name'=>'中国共产党问责条例解毒',
                'id'=>1
            ),
            array(
                'name'=>'中国共产党问责条例解毒',
                'id'=>1
            ),
            array(
                'name'=>'中国共产党问责条例解毒',
                'id'=>1
            ),
        );
        $this->to_back($data);
    }

}