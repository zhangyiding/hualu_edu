<?php
namespace Admin\Model;
use Think\Model;
use Common\Lib\Curl;
class CourseModel extends Model{

    protected $tableName = 'course';

	public function getCourseList($where,$offset,$limit){
	    $result = $this
            ->field('course_id,subsite_id,is_recommend,name,score,
                     level,price,is_pub,cover,intro,status,fast_teacher,open_status,is_pub')
            ->where($where)
            ->limit($offset,$limit)
            ->order('ctime desc')
            ->select();
	    return $result;
	}


    public function getCourseCount($where){
        $result = $this->where($where)
            ->count();
        return $result;
    }


    public function getCourseType($course_id){
        $result = $this->table('course_type_map')
            ->alias('ctm')
            ->field('ctm.course_id,ctm.ct_id,ct.name')
            ->join('left join course_type as ct on ctm.ct_id = ct.ct_id')
            ->where(array('ctm.course_id'=>$course_id,'ctm.status'=>'0'))
            ->select();
        return ($result)? $result['0'] : false;
    }


    public function delCourse($where){
        $result = $this
            ->where($where)
            ->save(array('status'=>-1));
        return $result;

    }

    public function getCourseInfo($id){
        $result = $this
            ->where(array('course_id'=>$id,'status'=>'0'))
            ->select();
        return ($result)? $result['0'] : false;
    }

}
?>