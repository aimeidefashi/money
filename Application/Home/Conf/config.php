<?php
return array(
    //'配置项'=>'配置值'
   'SHOW_PAGE_TRACE'=>false,
     /* 模板相关配置 */
    'TMPL_PARSE_STRING' => array(
        '__STATIC__' => __ROOT__ . '/Public/static',
        '__ADDONS__' => __ROOT__ . '/Public/' . MODULE_NAME . '/Addons',
        '__IMG__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/images',
        '__CSS__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/css',
        '__JS__'     => __ROOT__ . '/Public/' . MODULE_NAME . '/js',
        'SHOW_PAGE_TRACE'=>True,
    ),

    'pay_config'=>array(
        'merchant_code' =>'2020510025',
        'service_type' =>'direct_pay',
        'interface_version' =>'V3.0',
        'sign_type' =>'MD5',
        'input_charset' =>'UTF-8',
        'notify_url' =>'http://www.tuojingtz.com/index.php/home/Pay/notifyurl',
        'return_url' =>'http://www.tuojingtz.com/index.php/home/Pay/returnurl',
        'show_url' => 'http://www.tuojingtz.com/index.php/home/Index/index'
    ),
    'zhongyun_pay'=>array(
        'pay_memberid' =>'11489',
        'pay_bankcode' =>'YeePayWx',
        'pay_notifyurl' =>'http://hljzxsp.com/Home/Pay/notify_url.php',
        'pay_callbackurl' =>'http://hljzxsp.com//Home/Pay/return_url.php',
        'Md5key' =>'os29EkDKntXQh3yXRdnlVfaMKcyPXk',
        'tjurl' =>'http://zf.cnzypay.com/Pay_Index.html',
    ),

    'stspage' => 'Home/User/memberinfo'

);