<?php
namespace Admin\Model;
use Think\Model;
use Common\Lib\Curl;
class CourseModel extends Model{

    protected $tableName = 'course';

	public function getCourseList($where,$offset,$limit){
	    $result = $this
            ->field('course_id,subsite_id,is_recommend,name,score,
                     level,price,is_pub,cover,intro,status,teacher_id,open_status,is_pub')
            ->where($where)
            ->limit($offset,$limit)
            ->order('ctime desc')
            ->select();
	    return $result;
	}


    public function getResourceList($where,$offset,$limit){
        $result = $this->table('resource')
            ->field('resource_id,subsite_id,type,name,ext,file_path,file_size,
                     duration,remark,ctime,status')
            ->where($where)
            ->limit($offset,$limit)
            ->order('ctime desc')
            ->select();
        return $result;
    }


    public function getAllResource($where){
        $result = $this->table('resource')
            ->field('resource_id,subsite_id,type,name,ext,file_path,file_size,
                     duration,remark,ctime,status')
            ->where($where)
            ->order('ctime desc')
            ->select();
        return $result;
    }

    public function getCourseCount($where){
        $result = $this->where($where)
            ->count();
        return $result;
    }


    public function getResourceCount($where){
        $result = $this->table('resource')
            ->where($where)
            ->count();
        return $result;
    }

    public function getCourseType($course_id){
        $result = $this->table('course_type_map')
            ->alias('ctm')
            ->field('ctm.course_id,ctm.ct_id,ct.name,cd.name as cd_name')
            ->join('left join course_type as ct on ctm.ct_id = ct.ct_id')
            ->join('left join course_direction as cd on ctm.cd_id = cd.cd_id')
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

    public function delResource($where){
        $result = $this->table('resource')
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



    public function addVideo($where){
        $result = $this->table('resource')->add($where);
        return $result;
    }


    public function getResourceName($where){
        $result = $this->table('resource')
            ->where($where)
            ->select();
        return $result;
    }


    public function getTeacherInfo($teacher_id){
        $result = $this->table('teacher')
            ->where(array('teacher_id'=>$teacher_id,'status'=>0))
            ->find();
        return $result;
    }


    public function updateCourse($where,$data){

        $result = $this->where($where)
        ->save($data);
        return $result!==false? true: false;
    }


    public function updateCseType($course_id,$course_type,$course_dir){
        $result = $this->table('course_type_map')
        ->where(array('course_id'=>$course_id,'status'=>0))
        ->save(array('cd_id'=>$course_dir,'ct_id'=>$course_type));
        return $result;
    }


    public function updateCseRes($course_id,$resource_id){
        $result = $this->table('course_resource')
            ->where(array('resource_id'=>$resource_id,'status'=>0))
            ->save(array('course_id'=>$course_id));
        return $result;
    }

    public function addCourse($data){
        $result = $this->add($data);
        return $result;
    }


    public function addCseType($course_id,$course_type,$course_dir){
        $result = $this->table('course_type_map')
            ->add(array('cd_id'=>$course_dir,'ct_id'=>$course_type,'course_id'=>$course_id));
        return $result;
    }


    public function addCseRes($course_id,$res_id){
        $result = $this->table('course_resource')
            ->add(array('resource_id'=>$res_id,'course_id'=>$course_id));
        return $result;
    }

    public function getCseType($cd_id){
        $result = $this->table('course_type')
            ->where(array('status'=>0,'cd_id'=>$cd_id))
            ->select();
        return $result;
    }


    public function getCseDir(){
        $result = $this->table('course_direction')
            ->where(array('status'=>0))
            ->select();
        return $result;
    }

    public function getTeacherList($where){
        $result = $this->table('teacher')
            ->field('teacher_id,name')
            ->where($where)
            ->select();
        return $result;
    }
}
?>