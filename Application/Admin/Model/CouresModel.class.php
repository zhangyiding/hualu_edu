<?php
namespace Admin\Model;
use Think\Model;
use Common\Lib\Curl;
class CouresModel extends Model{
	protected $connection = 'DB_ETAGO';
    protected $tableName = 'hl_course';
	
	public function getCourseList(){
	    $result = $this->table('hl_course')->where(array('del_flag'=>0))->select();
	    return $result;
	}

    public function getCourseInfo($id){
        $result = $this->table('hl_course')->where(array('id'=>$id))->select();
        return $result['0'];
    }

    public function getAreInfo($id){
        $result = $this->table('hl_course_area')->where(array('id'=>$id))->select();
        return $result['0'];
    }


    public function getCourseAreaList(){
        $result = $this->table('hl_course_area')->where(array('del_flag'=>0))->select();
        return $result;
    }

    public function getCourseAreaArr(){
        $result = $this->table('hl_course_area')->where(array('del_flag'=>0))->select();
        $area_arr = array();
        foreach($result as $k=>$v){
            $area_arr[$v['id']] = $v['area_name'];
        }
        return $area_arr;
    }

    public function doAddCoures($where){
        $result = $this->table('hl_course')->add($where);
        return $result;
    }

    public function doAddArea($where){
        $result = $this->table('hl_course_area')->add($where);
        return $result;
    }

    public function delCourse($where){
        $result = $this->table('hl_course')
            ->where($where)
            ->save(array('del_flag'=>1));
        return $result;
    }



}
?>