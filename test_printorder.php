<?php
require_once('shuwang/swcloudprint.php');
require_once('shuwang/swprintutil.php');
header("Content-Type:text/html;charset=utf-8");

$swconfig = array(
	"appid" => $_POST["appid"],
	"appsecret" => $_POST["appsecret"],
	"urlapis" => $_POST["urlapis"]
);
//
$orderid = $_POST["orderid"]; //订单编号不能重复
$deviceid = $_POST["deviceid"]; //设备编号，参考设备铭牌SN
$order = array(
	"seq" => $_POST["seq"] ? $_POST["seq"] : 1,
	"shopname" => $_POST["shopname"],
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
	"username" => $_POST["username"],
	"userphone" => $_POST["userphone"],
	"useraddr" => $_POST["useraddr"],
	"qrcodeurl" => $_POST["qrcodeurl"],
	"qrcodetext" => $_POST["qrcodetext"],
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
