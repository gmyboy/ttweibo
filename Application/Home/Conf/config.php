<?php
return array(
	//    'DB_TYPE' => 'mysql',
		// 'DB_HOST' => 'qdm176236675.my3w.com',
		// 'DB_NAME' => 'qdm176236675_db',
		// 'DB_USER' => 'qdm176236675',
		// 'DB_PWD' => 'profe13956672835',
	
    	'DB_TYPE' => 'mysql',
		'DB_HOST' => 'localhost',
		'DB_NAME' => 'ttweibo',
		'DB_USER' => 'root',
		'DB_PWD' => '',
		'DB_PORT' => '3306',
		'DB_PREFIX' => 'tt_',

		'TMPL_FILE_DEPR' => '_',//view/Index_index
		'URL_HTML_SUFFIX'=>'',//伪静态，去除访问url的html、php后缀
		//'ACTION_SUFFIX' => 'ACTION'//操作方法后缀
		//'ACTION_BIND_CLASS' => true//操作方法绑定到类
		'URL_MODEL' => '2',//配置url路由模式
			//URL路由配置
		'URL_ROUTER_ON' => true,	//开启路由功能
		'URL_ROUTE_RULES' => array(
			':id\d' => 'User/index',
			'follow/:uid\d' => array('User/followList', 'type=1'),
			'fans/:uid\d' => array('User/followList', 'type=0')
		),
		// 'TMPL_PARSE_STRING'  =>array(//自定义模板引擎替换规则
		//      '__JS__'     => '/Public/Js', // 增加新的JS类库路径替换规则
		//      '__CSS__'    => '/Public/Css', // 增加新的css类库路径替换规则
		//      '__UPLOAD__' => '/Uploads' // 增加新的上传路径替换规则
		// ),

		'WEBNAME' => '天天微博',
		'ENCTYPTION_KEY' => 'www.gmyboy.com',//用于异位或加密的KEY
		'AUTO_LOGIN_TIME' => time() + 3600 * 24 * 7,	//自动登录保存时间  默认一个星期
		//图片上传
		'UPLOAD_MAX_SIZE' => 2000000,	//最大上传大小
		'UPLOAD_PATH' => './Uploads/',	//文件上传保存路径
		'UPLOAD_EXTS' => array('jpg', 'jpeg', 'gif', 'png')	//允许上传文件的后缀
);