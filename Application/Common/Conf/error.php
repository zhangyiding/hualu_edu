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


		


	),
	
);