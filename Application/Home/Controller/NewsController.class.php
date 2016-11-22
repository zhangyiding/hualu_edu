<?php
namespace Home\Controller;
use Common\Controller\BaseController;
use Home\Model\NewsModel;
use Think\Controller;
class NewsController extends BaseController {


    public function index(){
        $m_news = new \Home\Model\NewsModel();
        $type = $this->params['news_type'];
        //根据类型判断显示主站资讯或者分站
        $where['subsite_id'] = ($type == C('MASTER_NEWS'))? 0: $this->subsite_id;

        $data = array();
        $page_arr = array();
        if($count = $m_news->getNewsCount($where)){
            //获取分页总数进一取整
            $page_arr = listPage($count,$this->limit);

            $data = $m_news->getNewsList($where,$this->offset,$this->limit);
            foreach($data as $k=>$v){
                $data[$k]['cover'] = getImageBaseUrl($v['cover']);
            }
        }

        $this->assign('news_list',$data);
        $this->assign('page_arr',$page_arr);
        $this->assign('news_type',C('news_type'));
        $this->display();
        }


    public function newInfo(){
        $m_news = new \Home\Model\NewsModel();

        $news_id = $this->params['news_id'];
        if($news_info = $m_news->getNewInfo($news_id)){
            $this->assign('news_info',$news_info);
        }
        $this->display();
    }
}