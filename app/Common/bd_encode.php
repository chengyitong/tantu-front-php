<?php
/*
Class Encode PHP v1.0
数字类型加密 v1.0
By william 2013.12.20
private $arrTab [array] 键值匹配,固定无需修改
public $keyInd [int=20] 键值起始位置,默认20
引用: [支持正负数及小数]
	$q = new Encode();
	$q->keyInd = 20;
	测试function:
	$q->EncodeClass([int=0]);
	加密function:
	$q->analyseData([int=0]);
	解密function:
	$q->receiveData([string]);
*/
//  头文件   utf-8格式化
header("Content-Type: text/html; charset=utf-8");
class Encode
{
	private $arrTab = array(25,23,113,132,37,66,69,55,50,114,
		 130,101,72,37,101,54,116,148,53,66,
		 20,99,81,23,82,55,152,97,81,135,
		 86,117,17,135,149,56,149,23,20,67,
		 38,21,56,25,39,121,132,49,19,103,
		 115,37,38,118,21,100,145,85,57,99,
		 22,133,120,21,147,83,100,130,67,135,
		 24,25,130,18,117,33,36,84,83,65,
		 131,101,67,37,56,54,118,148,117,116,
		 70,117,33,135,145,56,117,23,36,67,
		 82,21,50,25,55,121,131,49,72,55,
		 54,37,70,118,53,100,146,85,25,100,
		 129,25,132,18,101,33,68,84,67,65,
		 41,23,97,132,36,66,104,55,55,114,
		 116,99,87,23,89,55,120,97,82,129,
		 147,49,19,149,34,71,56,36,96,1,
		 7,66,132,69,115,87,32,112,144,34,
		 135,83,21,99,132,66,49,88,104,115,
		 134,86,99,117,147,64,88,84,38,6,
		 21,119,19,146,101,129,16,56,70,117,
		 146,96,98,132,83,73,8,116,48,4,
		 19,120,131,149,145,7,69,83,101,121,
		 128,153,7,0,49,65,50,118,97,114,
		 96,100,4,149,83,21,151,22,114,49,
		 40,33,20,37,85,64,56,148,129,71,
		 131,21,41,22,65,41,132,89,50,67,
		 82,21,50,25,55,121,131,49,72,55);
	public $keyInd = 20;
	public function EncodeClass($input=0) {
		echo '输入: '.$input.'<br />';
		//  加密
		$encode = $this->analyseData($input);
		echo '加密: '.$encode.'<br />';
		//  解密
		$decode = $this->receiveData($encode);
		echo '解密: '.$decode.'<br />';
	}
	//  补齐0
	private function addZero($str,$bit){
		$num = $bit - strlen($str) % $bit;
		$str = strval($str);
		if ($num > 0 && strlen($str) != $bit) {
			for ($i=0; $i<$num; $i++) {
				$str = "0".$str;
			}
		}
		return $str;
	}
	//  加密
	public function analyseData($old=0){
		$markInd = $this->keyInd;
		$str = strval($old);
		if($markInd+strlen($str) > count($this->arrTab)) return 'Err:ToLongKeyInd';
		$totalStr = "";
		$sign = "0";
		if (substr($str,0,1) == "-") {
			$sign = "1";
			$str = substr($str,1,strlen($str));
		}
		$arr = explode(".",$str);
		$arrlen = count($arr);
		$arr[$arrlen] = $sign;
		for ($i=0;$i<count($arr);$i++) {
			$totalStr .=  $this->formatData($arr[$i]);
			if ($i == 0) {
				$str = $this->formatData(strlen($totalStr));
			}
		}
		$totalStr .=  $str;
		//  输出加密
		return $totalStr;
	}
	private function formatData($old) {
		$markInd = $this->keyInd;
		$i = 0;
		$str = strval($old);
		$arr = '';
		$totalStr = "";
		$sum = 0;
		for ($i=0; $i<strlen($str); $i++) {
			$sum +=  substr($str,$i,1)*1;
		}
		$ascsum = decbin($sum);//把10进制转换为2进制
		$str = $this->addZero($ascsum,8);
		$ascold = decbin($old);//把10进制转换为2进制  !!!!!!!!!!!!!
		$totalStr = $this->addZero($ascold,8) . $str;
		$sum = strlen($totalStr) - 5;
		$totalStr = substr($totalStr,$sum,5) . substr($totalStr,0,$sum);
		$arr = '';
		$arr_i = 0;
		for ($i=0; $i<strlen($totalStr)/8; $i++) {
			$str = substr($totalStr,$i * 8,8);
			$sum = bindec($str)*1;//把2进制转换为10进制
			//$sum = $this->bin2asc($str);//parseInt(str,2);//把2进制转换为10进制
			$arr[$arr_i]=$sum;
			$arr_i++;
		}
		$sum = 0;
		$totalStr = "";
		for ($i = 0; $i < count($arr); $i++) {
			if ($arr[$i] - $this->arrTab[$markInd + $i] < 0) {
				$sum = 256 + $arr[$i] - $this->arrTab[$markInd + $i];
			} else {
				$sum = $arr[$i] - $this->arrTab[$markInd + $i];
			}
			$ascsum = dechex($sum);//十进制转十六进制
			$totalStr .=  $this->addZero($ascsum,2);
		}
		return $totalStr;
	}
	//  解密
	public function receiveData($totalStr='') {
		if($totalStr=='') return 'Err:NoParam';
		$markInd = $this->keyInd;
		$type = $this->recodeData(substr($totalStr,strlen($totalStr) - 4,4),$markInd);
		if($type=='err') return 'Err:NoSign';
		$sign = $this->recodeData(substr($totalStr,strlen($totalStr) - 8,4),$markInd);
		$s = $this->recodeData(substr($totalStr,0,$type*1),$markInd);
		$e = $this->recodeData(substr($totalStr,$type,strlen($totalStr) - 8 - $type*1),$markInd);
		if ($e == "0") {
			$totalStr = $s;
		} else {
			$totalStr = $s . "." . $e;
		}
		if ($sign == "1") {
			$totalStr = "-" . $totalStr;
		}
		return $totalStr;
	}
	private function recodeData($old) {
		$markInd = $this->keyInd;
		$sum = 0;
		$totalStr = "";
		$i = 0;
		$arr = '';
		$arr_i=0;
		for ($i=0; $i<strlen(strval($old)); $i+=2) {
			$arr[$arr_i]=substr($old,$i, 2);
			$arr_i++;
			$sum = hexdec($arr[$i/2])*1;//把16进制转换为10进制
			//$sum = parseInt($arr[$i / 2],16);//把16进制转换为10进制
			if ($sum + $this->arrTab[$markInd + $i / 2] > 255) {
				$arr[$i / 2] = $sum + $this->arrTab[$markInd + $i / 2] - 256;
			} else {
				$arr[$i / 2] = $sum + $this->arrTab[$markInd + $i / 2];
			}
			$totalStr .=  $this->addZero(decbin($arr[$i / 2]),8);
		}
		$totalStr = substr($totalStr,5,strlen($totalStr) - 5) . substr($totalStr,0,5);
		$str = "";
		$result = "";
		$sum = 0;
		$endNum = bindec(substr($totalStr,strlen($totalStr) - 8,8));//校检数  二进制转十进制
		$resultNum = bindec(substr($totalStr,0,strlen($totalStr) - 8));//原始数  二进制转十进制
		$totalStr = strval($resultNum);
		for ($i=0; $i<strlen($totalStr); $i++) {
			$sum +=  substr($totalStr,$i,1)*1;
		}
		$resultNum = $sum == $endNum ? $resultNum : 'err';
		return $resultNum;
	}
	//  三方  二进制、十进制转换
	public function asc2bin($temp){
		$len = strlen($temp);
		for($i=0;$i<$len;$i++){
			$data .= sprintf("%08b",ord(substr($temp,$i,1)));
		}
		return $data;
	}
	public function bin2asc($temp){
		$len = strlen($temp);
		for($i=0;$i<($len/8);$i++){
			$data .= chr(intval(substr($temp,$i*8,8),2));
		}
		return $data;
	}
}