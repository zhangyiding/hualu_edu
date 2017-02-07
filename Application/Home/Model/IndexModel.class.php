<?php
namespace Home\Model;
use Think\Model;
class IndexModel extends Model{


    public function getCseRes(){

        $result = $this->table('course_resource')
            ->alias('cr')
            ->field('cr.course_id,sum(r.duration) as dur')
            ->join('left join resource as r on cr.resource_id = r.resource_id')
////            ->group('w.teacher_id')
                ->where(array('r.status'=>array('NEQ','-1')))
            ->group('cr.course_id')
            ->order('cr.course_id desc')
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