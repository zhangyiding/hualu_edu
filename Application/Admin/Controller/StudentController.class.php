<?php
namespace Admin\Controller;
use Common\Lib\Upimg;
use Common\Lib\UpimgClass;
use Think\Controller;
use Common\Controller\BaseController;
class StudentController extends BaseController {

    public function index(){
        $m_student = new \Admin\Model\StudentModel();
        $m_base = new \Common\Model\BaseModel();
        //当分站管理员访问时只能查看所属分站的课程
        if($this->su_type = C('SUBSITE_USER')){
            $where['subsite_id'] = $this->subsite_id;
        }

        $where['status'] = 0;

        $data = array();
        $page_arr = array();
       if($count = $m_student->getStudentCount($where)){
           //获取分页总数进一取整
           $page_count = ceil($count/$this->limit);
           for($i=1;$i<=$page_count;$i++){
               $page_arr[] = $i;
           }

           $data = $m_student->getStudentList($where,$this->offset,$this->limit);

           foreach($data as $k=>$v){
               //处理头像
               $data[$k]['avatar'] = getImageBaseUrl($v['avatar']);


               //获取分站信息
               $subsite_info = $m_base->getSubsiteInfo($this->subsite_id);
               $data[$k]['subsite_name'] = $subsite_info['name'];
           }
       }

        $this->assign('stu_list',$data);
        $this->assign('page_arr',$page_arr);
        $this->display();
    }


    /*
     * 新增/修改学员
     */
    public function addStudent(){
        //当operation_type为1时执行更新操作，默认为0执行插入
        $m_student= new \Admin\Model\StudentModel();
        $op_type = $this->params['op_type'];
        if($op_type == 1){
            $id = $this->params['student_id'];
            $data = $m_student->getStudentInfo($id);

            $data['avatar'] = getImageBaseUrl($data['avatar']);

            $this->assign('data',$data);
        }


        $ethnic = C('ETHNIC_LIST');

        $this->assign('op_type',$op_type);
        $this->assign('ethnic_list',$ethnic);
        $this->display();
    }


    /*
     * 新增/修改学生动作
     */
    public function doAddStudent(){

        $op_type = $this->params['op_type'];

        $data['gender'] = $this->params['gender'];
        $data['avatar'] = uploadImg('student');
        $data['ethnic'] = $this->params['ethnic'];
        $data['name'] = $this->params['name'];
        $data['birthday'] = $this->params['birthday'];
        $data['mobile'] = $this->params['mobile'];
        $data['email'] = $this->params['email'];
        $data['address'] = $this->params['address'];
        $data['unit'] = $this->params['unit'];
        $data['remark'] = $this->params['remark'];
        $data['job_position'] = $this->params['job_position'];

        $data['subsite_id'] = $this->subsite_id;
        $data['creator'] = $this->creator;

        $m_student = new \Admin\Model\StudentModel();

        if($op_type == 1){
            $student_id = $this->params['student_id'];
            $where = array('student_id'=>$student_id,'status'=>0);
            $data['mtime'] = date('Y-m-d H:i:s',time());
            $result = $m_student->updateStudent(array_filter($data),$where);
        }else{
            if($m_student->checkEmail($data)){
                $this->showMsg('该用户已存在');
            }
            $data['ctime'] = date('Y-m-d H:i:s',time());
            $result = $m_student->addStudent($data);
        }

        if($result!== false){
            $this->showMsg('添加成功','index',1);
        }else{
            $this->showMsg('添加失败，请重试');
        }
    }



    /*
     * 删除学员动作
     */
    public function delStudent(){
        $where['student_id'] = $this->params['student_id'];
        $m_student = new \Admin\Model\StudentModel();
        if($m_student->delStudent($where) !== false){
            $this->showMsg('删除成功','index',1);
        }else{
            $this->showMsg('删除失败，请重试');
        }
    }





}