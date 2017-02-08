<?php
namespace Home\Model;
use Think\Model;
class IndexModel extends Model{


    public function getCseRes(){

        $result = $this->table('course_type_map')
//            ->alias('cr')
            ->field('course_id,ct_id')
//            ->join('left join resource as r on cr.resource_id = r.resource_id')
////            ->group('w.teacher_id')
                ->where(array('status'=>array('NEQ','-1'),'ct_id'=>array('NEQ','0')))
//            ->group('cr.course_id')
            ->order('course_id desc')
            ->select()
        ;
        return $result? $result: false;
    }



    public function addCseRes($map,$where){
        $result = $this->table('course')
            ->where($where)
            ->save($map);

        return $result? $result: false;
    }

    public function getCseByTrain($train_id){
        $result = $this->table('train_course')
            ->field('course_id')
            ->where(array('train_id'=>$train_id,'status'=>0))
            ->select();
        return $result? $result: false;
    }


}
?>