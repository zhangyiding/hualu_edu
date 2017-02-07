<?php
namespace Admin\Controller;

use Admin\Model\CourseModel;
use Think\Controller;
use Common\Lib\UpVideo;
use Common\Controller\BaseController;

class CourseController extends BaseController {


    protected $cse_id = '';//课程id
    protected $cse_type = '';//课程类型
    protected $cse_dir = '';//课程学习方向
    protected $cse_res = '';//课程相关资源



    /*
     * 课程管理
     */
    public function index()
    {

        $m_base = new \Common\Model\BaseModel();
        $m_course = new \Admin\Model\CourseModel();
        //当分站管理员访问时只能查看所属分站的课程
        if($this->su_type == C('SUBSITE_USER')){
            $where['subsite_id'] = $this->subsite_id;
        }

        if($name = $this->params['name']){
            $where['name'] = array('like','%'.$name.'%');
        }

        if($is_recommend = $this->params['is_recommend']){
            $where['is_recommend'] = $is_recommend;
        }

        if($teacher_id = $this->params['teacher_id']){
            $where['teacher_id'] = $teacher_id;
        }

        if($cse_type = $this->params['cse_type']){
            $map['ct_id'] = $cse_type;
            if(!$cse_list = $m_course->getCseListByType($map)){
                $this->showMsg('暂无数据');
            }
        }

        if($cse_dir = $this->params['cse_dir']){
            $map['cd_id'] = $cse_dir;
            if(!$cse_list = $m_course->getCseListByType($map)){
                $this->showMsg('暂无数据');
            }
        }

        if(!empty($cse_list)){
            $where['course_id'] = array('in',array_unique($cse_list));
        }



        $where['status'] = 0;

        $data = array();
        $page_arr = array();
        if($count = $m_course->getCourseCount($where)){
            //获取分页总数进一取整
            $page_arr = listPage($count,$this->limit);

            $data = $m_course->getCourseList($where,$this->offset,$this->limit);
            foreach($data as $k=>$v){
                $data[$k]['cover'] = getImageBaseUrl($v['cover']);

                //获取教师信息
                $tea_info = $m_course->getTeacherInfo($v['teacher_id']);
                $data[$k]['tea_name'] = $tea_info['name'];

                //获取课程父级分类名称
                $type = $m_course->getCourseType($v['course_id']);
                $data[$k]['type_name'] = $type['name'];
                $data[$k]['dir_name'] = $type['cd_name'];

                //获取分站信息
                $subsite_info = $m_base->getSubsiteInfo($v['subsite_id']);
                $data[$k]['subsite_name'] = $subsite_info['name'];
            }
        }

        $cse_dir = $m_course->getCseDir();

        $this->assign('cse_dir',$cse_dir);
        $this->assign('course_list',$data);
        $this->assign('page_arr',$page_arr);

        $this->display();

    }



    /*
    * 课程类型管理
    */
    public function cseTypeManage(){
        $m_course = new \Admin\Model\CourseModel();

        $where['ct.status'] = 0;
        $data = array();
        $page_arr = array();
        if($count = $m_course->getCseTypeCount($where)){
            //获取分页总数进一取整
            $page_arr = listPage($count,$this->limit);

            $data = $m_course->getCseTypeList($where,$this->offset,$this->limit);

        }

        $this->assign('ct_list',$data);
        $this->assign('page_arr',$page_arr);
        $this->display();
    }



    /*
    * 课件管理
    */
    public function wareManage(){
        $m_course = new \Admin\Model\CourseModel();
        $m_base = new \Common\Model\BaseModel();

        //当分站管理员访问时只能查看所属分站的课程
        if($this->su_type == C('SUBSITE_USER')){
            $where['subsite_id'] = $this->subsite_id;
        }

        $where['type'] = C('COURSE_VE');

        $data = array();
        $page_arr = array();
        if($count = $m_course->getResourceCount($where)){
            //获取分页总数进一取整
            $page_arr = listPage($count,$this->limit);

            $data = $m_course->getResourceList($where,$this->offset,$this->limit);
            foreach($data as $k=>$v){

                $data[$k]['duration'] = changeTimeType($v['duration']);

                $data[$k]['file_path'] = getFileBaseUrl($v['file_path']);

                //获取分站信息
                $subsite_info = $m_base->getSubsiteInfo($v['subsite_id']);
                $data[$k]['subsite_name'] = $subsite_info['name'];
            }
        }



        $this->assign('ware_list',$data);
        $this->assign('page_arr',$page_arr);
        $this->display();
    }


    /*
     * 上传视频
     */
    public function addVideo(){


        $this->display();
    }

    /*
     * @todu 保存上传文件
     */
    public function upload(){

        //上传文件类型,视频，普通文件,图片
        $type = $this->params['type'];
        $this->checkFile($type);

        if($path = uploadFile($type)){
            $result['path'] = ($path);
            $this->to_back($result);
        }else{
            $this->to_back('10101');
        }

    }

    /*
     * @todu 保存上传视频文件
     */
    public function saveFile(){
        $map['subsite_id'] = $this->subsite_id;
        $map['creator'] = $this->creator;

        //上传文件类型,视频，普通文件,图片
        $type = $this->params['type'];
        $this->checkFile($type);


        $Upvideo = new UpVideo();
        $m_course = new CourseModel();
        if($path = uploadFile($type)){

           if($type == C('COURSE_VE')){
               $file_info = $Upvideo->getInfo($path);
           }else{
               $file_info = getFileInfo($path);
           }


            if($file_info){
                $map['name'] = $file_info['name'];
                $map['ext'] = $file_info['ext'];
                $map['file_path'] = $path;
                $map['file_size'] = $file_info['size'];
                $map['duration'] = $file_info['duration'];
                $map['ctime'] = date('Y-m-d H:i:s',time());
                $map['type'] = $type;

                if($m_course->addVideo($map)){
                    $data['path'] = $path;
                    $this->to_back($data);
                }else{
                    $this->to_back('10100');
                }
            }else{
                $this->to_back('10101');
            }

        }else{
           $this->to_back('10101');
       }

    }

    /*
    * 检测是否存在重复名称
    */
    public function checkFile($type){
      list($name,$ext) =  explode('.',$_FILES['uploadFile']['name']);

       $m_course = new CourseModel();

        if($type == C('COURSE_VE')){
            if(!in_array($ext,array(
                'rmvb','avi','mp4','3gp','mkv','swf','wmv','flv','f4v','dat','ts','m4v'))){
                $this->to_back('10104');
            }
        }elseif($type == C('COURSE_FILE')){
            if(!in_array($ext,array('pdf','ppt','doc','docx','txt','zip','xls','xlsx'))){
                $this->to_back('10104');
            }
        }

        $where['name'] = $name;
        $where['ext'] = $ext;
        $where['type'] = $type;


        if($result = $m_course->getResourceName($where)){
            $this->to_back('10102');
        }else{
            return true;
        }
    }






    /*
     * 添加课程
     */
    public function addCourse(){

        $m_course = new \Admin\Model\CourseModel();
        $op_type = $this->params['op_type'];
        if($op_type == 1){
            $id = $this->params['course_id'];
            $data = $m_course->getCourseInfo($id);

            $data['cover'] = getImageBaseUrl($data['cover']);
            $data['enroll_time'] = date('Y-m-d',$data['enroll_time']);
            $data['end_time'] = date('Y-m-d',$data['end_time']);

            if($cse_type = $m_course->getCourseType($data['course_id'])){
                $data['cse_type'] = $cse_type;
            }

            $where['r.type'] = C('COURSE_VE');
            $where['cr.course_id'] = $data['course_id'];
            if( $cse_video = $m_course->getCseRes($where)){
                $data['cse_video'] = $cse_video;
            }

            $where['r.type'] = C('COURSE_FILE');
            if( $cse_file = $m_course->getCseRes($where)){
               $data['cse_file'] = $cse_file;
            }

            $this->assign('data',$data);
            $this->assign('data_json',json_encode($data));

        }

        if($tea_list = $m_course->getTeacherList()){
            $this->assign('tea_list',$tea_list);
        }

        //只显示未导入的课件列表
        $map['type'] = C('COURSE_VE');

        if($video = $m_course->getAllResource($map)){
            $this->assign('video',$video);
        }
        $map['type'] =  C('COURSE_FILE');
        if($study_file = $m_course->getAllResource($map)){
            $this->assign('study_file',$study_file);
        }

        if($cd_list = $m_course->getCseDir()){
            $this->assign('cse_dir',$cd_list);
        }

        $this->display();
    }


    /*
     * 添加课程分类
    */
    public function addCseType(){

        $m_course = new \Admin\Model\CourseModel();
        $ct_id = $this->params['ct_id'];
        if(!empty($ct_id)){
            if( $data = $m_course->getCseTypeById($ct_id)){
                $this->assign('data',$data);
            }
        }

        if($cd_list = $m_course->getCseDir()){
            $this->assign('cse_dir',$cd_list);
        }

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



    /*
     * 删除视频资源动作
     */
    public function delResource(){
        $where['resource_id'] = $this->params['resource_id'];
        $m_course = new \Admin\Model\CourseModel();
        if($m_course->delResource($where) !== false){
            $this->showMsg('删除成功','index',1);
        }else{
            $this->showMsg('删除失败，请重试');
        }
    }



    /*
     * 删除课程类型
     */
    public function delCseType(){
        $where['ct_id'] = $this->params['ct_id'];
        $m_course = new \Admin\Model\CourseModel();
        if($m_course->delCseType($where) !== false){
            $this->showMsg('删除成功','index',1);
        }else{
            $this->showMsg('删除失败，请重试');
        }
    }

    /*
        * 上传学习资料
        */
    public function addStudyFile(){


        $this->display();
    }



    /*
    * 课程资料管理
    */
    public function studyManage(){
        $m_course = new \Admin\Model\CourseModel();
        $m_base = new \Common\Model\BaseModel();

        //当分站管理员访问时只能查看所属分站的课程
        if($this->su_type == C('SUBSITE_USER')){
            $where['subsite_id'] = $this->subsite_id;
        }

        $where['status'] = 0;
        $where['type'] = C('COURSE_FILE');

        $data = array();
        $page_arr = array();
        if($count = $m_course->getResourceCount($where)){
            //获取分页总数进一取整
            $page_arr = listPage($count,$this->limit);

            $data = $m_course->getResourceList($where,$this->offset,$this->limit);
            foreach($data as $k=>$v){

                $data[$k]['file_size'] = round($v['file_size']);

                $data[$k]['file_path'] = getFileBaseUrl($v['file_path']);

                //获取分站信息
                $subsite_info = $m_base->getSubsiteInfo($v['subsite_id']);
                $data[$k]['subsite_name'] = $subsite_info['name'];
            }
        }

        $this->assign('study_list',$data);
        $this->assign('page_arr',$page_arr);
        $this->display();

    }




    /*
     * 根据课程方向获取课程类型内容
     */
    public function getCseType(){
        $m_course = new CourseModel();
        $cd_id = $this->params['cd_id'];

        if($result = $m_course->getCseType($cd_id)){
            $this->to_back($result);
        }else{
            $this->to_back('11015');
        }

    }


    /*
     * 新增/修改课程动作
     */
    public function doAddCourse (){


        //操作类型，为1时更新，为空时插入
        $op_type = $this->params['op_type'];

        $data['name'] = $this->params['name'];
        if(!empty($this->params['cover'])){
            $data['cover'] = $this->params['cover'];
        }
        $data['intro'] = $this->params['intro'];
        $data['is_pub'] = $this->params['is_pub'];
        $data['price'] = $this->params['price'];
        $data['is_recommend'] = $this->params['is_recommend'];
        $data['enroll_time'] = strtotime($this->params['enroll_time']);
        $data['end_time'] = strtotime($this->params['end_time']);
        $data['teacher_id'] = $this->params['teacher_id'];


        $cse_type = $this->params['cse_type'];
        $cse_dir = $this->params['cse_dir'];

        $resource_id = $this->params['resource_id'];

        $data['subsite_id'] = $this->subsite_id;
        $data['su_id'] = $this->creator;

        $m_cse = new \Admin\Model\CourseModel();

        if($op_type == 1){
            $this->cse_id = $this->params['course_id'];
            $where = array('course_id'=>$this->cse_id,'status'=>0);
            $data['mtime'] = date('Y-m-d H:i:s',time());

           if($result = $m_cse->updateCourse($where,array_filter($data))){
               if(!empty($cse_type) && !empty($cse_dir)){
                   $m_cse->updateCseType($this->cse_id,$cse_type,$cse_dir);
               }

               if(!empty($resource_id)){
                   $res_arr = array_filter(explode(',',$resource_id));
                   foreach($res_arr as $v){
                       $m_cse->updateCseRes($this->cse_id,$v);
                   }
               }
               $this->to_back('10000');

           }else{
               $this->to_back('11016');
           }


        }else{

            $data['ctime'] = date('Y-m-d H:i:s',time());

            if($this->cse_id = $m_cse->addCourse($data)) {

                if(!empty($cse_type) && !empty($cse_dir)){
                    $m_cse->addCseType($this->cse_id,$cse_type,$cse_dir);
                }

                if(!empty($resource_id)){
                    $res_arr = array_filter(explode(',',$resource_id));
                    foreach($res_arr as $v){
                        $m_cse->addCseRes($this->cse_id,$v);
                    }
                }
               $this->to_back('10000');
            }else{
                $this->to_back('11016');
            }




        }
    }


    /*
      * 新增/修改课程类型
      */
    public function doAddCseType (){

        //类型Id,为空时插入，有值时更新
        $ct_id = $this->params['ct_id'];

        $data['name'] = $this->params['name'];
        $data['cd_id'] = $this->params['cd_id'];
//        $data['order'] = $this->params['order'];

        $m_cse = new \Admin\Model\CourseModel();

        if(!empty($ct_id)){

            $where = array('ct_id'=>$ct_id,'status'=>0);
            $data['mtime'] = date('Y-m-d H:i:s',time());

            if($result = $m_cse->updateCT($where,array_filter($data))){
                $this->to_back('10000');
                }else{
                $this->to_back('11016');
            }

        }else{

            $data['ctime'] = date('Y-m-d H:i:s',time());

            if($result = $m_cse->addCT($data)) {
                $this->to_back('10000');
            }else{
                $this->to_back('11016');
            }




        }
    }


}