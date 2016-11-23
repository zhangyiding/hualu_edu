<?php
namespace Home\Controller;
use Common\Controller\BaseController;
use Common\Model\BaseModel;
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


        $c_course = new CourseController();

        if($count = $m_course->getCourseCount($where)){

            if($hot_ces = $this->getHotCse()){

                if($hot_ces = $c_course->formatCourse($hot_ces)){
                    $this->assign('hot_cse',$hot_ces);
                }

            }

            $ct_data = $m_course->getCourseType();
            $this->assign('ct_data',$ct_data);


        }

        $time = date('Y年m月d日 ',time());
        $wtime = date('N',time());
        $week_time = C('week')[$wtime];

        if($location = $this->ipToCoord()){
            $location = 'beijing';
            if($weather = $this->getWeather($location)){

                $this->assign('weather',$weather);
            }

        }

        $this->assign('time',$time .' ' .$week_time);
        $this->display();
    }




    /**
     * @todo 根据ip获取坐标
     * @param string $ip
     * @return bool
     */
    public function ipToCoord(){
        $ip = $this->ip;

        if(empty($ip)){
            return false;
        }
        //判断是否为合法的公共ipv4地址，私网地址将会返回false
        if(!filter_var($ip,FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE)){
            return false;
        }
        $m_base = new BaseModel();
        $data = array();
        if($result = $m_base->ipToCoord($ip)){
            $data['city'] = $result['geoplugin_city'];
            $data['country_code'] = $result['geoplugin_countryCode'];
            $data['country_name'] = $result['geoplugin_countryName'];
            $data['lat'] = $result['geoplugin_latitude'];
            $data['lon'] = $result['geoplugin_longitude'];
            $data['currenct_code'] = $result['geoplugin_currencyCode'];
            return $data;

        }else{
           return false;
        }
    }


    /**
     * @todo 根据城市名称获取天气数据
     * @param string $city_name
     * @return array
     */
    public function getWeather($city_name){

        if(empty($city_name)){
            return false;
        }

        $m_base = new BaseModel();
        $data = array();
        if($result = $m_base->getWeather($city_name)){
            $data['name'] = $result['location']['name'];
            $data['path'] = $result['location']['path'];
            $data['text'] = $result['now']['text'] . ' 温度：'.$result['now']['temperature'] .' ℃';
            $data['img'] = C('WEATHER_IMG').$result['now']['code'].'.png';

            return $data;

        }else{
            return false;
        }
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