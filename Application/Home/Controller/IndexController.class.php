<?php
namespace Home\Controller;
use Common\Controller\BaseController;
use Think\Controller;
class IndexController extends BaseController {
    public function index(){
       $m_course = new \Home\Model\CourseModel();
        $area_list = $m_course->getCourseAreaArr();

        $news_type = C('news_t_list');

        $this->assign('area_list',$area_list);
        $this->assign('news_type',$news_type);
        $this->display();
    }

    //获取新闻信息
    public function news(){
        $news_type = $this->params['news_type'];
        $m_index = new \Home\Model\IndexModel();

        $news_list = $m_index->getNewsForType($news_type);
        $rmd_news = array();
        $no_rmd_news = array();
        foreach($news_list as $k=>$v){
            $news_list[$k]['img'] = getImageBaseUrl($v['img']);
            if($v['is_recommend'] == 1){
                $rmd_news = $news_list[$k];
            }else{
                $no_rmd_news[] = $news_list[$k];
            }
        }
        $this->assign('rmd_news',$rmd_news);
        $this->assign('no_rmd_news',$no_rmd_news);

        $this->display();
    }


}