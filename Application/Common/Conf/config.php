<?php
//系统及固定配置
return array(
    //路由配置
    'URL_MODEL' =>2,
    'URL_CASE_INSENSITIVE' => true, //url支持大小写
    'VAR_MODULE' => 'ecm',     // 默认模块获取变量
    'VAR_CONTROLLER' => 'ecc',    // 默认控制器获取变量
    'VAR_ACTION' => 'eca',    // 默认操作获取变量
    'VAR_PATHINFO' => 'ecs',    // 兼容模式PATHINFO获取变量例如 ?s=/module/action/id/1 后面的参数取决于URL_PATHINFO_DEPR
    'VAR_TEMPLATE' => 'ect',    // 默认模板切换变量
    'MODULE_DENY_LIST' => array('Common', 'Runtime'), // 禁止访问的模块列表

    'MODULE_ALLOW_LIST' => array('Admin', 'Home'), //模块配置
    'DEFAULT_MODULE' => 'Home',

    'UN_AUTH_CONTROLLER' => array(),
    //'UN_AUTH_ACTION' => ['Admin/Login/index', 'Admin/City/getCityList', 'admin/role/index'],

    //session cookie配置
    'SESSION_AUTO_START' => true,    // 是否自动开启Session
    'SESSION_OPTIONS' => array(), // session 配置数组 支持type name id path expire domain 等参数
    'SESSION_TYPE' => '', // session hander类型 默认无需设置 除非扩展了session hander驱动
    'SESSION_PREFIX' => '', // session 前缀
    'COOKIE_DOMAIN' => '',      // Cookie有效域名
    'COOKIE_PATH' => '/',     // Cookie路径
    'COOKIE_PREFIX' => '',      // Cookie前缀 避免冲突

    //数据库配置
    'DB_FIELDS_CACHE' => true,

    //模版配置
   // 'TMPL_FILE_DEPR'=>'_',

    //日志配置
    'LOG_RECORD' => false,   // 默认不记录日志
    'LOG_TYPE' => 'File', // 日志记录类型 默认为文件方式
    'LOG_LEVEL' => 'EMERG,ALERT,CRIT,ERR',// 允许记录的日志级别
    'LOG_EXCEPTION_RECORD' => false,    // 是否记录异常信息日志

    //加载自定义配置
    'LOAD_EXT_CONFIG' => 'error,system,interface',

    //日志目录
    'TC_LOG_PATH' => '/tmp/etacarlog/',//上线需创建目录
    'TC_IMAGE_PATH' => '/opt/web/tmpimg/',

    //缓存前缀
    'CACHE_PREFIX' => 'hualu:edu:',

    'LANG_SWITCH_ON' => true,   // 开启语言包功能
    'LANG_AUTO_DETECT' => false, // 自动侦测语言 开启多语言功能后有效
    'DEFAULT_LANG' => 'zh-cn', // 自动侦测语言 开启多语言功能后有效
    'PAGE_SITE' => 20,           //分页展示条数
    'HOST_NAME' => 'http://' . $_SERVER['HTTP_HOST'],
    'SMS_SEND_NUM_PER_HOUR' => '3',    //手机登录短信验证码连续最大发送次数
    'SMS_CAPTCHA_DEBUG_CODE' => '99998',  //万能验证码
    'SMS_CATCH_TIME' => '300',

);