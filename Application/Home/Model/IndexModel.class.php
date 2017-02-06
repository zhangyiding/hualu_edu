<?php
namespace Home\Model;
use Think\Model;
class IndexModel extends Model{


    public function getCseRes(){

        $result = $this->table('train_member')
//            ->alias('tc')
            ->field('train_id,student_id,final_status,is_answer,ctime')
//            ->join('left join ware as w on cw.ware_id = w.ware_id')
////            ->group('w.teacher_id')
                ->where(array('status'=>0,'ctime'=>array('gt','2016-01-01')))
            ->order('ctime desc ')

//            ->limit(100)
            ->select();
        return $result? $result: false;
    }



    public function addCseRes($map){
        $result = $this->table('student_course_map')
            ->add($map);
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