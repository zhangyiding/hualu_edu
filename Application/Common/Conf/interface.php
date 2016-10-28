<?php
/**
 * api接口方法配置
 */
return array(
	'GET_SMS_NUM_BY_MOBILE'=>'sms/getSendSmsNum',
	'SEND_SMS_CAPTCHA' =>'sms/sendsmscaptcha',
	'ADD_CARD' =>'payment/bankcard/addcard',
	'GET_CARD_LIST' =>'payment/bankcard/getcardlist',
	'OPERATE_CARD' =>'payment/bankcard/operatecard',
	'PAYMENT_PAY' =>'payment/pay',
	'PAY_GETPAYTOKEN' =>'payment/pay/getpaytoken',
	'PAY_NOTIFY' =>'payment/notify/wxpay',
		
);