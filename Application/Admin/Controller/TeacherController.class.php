<?php
namespace Admin\Controller;
use Admin\Model\CourseModel;
use Think\Controller;
use Common\Controller\BaseController;
class TeacherController extends BaseController {

    public function index(){
        $m_teacher = new \Admin\Model\TeacherModel();
        $m_base = new \Common\Model\BaseModel();
        $m_course = new CourseModel();
        //当分站管理员访问时只能查看所属分站的课程
        if($this->su_type = C('SUBSITE_USER')){
            $where['subsite_id'] = $this->subsite_id;
        }

        if($name = $this->params['name']){
            $where['name'] = array('like','%'.$name.'%');
        }
        if($cse_type = $this->params['cse_type']){
            $where['ct_id'] = array('in',$cse_type);
        }
        $where['status'] = 0;

        $data = array();
        $page_arr = array();
       if($count = $m_teacher->getTeacherCount($where)){
           //获取分页总数进一取整
           $page_count = ceil($count/$this->limit);
           for($i=1;$i<=$page_count;$i++){
               $page_arr[] = $i;
           }

           $data = $m_teacher->getTeacherList($where,$this->offset,$this->limit);

           foreach($data as $k=>$v){
               //处理教师头像
               $data[$k]['avatar'] = getImageBaseUrl($v['cover']);

               //获取所擅长课程分类名称
               $ct_arr = $m_teacher->getCourseType($v['ct_id']);
               $cu_ty_name = array();
               foreach($ct_arr as $key=>$value){
                   $cu_ty_name[] = $value['name'];
               }

               $data[$k]['course_type'] = implode('、',$cu_ty_name);

               //获取分站信息
               $subsite_info = $m_base->getSubsiteInfo($this->subsite_id);
               $data[$k]['subsite_name'] = $subsite_info['name'];
           }
       }

        $ct_list = $m_course->getCseType();
        $this->assign('ct_list',$ct_list);
        $this->assign('tec_list',$data);
        $this->assign('page_arr',$page_arr);
        $this->display();
    }


    /*
     * 新增教师
     */
    public function addTeacher(){
        //当operation_type为1时执行更新操作，默认为0执行插入
        $m_tea = new \Admin\Model\TeacherModel();
        $op_type = $this->params['op_type'];
        if($op_type == 1){
            $id = $this->params['teacher_id'];
            $data = $m_tea->getTeacherInfo($id);
            $teacher_work = $m_tea->getTeacherWork($id);
            $data['unit'] = $teacher_work['unit'];
            $data['duties'] = $teacher_work['duties'];

            $data['avatar'] = getImageBaseUrl($data['avatar']);

            $this->assign('data',$data);
        }

        $ct_list = $m_tea->getAllCT();
        $ethnic = C('ETHNIC_LIST');

        $this->assign('op_type',$op_type);
        $this->assign('ct_list',$ct_list);
        $this->assign('ethnic_list',$ethnic);
        $this->display();
    }


    /*
     * 新增教师动作
     */
    public function doAddTeacher(){

        $op_type = $this->params['op_type'];

        if(!empty($this->params['ct_id'])){
            $data['ct_id'] = implode(',',$this->params['ct_id']);
        }
        $data['gender'] = $this->params['gender'];
        if($this->params['cover']){
            $data['avatar'] = $this->params['cover'];
        }

        $data['ethnic'] = $this->params['ethnic'];
        $data['name'] = $this->params['name'];
        $data['birthday'] = $this->params['birthday'];
        $data['mobile'] = $this->params['mobile'];
        $data['email'] = $this->params['email'];
        $data['address'] = $this->params['address'];
        $data['intro'] = $this->params['intro'];

        $data['unit'] = $this->params['unit'];
        $data['duties'] = $this->params['duties'];

        $data['subsite_id'] = $this->subsite_id;
        $data['creator'] = $this->creator;

        $m_teacher = new \Admin\Model\TeacherModel();

        if($op_type == 1){
            $teacher_id = $this->params['teacher_id'];
            $where = array('teacher_id'=>$teacher_id,'status'=>0);
            $data['mtime'] = date('Y-m-d H:i:s',time());
            $result = $m_teacher->updateTeacher(array_filter($data),$where);
        }else{

            $data['ctime'] = date('Y-m-d H:i:s',time());
            $result = $m_teacher->doAddTeacher($data);
        }

        if($result!== false){
            $this->showMsg('添加成功','index',1);
        }else{
            $this->showMsg('添加失败，请重试');
        }
    }

    /*
     * 删除教师动作
     */
    public function delTeacher(){
        $where['teacher_id'] = $this->params['teacher_id'];
        $m_news = new \Admin\Model\TeacherModel();
        if($m_news->delTeacher($where) !== false){
            $this->showMsg('删除成功','index',1);
        }else{
            $this->showMsg('删除失败，请重试');
        }
    }





}