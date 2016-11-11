<?php
$config_hualu = array(

    'ADMIN_USER'=>1,//主站管理员
    'SUBSITE_USER'=>2,//分站管理员

    'IMG_URL'=>'http://img.hualu.com',

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



$config_key    =   array(
    'PARAM_KEY' => 'z&-etago0n!',//解密接口数据key
    'SIGN_KEY'  => 'etago2016#*$%^*)##%(2026',//签名key
    'GOOGLE_API_KEY' => 'AIzaSyDNGO0STvZ3FwWYyMQiHfiJgfmbFXP5YJs',//地图key
);

return array_merge($config_hualu,$config_key);