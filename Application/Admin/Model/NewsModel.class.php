<?php
namespace Admin\Model;
use Think\Model;
use Common\Lib\Curl;
class NewsModel extends Model{

    protected $tableName = 'news';

	public function getNewsList($where,$offset,$limit){
	    $result = $this->table('news')
            ->field('news_id,title,subsite_id,type,cover,status,ctime')
            ->where($where)
            ->limit($offset,$limit)
            ->order('ctime desc')
            ->select();
	    return $result;
	}


    public function getNewsCount($where){
        $result = $this->table('news')
            ->where($where)
            ->count();
        return $result;
    }

    public function getNewsInfo($id){
        $result = $this->table('news')
            ->where(array('news_id'=>$id,'status'=>'0'))
            ->select();
       return ($result)? $result['0'] : false;
    }



    public function doAddNews($where){
        $result = $this->table('news')->add($where);
        return $result;
    }

    public function updataNews($data,$where){
        $result = $this->table('news')
            ->where($where)
            ->save($data);
        return $result;
    }

    public function delNews($where){
        $result = $this->table('news')
            ->where($where)
            ->save(array('status'=>-1));
        return $result;
    }




}
?>