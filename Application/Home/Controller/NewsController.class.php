<?php
namespace Home\Controller;
use Common\Controller\BaseController;
use Home\Model\NewsModel;
use Think\Controller;
use \Home\Controller\IndexController;
class NewsController extends BaseController {


    public function index(){
        $m_news = new \Home\Model\NewsModel();
        $where['subsite_id'] = C('MASTER_ID');
        $where['status'] = 0;
        $data = array();


        if($count = $m_news->getNewsCount($where)){

            $data = $m_news->getNewsList($where,$this->offset,$this->limit,40);

            foreach($data as $k=>$v){
                $data[$k]['cover'] = getImageBaseUrl($v['cover']);
                $data[$k]['ctime'] = date('Y-m-d',strtotime($v['ctime']));

                if($v['subsite_id'] == 0){
                    $master_news[] = $data[$k];
                }

            }
        }

        //获取分页总数进一取整
        $this->assign('news_list',$data);
        $this->assign('css_path',$this->css_path);
        $this->display();
        }


    public function getNewsList(){
        $m_news = new \Home\Model\NewsModel();

        $type = $this->params['news_type'];
        // 根据类型判断显示主站资讯或者分站
        if(!empty($type)){
            $where['subsite_id'] = ($type == C('MASTER_NEWS'))? C('MASTER_ID'): $this->subsite_id;
        }else{
            $where['subsite_id'] =  C('MASTER_ID');
        }
        if($title = $this->params['title']){
            $where['title'] = array('like','%'.$title.'%');
        }

        $where['status'] = 0;

        if($count = $m_news->getNewsCount($where)){

            $result = $m_news->getNewsList($where,$this->offset,$this->limit,40);

            foreach($result as $k=>$v){
                $result[$k]['cover'] = getImageBaseUrl($v['cover']);
                $result[$k]['ctime'] = date('Y-m-d',strtotime($v['ctime']));

                if($v['subsite_id'] ==  C('MASTER_ID')){
                    $master_news[] = $result[$k];
                }
            }
            $data = array(
                'count'=>$count,
                'page'=>$this->page,
                'data_list'=>$result
            );
            $this->to_back($data);
        }else{
            $this->to_back('11015');
        }

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
        $this->assign('css_path',$this->css_path);
        $this->display();
    }




}