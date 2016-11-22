<?php
namespace Home\Model;
use Think\Model;
class CourseModel extends Model{

    protected $tableName = 'course';

    public function getCourseList($where,$offset=0,$limit=0){

        $result = $this
            ->field('course_id,subsite_id,is_recommend,open_status,name,score,price,is_pub,
                     cover,intro,ctime')
            ->where($where)
            ->order('ctime desc')
            ->limit($offset,$limit)
            ->select();

        return $result;
    }



    public function getCourse($subsite_id){
        $where['c.subsite_id'] = $subsite_id;
        $where['c.status'] = '0';
        $result = $this->alias('c')
            ->field('c.course_id,c.subsite_id,c.is_recommend,c.open_status,c.name,c.score,c.price,c.is_pub,
                     c.cover,c.intro,c.ctime,ct.ct_id,ct.name as ct_name')
            ->join('left join course_type_map as ctm on c.course_id = ctm.course_id')
            ->join('left join course_type as ct on ctm.ct_id = ct.ct_id')
            ->where($where)
            ->order('c.ctime desc')
            ->select();
        return $result;
    }

    public function getCourseType(){
        $result = $this->table('course_type')
            ->where(array('status'=>'0'))
            ->select();
        return ($result)? $result : false;
    }

    public function getCourseNum($course_id){
        $where['course_id'] = $course_id;
        $where['status'] = 0;
        $result = $this->table('course_resource')
            ->where($where)
            ->count();
        return ($result)? $result : false;
    }




    public function getCourseCount($where){
        $result = $this->where($where)
            ->count();
        return $result;
    }

    public function getCseByType($ct_id){
        $where['ct_id'] = array('in',$ct_id);
        $result = $this->table('course_type_map')
            ->field('course_id')
            ->where($where)
            ->select();
        if($result !== false){
            foreach($result as $k=>$v){
                $data[] = $result[$k]['course_id'];
            }

            return $data;
        }else{
            return false;
        }

    }



}
?>