<?php
$hualu_admin = array(

    'ADMIN_USER'=>1,//主站管理员
    'SUBSITE_USER'=>2,//分站管理员

    'RESOURCE_URL'=>'http://edu.hl.com',

    //图片资源路径
    'IMG_DIR'=>'public/resource/image',
    //视频资源路径
    'VIDEO_DIR'=>'public/resource/video',
    //其他文件资源路径
    'FILE_DIR'=>'public/resource/file',


    'news_type_admin'=>array(
        '1'=>'头条新闻',
        '2'=>'国资委资讯',
        '3'=>'公司培训',
        '4'=>'轮播资讯',
    ),

    'news_type_admin_sub'=>array(
        '3'=>'公司培训',
        '4'=>'轮播资讯',
    ),

    'ETHNIC_LIST'=>array(
        '1'=>'汉族',
        '2'=>'蒙古族',
        '3'=>'回族',
        '4'=>'藏族',
        '5'=>'维吾尔族',
        '6'=>'苗族',
        '7'=>'彝族',
        '8'=>'壮族',
        '9'=>'布依族',
        '10'=>'朝鲜族',
        '11'=>'满族',
        '12'=>'侗族',
        '13'=>'瑶族',
        '14'=>'白族',
        '15'=>'土家族',
        '16'=>'哈尼族',
        '17'=>'哈萨克族',
        '18'=>'傣族',
        '19'=>'黎族',
        '20'=>'傈僳族',
        '21'=>'佤族',
        '22'=>'畲族',
        '23'=>'高山族',
        '24'=>'拉祜族',
        '25'=>'水族',
        '26'=>'东乡族',
        '27'=>'纳西族',
        '28'=>'景颇族',
        '29'=>'柯尔克孜族',
        '30'=>'土族',
        '31'=>'达斡尔族',
        '32'=>'仫佬族',
        '33'=>'羌族',
        '34'=>'布朗族',
        '35'=>'撒拉族',
        '36'=>'毛南族',
        '37'=>'仡佬族',
        '38'=>'锡伯族',
        '39'=>'阿昌族',
        '40'=>'普米族',
        '41'=>'塔吉克族',
        '42'=>'怒族',
        '43'=>'乌孜别克族',
        '44'=>'俄罗斯族',
        '45'=>'鄂温克族',
        '46'=>'德昂族',
        '47'=>'保安族',
        '48'=>'裕固族',
        '49'=>'京族',
        '50'=>'塔塔尔族',
        '51'=>'独龙族',
        '52'=>'鄂伦春族',
        '53'=>'赫哲族',
        '54'=>'门巴族',
        '55'=>'珞巴族',
        '56'=>'基诺族',
    )
);

$hualu_user = array(
    'news_type'=> array(
        'home_page'=>1,//头条资讯类型
        'master'=>2,//主站国资委资讯类型
        'subsite'=>3,//分站公司类型
        'image'=>4,//轮播图类型
        'little_head'=>5//小标题类型
    ),
    'RECOMMEND_CSE'=>'1',//推荐课程
    'HOT_CSE'=>'2',//热门课程

    'COURSE_VE'=>'1',//课程视频
    'COURSE_FILE'=>'2',//课程学习资料
    'COURSE_IMG'=>'3',//课程图片

    'MASTER_NEWS'=>'1',//主站资讯
    'SUBSITE_NEWS'=>'2',//分站资讯

    'region_type'=>array(
    'system'=>0,//系统导入
        'user'=>1,//用户自己注册
        'admin'=>2,//后台管理员添加
),
    'week'=>array(
        '1'=>'星期一',
        '2'=>'星期二',
        '3'=>'星期三',
        '4'=>'星期四',
        '5'=>'星期五',
        '6'=>'星期六',
        '7'=>'星期日',

    ),




    'WEATHER_IMG'=>'/Public/home/image/weather_img/',




    'IP_TO_COORD'=>'http://www.geoplugin.net/json.gp',
    'GET_WEATHER'=>'https://api.thinkpage.cn/v3/weather/now.json',
    'WEATHER_KEY'=>'4wpm1e6mqnah34ut',//获取天气KEY


);
$config_key    =   array(
    'PARAM_KEY' => 'z&-etago0n!',//解密接口数据key
    'SIGN_KEY'  => 'etago2016#*$%^*)##%(2026',//签名key
    'GOOGLE_API_KEY' => 'AIzaSyDNGO0STvZ3FwWYyMQiHfiJgfmbFXP5YJs',//地图key
);





return array_merge($hualu_admin,$config_key,$hualu_user);