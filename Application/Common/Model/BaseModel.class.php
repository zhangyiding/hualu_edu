<?php
namespace Common\Model;
use Common\Lib\Curl;
use Think\Model;
class BaseModel extends Model{
    protected $connection = 'DB_ETAGO';
    protected $tableName = 'etago_user_token';
	
	public function getUserToken($uid,$deviceid='',$is_logout=2){
	    $data = array();
	    $data['user_id'] = $uid;
	    if($deviceid){
	        $data['deviceid'] = $deviceid;
	    }
	    if($is_logout<2){
	        $data['is_logout'] = $is_logout;
	    }
	    $result = $this->where($data)->select();
	    return $result;
	}

	public function getTimezoneByCityId($city_id){
	    $sql = "select timezone from savor_city where cityid=$city_id";
	    $result = $this->query($sql);
	    if(!empty($result)){
	        $timezone = $result[0]['timezone'];
	    }else{
	        $timezone = 'America/Los_Angeles';
	    }
	    return $timezone;
	}


}
?>