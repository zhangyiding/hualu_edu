<?php
namespace Admin\Model;
use Think\Model;
use Common\Lib\Curl;
class TeacherModel extends Model{

    protected $tableName = 'teacher';

	public function getTeacherList($where,$offset,$limit){
        $map['t.subsite_id'] = $where['subsite_id'];
        $map['t.status'] = $where['status'];

	    $result = $this->alias('t')
            ->field('t.teacher_id,t.cu_id,t.ct_id,t.name,t.subsite_id,t.avatar,t.ethnic,t.gender,t.birthday,t.degree,
            t.mobile,t.email,t.post_code,t.address,t.intro,t.course_num,t.ctime,tw.start_time,tw.end_time,tw.unit,tw.apartment,
            tw.duties')
            ->join('left join teacher_work_record as tw on t.teacher_id = tw.teacher_id')
            ->where($map)
            ->limit($offset,$limit)
            ->order('t.ctime desc')
            ->select();
	    return $result;
	}


    public function getTeacherCount($where){
        $result = $this->where($where)
            ->count();
        return $result;
    }



    public function getCourseType($ct_id){
        $ct_id_arr = array_filter(explode(',',$ct_id));

        $result = $this->table('course_type')
            ->where(array('ct_id'=>array('in',$ct_id_arr),'status'=>0))
            ->select();
        return $result;

    }

    public function delTeacher($where){
        $result = $this
            ->where($where)
            ->save(array('status'=>-1));
        return $result;
    }


    public function getTeacherInfo($id){
        $result = $this
            ->where(array('teacher_id'=>$id,'status'=>'0'))
            ->select();
        return ($result)? $result['0'] : false;
    }


    public function getTeacherWork($teacher_id){
        $result = $this->table('teacher_work_record')
            ->where(array('teacher_id'=>$teacher_id,'status'=>'0'))
            ->select();
        return ($result)? $result['0'] : false;
    }

    public function getTeacherBySub($subsite_id){
        $result = $this->field('teacher_id,name,subsite_id')
            ->where(array('subsite_id'=>$subsite_id,'status'=>'0'))
            ->select();
        return $result;
    }


    public function getAllCT(){
        $result = $this->table('course_type')
            ->field('name,ct_id')
            ->where(array('status'=>0))
            ->select();
        return $result;
    }


    public function doAddTeacher($where){
        $work_data['unit'] = $where['unit'];
        $work_data['duties'] = $where['duties'];
        unset($where['unit']);
        unset($where['duties']);
        $result = $this
            ->add($where);
        if($result){
            $work_data['teacher_id'] = $result;
            $this->addTeacherWork($work_data);
        }

    }


    public function updateTeaWork($work_data,$where){
        $result = $this->table('teacher_work_record')
            ->where($where)
            ->save($work_data);
        return $result;
    }

    public function addTeacherWork($work_data){
        $result = $this->table('teacher_work_record')
            ->add($work_data);
        return $result;
    }

    public function updateTeacher($data,$where){
        $work_data['unit'] = $data['unit'];
        $work_data['duties'] = $data['duties'];
        unset($data['unit']);
        unset($data['duties']);

        $result = $this
            ->where($where)
            ->save($data);

       if($result){
           $this->updateTeaWork($work_data,$where);
           return true;
       }
    }

    public function getSubsiteInfo($subsite_id){
        $result = $this->table('subsite')
            ->where(array('subsite_id'=>$subsite_id,'status'=>0))
            ->select();
        return $result[0];
    }
}
?>