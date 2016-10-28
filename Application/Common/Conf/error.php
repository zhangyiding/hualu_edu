<?php
/**
 * 错误码以及错误信息配置定义
 */
return array(
	'errorinfo'=>array(
	    '10000' => 'success',

        //基础错误
        '10001'=>'user_not_login',//用户尚未登录
        '10002'=>'permission_denied',//无权访问
        '10003'=>'data_empty',

		//账户相关

        '10100' => 'username_not_null',
        '10101' => 'password_not_null',

        //管理用户相关
        '10200'=>'parmas_not_null',
        '10201'=>'member_add_fail',

        //admin

        //权限
//        '10120'=>'access_denied',

        '1001'=>'parmas_not_null',
		'1002'=>'parmas_null',
		'1003'=>'traceinfo_not_null',
		'1004'=>'mobile_deviceid_not_match',
		'1005'=>'token_not_null',
		'1006'=>'token_has_expired',
		'1007'=>'sign_error',


	     //后台全局配置相关 11000-12000
        '11001'=>'base_config_is_null',
        '11002'=>'get_base_config_fail',
        '11003'=>'type_param_error',

        //后台城市配置相关
        '11004'=>'get_country_list_fail',
        '11005'=>'city_list_is_null',
        '11006'=>'city_id_is_null',
        '11007'=>'location_param_error',
        '11008'=>'update_city_fail',
        '11010'=>'add_fail',
        '11009'=>'status_param_error',

        //后台内容管理
        '11011'=>'goods_list_is_null',
        '11012'=>'goods_id_is_null',
        '11013'=>'update_good_fail',
        '11014'=>'goods_is_reported',


		//后台用户提示 12000-12999
		'12001'=>'user_id_not_empty',
		'12002'=>'value_range_err',
		'12003'=>'user_forbid_success',
		'12004'=>'user_enable_success',
		'12005'=>'oprate_err',
		'12006'=>'user_not_exist',
	    
	    //公司通讯录
	    '15001'=>'contract_query_failure',
	    '15002'=>'contract_user_id_not_empty',
		


	),
	
);