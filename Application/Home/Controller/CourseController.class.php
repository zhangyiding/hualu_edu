<?php
namespace Home\Controller;
use Common\Controller\BaseController;
use Common\Model\BaseModel;
use Home\Model\CourseModel;
use Home\Model\NewsModel;
use Home\Model\UserModel;
use Think\Controller;
class CourseController extends BaseController
{


    /*
   * 根据课程方向获取课程类型内容
   */
    public function getCtList(){
        $m_course = new CourseModel();
        $cse_dir = $this->params['cse_dir'];
        if($result = $m_course->getCseType($cse_dir)){
            $this->to_back($result);
        }else{
            $this->to_back('11015');
        }

    }


    /*
     * 选课首页
     */
    public function getCseList()
    {
        $course_type = $this->params['course_type'];//课程分类
        $is_pub = $this->params['is_pub'];//课程状态1公共|2内训|3定向
        $is_master = $this->params['is_master'];
        $course_dir = $this->params['course_dir'];

        if ($is_master == 1) {
            $where['subsite_id'] = 0;
        } elseif ($is_master == 2) {
            $where['subsite_id'] = $this->subsite_id;
        }

        $where['status'] = 0;
        $m_course = new CourseModel();

        $ct_list = array();
        if (!empty($course_type)) {
            $ct_list = explode(',', $course_type);
        }else{
            if (!empty($course_dir)) {
                $cse_type = $m_course->getCseTypeByDir($course_dir);
                foreach ($cse_type as $k => $v) {
                    $ct_list[] = $v['ct_id'];
                }

            }
        }

        if (!empty($ct_list)) {
            if ($cse_d_id = $m_course->getCseByType($ct_list)) {
                $where['course_id'] = array('in', $cse_d_id);
            } else {
                $this->showMsg('暂无相关课程');
            }
        }

        //判断公开、内训、定向
        if (!empty($is_pub)) {
            $where['is_pub'] = $is_pub;
        }

        if ($count = $m_course->getCourseCount($where)) {
            $data = $m_course->getCourseList($where, $this->offset, 12);
            $data = $this->formatCourse($data);
            $this->to_back(array('count'=>$count,'page'=>$this->page,'data_list'=>$data));

        } else {
            $this->to_back('11015');
        }



    }



    /*
     * 选课首页
     */
    public function index()
    {
        $where['subsite_id'] = 0;
        $where['status'] = 0;
        $m_course = new CourseModel();

        //获取课程方向列表
        $cd_list = $m_course->getCourseDir();
        $this->assign('cd_list', $cd_list);
        $this->assign('sub_name', $this->subsite_name);



        if ($count = $m_course->getCourseCount($where)) {
            $data = $m_course->getCourseList($where, $this->offset, 12);
            $data = $this->formatCourse($data);
            $this->assign('course_list', $data);
        } else {
            $this->showMsg('暂无相关课程');
        }

        $this->display();

    }




    /*
     * 处理课程列表部分数据
     * $param array $course_list 课程列表数据
     * $param int $option 标识操作符
     * return array
     */
    public function formatCourse($course_list, $op = 1)
    {
        $m_course = new CourseModel();
        if (!empty($course_list) && is_array($course_list)) {
            if ($op == 1) {
                foreach ($course_list as $k => $v) {
                    $open_status = ($v['open_status'] == 1) ? '最新上线' : '即将结课';

                    $course_num = $m_course->getCourseNum($v['course_id']);
                    //open_status and course_num
                    $course_list[$k]['os_cn'] = $open_status . ' (共' . $course_num . '节课)';

                    $course_list[$k]['name'] = cutStr($v['name'], 17);

                    $course_list[$k]['cover'] = getImageBaseUrl($v['cover']);
                }
            } else {
                $open_status = ($course_list['open_status'] == 1) ? '最新上线' : '即将结课';

                $course_num = $m_course->getCourseNum($course_list['course_id']);
                //open_status and course_num
                $course_list['os_cn'] = $open_status . ' (共' . $course_num . '节课)';

                $course_list['name'] = cutStr($course_list['name'], 12);

                $course_list['cover'] = getImageBaseUrl($course_list['cover']);
            }
            return $course_list;
        } else {
            return false;
        }
    }


    /*
   * 首页热门课程列表
   */
    public function courseList()
    {

        $course_type = $this->params['course_type'];//课程分类
        $is_recommend = $this->params['is_recommend'];//课程类型，2热门或者1推荐

        $where['status'] = 0;
        $m_course = new CourseModel();

        if (!empty($course_type)) {
            $ct_arr = explode(',', $course_type);
            if ($cse_id = $m_course->getCseByType($ct_arr)) {
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
                $this->showMsg('暂无数据');
            }
        }
        $this->assign('course_list', $data);

        $this->display();

    }


    /*
     * 课程详情页
     */
    public function courseInfo()
    {

        $course_id = $this->params['course_id'];

        $m_course = new CourseModel();
        $m_base = new BaseModel();
        if ($course_info = $m_course->getCourseInfo($course_id)) {

            //根据课程id获取分类信息
            if ($ct_info = $m_course->getCseTypeInfo($course_id)) {
                $course_info['ct_name'] = $ct_info['ct_name'];
                $ct_id = $ct_info['ct_id'];

                $cse_list = $m_course->getCseListByType($ct_id);

                $this->assign('cse_list', $cse_list);
            }


            $course_info['enroll_time'] = formatTime($course_info['enroll_time']);
            $course_info['end_time'] = formatTime($course_info['end_time']);
            $course_info['tea_img'] = getImageBaseUrl($course_info['tea_img']);

            if ($course_info['subsite_id'] !== 0) {
                $sub_info = $m_base->getSubsiteInfo($course_info['subsite_id']);
                $course_info['subsite_name'] = $sub_info['name'];
            } else {
                $course_info['subsite_name'] = '国资委培训';
            }


            //根据课程id获取资源详情列表
            $where['cr.course_id'] = $course_id;
            if ($c_res = $m_course->getResource($where)) {

                $cse_video = array();
                $cse_file = array();
                $cse_time = array();
                foreach ($c_res as $k => $v) {
                    $cse_time[] = $v['duration'];

                    if (!empty($v['duration'])) {
                        $c_res[$k]['duration'] = changeTimeType($v['duration']);
                    }

                    if (!empty($v['file_path'])) {
                        $c_res[$k]['file_path'] = getFileBaseUrl($v['file_path']);
                    } else {
                        continue;
                    }

                    $c_res[$k]['file_cover'] = getFileCoverByExt($v['ext']);

                    if ($v['type'] == C('COURSE_VE')) {
                        $cse_video[] = $c_res[$k];
                    } else {
                        $cse_file[] = $c_res[$k];
                    }
                }

                $course_info['sum_dur'] = changeTimeType(array_sum($cse_time));

                $cse_pub_type = C('cse_pub');
            }
            $this->assign('cse_info', $course_info);
            $this->assign('cse_pub', $cse_pub_type);
            $this->assign('cse_video', $cse_video);
            $this->assign('cse_file', $cse_file);
            $this->display();

        }

    }


    /*
     * 课程报名页
     */
    public function register()
    {


        $m_course = new CourseModel();

        if ($this->isLogin()) {
            $user_info = $this->isLogin();
        } else {
            $this->showMsg('尚未登录');
        }


        $course_id = $this->params['course_id'];
        $student_id = $user_info['student_id'];


        $where['course_id'] = $course_id;
        $where['student_id'] = $student_id;
        if ($reg_info = $m_course->getRegister($where)) {
            $this->showMsg('该课程已报名过');
            $reg_id = $reg_info['scm_id'];

        } else {
            $map['course_id'] = $course_id;
            $map['student_id'] = $student_id;
            $map['subsite_id'] = $user_info['subsite_id'];
            $map['ctime'] = date('Y-m-d H:i:s', time());

            $reg_id = $m_course->doSignUp($map);
        }


        $this->assign('reg_id', $reg_id);
        $cse_info = $m_course->getCourseInfo($course_id);


        $this->assign('user_info', $user_info);
        $this->assign('cse_info', $cse_info);


        $this->display();
    }


    /*
     * 听课页面
     */
    public function listenCourse()
    {

        if ($this->isLogin()) {
            $user_info = $this->isLogin();
        } else {
            $this->showMsg('尚未登录');
        }

        $res_id = $this->params['resource_id'];

        $m_course = new CourseModel();
        $cse_id = $m_course->getCseId($res_id);

        //获取学员已观看课程的时间
        if(!$watch_time = $m_course->getWatchTime($user_info['student_id'],$res_id)){
            $watch_time = 0;
        }

       $this->assign('watch_time',$watch_time);

        if($res_info = $m_course->getResInfo($res_id)){
            $res_info['file_path'] = getFileBaseUrl($res_info['file_path']);
            $this->assign('res_info',$res_info);

        }

        if ($cse_info = $m_course->getCourseInfo($cse_id)) {

            $end_time = round(($cse_info['end_time'] - time()) / 86400);

            $cse_info['end_time'] = $end_time>0 ?$end_time.'天以后课程结束':'该课程已结束';

            $cse_info['tea_img'] = getImageBaseUrl($cse_info['tea_img']);

            $this->assign('cse_info', $cse_info);

            //根据课程id获取资源详情列表
            $where['cr.course_id'] = $cse_id;

            if ($c_res = $m_course->getResource($where)) {

                $cse_video = array();
                $cse_file = array();
                foreach ($c_res as $k => $v) {

                    $c_res[$k]['file_cover'] = getFileCoverByExt($v['ext']);

                    if ($v['type'] == C('COURSE_VE')) {
                        $cse_video[] = $c_res[$k];
                    } else {
                        $cse_file[] = $c_res[$k];
                    }
                }


                $this->assign('cse_video',$cse_video);
                $this->assign('cse_file',$cse_file);
                $this->assign('file_url',$res_info['file_path']);

            }

            //根据课程id获取分类信息
            if ($ct_info = $m_course->getCseTypeInfo($cse_id)) {
               if($cse_list = $m_course->getCseListByType($ct_info['ct_id'])){
                   $cse_list = $this->formatCourse($cse_list);
               }

                $this->assign('cse_list', $cse_list);
            }


            $this->display();
        }

    }


    /*
     * 返回视频URL
     */
    public function showVideo(){
        $outputID = $this->params['id'];
        if($outputID){
            $dir1 = $outputID / 16777216 % 256;
            $dir2 = $outputID / 65536 % 256;
            $dir3 = $outputID /256 % 256;
            $relativePath = C('TCS_VIDEO').'/'.$dir1."/".$dir2."/".$dir3."/".$outputID.".f4v";
            print_r($relativePath);exit;
        }else{
            $this->showMsg('视频加载失败');
        }

}


    /*
     * 记录学生听课时间
     */
    public function learnRecord(){

        if ($this->isLogin()) {
            $user_info = $this->isLogin();
        } else {
            $this->showMsg('尚未登录');
        }

        $res_id = $this->params['resource_id'];
        $watch_time = $this->params['watch_time'];
        $duration = $this->params['duration'];
        if(empty($res_id) || empty($watch_time)){
            $this->to_back('11014');
        }

        $m_course = new CourseModel();

        if($record_id = $m_course->getStuRecord($user_info['student_id'],$res_id)){
            $data['watch_time'] = $watch_time;
            $where['id'] = $record_id;
            $m_course->updataRecord($data,$where);
            $this->to_back('10000');

        }else{
            $course_id = $m_course->getCseId($res_id);

            $data['student_id'] = $user_info['student_id'];
            $data['course_id'] = $course_id;
            $data['resource_id'] = $res_id;
            $data['watch_time'] = $watch_time;
            $data['duration'] = $duration;
            if($m_course->addRecord($data)){
                $this->to_back('10000');
            }
        }



    }





}