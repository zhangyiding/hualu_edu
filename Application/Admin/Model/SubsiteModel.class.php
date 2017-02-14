<?php
namespace Admin\Model;
use Think\Model;
use Common\Lib\Curl;
class SubsiteModel extends Model{

    protected $tableName = 'subsite';

	public function getSubsiteList($where,$offset,$limit){
	    $result = $this->field('subsite_id,name,ename,site_url,province_id,city_id,address,postcode,subsite_type,subsite_banner,intro,remark,ctime,mtime')
            ->where($where)
            ->limit($offset,$limit)
            ->order('ctime desc')
            ->select();
	    return $result;
	}


    public function getSubsiteCount($where){
        $result = $this->where($where)
            ->count();
        return $result;
    }



    public function delSubsite($where){
        $result = $this
            ->where($where)
            ->save(array('status'=>-1));
        return $result;
    }


    public function getSubsiteInfo($id){
        $result = $this
            ->where(array('subsite_id'=>$id,'status'=>'0'))
            ->select();
        return ($result)? $result['0'] : false;
    }



    public function addSubsite($data){
        $result = $this
            ->add($data);
       return $result;
    }


    public function checkName($data){
        $result = $this
            ->where(array('name'=>$data['name'],'status'=>0,'_logic'=>'OR'));
        return $result;
    }

    public function updateSubsite($data,$where){
        $result = $this
            ->where($where)
            ->save($data);
        return $result;

    }


}
?>