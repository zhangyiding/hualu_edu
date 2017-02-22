<?php
namespace Home\Controller;
use Common\Controller\BaseController;
use Common\Model\BaseModel;
use Home\Model\CourseModel;
use Home\Model\IndexModel;
use Home\Model\NewsModel;
use Common\Lib\Redis;
use Think\Controller;
class IndexController extends BaseController {


    public function __construct()
    {
        parent::__construct();
        $this->redis = new Redis();
        $this->service_cache_key_weather = C("CACHE_PREFIX") . "weather:";

    }





    public function index(){

        $m_news = new \Home\Model\NewsModel();
        $m_course = new \Home\Model\CourseModel();

        //处理资讯信息
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

            if($subsite_news = $this->getSubsite()){
                $this->assign('subsite_news',$subsite_news);
            }

            if($banner_news = $this->getBanner()){

                $this->assign('banner_news',$banner_news);
            }
        }


        //处理课程信息
        $c_course = new CourseController();

        if($count = $m_course->getCourseCount($where)){

            if($hot_ces = $this->getHotCse()){
                if($hot_ces = $c_course->formatCourse($hot_ces)){
                    $this->assign('hot_cse',$hot_ces);
                }

            }

            $cd_data = $m_course->getCourseDir();

            $this->assign('cd_data',$cd_data);
            $this->assign('title',$this->subsite_name);


        }


        $this->display();
    }


    /**
     * @todo 加载页面header
     */

    public function header(){

        $m_base = new BaseModel();
        $sub_list = $m_base->getSubsiteList();

        $this->assign('sub_list',$sub_list);


        $time = $this->getTime();
        $this->assign('time',$time);

        if($weather = $this->getWeather()){

            $this->assign('weather',$weather);
        }
        $this->display();
    }

    /**
     * @todo 加载页面footer
     */

    public function footer(){
        $this->assign('sub_name',$this->subsite_name);
        $this->display();
    }


    /**
     * @todo 加载页面nav
     */

    public function nav(){
        $nav_img = getImageBaseUrl($this->subsite_info['subsite_banner']);
        $this->assign('nav_img',$nav_img);

        $this->display();
    }





    /**
     * @todo 根据ip获取坐标
     * @param string $ip
     * @return bool
     */
    private function ipToCoord(){
         $data['city'] = 'beijing';
        return $data;
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



    public function getTime(){
        //处理天气日期信息
        $time = date('Y年m月d日 ',time());
        $wtime = date('N',time());
        $week_time = C('week')[$wtime];
        return $time . ' ' .$week_time;
    }

    /**
     * @todo 根据城市名称获取天气数据
     * @param string $city_name
     * @return array
     */
    public function getWeather(){

        if($location = $this->ipToCoord()){

            $key = $this->service_cache_key_weather . $location['city'];

            $redis = Redis::getInstance();

            if($result = $redis->Get($key)){

                return json_decode($result,true);
            }
            $m_base = new BaseModel();
            $data = array();
            if($result = $m_base->getWeather($location['city'])){
                $data['name'] = $result['location']['name'];
                $data['path'] = $result['location']['path'];
                $data['text'] = $result['now']['text'] . ' 温度：'.$result['now']['temperature'] .' ℃';
                $data['img'] = C('WEATHER_IMG').$result['now']['code'].'.png';

                $this->redis->set($key,json_encode($data),'86400');
                return $data;

            }else{
                return false;
            }
        }

    }




    private function getHotCse(){
        $m_course = new CourseModel();
        $where['subsite_id'] = $this->subsite_id;
        $where['is_recommend'] = C('HOT_CSE');
        if($data = $m_course->getCourseList($where,0,4)){
            return $data;
        }else{
            $where['subsite_id'] = C('MASTER_ID');

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
            $where['subsite_id'] = C('MASTER_ID');
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
            $where['subsite_id'] =  C('MASTER_ID');
            if($data1 = $m_news->getNewsList($where,0,2)){
                return $data1;
            }else{
                return false;
            }
        }
    }

    private function getMaster(){
        $m_news = new NewsModel();
        $where['subsite_id'] = C('MASTER_ID');
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
        if ($data = $m_news->getNewsList($where, 0, 5,25)) {
            return $data;
        } else {
            $where['subsite_id'] = C('MASTER_ID');
            if ($data1 = $m_news->getNewsList($where, 0, 5,25)) {
                return $data1;
            } else {
                return false;
            }
        }
    }



}