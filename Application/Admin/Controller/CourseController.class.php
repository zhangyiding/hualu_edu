<?php
namespace Admin\Controller;
use Common\Lib\Upimg;
use Think\Controller;
use Common\Controller\BaseController;
class CourseController extends BaseController {


    /*
     * 课程管理
     */
    public function index()
    {
        $m_course = new \Admin\Model\CourseModel();
        //当分站管理员访问时只能查看所属分站的课程
        if($this->su_type = C('SUBSITE_USER')){
            $where['subsite_id'] = $this->subsite_id;
        }

        $where['status'] = 0;

        $data = array();
        $page_arr = array();
        if($count = $m_course->getCourseCount($where)){
            //获取分页总数进一取整
            $page_count = ceil($count/$this->limit);
            for($i=1;$i<=$page_count;$i++){
                $page_arr[] = $i;
            }

            $data = $m_course->getCourseList($where,$this->offset,$this->limit);
            foreach($data as $k=>$v){
                $data[$k]['cover'] = getImageBaseUrl($v['cover']);

                //获取课程父级分类名称
                $type = $m_course->getCourseType($v['course_id']);
                $data[$k]['type_name'] = $type['name'];
            }
        }

        $this->assign('course_list',$data);
        $this->assign('page_arr',$page_arr);

        $this->display();

    }


    /*
     * 上传视频
     */
    public function addVideo(){
        $m_tea = new \Admin\Model\TeacherModel();
        $teacher_list = $m_tea->getTeacherBySub($this->subsite_id);


        $this->assign('teacher',$teacher_list);

        $this->display();
    }


    /*
     * 上传学习资料
     */
    public function addStudyFile(){
        $this->display();
    }

    /*
     * 发布课程
     */
    public function addCourse(){
        $m_course = new \Admin\Model\CourseModel();
        $op_type = $this->params['op_type'];
        if($op_type == 1){
            $id = $this->params['course_id'];
            $data = $m_course->getCourseInfo($id);

            $data['cover'] = getImageBaseUrl($data['cover']);
            $this->assign('data',$data);
        }
        $this->assign('op_type',$op_type);
        $this->display();
    }



    /*
     * 删除课程动作
     */
    public function delCourse(){
        $where['course_id'] = $this->params['course_id'];
        $m_course = new \Admin\Model\CourseModel();
        if($m_course->delCourse($where) !== false){
            $this->showMsg('删除成功','index',1);
        }else{
            $this->showMsg('删除失败，请重试');
        }
    }






}