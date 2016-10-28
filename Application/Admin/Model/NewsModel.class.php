<?php
namespace Admin\Model;
use Think\Model;
use Common\Lib\Curl;
class NewsModel extends Model{
	protected $connection = 'DB_ETAGO';
    protected $tableName = 'hl_news';
	
	public function getNewsList(){
	    $result = $this->table('hl_news')->where(array('del_flag'=>0))->select();
	    return $result;
	}

    public function getNewsInfo($id){
        $result = $this->table('hl_news')->where(array('id'=>$id))->select();
        return $result['0'];
    }



    public function doAddNews($where){
        $result = $this->table('hl_news')->add($where);
        return $result;
    }

    public function delNews($where){
        $result = $this->table('hl_news')
            ->where($where)
            ->save(array('del_flag'=>1));
        return $result;
    }




}
?>