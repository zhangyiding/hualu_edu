<?php
$config_etago = array(
    'course_type'=>array(
      '1'=>'推荐班',
        '2'=>'近期开班'
    ),
    'course_status'=>array(
       '1'=>'招生中',
        '2'=>'招生完毕'
    ),

    'news_t_list'=>array(
        '1'=>'集团新闻',
        '2'=>'行业新闻',
        '3'=>'业界新闻'
    ),

    'IMG_URL'=>'http://img.hualu.com',
    'RMD_CUS'=>'1',//推荐课程 recommend course
    'RCY_CUS'=>'1',//最近课程 recently course

);


$config_key    =   array(
    'PARAM_KEY' => 'z&-etago0n!',//解密接口数据key
    'SIGN_KEY'  => 'etago2016#*$%^*)##%(2026',//签名key
    'GOOGLE_API_KEY' => 'AIzaSyDNGO0STvZ3FwWYyMQiHfiJgfmbFXP5YJs',//地图key
);

$config_third_api  =   array(
    'GOOGLE_MAP_API' => 'https://maps.google.com/maps/api/geocode/json',//谷歌定位接口
);
$config_search_host = array(
    'SEARCH_USER_BASE＿URL' => "http://search-etago-users-lrwim7hxtiisqzmdideswmhqe4.us-west-2.cloudsearch.amazonaws.com",
    'SEARCH_PRODUCT_BASE＿URL' => "http://search-etago-goods-iqsbfpheme4kul6mndtd5vrd4y.us-west-2.cloudsearch.amazonaws.com",
    'CONTRACTS_BASE_URL' => "http://search-etago-users-lrwim7hxtiisqzmdideswmhqe4.us-west-2.cloudsearch.amazonaws.com"
);

return array_merge($config_etago,$config_key,$config_third_api,$config_search_host);