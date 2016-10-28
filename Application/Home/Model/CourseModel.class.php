<?php
namespace Home\Model;
use Think\Model;
class CourseModel extends Model{
	protected $connection = 'DB_ETAGO';
    protected $tableName = 'hl_course';
	
	public function getCourseList($area_id){
        //当区域id为空时查询全部区域的数据
       $where = (empty($area_id))? array('del_flag'=>0) : array('del_flag'=>0,'area'=>1);
	    $result = $this->where($where)->select();
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

}
?>