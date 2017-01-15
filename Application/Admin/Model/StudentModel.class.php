<?php
namespace Admin\Model;
use Think\Model;
use Common\Lib\Curl;
class StudentModel extends Model{

    protected $tableName = 'student';

	public function getStudentList($where,$offset,$limit){
	    $result = $this->field('student_id,subsite_id,department,job_position,name,avatar,gender,mobile,email,ctime,remark')
            ->where($where)
            ->limit($offset,$limit)
            ->order('ctime desc')
            ->select();
	    return $result;
	}


    public function getStudentCount($where){
        $result = $this->where($where)
            ->count();
        return $result;
    }



    public function delStudent($where){
        $result = $this
            ->where($where)
            ->save(array('status'=>-1));
        return $result;
    }


    public function getStudentInfo($id){
        $result = $this
            ->where(array('student_id'=>$id,'status'=>'0'))
            ->select();
        return ($result)? $result['0'] : false;
    }



    public function addStudent($data){
        $result = $this
            ->add($data);
       return $result;
    }


    public function checkEmail($data){
        $result = $this
            ->where(array('email'=>$data['email'],'status'=>0,'_logic'=>'OR'));
        return $result;
    }

    public function updateStudent($data,$where){
        $result = $this
            ->where($where)
            ->save($data);
        return $result;

    }


}
?>