<?php
namespace Home\Controller;
use Common\Controller\BaseController;
use Home\Model\NewsModel;
use Think\Controller;
use \Home\Controller\IndexController;
class NewsController extends BaseController {


    public function index(){
        $m_news = new \Home\Model\NewsModel();
        $type = $this->params['news_type'];
        //根据类型判断显示主站资讯或者分站
        if(!empty($type)){
            $where['subsite_id'] = ($type == C('MASTER_NEWS'))? 0: $this->subsite_id;
        }else{
            $where['subsite_id'] = 0;
        }

        $where['status'] = 0;
        $data = array();
        $page_arr = array();

        if($count = $m_news->getNewsCount($where)){

            $page_arr = listPage($count,$this->limit);

            $data = $m_news->getNewsList($where,$this->offset,$this->limit,40);

            foreach($data as $k=>$v){
                $data[$k]['cover'] = getImageBaseUrl($v['cover']);

                $data[$k]['ctime'] = date('Y-m-d',strtotime($v['ctime']));

            }
        }

        //获取分页总数进一取整
        $this->assign('news_list',$data);
        $this->assign('page_arr',$page_arr);
        $this->assign('news_type',$type);
        $this->assign('page',$this->page);

        $index = new IndexController();
        $time = $index->getTime();
        $this->assign('time',$time);
        if($weather = $index->getWeather()){
            $this->assign('weather',$weather);
        }

        $this->display();
        }


    public function newsInfo(){
        $m_news = new \Home\Model\NewsModel();

        $news_id = $this->params['news_id'];
        if($news_info = $m_news->getNewInfo($news_id)){
            $this->assign('news_info',$news_info);
        }


        $index = new IndexController();
        $time = $index->getTime();
        $this->assign('time',$time);

        if($weather = $index->getWeather()){
            $this->assign('weather',$weather);
        }
        $this->display();
    }


}