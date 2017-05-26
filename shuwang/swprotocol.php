<?php
class ShuwangProtocol
{
	private $appid = "";
	private $appsecret = "";
	private $urlapis = "";
	
	public function __construct($swconfig) {
		$this->appid = $swconfig['appid'];
		$this->appsecret = $swconfig['appsecret'];
		$this->urlapis = $swconfig['urlapis'];
	}
	
	public function callDirect(array $params) {
		// call
		return $this->_curlCall($params);
	}
	
	public function callMethod(array $params) {
		$params["appid"] = $this->appid;
		// sign
		$signature = $this->signRequest($params);
		$params["sign"] = $signature;
		// call
		return $this->_curlCall($params);
	}
	
	public function signRequest(array $params) {
		// sort string
		ksort($params);
		$sorted_str = "";
		foreach ($params as $key => $val) {
			if (!empty($val)) {
				$sorted_str .= "$key$val";
			}
		}
		//var_dump($sorted_str);
		// hmac md5
		$signature = hash_hmac("md5", $sorted_str, $this->appsecret);
		return strtoupper($signature);
	}
	
	private function _curlCall(array $params) {
		//var_dump($params);
		$json_string = json_encode($params);
		// call
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->urlapis);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json;charset=UTF-8',
			'Content-Length: ' . strlen($json_string))
		);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json_string);
		$output = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		//var_dump($httpcode, $output);
		curl_close($ch);
		return array(
			'httpcode' => $httpcode,
			'body' => $output
		);
	}
}
?>
