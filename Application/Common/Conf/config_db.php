<?php
//测试环境数据库配置及host配置

//Admin后台数据配置
$ETAGO_ADMIN_DB_MASTER_ETAGO='127.0.0.1';
$ETAGO_ADMIN_DB_SLAVE_ETAGO='127.0.0.1';
$ETAGO_ADMIN_DB_NAME='hualu';
$ETAGO_ADMIN_DB_USER='root';
$ETAGO_ADMIN_DB_PWD='root';

//
////redis缓存配置
//$redis['db1']['0']['host'] = $_SERVER['ETAGO_API_REDIS_MASTER'];
//$redis['db1']['0']['port'] = $_SERVER['ETAGO_API_REDIS_MASTER_PORT'];
//$redis['db1']['0']['isMaster'] = '1';
//$redis['db1']['1']['host'] = $_SERVER['ETAGO_API_REDIS_SLAVE'];
//$redis['db1']['1']['port'] = $_SERVER['ETAGO_API_REDIS_SLAVE_PORT'];
//$redis['db1']['1']['isMaster'] = '0';


$config_db =  array(

	
	'DB_DEPLOY_TYPE' => 1, //数据库主从支持
    'DB_RW_SEPARATE' => true, //读写分离
    'DB_TYPE' => 'mysql',
    'DB_HOST' => "$ETAGO_ADMIN_DB_MASTER_ETAGO,$ETAGO_ADMIN_DB_SLAVE_ETAGO",
    'DB_NAME' => $ETAGO_ADMIN_DB_NAME,
    'DB_USER' => $ETAGO_ADMIN_DB_USER,
    'DB_PWD' => $ETAGO_ADMIN_DB_PWD,
    'DB_PORT' => 3306,
    'DB_CHARSET' => 'UTF8',
    'DB_PREFIX' => '',

	'DB_ETAGO' => array(
		'DB_TYPE' => 'mysql',
		'DB_HOST' => "127.0.0.1",
		'DB_NAME' => 'hualu',
		'DB_USER' => 'root',
		'DB_PWD' => 'root',
		'DB_PORT' => 3306,
		'DB_CHARSET' => 'UTF8',
		'DB_DEPLOY_TYPE' => 0, //数据库主从支持
		'DB_RW_SEPARATE' => false, //读写分离
	),
		
 	'REDIS_CONFIG' => $redis,
);
//end

$config_others = array(
	'APP_ENV'=>$_SERVER['APP_ENV'],
	'ETASERVER_API_URL'=>$_SERVER['ETASERVER_API_URL'],//测试环境api地址
);

return array_merge($config_db,$config_others);


