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

    public function getCseRegCount($where){
        $where['scm.status'] = 1;
        $where['scm.is_pay'] = 1;
        $result = $this->table('course')
            ->alias('c')
            ->field(array(
                'count(scm.student_id)'=>'scm_count',
                'c.course_id',
                'c.price'
            ))
            ->join('left join student_course_map AS scm ON c.course_id = scm.course_id')
            ->where($where)
            ->group('c.course_id')
            ->count();
        return $result;
    }


    public function getCseRegCountList($where,$offset,$limit){
        $where['scm.status'] = 1;
        $result = $this->table('course')
            ->alias('c')
            ->field(array(
                'count(scm.student_id)'=>'scm_count',
                'c.course_id',
                'c.price',
                'c.name'
            ))
            ->join('left join student_course_map AS scm ON c.course_id = scm.course_id')
            ->where($where)
            ->group('c.course_id')
            ->limit($offset,$limit)
            ->select();
        return $result;
    }
}
?>