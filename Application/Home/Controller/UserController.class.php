<?php
namespace Home\Controller;
use Common\Controller\BaseController;
use Common\Model\BaseModel;
use Home\Model\CourseModel;
use Home\Model\UserModel;
use Common\Lib\Verify;
use Think\Controller;
class UserController extends BaseController {

    protected $username = '';
    protected $password;
    protected $user_info;
    protected $student_id;


    /*
     * 用户登录接口
     */
    public function login(){

        $this->username = $this->params['username'];
        $this->password = $this->params['password'];

        if($this->doLogin()){
            $this->to_back($this->user_info);
        }else{
            $this->to_back('11005');
        }
    }





    /**
     * 退出登录
     */
    public function logout()
    {
        $this->destroyStatus();
        $this->to_back('成功');
    }

    /**
     * 退出登录
     */
    private function destroyStatus()
    {
        session_destroy();
    }



    /*
     * 根据用户名来判断使用哪种方式登录
     */

    private function doLogin()
    {


        if (strripos($this->username, '@') != false) {
            return $this->loginByEmail();
        } elseif (intval($this->username) > 0 && strlen($this->username) > 10) {
            return $this->loginByMobile();
        }else{
            return false;
        }

    }



    /**
     * 根据email获取用户信息
     * @param $email
     */
    private function loginByEmail()
    {
        $m_user = new UserModel();
        $where['email'] = $this->username;
        $where['status'] = 0;
        $where['password'] =  encryptpass($this->password);
        if($this->user_info = $m_user->getUserInfo($where)){
            $this->storeStatus();
            return true;
        }else{
            return false;
        }
    }



    /**
     * 根据mobile获取用户信息
     * @param $mobile
     */
    private function loginByMobile()
    {
        $m_user = new UserModel();
        $where['mobile'] = $this->username;
        $where['status'] = 0;
        $where['password'] =  encryptpass($this->password);
        if($this->user_info = $m_user->getUserInfo($where)){
            $this->storeStatus();
            return true;
        }else{
            return false;
        }
    }


    /**
     * @todo 生成图片验证码
     *
     * @return image
     */
    public function createVerify(){
        session_start();
        $session_id = session_id();
        $verify = new Verify();
        $verify->entry($session_id);
    }


    /*
     * 用户注册接口
     *
     */
    public function region(){

        $m_user = new UserModel();
        $verify = new Verify();

        $map['email'] = $this->params['email'];
        $map['subsite_id']  = $this->params['subsite_id'];
        $map['gender']  = $this->params['gender'];
        $map['unit']  = $this->params['unit'];
        $map['name']  = $this->params['name'];
        $map['password']  = encryptpass($this->params['password']);

        $map['mobile']  = $this->params['mobile'];
        $map['comefrom'] = C('region_type')['user'];


        if(!check_mobile($this->params['mobile'])){
            $this->to_back('11003');
        }
//
//        if(!$verify->check($this->params['code'],session_id())){
//            $this->to_back('11007');
//        }

        if($this->params['password'] !== $this->params['confirm_pwd']){
            $this->to_back('11009');
        }

        if(!check_email($this->params['email'])){
            $this->to_back('11002');
        }

        if($m_user->checkUname($this->params['email'])){
            $this->to_back('11010');
        }

        if($this->student_id = $m_user->region($map)){

            $where['student_id'] = $this->student_id;
            $where['status'] = 0;
            $this->user_info = $m_user->getUserInfo($where);
            $this->storeStatus();

            $this->to_back($this->user_info);
        }else{
            $this->to_back('11004');
        }





        $this->display();
    }


    /**
     * 缓存登录信息
     */
    private function storeStatus()
    {
        $user_info = $this->user_info;

        session_start();
        session('subsite_id', $user_info['subsite_id']);
        session('student_id', $user_info['student_id']);
        session('name', $user_info['name']);
        session('user_info', $user_info);
    }


    /*
     * 用户找回密码接口
     */
    public function findPwd(){



        $this->display();
    }



    /*
     * 用户修改密码接口
     */
    public function changePwd(){

        $student_id = session('student_id');
        $old_pwd = $this->params['old_pwd'];
        $new_pwd = $this->params['new_pwd'];
        $cfm_pwd = $this->params['cfm_pwd'];

        $m_user = new UserModel();
        $where['student_id'] = $student_id;
        $where['password'] = encryptpass($old_pwd);
        $where['status'] = 0;

        if($user_info = $m_user->getUserInfo($where)){

            if($new_pwd !== $cfm_pwd){
                $this->to_back('11012');
            }

            $data['password'] = encryptpass($new_pwd);
           if($m_user->updateInfo($data,$student_id)){
               $this->to_back('10000');
           }
        }else{
            $this->to_back('11011');
        }


    }



    /*
     * 判断用户是否登录
     */
    public function userIsLogin(){

        if ($user_info = $this->isLogin()) {
            $this->to_back($user_info);
        } else {
            $this->to_back('10001');
        }

    }


    /*
     * 用户学习中心界面
     *
     */
    public function center(){

        if($user_info = $this->isLogin()){
            $student_id = $user_info['student_id'];
            $this->assign('user_info',$user_info);
        }else{
            $this->showMsg('尚未登录');
        }

        $m_user = new UserModel();

        if($u_cse_map = $m_user->getUserCse($student_id)){

            $wait_cse = array();//待审核课程列表
            $len_cse = array();//正在学习learning学习列表
            $over_cse = array();//已经结课列表

            //根据课程状态字段分别划分三种类型课程
            foreach($u_cse_map as $k=>$v){
                if($v['status'] == 0 ){
                    if($data = $this->getWaitCse($v['course_id'])){
                        $wait_cse[] = $data;
                    }


                }elseif($v['status'] == 1 && $v['cse_status'] == 1){

                    if($data = $this->formatCse($v['course_id'])){
                        $len_cse[] = $data;
                    }

                }elseif($v['cse_status'] == 2){
                    if($data = $this->formatCse($v['course_id'])){
                        $over_cse[] = $data;
                    }
                }
            }

            $cse_count = array(
                'wait'=>count($wait_cse),
                'learning'=>count($len_cse),
                'over'=>count($over_cse)
            );

            $learn_time = formatTime($m_user->getLearnTime($student_id),1);


            $this->assign('learn_time',$learn_time);
            $this->assign('cse_count',$cse_count);
            $this->assign('wait_cse',$wait_cse);
            $this->assign('len_cse',$len_cse);
            $this->assign('over_cse',$over_cse);

        }

        $this->display();
    }


    private function getWaitCse($course_id){
        $m_course = new CourseModel();
        $c_course = new CourseController();
        $where['course_id'] = $course_id;
        if($cse_list = $m_course->getCourseList($where)){

            $data = $c_course->formatCourse($cse_list[0],2);
            return $data;
        }else{
            return false;
        }
    }


    private function formatCse($course_id){
        $m_course = new CourseModel();

        if($cse_info = $m_course->getCourseInfo($course_id)){

            $end_time = $this->formatEndTime($cse_info['end_time']);

            $where['cr.course_id'] = $course_id;
            $where['r.type'] = C('COURSE_VE');
            if($c_res = $m_course->getResource($where)){
                $cse_time = array();
                foreach($c_res as $k=>$v){
                    $cse_time[] = $v['duration'];

                    if(!empty($v['duration'])){
                        $c_res[$k]['duration'] = changeTimeType($v['duration']);
                    }

                    if(!empty($v['file_path'])){
                        $c_res[$k]['file_path'] = getFileBaseUrl($v['file_path']);
                    }else{
                        continue;
                    }
                }

                $data['name'] = $cse_info['name'];
                $data['end_time'] = $end_time;
                $data['is_pub'] = $cse_info['is_pub'];
                $data['sum_dur'] = changeTimeType(array_sum($cse_time));
                $data['cse_video'] = $c_res;
                return $data;
            }else{
                return false;
            }

        }

    }



    private function formatEndTime($end_time){
        if(!empty($end_time)){
           $time_diff = $end_time-time();
           return $time = ($time_diff>0)?round($time_diff/86400). '天后结束':'已结束';
        }
    }


    public function info(){
        if(!$user_info = $this->isLogin()){
            $this->showMsg('尚未登录');
        }
        $m_base = new BaseModel();
        $m_user = new UserModel();

        $where['student_id'] = $user_info['student_id'];
        if($data = $m_user->getUserInfo($where)){
            $subsite_info = $m_base->getSubsiteInfo($this->subsite_id);
            $data['subsite_name'] = $subsite_info['name'];
            $this->assign('user_info',$data);
        }

        $ethnic_list = C('ETHNIC_LIST');
        $this->assign('ethnic_list',$ethnic_list);

        $this->display();
    }

    public function userInfo(){
        if(!$user_info = $this->isLogin()){
            $this->to_back(10001);
        }
        $m_base = new BaseModel();
        $subsite_info = $m_base->getSubsiteInfo($this->subsite_id);
        $user_info['subsite_name'] = $subsite_info['name'];

        $this->to_back($user_info);


    }


    /*
     * 用户更新资料
     */
    public function updateInfo(){

        if(!$user_info = $this->isLogin()){
            $this->to_back('10001');
        }

        $data['gender'] = $this->params['gender'];
        if($this->params['avatar']){
            $data['avatar'] = $this->params['avatar'];
        }
        $data['ethnic'] = $this->params['ethnic'];
        $data['name'] = $this->params['name'];
        $data['birthday'] = $this->params['birthday'];
        $data['mobile'] = $this->params['mobile'];
        $data['unit'] = $this->params['unit'];
        $data['job_position'] = $this->params['job_position'];
        $data['politics_status'] = $this->params['politics_status'];
        $data['education'] = $this->params['education'];
        $data['post_code'] = $this->params['post_code'];
        $data['apartment'] = $this->params['apartment'];
        $data['mtime'] = date('Y-m-d H:i:s',time());

        $m_user = new UserModel();

        $student_id = $user_info['student_id'];

        $result = $m_user->updateInfo(array_filter($data),$student_id);

        if($result!== false){
            $this->to_back($user_info);
        }else{
            $this->to_back('11013');
        }
    }







    public function help(){
        $this->display();
    }



    public function prove(){
        $this->display();
    }





}