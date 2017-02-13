<?php
class ShuwangPrintUtil
{
	protected static $FONTZOOM_WIDTH_HIGHT = 0x11; //拉宽一倍拉高一倍
	//protected static $FONTZOOM_WIDTH = 0x10; //拉宽一倍
	protected static $FONTZOOM_HIGHT = 0x01; //拉高一倍
	
	/**
	 * 将订单格式化为打印流
	 * @param $order 订单内容
	 * @return 打印流
	 */
	public static function formOrderPrintStream(array $order) {
		$header = " #".$order["seq"]." 云订单 ";
		$shopname = $order["shopname"];
		$ordertime = $order["ordertime"];
		$totalprice = $order["totalprice"];
		$username = $order["username"];
		$userphone = $order["userphone"];
		$useraddr = $order["useraddr"];
		$qrcodeurl = $order["qrcodeurl"];
		$qrcodetext = $order["qrcodetext"];
		// form order print stream
		$printStream = "**********".$header."**********\n";
		$printStream .= "店铺名称: ".$shopname."\n";
		$printStream .= "下单时间: ".$ordertime."\n";
		$printStream .= "--------------------------------\n";
		// orders
		foreach ($order["items"] as $item) {
			$linestr = $item["name"] ."\t\tx".$item["count"]."  ".$item["price"];
			$printStream .= self::formCustomFontLine($linestr, self::$FONTZOOM_HIGHT);
		}
		$printStream .= "--------------------------------\n";
		$totalPriceStr = "总计: ￥".$totalprice;
		$printStream .= self::formCustomFontLine($totalPriceStr, self::$FONTZOOM_HIGHT);
		$printStream .= self::formCustomFontLine($useraddr, self::$FONTZOOM_WIDTH_HIGHT);
		$userNamePhone = $username . "  " . $userphone;
		$printStream .= self::formCustomFontLine($userNamePhone, self::$FONTZOOM_HIGHT);
		//$printStream .= "************ #".$order["seq"]." 完 ************\n";
		// qrcode
		if ($qrcodeurl) {
			$printStream .= self::formQrcodeSteam($qrcodeurl);
			if ($qrcodetext) {
				$printStream .= "\t" . $qrcodetext . "\n";
			}
		}
		// 
		return $printStream . "\n\n";
	}
	
	/**
	 * 将内容按指定放大字号打印一行
	 * @param $str 打印内容
	 * @param $fontzoom 字体放大
	 * @return 打印流
	 */
	protected static function formCustomFontLine($str, $fontzoom) {
		$customfontBytes = chr(0x1D);
		$customfontBytes .= chr(0x21);
		$customfontBytes .= chr($fontzoom);
		$customfontBytes .= $str;
		$customfontBytes .= chr(0x0A);
		$customfontBytes .= chr(0x1B);
		$customfontBytes .= chr(0x40);
		// 
		return $customfontBytes;
	}
	
	/**
	 * 打印并向前走纸$padline个点位
	 * 走纸24个点位正好是一个物理行\n
	 * @param $padline 走纸点位个数
	 * @return 打印流
	 */
	protected static function formLinePadding($padline) {
		$customfontBytes = chr(0x1B);
		$customfontBytes .= chr(0x4A);
		$customfontBytes .= chr($padline);
		// 
		return $customfontBytes;
	}
	
	/**
	 * 打印二维码
	 * @param $url 二维码地址
	 * @return 打印流
	 */
	protected static function formQrcodeSteam($url) {
		$ver = 0;// 二维码版本(建议填0自动选择)
		$amp = 6;// 放大倍数(建议填6)
		$lever = 0;// 二维码纠错等级0~3，一般填0
		//
		$customfontBytes = chr(0x1D);
		$customfontBytes .= chr(0x6B);
		$customfontBytes .= chr(0x5D);
		$customfontBytes .= chr($ver);
		$customfontBytes .= chr($amp);
		$customfontBytes .= chr($lever);
		$customfontBytes .= chr(strlen($url));
		$customfontBytes .= chr(0x00);
		$customfontBytes .= $url;
		// 
		return $customfontBytes;
	}
}
?>
