<?php
namespace Home\Model;
use Think\Model;
class UserModel extends Model{

    protected $tableName = 'student';

    /*
     * 查询是否存在重复email
     */
    public function checkUname($email){
        $where['email'] = $email;
        $where['status'] = '0';
        $result = $this
            ->where($where)
            ->order('ctime desc')
            ->select();
        return $result;
    }


    public function region($map){
        $result = $this->add($map);
        return $result;
    }


    /*
     * 根据id获取学员详细信息
     */
    public function getUserInfo($where){
        $result = $this->field('student_id,cu_id,subsite_id,name,money,unit,job_position,level,avatar,
                                mobile,ethnic,gender,birthday,email,politics_status,education,post_code,address,apartment')
            ->where($where)
            ->order('ctime desc')
            ->find();
        if($result){
            $result['avatar'] = getImageBaseUrl($result['avatar']);
            return $result;
        }else{
            return false;
        }

    }


    /*
     * 根据学员id获取学院课程信息
     */
    public function getUserCse($student_id){
        $result = $this->table('student_course_map')
            ->field('student_id,course_id,subsite_id,cse_status,status,scm_id')
            ->where(array('student_id'=>$student_id))
            ->select();
        return $result;
    }




    /*
     * 修改密码
     */
    public function updateInfo($data,$student_id){
        $result = $this
            ->where(array('student_id'=>$student_id))
            ->save($data);
        return $result;
    }



    /*
 * 修改密码
 */
    public function getLearnTime($student_id){
        $result = $this->table('student_course_record')
            ->field("sum(watch_time) as learn_time")
            ->where(array('student_id'=>$student_id))
            ->find();
        return $result['learn_time'];
    }




}
?>