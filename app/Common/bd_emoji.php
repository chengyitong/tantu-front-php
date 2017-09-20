<?php
//  Emoji
function str2emoji($content){
	//
	// 把iOS表情键盘输出的特殊符号转码为可都，可存入Text类型数据库的标签
	//
	$emoji = array_emoji();
	// 转为16进制 26233132383531363b
	$hex_content = bin2hex($content);
	$pattern_emoji = array();
	foreach($emoji as $k => $v) {
		$pattern_emoji[] = "/{$k}/";
	}
	// 把emoji表情替换为标签的16进制 5b453431355d
	return preg_replace_callback($pattern_emoji, 'replace_emoji', $hex_content);
}
function emoji2str($encoded_hex_content){
	return hex2bin($encoded_hex_content);
}
function hex2bin($h)
{
    if (!is_string($h)) return null;
    $r='';
    for ($a=0; $a<strlen($h); $a+=2) { 
        $r.=chr(hexdec($h{$a}.$h{($a+1)})); 
    }
    return $r;
}  
function replace_emoji($match) {
    $emoji = array_emoji();
    return $emoji[$match[0]];
}
function array_emoji(){
	return array (
		'26233132383531363b' => '5b453431355d',
		'26233132383532323b' => '5b453035365d',
		'26233132383531353b' => '5b453035375d',
		'2623393738363b' => '5b453431345d',
		'26233132383532313b' => '5b453430355d',
		'26233132383532353b' => '5b453130365d',
		'26233132383533363b' => '5b453431385d',
		'26233132383533383b' => '5b453431375d',
		'26233132383536333b' => '5b453430645d',
		'26233132383532343b' => '5b453430615d',
		'26233132383531333b' => '5b453430345d',
		'26233132383534303b' => '5b453130355d',
		'26233132383534313b' => '5b453430395d',
		'26233132383533303b' => '5b453430655d',
		'26233132383532373b' => '5b453430325d',
		'26233132383533313b' => '5b453130385d',
		'26233132383533323b' => '5b453430335d',
		'26233132383534323b' => '5b453035385d',
		'26233132383533343b' => '5b453430375d',
		'26233132383534393b' => '5b453430315d',
		'26233132383536303b' => '5b453430665d',
		'26233132383535323b' => '5b453430625d',
		'26233132383534373b' => '5b453430365d',
		'26233132383534363b' => '5b453431335d',
		'26233132383535373b' => '5b453431315d',
		'26233132383531343b' => '5b453431325d',
		'26233132383536323b' => '5b453431305d',
		'26233132383536313b' => '5b453130375d',
		'26233132383534343b' => '5b453035395d',
		'26233132383534353b' => '5b453431365d',
		'26233132383535343b' => '5b453430385d',
		'26233132383536373b' => '5b453430635d',
		'26233132383132373b' => '5b453131615d',
		'26233132383132353b' => '5b453130635d',
		'26233132383135353b' => '5b453332635d',
		'26233132383135333b' => '5b453332615d',
		'26233132383135363b' => '5b453332645d',
		'26233132383135313b' => '5b453332385d',
		'26233132383135343b' => '5b453332625d',
		'262331303038343b' => '5b453032325d',
		'26233132383134383b' => '5b453032335d',
		'26233132383134373b' => '5b453332375d',
		'26233132383135323b' => '5b453332395d',
		'262331303032343b' => '5b453332655d',
		'26233132373737353b' => '5b453332665d',
		'26233132383136323b' => '5b453333345d',
		'262331303036393b' => '5b453032315d',
		'262331303036383b' => '5b453032305d',
		'26233132383136343b' => '5b453133635d',
		'26233132383136383b' => '5b453333305d',
		'26233132383136363b' => '5b453333315d',
		'26233132373932363b' => '5b453332365d',
		'26233132373932353b' => '5b453033655d',
		'26233132383239333b' => '5b453131645d',
		'26233132383136393b' => '5b453035615d',
		'26233132383037373b' => '5b453030655d',
		'26233132383037383b' => '5b453432315d',
		'26233132383037363b' => '5b453432305d',
		'26233132383037343b' => '5b453030645d',
		'2623393939343b' => '5b453031305d',
		'2623393939363b' => '5b453031315d',
		'26233132383037353b' => '5b453431655d',
		'2623393939353b' => '5b453031325d',
		'26233132383038303b' => '5b453432325d',
		'26233132383037303b' => '5b453232655d',
		'26233132383037313b' => '5b453232665d',
		'26233132383037333b' => '5b453233315d',
		'26233132383037323b' => '5b453233305d',
		'26233132383538383b' => '5b453432375d',
		'26233132383539313b' => '5b453431645d',
		'2623393735373b' => '5b453030665d',
		'26233132383037393b' => '5b453431665d',
		'26233132383137303b' => '5b453134635d',
		'26233132383639343b' => '5b453230315d',
		'26233132373933393b' => '5b453131355d',
		'26233132383130373b' => '5b453432385d',
		'26233132383133313b' => '5b453531665d',
		'26233132383131313b' => '5b453432395d',
		'26233132383538323b' => '5b453432345d',
		'26233132383538313b' => '5b453432335d',
		'26233132383132393b' => '5b453235335d',
		'26233132383538333b' => '5b453432365d',
		'26233132383134333b' => '5b453131315d',
		'26233132383134353b' => '5b453432355d',
		'26233132383133343b' => '5b453331655d',
		'26233132383133353b' => '5b453331665d',
		'26233132383133333b' => '5b453331645d',
		'26233132383130323b' => '5b453030315d',
		'26233132383130333b' => '5b453030325d',
		'26233132383130353b' => '5b453030355d',
		'26233132383130343b' => '5b453030345d',
		'26233132383131383b' => '5b453531615d',
		'26233132383131373b' => '5b453531395d',
		'26233132383131363b' => '5b453531385d',
		'26233132383131333b' => '5b453531355d',
		'26233132383131343b' => '5b453531365d',
		'26233132383131353b' => '5b453531375d',
		'26233132383131393b' => '5b453531625d',
		'26233132383131303b' => '5b453135325d',
		'26233132383132343b' => '5b453034655d',
		'26233132383132303b' => '5b453531635d',
		'26233132383133303b' => '5b453531655d',
		'26233132383132383b' => '5b453131635d',
		'26233132383039393b' => '5b453533365d',
		'26233132383133393b' => '5b453030335d',
		'26233132383036383b' => '5b453431635d',
		'26233132383036363b' => '5b453431625d',
		'26233132383036343b' => '5b453431395d',
		'26233132383036373b' => '5b453431615d',
		'2623393732383b' => '5b453034615d',
		'2623393734383b' => '5b453034625d',
		'2623393732393b' => '5b453034395d',
		'2623393932343b' => '5b453034385d',
		'26233132373736393b' => '5b453034635d',
		'2623393838393b' => '5b453133645d',
		'26233132373734343b' => '5b453434335d',
		'26233132373735343b' => '5b453433655d',
		'26233132383034393b' => '5b453034665d',
		'26233132383035343b' => '5b453035325d',
		'26233132383034353b' => '5b453035335d',
		'26233132383035373b' => '5b453532345d',
		'26233132383034383b' => '5b453532635d',
		'26233132383035383b' => '5b453532615d',
		'26233132383035363b' => '5b453533315d',
		'26233132383034373b' => '5b453035305d',
		'26233132383034303b' => '5b453532375d',
		'26233132383035393b' => '5b453035315d',
		'26233132383035353b' => '5b453130625d',
		'26233132383034363b' => '5b453532625d',
		'26233132383032333b' => '5b453532665d',
		'26233132383035333b' => '5b453532385d',
		'26233132383031383b' => '5b453031615d',
		'26233132383035323b' => '5b453133345d',
		'26233132383031343b' => '5b453533305d',
		'26233132383034333b' => '5b453532395d',
		'26233132383031373b' => '5b453532365d',
		'26233132383032343b' => '5b453532645d',
		'26233132383031333b' => '5b453532315d',
		'26233132383033383b' => '5b453532335d',
		'26233132383033363b' => '5b453532655d',
		'26233132383032303b' => '5b453035355d',
		'26233132383033393b' => '5b453532355d',
		'26233132383032373b' => '5b453130615d',
		'26233132383032353b' => '5b453130395d',
		'26233132383033323b' => '5b453532325d',
		'26233132383033313b' => '5b453031395d',
		'26233132383035313b' => '5b453035345d',
		'26233132383034343b' => '5b453532305d',
		'26233132383134343b' => '5b453330365d',
		'26233132373830303b' => '5b453033305d',
		'26233132373739393b' => '5b453330345d',
		'26233132373830383b' => '5b453131305d',
		'26233132373830313b' => '5b453033325d',
		'26233132373830333b' => '5b453330355d',
		'26233132373830323b' => '5b453330335d',
		'26233132373830393b' => '5b453131385d',
		'26233132373831313b' => '5b453434375d',
		'26233132373831303b' => '5b453131395d',
		'26233132373739363b' => '5b453330375d',
		'26233132373739373b' => '5b453330385d',
		'26233132373830363b' => '5b453434345d',
		'26233132383032363b' => '5b453434315d',
		'26233132373838353b' => '5b453433365d',
		'26233132383135373b' => '5b453433375d',
		'26233132373838363b' => '5b453433385d',
		'26233132373839303b' => '5b453433615d',
		'26233132373839313b' => '5b453433395d',
		'26233132373838373b' => '5b453433625d',
		'26233132373837383b' => '5b453131375d',
		'26233132373837393b' => '5b453434305d',
		'26233132373838383b' => '5b453434325d',
		'26233132373838393b' => '5b453434365d',
		'26233132373837353b' => '5b453434355d',
		'26233132383132333b' => '5b453131625d',
		'26233132373837373b' => '5b453434385d',
		'26233132373837363b' => '5b453033335d',
		'26233132373837333b' => '5b453131325d',
		'26233132383237363b' => '5b453332355d',
		'26233132373838313b' => '5b453331325d',
		'26233132373838303b' => '5b453331305d',
		'26233132383139313b' => '5b453132365d',
		'26233132383139323b' => '5b453132375d',
		'26233132383234373b' => '5b453030385d',
		'26233132373930393b' => '5b453033645d',
		'26233132383138373b' => '5b453030635d',
		'26233132383235303b' => '5b453132615d',
		'26233132383234313b' => '5b453030615d',
		'26233132383232343b' => '5b453030625d',
		'2623393734323b' => '5b453030395d',
		'26233132383138393b' => '5b453331365d',
		'26233132383235323b' => '5b453132395d',
		'26233132383236363b' => '5b453134315d',
		'26233132383232363b' => '5b453134325d',
		'26233132383232373b' => '5b453331375d',
		'26233132383235313b' => '5b453132385d',
		'26233132383232353b' => '5b453134625d',
		'262331303137353b' => '5b453231315d',
		'26233132383236393b' => '5b453131345d',
		'26233132383237353b' => '5b453134355d',
		'26233132383237343b' => '5b453134345d',
		'26233132383237333b' => '5b453033665d',
		'2623393938363b' => '5b453331335d',
		'26233132383239363b' => '5b453131365d',
		'26233132383136313b' => '5b453130665d',
		'26233132383234323b' => '5b453130345d',
		'26233132383233333b' => '5b453130335d',
		'26233132383233353b' => '5b453130315d',
		'26233132383233383b' => '5b453130325d',
		'26233132383730343b' => '5b453133665d',
		'26233132383730313b' => '5b453134305d',
		'26233132383138363b' => '5b453131665d',
		'26233132383137363b' => '5b453132665d',
		'26233132383330353b' => '5b453033315d',
		'26233132383638343b' => '5b453330655d',
		'26233132383136333b' => '5b453331315d',
		'26233132383239393b' => '5b453131335d',
		'26233132383133383b' => '5b453330665d',
		'26233132383133373b' => '5b453133625d',
		'26233132373934343b' => '5b453432625d',
		'26233132373933363b' => '5b453432615d',
		'2623393931373b' => '5b453031385d',
		'2623393931383b' => '5b453031365d',
		'26233132373933343b' => '5b453031355d',
		'2623393937313b' => '5b453031345d',
		'26233132373932313b' => '5b453432635d',
		'26233132373934363b' => '5b453432645d',
		'26233132373934303b' => '5b453031375d',
		'26233132373933353b' => '5b453031335d',
		'2623393832343b' => '5b453230655d',
		'2623393832393b' => '5b453230635d',
		'2623393832373b' => '5b453230665d',
		'2623393833303b' => '5b453230645d',
		'26233132373934323b' => '5b453133315d',
		'26233132383132363b' => '5b453132625d',
		'26233132373931393b' => '5b453133305d',
		'26233132363938303b' => '5b453132645d',
		'26233132373931363b' => '5b453332345d',
		'26233132383232313b' => '5b453330315d',
		'26233132383231343b' => '5b453134385d',
		'26233132373931323b' => '5b453530325d',
		'26233132373930383b' => '5b453033635d',
		'26233132373931313b' => '5b453330615d',
		'26233132373933303b' => '5b453034325d',
		'26233132373932373b' => '5b453034305d',
		'26233132373932383b' => '5b453034315d',
		'262331323334393b' => '5b453132635d',
		'26233132383039353b' => '5b453030375d',
		'26233132383039373b' => '5b453331615d',
		'26233132383039363b' => '5b453133655d',
		'26233132383039383b' => '5b453331625d',
		'26233132383038353b' => '5b453030365d',
		'26233132383038343b' => '5b453330325d',
		'26233132383038373b' => '5b453331395d',
		'26233132383038383b' => '5b453332315d',
		'26233132383038393b' => '5b453332325d',
		'26233132373837323b' => '5b453331345d',
		'26233132373931333b' => '5b453530335d',
		'26233132383038313b' => '5b453130655d',
		'26233132383038323b' => '5b453331385d',
		'26233132373734363b' => '5b453433635d',
		'26233132383138383b' => '5b453131655d',
		'26233132383039323b' => '5b453332335d',
		'26233132383133323b' => '5b453331635d',
		'26233132383134313b' => '5b453033345d',
		'26233132383134323b' => '5b453033355d',
		'2623393734393b' => '5b453034355d',
		'26233132373836313b' => '5b453333385d',
		'26233132373836363b' => '5b453034375d',
		'26233132373836373b' => '5b453330635d',
		'26233132373836343b' => '5b453034345d',
		'26233132373836323b' => '5b453330625d',
		'26233132373836303b' => '5b453034335d',
		'26233132373832383b' => '5b453132305d',
		'26233132373833393b' => '5b453333625d',
		'26233132373833373b' => '5b453333665d',
		'26233132373833353b' => '5b453334315d',
		'26233132373835373b' => '5b453334635d',
		'26233132373834333b' => '5b453334345d',
		'26233132373833333b' => '5b453334325d',
		'26233132373833323b' => '5b453333645d',
		'26233132373833343b' => '5b453333655d',
		'26233132373833363b' => '5b453334305d',
		'26233132373835383b' => '5b453334645d',
		'26233132373833383b' => '5b453333395d',
		'26233132373835393b' => '5b453134375d',
		'26233132373834323b' => '5b453334335d',
		'26233132373834313b' => '5b453333635d',
		'26233132373834363b' => '5b453333615d',
		'26233132373834373b' => '5b453433665d',
		'26233132373837343b' => '5b453334625d',
		'26233132373835363b' => '5b453034365d',
		'26233132373832323b' => '5b453334355d',
		'26233132373831383b' => '5b453334365d',
		'26233132373831373b' => '5b453334385d',
		'26233132373832373b' => '5b453334375d',
		'26233132373831343b' => '5b453334615d',
		'26233132373831333b' => '5b453334395d',
		'26233132373936383b' => '5b453033365d',
		'26233132373937393b' => '5b453135375d',
		'26233132373937303b' => '5b453033385d',
		'26233132373937313b' => '5b453135335d',
		'26233132373937333b' => '5b453135355d',
		'26233132373937343b' => '5b453134645d',
		'26233132373937383b' => '5b453135365d',
		'26233132373937373b' => '5b453530315d',
		'26233132373937363b' => '5b453135385d',
		'26233132383134363b' => '5b453433645d',
		'2623393936323b' => '5b453033375d',
		'26233132373938303b' => '5b453530345d',
		'26233132373735313b' => '5b453434615d',
		'26233132373735303b' => '5b453134365d',
		'26233132373937353b' => '5b453135345d',
		'26233132373938333b' => '5b453530355d',
		'26233132373938343b' => '5b453530365d',
		'2623393937383b' => '5b453132325d',
		'26233132373938313b' => '5b453530385d',
		'26233132383530383b' => '5b453530395d',
		'26233132383530373b' => '5b453033625d',
		'26233132373734383b' => '5b453034645d',
		'26233132373734393b' => '5b453434395d',
		'26233132373734373b' => '5b453434625d',
		'26233132383530393b' => '5b453531645d',
		'26233132373735323b' => '5b453434635d',
		'26233132373930353b' => '5b453132345d',
		'2623393937303b' => '5b453132315d',
		'26233132373930363b' => '5b453433335d',
		'26233132383637343b' => '5b453230325d',
		'26233132383637363b' => '5b453133355d',
		'2623393937333b' => '5b453031635d',
		'2623393939323b' => '5b453031645d',
		'26233132383634303b' => '5b453130645d',
		'26233132383639303b' => '5b453133365d',
		'26233132383636353b' => '5b453432655d',
		'26233132383636333b' => '5b453031625d',
		'26233132383636313b' => '5b453135615d',
		'26233132383635323b' => '5b453135395d',
		'26233132383635393b' => '5b453433325d',
		'26233132383635383b' => '5b453433305d',
		'26233132383635373b' => '5b453433315d',
		'26233132383636363b' => '5b453432665d',
		'26233132383634333b' => '5b453031655d',
		'26233132383634393b' => '5b453033395d',
		'26233132383634343b' => '5b453433355d',
		'26233132383634353b' => '5b453031665d',
		'26233132373931353b' => '5b453132355d',
		'2623393938313b' => '5b453033615d',
		'26233132383637373b' => '5b453134655d',
		'2623393838383b' => '5b453235325d',
		'26233132383637393b' => '5b453133375d',
		'26233132383330343b' => '5b453230395d',
		'26233132373932303b' => '5b453133335d',
		'26233132383635353b' => '5b453135305d',
		'26233132383133363b' => '5b453332305d',
		'2623393833323b' => '5b453132335d',
		'26233132373933373b' => '5b453133325d',
		'26233132373838343b' => '5b453134335d',
		'26233132373437313b26233132373437373b' => '5b453530625d',
		'26233132373437323b26233132373437393b' => '5b453531345d',
		'26233132373436343b26233132373437353b' => '5b453531335d',
		'26233132373438323b26233132373438303b' => '5b453530635d',
		'26233132373436373b26233132373437393b' => '5b453530645d',
		'26233132373436363b26233132373438303b' => '5b453531315d',
		'26233132373437303b26233132373438313b' => '5b453530665d',
		'26233132373437393b26233132373438323b' => '5b453531325d',
		'26233132373436383b26233132373436333b' => '5b453531305d',
		'26233132373436353b26233132373436363b' => '5b453530655d',
		'312623383431393b' => '5b453231635d',
		'322623383431393b' => '5b453231645d',
		'332623383431393b' => '5b453231655d',
		'342623383431393b' => '5b453231665d',
		'352623383431393b' => '5b453232305d',
		'362623383431393b' => '5b453232315d',
		'372623383431393b' => '5b453232325d',
		'382623383431393b' => '5b453232335d',
		'392623383431393b' => '5b453232345d',
		'302623383431393b' => '5b453232355d',
		'232623383431393b' => '5b453231305d',
		'262331313031343b' => '5b453233325d',
		'262331313031353b' => '5b453233335d',
		'262331313031333b' => '5b453233355d',
		'262331303134353b' => '5b453233345d',
		'a84a' => '5b453233365d',
		'a849' => '5b453233375d',
		'a84b' => '5b453233385d',
		'a84c' => '5b453233395d',
		'2623393636343b' => '5b453233625d',
		'2623393635343b' => '5b453233615d',
		'2623393139343b' => '5b453233645d',
		'2623393139333b' => '5b453233635d',
		'26233132373338333b' => '5b453234645d',
		'26233132373338313b' => '5b453231325d',
		'26233132383238353b' => '5b453234635d',
		'26233132373338353b' => '5b453231335d',
		'26233132373337383b' => '5b453231345d',
		'26233132373931303b' => '5b453530375d',
		'26233132373438393b' => '5b453230335d',
		'26233132383234363b' => '5b453230625d',
		'26233132373534313b' => '5b453232615d',
		'26233132373533393b' => '5b453232625d',
		'26233132373536383b' => '5b453232365d',
		'26233132373534353b' => '5b453232375d',
		'26233132373533353b' => '5b453232635d',
		'26233132373534363b' => '5b453232645d',
		'26233132373534323b' => '5b453231355d',
		'26233132373531343b' => '5b453231365d',
		'26233132373534333b' => '5b453231375d',
		'26233132373534343b' => '5b453231385d',
		'26233132373439303b' => '5b453232385d',
		'26233132383639393b' => '5b453135315d',
		'26233132383639373b' => '5b453133385d',
		'26233132383639383b' => '5b453133395d',
		'26233132383730303b' => '5b453133615d',
		'26233132383638353b' => '5b453230385d',
		'26233132373335393b' => '5b453134665d',
		'2623393835353b' => '5b453230615d',
		'26233132383634373b' => '5b453433345d',
		'26233132383730323b' => '5b453330395d',
		'c3d8' => '5b453331355d',
		'd7a3' => '5b453330645d',
		'26233132383238363b' => '5b453230375d',
		'26233132373338303b' => '5b453232395d',
		'262331303033353b' => '5b453230365d',
		'262331303033363b' => '5b453230355d',
		'26233132383135393b' => '5b453230345d',
		'26233132373338363b' => '5b453132655d',
		'26233132383234333b' => '5b453235305d',
		'26233132383234343b' => '5b453235315d',
		'26233132383138353b' => '5b453134615d',
		'26233132383137373b' => '5b453134395d',
		'2623393830303b' => '5b453233665d',
		'2623393830313b' => '5b453234305d',
		'2623393830323b' => '5b453234315d',
		'2623393830333b' => '5b453234325d',
		'2623393830343b' => '5b453234335d',
		'2623393830353b' => '5b453234345d',
		'2623393830363b' => '5b453234355d',
		'2623393830373b' => '5b453234365d',
		'2623393830383b' => '5b453234375d',
		'2623393830393b' => '5b453234385d',
		'2623393831303b' => '5b453234395d',
		'2623393831313b' => '5b453234615d',
		'2623393933343b' => '5b453234625d',
		'26233132383330333b' => '5b453233655d',
		'26233132373334343b' => '5b453533325d',
		'26233132373334353b' => '5b453533335d',
		'26233132373337343b' => '5b453533345d',
		'26233132373335383b' => '5b453533355d',
		'26233132383330363b' => '5b453231615d',
		'26233132383330383b' => '5b453231395d',
		'26233132383330373b' => '5b453231625d',
		'26233132383334373b' => '5b453032665d',
		'26233132383333363b' => '5b453032345d',
		'26233132383333373b' => '5b453032355d',
		'26233132383333383b' => '5b453032365d',
		'26233132383333393b' => '5b453032375d',
		'26233132383334303b' => '5b453032385d',
		'26233132383334313b' => '5b453032395d',
		'26233132383334323b' => '5b453032615d',
		'26233132383334333b' => '5b453032625d',
		'26233132383334343b' => '5b453032635d',
		'26233132383334353b' => '5b453032645d',
		'26233132383334363b' => '5b453032655d',
		'262331313039333b' => '5b453333325d',
		'262331303036303b' => '5b453333335d',
		'26233136393b' => '5b453234655d',
		'26233137343b' => '5b453234665d',
		'2623383438323b' => '5b453533375d',
	);
}