<?php
namespace Home\Controller;
use Common\Controller\BaseController;
use Think\Controller;
class CourseController extends BaseController {
    public function courseList(){
        $area_id = $this->params['area_id'];
        $m_course = new \Home\Model\CourseModel();
        $course_list = $m_course->getCourseList($area_id);
        $area_list = $m_course->getCourseAreaArr();
        $data = array();

        //通过课程类型更改数据结构
        foreach($course_list as $k=>$v){
            $course_list[$k]['img'] = getImageBaseUrl($v['img']);
            if($v['course_tag'] == C('RMD_CUS')){
                $data['rmd_cus'][] = $course_list[$k];
            }else{
                $data['rcy_cus'][] = $course_list[$k];
            }

        }
        $cus_status = C('course_status');

        $this->assign('rcy_cus',$data['rcy_cus']);
        $this->assign('cus_status',$cus_status);
        $this->assign('rmd_cus',$data['rmd_cus']);

        $this->assign('area_list',$area_list);
        $this->display();
    }

    public function index(){
        $this->display();
    }

    /*
     * unity3D页面
     */
    public function unity(){
        $this->display();
    }

    /*
 * UI专题页面
 */
    public function ui(){
        $this->display();
    }


    /*
 * WEB专题页面
 */
    public function web(){
        $this->display();
    }


    /*
 * 建筑专题页面
 */
    public function building(){
        $this->display();
    }



}