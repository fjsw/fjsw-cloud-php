<?php
require_once('shuwang/swcloudprint.php');
header("Content-Type:text/html;charset=utf-8");

$swconfig = array(
	"appid" => "173",
	"appsecret" => "",
	"urlapis" => "http://api.test.shuwang.info/gateway/rest"
);
//
$printTitle = "测试打印";
$printStream = "测试打印1\nse测试打印内容tst\n\n";
$orderid = '1234567892'; //订单编号不能重复
//
$swprint = new ShuwangCloudPrint($swconfig);
$result = $swprint->cloudPrint($orderid, 12039, $printTitle, $printStream);
//
//var_dump($result);
$httpcode = $result['httpcode'];
if ($httpcode >=200 && $httpcode <=299) {
	echo "提交打印成功";
} else {
	echo "提交打印失败,".$result['body'];
}
