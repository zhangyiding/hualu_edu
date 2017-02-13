<?php
namespace Home\Model;
use Think\Model;
class CourseModel extends Model{

    protected $tableName = 'course';

    public function getCourseList($where,$offset=0,$limit=0){

        $result = $this
            ->field('course_id,subsite_id,is_recommend,open_status,name,default_score,price,is_pub,
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
            ->field('c.course_id,c.subsite_id,c.is_recommend,c.open_status,c.name,c.default_score,c.price,c.is_pub,
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
            ->alias('ct')
            ->where(array('ct.status'=>'0'))
            ->order('ct.order desc')
            ->limit(0,8)
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


    public function getCseTypeByDir($cd_id){
        $result = $this->table('course_type')
            ->field('ct_id')
            ->where(array('cd_id'=>$cd_id,'status'=>0))
            ->select();
        if($result){
            foreach($result as $k=>$v){
                $data[] = $v['ct_id'];
            }
            return $data;
        }else{
            return false;
        }

    }



    public function getCourseInfo($course_id){

        $result = $this->alias('c')
            ->field('c.course_id,c.subsite_id,c.ct_id,c.is_recommend,c.enroll_time,c.end_time,c.open_status,
                     c.name,c.default_score,c.price,c.is_pub,c.cover,c.intro,c.remark,c.teacher_id,c.ctime,
                     t.name as tea_name,t.intro as tea_intro,t.avatar as tea_img')
            ->join('left join teacher as t on c.teacher_id = t.teacher_id')
            ->where(array(
                'c.course_id'=>$course_id,
                'c.status'=>0,
                ))
            ->find();

        return $result;
    }



    public function getCseTypeInfo($ct_id){

        $where['ct_id'] = (strpos($ct_id,','))? array('in',explode(',',$ct_id)) : $ct_id;
        $where['status'] = 0;

        $result = $this->table('course_type')
            ->where($where)
            ->find();
        return $result;
    }


    public function getCseListByType($course_type,$limit=8){

        $result = $this->table('course')
            ->where(array(
                'ct_id'=>array('in',$course_type),
                'status'=>0))
            ->order('ctime desc')
            ->limit(0,$limit)
            ->select();
        return $result;
    }

    public function getResource($where){

        $result = $this->table('course_resource')
            ->alias('cr')
            ->field('cr.order,r.resource_id,r.name as res_name,r.type,r.output_id,r.ext,r.file_path,r.file_size,
                     r.duration,r.remark')
            ->join('join resource as r on cr.resource_id=r.resource_id')
            ->where($where)
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


    public function getCseId($res_id){
        $result = $this->table('course_resource')
            ->field('course_id')
            ->where(array('resource_id'=>$res_id))
            ->find();
        return $result['course_id'];
    }



    public function getResInfo($res_id){
        $result = $this->table('resource')
            ->field('resource_id,output_id,name,type,ext,file_path,file_size,duration')
            ->where(array('resource_id'=>$res_id))
            ->find();
        return $result;
    }


    public function getStuRecord($student_id,$res_id){
        $result = $this->table('student_course_record')
            ->field('id,watch_time')
            ->where(array('resource_id'=>$res_id,'student_id'=>$student_id))
            ->find();
        return $result;
    }


    public function updataRecord($data,$where){
        $result = $this->table('student_course_record')
            ->where($where)
            ->save($data);
        return $result;
    }

    public function addRecord($data){
        $result = $this->table('student_course_record')
            ->add($data);
        return $result;
    }



    public function getWatchTime($student_id,$resource_id){
        $result = $this->table('student_course_record')
            ->field('watch_time')
            ->where(array('student_id'=>$student_id,'resource_id'=>$resource_id))
            ->find();
        return $result['watch_time'];
    }


    public function getCseType($cd_id='',$limit='6'){
        if(!empty($cd_id)){
            $where['cd_id'] = $cd_id;
        }
        $where['status'] = 0;
        $result = $this->table('course_type')
            ->field('ct_id,name,cd_id')
            ->where($where)
            ->limit($limit)
            ->select();
        return $result;
    }
}
?>