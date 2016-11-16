<?php
namespace Home\Model;
use Think\Model;
class NewsModel extends Model{

    protected $tableName = 'news';


	public function getNewsList($subsite_id){
        $where['subsite_id'] = $subsite_id;
        $where['status'] = '0';
	    $result = $this->field('news_id,title,subsite_id,type,cover')
            ->where($where)
            ->order('ctime desc')
            ->select();
	    return $result;
	}



}
?>