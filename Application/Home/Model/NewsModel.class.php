<?php
namespace Home\Model;
use Think\Model;
class NewsModel extends Model{

    protected $tableName = 'news';




    public function getNewsList($where,$offset=0,$limit=0){
        $where['status'] = 0;
        $result = $this->field('news_id,title,subsite_id,type,cover')
            ->where($where)
            ->limit($offset,$limit)
            ->order('ctime desc')
            ->select();
        if($result !== false && is_array($result)){
            foreach($result as $k=>$v){
                $result[$k]['cover'] = getImageBaseUrl($v['cover']);
            }
            return $result;
        }else{
            return false;
        }

    }


    public function getNewsCount($where){
        $result = $this
            ->where($where)
            ->count();
        return $result;
    }


    public function getNewInfo($news_id){
        $where['news_id'] = $news_id;
        $where['status'] = 0;
        $result = $this
            ->where($where)
            ->select();
        return $result ? $result[0]:false;
    }


}
?>