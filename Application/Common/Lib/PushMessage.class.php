<?php
namespace Common\Lib;
use Think\Model;
use Common\Lib\Curl;
class PushMessage extends Model{
    protected $tableName = 'etago_user_token';
	/**
     * 推送消息
     * @param array $user_arr 用户id数组
     * @param string $title   标题
     * @param string $content 内容
     * @param string $option 推送类型，
     * @return string 成功返回字符串
     */
	public function pushMsg($user_id, $title='', $content,$option){
		if(!is_array($user_id)){
            return false;
        }
        switch ($option) {
            case 'pushMsg':
                $user_arr = array();
                foreach($user_id as $v){
                    $user_info = $this->field('id,provider_master,provider_slave')->where(array(
                        'user_id'=>$v
                    ))->order('create_time desc, update_time')->limit(1)->select();
                    $user_arr[] = $user_info['0'];
                }
                $arn_arr = array();
                if(!empty($user_arr)){
                    foreach($user_arr as $k=>$v){
                        if($v['provider_master']){
                            $arn_arr[] = $v['provider_master'];
                        }
                        if($v['provider_slave']){
                            $arn_arr[] = $v['provider_slave'];
                        }
                    }
                }
                if(!empty($arn_arr)){
                    $curl = new Curl();
                    $push_url = getenv('ETASERVER_API_URL'). 'BaseService/task/pushNotify';
                    $data = array(
                        'arn'=>$arn_arr,
                        'title'=>$title,
                        'content'=>$content
                    );
                    $curl->post($push_url,json_encode($data),$result);
                    $result = json_decode($result,true);
                    if($result && $result['code']==10000){
                        return true;
                    }
                }
            break;
            case 'sendSms':
                break;
            case 'sendEmail':
                break;
        }
	}

}
?>