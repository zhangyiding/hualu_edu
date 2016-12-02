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
            ->limit(0,8)
            ->order('ctime desc')
            ->select();
        if($result !== false){
            foreach ($result as $k=>$v){
                $result[$k]['name'] = cutStr($v['name'],6);
            }
        }
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


    public function getCseDirByType($course_type){
        $result = $this->table('course_type')
            ->field('cd_id')
            ->where(array(
                'ct_id'=>$course_type,
                'status'=>0
            ))
            ->find();
        return $result['cd_id'];
    }

    public function getCseByType($ct_list){
        $where['ct_id'] = array('in',$ct_list);

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



    public function getCourseDir(){
        $result = $this->table('course_direction')
            ->field('cd_id,name,order')
            //因Order与mysql字段重复所以使用改格式
            ->order(array('order','order'=>'desc'))
            ->select();
        return $result;
    }



    public function getCseTypeByDir($course_dir){
        $result = $this->table('course_type')
            ->field('ct_id,name,cd_id,order')
            ->where(array('cd_id'=>$course_dir))
            //因Order与mysql字段重复所以使用改格式
            ->order(array('order','order'=>'desc'))
            ->limit(0,10)
            ->select();
        return $result;
    }




    public function getCourseInfo($course_id){

        $result = $this->alias('c')
            ->field('c.course_id,c.subsite_id,c.is_recommend,c.enroll_time,c.end_time,c.open_status,
                     c.name,c.score,c.price,c.is_pub,c.cover,c.intro,c.remark,c.teacher_id,c.ctime,
                     t.name as tea_name,t.intro as tea_intro,t.avatar as tea_img')
            ->join('left join teacher as t on c.teacher_id = t.teacher_id')
            ->where(array(
                'c.course_id'=>$course_id,
                'c.status'=>0,
                ))
            ->find();

        return $result;
    }



    public function getCseTypeInfo($course_id){

        $result = $this->table('course_type_map')
            ->alias('ctm')
            ->field('ctm.cd_id,ctm.ct_id,ct.name as ct_name')
            ->join('left join course_type as ct on ctm.ct_id = ct.ct_id')
            ->where(array('ctm.course_id'=>$course_id))
            //因Order与mysql字段重复所以使用改格式
            ->limit(0,1)
            ->find();
        return $result;
    }


    public function getCseListByType($course_type,$limit=8){

        $result = $this->table('course_type_map')
            ->alias('ctm')
            ->field('ctm.ct_id,c.name,c.course_id')
            ->join('left join course as c on ctm.course_id= c.course_id')
            ->where(array(
                'ctm.ct_id'=>$course_type,
                'c.subsite_id'=>0,
                'c.status'=>0))
            ->order('c.ctime desc')
            ->limit(0,$limit)
            ->select();
        return $result;
    }

    public function getResource($course_id){

        $result = $this->table('course_resource')
            ->alias('cr')
            ->field('cr.order,r.resource_id,r.name as res_name,r.type,r.ext,r.file_path,r.file_size,
                     r.duration,r.remark')
            ->join('join resource as r on cr.resource_id=r.resource_id')
            ->where(array('cr.course_id'=>$course_id))
            ->select();

        return $result;
    }

    public function getRegister($where){
        $result = $this->table('student_course_map')
            ->field('student_id,scm_id,course_id,status')
            ->where($where)
            ->find();
        return $result;
    }


    public function doSignUp($map){
        $result = $this->table('student_course_map')
            ->add($map);
        return $result;
    }


}
?>