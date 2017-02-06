<?php
require_once('swprotocol.php');

class ShuwangCloudPrint
{
	private $appid = "";
	private $swprotocol = null;
	
	public function __construct($swconfig) {
		$this->appid = $swconfig['appid'];
		$this->swprotocol = new ShuwangProtocol($swconfig);
	}
	
	public function cloudPrint($orderid, $devid, $printTitle, $printStream) {
		$params = array(
			'method' => 'print.cloud.text',
			'appid' => $this->appid,
			'timestamp' => time(),
			'printid' => $orderid,
			'devid' => $devid,
			'title' => $printTitle
		);
		$signature = $this->swprotocol->signRequest($params);
		$params['sign'] = $signature;
		$params['printstream'] = $printStream;
		//
		return $this->swprotocol->callDirect($params);
	}
}
?>
