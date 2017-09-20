<?

function lang($lang,$txt){

	$lang = strtolower($lang);
	if(in_array($lang,array('us'))) $lang = 'en';
	if(strtolower($lang)=='en') return $txt;

	$arr = array(

		'24hour ATM Service'=>'24小時自助銀行服務中心',

		'Go map'=>'查看路线',

		'Home'=>'首页',

		'Share'=>'分享',

		'Group'=>'群组',

		'Call'=>'电话',

	);

	return $arr[$txt];

}



function lang_cn2en($lang,$txt){

	if(strtolower($lang)=='cn') return $txt;

	$arr = array(

		'即时资讯'=>'Infomations',

		'旅游指引'=>'Travels',

	);

	return $arr[$txt];

}