<?php
namespace Home\Controller;
use Common\Controller\BaseController;
use Think\Controller;
class IndexController extends BaseController {
    public function index(){
        $m_news = new \Home\Model\NewsModel();
        $m_course = new \Home\Model\CourseModel();

        if($result = $m_news->getNewsList($this->subsite_id)){

            foreach($result as $key=>$val){
                $result[$key]['cover'] = getImageBaseUrl($val['cover']);
            }

            $news_type = C('news_type');

            foreach($result as $k=>$v){
                switch( $v['type']){
                    case $news_type['home_page']:
                        $home_page[]=$result[$k];
                        break;
                    case $news_type['master']:
                        $master[] = $result[$k];
                        break;
                    case $news_type['subsite']:
                        $subsite[] = $result[$k];
                        break;
                    case $news_type['image']:
                        $image[] = $result[$k];
                        break;
                    default:
                        break;
                }
            }
        }

        if($course_data = $m_course->getCourseList($this->subsite_id)){

            foreach($course_data as $k=>$v){
                if($v['is_recommend'] == C('RECOMMEND_CSE')){
                    $rcd_cse[] = $course_data[$k];
                }elseif($v['is_recommend'] == C('HOT_CSE')){
                    $hot_cse[] = $course_data[$k];
                }
            }

            if(!empty($rcd_cse)){
                foreach($rcd_cse as $k=>$v){

                }
            }

        }





        $this->display();
    }



}