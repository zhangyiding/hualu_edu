<?php
namespace Home\Controller;
use Common\Controller\BaseController;
use Home\Model\CourseModel;
use Home\Model\NewsModel;
use Think\Controller;
class IndexController extends BaseController {


    public function index(){
        $m_news = new \Home\Model\NewsModel();
        $m_course = new \Home\Model\CourseModel();

        $where['status'] = 0;
        if($count = $m_news->getNewsCount($where)){

            if($home_page = $this->getHomePage()){
                $this->assign('home_page',$home_page);
            }


            if($little_home = $this->getLittleHead()){
                $this->assign('little_home',$little_home);
            }

            if($master_news = $this->getMaster()){
                $this->assign('master_news',$master_news);
            }

            if($subsite_new = $this->getSubsite()){
                $this->assign('subsite_new',$subsite_new);
            }

            if($banner_news = $this->getBanner()){
                $this->assign('banner_news',$banner_news);
            }
        }



        if($count = $m_course->getCourseCount($where)){

            if($hot_ces = $this->getHotCse()){

                foreach($hot_ces as $k=>$v){
                    $open_status = ($v['open_status'] == 1)?'最新上线':'即将结课';

                    $course_num = $m_course->getCourseNum($v['course_id']);
                    $hot_ces[$k]['os_cn'] = $open_status .' (共' .$course_num .'节课)';

                }

                $this->assign('hot_cse',$hot_ces);
            }

            $ct_data = $m_course->getCourseType();
            $this->assign('ct_data',$ct_data);

        }
        $this->display();
    }



    private function getHotCse(){
        $m_course = new CourseModel();
        $where['subsite_id'] = $this->subsite_id;
        $where['is_recommend'] = C('HOT_CSE');
        if($data = $m_course->getCourseList($where,0,4)){
            return $data;
        }else{
            $where['subsite_id'] = 0;
            if($data1 = $m_course->getCourseList($where,0,4)){
                return $data1;
            }else{
                return false;
            }
        }
    }



    private function getHomePage(){
        $m_news = new NewsModel();
        $where['subsite_id'] = $this->subsite_id;
        $where['type'] = C('news_type')['home_page'];
        if($data = $m_news->getNewsList($where,0,1)){
            return $data['0'];
        }else{
            $where['subsite_id'] = 0;
            if($data1 = $m_news->getNewsList($where,0,1)){
                return $data1['0'];
            }else{
                return false;
            }
        }
    }

    private function getLittleHead(){
        $m_news = new NewsModel();
        $where['subsite_id'] = $this->subsite_id;
        $where['type'] = C('news_type')['little_head'];
        if($data = $m_news->getNewsList($where,0,2)){
            return $data;
        }else{
            $where['subsite_id'] = 0;
            if($data1 = $m_news->getNewsList($where,0,2)){
                return $data1;
            }else{
                return false;
            }
        }
    }

    private function getMaster(){
        $m_news = new NewsModel();
        $where['subsite_id'] = 0;
        $where['type'] = C('news_type')['master'];
        if($data = $m_news->getNewsList($where,0,5)){
            return $data;
        }else{
            return false;
        }
    }


    private function getSubsite(){
        $m_news = new NewsModel();
        $where['subsite_id'] = $this->subsite_id;
        $where['type'] = C('news_type')['subsite'];
        if($data = $m_news->getNewsList($where,0,5)){
            return $data;
        }else{
            return false;
        }
    }


    private function getBanner()
    {
        $m_news = new NewsModel();
        $where['subsite_id'] = $this->subsite_id;
        $where['type'] = C('news_type')['image'];
        if ($data = $m_news->getNewsList($where, 0, 5)) {
            return $data;
        } else {
            $where['subsite_id'] = 0;
            if ($data1 = $m_news->getNewsList($where, 0, 5)) {
                return $data1;
            } else {
                return false;
            }
        }
    }



}