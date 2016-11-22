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

		//上传视频相关

        '10100' => '插入视频信息错误',
        '10101' => '保存视频失败',
        '10102' => '存在相同名称文件，请更改文件名称',
        '10103' => '该文件不合法，请重新检查',
        '10104' => '该文件格式不正确，请重新检查',

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



        /*
         * home相关
         *
         */
		

        '11001'=>'所属单位不存在',
        '11002'=>'Email地址不合法',
        '11003'=>'手机号码不合法',
        '11004'=>'注册失败，请联系管理员或者重试',
        '11005'=>'登录失败，用户名或密码错误',

	),
	
);