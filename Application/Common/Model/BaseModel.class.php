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


    public function getSubsiteId($host_name){
        $result = $this->table('subsite')
            ->where(array('ename'=>$host_name,'status'=>0))
            ->select();
        return $result[0];
    }


    public function getSubsiteList(){
        $result = $this->table('subsite')
            ->field('subsite_id,name')
            ->where(array('status'=>0))
            ->select();
        return $result;
    }




    public function ipToCoord($ip){
//        $reids_key = $this->ip_to_coord_cache_key.$ip;
//        $this->redis->connect('db1',1);
//        if($result = $this->redis->get($this->$reids_key)){
//            return json_decode($result,true);
//        }

        $_url = C('IP_TO_COORD');
        $url = $_url."?ip=".$ip;
        $curl = new Curl();
        $curl->get($url,$re);
        $result = json_decode($re,true);
        if(!empty($result) && $result['geoplugin_status'] == 200){

            return $result;
        }else{
            return false;
        }
    }


    public function getWeather($city_name){

        $_url = C('GET_WEATHER');
        $url = $_url."?key=".C('WEATHER_KEY').'&location='.$city_name.'&language=zh-Hans&unit=c';
        $curl = new Curl();
        $curl->get($url,$re);
        $result = json_decode($re,true);

        if(!empty($result['results'])){
            return $result['results'][0];
        }else{
            return false;
        }
    }

}
?>