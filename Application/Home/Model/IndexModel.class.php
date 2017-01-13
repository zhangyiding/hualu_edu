<?php
namespace Home\Model;
use Think\Model;
class IndexModel extends Model{


    public function getCseRes(){

        $result = $this->table('subsite_course')
            ->alias('sc')
            ->field('sc.course_id,sc.subsite_id,sc.deadline')
//            ->join('left join ware as w on cw.ware_id = w.ware_id')
////            ->group('w.teacher_id')
//            ->order('cw.course_id desc')
            ->select();
        return $result? $result: false;
    }



    public function addCseRes($where,$map){
        $result = $this->table('course')
            ->where($where)
            ->save($map);
        return $result? $result: false;
    }


}
?>