<?php
require_once('shuwang/swcloudprint.php');
require_once('shuwang/swprintutil.php');
header("Content-Type:text/html;charset=utf-8");

$swconfig = array(
	"appid" => "173",
	"appsecret" => "",
	"urlapis" => "http://api.test.shuwang.info/gateway/rest"
);
//
$orderid = '1234567899'; //订单编号不能重复
$deviceid = 10693; //设备编号，参考设备铭牌SN
$order = array(
	"seq" => 9,
	"shopname" => "A咖果派",
	"ordertime" => "2017-02-13 13:51",
	"items" => array(
		array(
			"name" => "鳕鱼饭套餐",
			"count" => 2,
			"price" => 36,
		),
		array(
			"name" => "鸡排饭套餐",
			"count" => 1,
			"price" => 16.5,
		),
		array(
			"name" => "迎新年送红包",
			"count" => 1,
			"price" => -3,
		),
	),
	"totalprice" => 49.5,
	"username" => "陈先生",
	"userphone" => "180xxxx6326",
	"useraddr" => "海西科技园高新大道启迪之星52区",
	"qrcodeurl" => "http://wx.shuwang.info/",
	"qrcodetext" => "数网订单机提供服务",
);
//
$printTitle = "云订单#".$order["seq"];
$printStream = ShuwangPrintUtil::formOrderPrintStream($order);
$swprint = new ShuwangCloudPrint($swconfig);
$result = $swprint->cloudPrint($orderid, $deviceid, $printTitle, $printStream);
//
//var_dump($result);
$httpcode = $result['httpcode'];
if ($httpcode >=200 && $httpcode <=299) {
	echo "提交打印成功";
} else {
	echo "提交打印失败,".$result['body'];
}
