<?php
namespace Admin\Model;
use Think\Model;
use Common\Lib\Curl;
class RegisterModel extends Model{

    protected $tableName = 'student_course_map';

	public function getRegisterList($where,$offset,$limit){
	    $result = $this->field('scm_id,student_id,course_id,subsite_id,status,ctime,mtime')
            ->where($where)
            ->limit($offset,$limit)
            ->order('ctime desc')
            ->select();
	    return $result;
	}


    public function getRegisterCount($where){
        $result = $this->where($where)
            ->count();
        return $result;
    }




    public function changeStatus($where,$data){
        $result = $this
            ->where($where)
            ->save($data);
        return $result;

    }


}
?>