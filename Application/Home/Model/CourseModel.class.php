<?php
namespace Home\Model;
use Think\Model;
class CourseModel extends Model{

    protected $tableName = 'course';

    public function getCourseList($subsite_id){
        $where['subsite_id'] = $subsite_id;
        $where['status'] = '0';
        $result = $this->field('course_id,subsite_id,is_recommend,open_status,name,score,price,is_pub,
        cover,intro,ctime')
            ->where($where)
            ->order('ctime desc')
            ->select();
        return $result;
    }



}
?>