<?php
function sms_sent_vcode($auth,$mobile,$code){
	if(session('sms_auth')!=$auth){
		$data['status'] = 0;
		$data['info'] = 'Auth error!';
		die(json_output($data));
	}
	$url = 'http://api.bindow.cn/sms/sent?sn=sms_sdk2118&sp='.md5('888888').'&tel='.$mobile.'&msg=您的验证码是：'.$code.'&sign=探图网';
	$r = curl($url);
	//$data['url'] = $url;
	//$data['info'] = $r;
	$data['status'] = 1;
	return $data;
}

function qcloudsms_sent_vcode($auth,$mobile,$code){
	if(session('sms_auth')!=$auth){
		$data['status'] = 0;
		$data['info'] = 'Auth error!';
		die(json_output($data));
	}
	require_once "bd_qcloud_sms.php";
	$singleSender = new SmsSingleSender("1400023536", "e973cd36bc99fc22347f64d70462369d");
    $result = $singleSender->send(0, "86", $mobile, "【比高视觉】您的验证码是：".$code, "", "");
    $rsp = json_decode($result,1);
    $data['status'] = 1;
    $data['cback'] = $rsp;
    return $data;
}