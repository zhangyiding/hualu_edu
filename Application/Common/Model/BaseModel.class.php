<?php
namespace Common\Model;
use Common\Lib\Curl;
use Think\Model;
class BaseModel extends Model{

    public function getSubsiteInfo($subsite_id){
        $result = $this->table('subsite')
            ->where(array('subsite_id'=>$subsite_id,'status'=>0))
            ->select();
        return $result[0];
    }


}
?>