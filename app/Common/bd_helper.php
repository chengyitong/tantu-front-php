<?php
	function index_class($str){
		if($str=='节日') return 36;
		if($str=='人物') return 27;
		if($str=='自然') return 34;
		if($str=='特写') return 33;
		if($str=='城市') return 32;
		if($str=='艺术') return 31;
	}
	function index_class_arr($str){
		return array(36,27,34,33,32,31);
	}
	function getRandChar($length){
		$str = null;
		$strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
		$max = strlen($strPol)-1;
		
		for($i=0;$i<$length;$i++){
			$str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
		}
		
		return $str;
	}
	function camerist_t($name){
		if(!$name) return '/static/images/userhead.jpg';
		return imgurl_tt1($name,0);
		$usertopimg = explode('_',$name);
		if(count($usertopimg)>1) return imgurl_t1($name,0);
		else{
			if(file_exists('./static/uploadfiles/albumimg/t_'.$name)) return '/static/uploadfiles/albumimg/t_'.$name;
			else return '/static/uploadfiles/albumimg/'.$name;
		}
	}
	function get_ip(){
		if (getenv("HTTP_CLIENT_IP"))
		$ip = getenv("HTTP_CLIENT_IP");
		else if(getenv("HTTP_X_FORWARDED_FOR"))
		$ip = getenv("HTTP_X_FORWARDED_FOR");
		else if(getenv("REMOTE_ADDR"))
		$ip = getenv("REMOTE_ADDR");
		else $ip = "Unknow";
		return $ip;
	}
	function user_status($id){
		if($id==1){
			return '正常';
		}else{
			return '失效';
		}
	}
	function order_status_h($id){
		if($id==1){
			return '待付款';
		}elseif($id==2){
			return '已付款, 待确认';
		}elseif($id==3){
			return '已完成';
		}elseif($id==9){
			return '已取消';
		}
	}
	function order_status_t($id){
		if($id==1){
			return '待付款';
		}elseif($id==2){
			return '已付款, 待确认';
		}elseif($id==3){
			return '已完成';
		}elseif($id==9){
			return '已取消';
		}
	}
	function order_status_c($id){
		if($id==1){
			return '待付款';
		}elseif($id==2){
			return '已付款, 待消费';
		}elseif($id==3){
			return '已完成';
		}elseif($id==9){
			return '已取消';
		}
	}
	function qiniu_ak(){
		return 'IYvupfDPqlaSvSVWVqD-dsYDvY40CLSMElmn8mts';
	}
	function qiniu_sk(){
		return '_hrfbdvcBDf45-PKfgYkAIF7ihEBs8s2825IxOXQ';
	}
	function qiniu_freedomain(){
		return 'http://oh2n6php5.bkt.clouddn.com/';
	}
	function qiniu_domain(){
		return 'https://oj8icns2m.qnssl.com/';//images.tantupix.com/';
	}
	function main_domain(){
		return 'http://www.tantupix.com/';//images.tantupix.com/';
	}
	function get_avatar($uid){
		$r = D('a_images')->field('id,name')->where('type=\'user_avatar\' and targetid='.$uid)->order('id desc')->find();
		$u = D('a_user')->field('avatarimg')->where('id='.$uid)->find();
		if($r) return './static/uploadfiles/images/'.$r['name'];
		else{
			if($u['avatarimg']) return $u['avatarimg'];
			else return './static/images/dperson.png';
		}
	}
	function imgurl_t1($name,$size) {
		$r = D('a_product')->where('isuse=1 and imgkey=\''.$name.'\'')->find();
		$name = str_replace('.JPG','.jpg',$name);
		if(!$r) return '/static/uploadfiles/xie.png';
		if(!in_array(strtolower($r['imgext']),array('jpg','png','jpeg','gif'))){
			if($r['thumbimg']!='') return '/static/uploadfiles/imgthumb/'.$r['thumbimg'];
			else return '/static/uploadfiles/xie.png';
		}
		if($r['size']*1>20*1024*1024) $name = 'thumb_'.$name;
		return qiniu_domain().$name.'-slist?_=';//.time();
	}
	function imgurl_t2($name,$size) {
		$r = D('a_product')->where('isuse=1 and imgkey=\''.$name.'\'')->find();
		$name = str_replace('.JPG','.jpg',$name);
		if(!$r) return '/static/uploadfiles/xie.png';
		if(!in_array(strtolower($r['imgext']),array('JPG','jpg','png','jpeg','gif'))){
			if($r['thumbimg']!='') return '/static/uploadfiles/imgthumb/'.$r['thumbimg'];
			else return '/static/uploadfiles/xie.png';
		}
		if($r['size']*1>20*1024*1024) $name = 'thumb_'.$name;
		return qiniu_domain().$name.'-ndetail?_=';//.time();
	}
	function imgurl_t3($name,$size) {
		$r = D('a_product')->where('isuse=1 and imgkey=\''.$name.'\'')->find();
		$name = str_replace('.JPG','.jpg',$name);
		if(!$r) return '/static/uploadfiles/xie.png';
		if(!in_array(strtolower($r['imgext']),array('JPG','jpg','png','jpeg','gif'))){
			if($r['thumbimg']!='') return '/static/uploadfiles/imgthumb/'.$r['thumbimg'];
			else return '/static/uploadfiles/xie.png';
		}
		if($r['size']*1>20*1024*1024) $name = 'thumb_'.$name;
		return qiniu_domain().$name.'-topimg?_=';//.time();
	}
	function imgurl_tt1($name,$size) {
		return qiniu_domain().$name.'-slist?_=';//.time();
	}
	function imgurl_tt3($name,$size) {
		if(!$name) return '/static/images/userhead.jpg';
		return qiniu_domain().$name.'-topimg?_=';//.time();
	}
	function order_num($id){
		return $id*1+100000;
	}
	function un_order_num($id){
		return $id*1-100000;
	}
	//  临时卡号
	function tcardno($cno,$id){
		$tcn = $id*1+100000;
		$cardnumber = $cno;
		if($cardnumber=='') $cardnumber = 'T'.$tcn;
		return $cardnumber;
	}
	function cstr($str,$set=''){
		if($str=='' || !$str) return $set;
		else return $str;
	}
	function ismobile(){
		  // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
		  if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
			return true;
		  }
		  //如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
		  if (isset($_SERVER['HTTP_VIA'])) {
			//找不到为flase,否则为true
			if(stristr($_SERVER['HTTP_VIA'], "wap"))
			{
			  return true;
			}
		  }
		  //脑残法，判断手机发送的客户端标志,兼容性有待提高
		  if (isset($_SERVER['HTTP_USER_AGENT'])) {
			$clientkeywords = array (
			  'nokia',
			  'sony',
			  'ericsson',
			  'mot',
			  'samsung',
			  'htc',
			  'sgh',
			  'lg',
			  'sharp',
			  'sie-',
			  'philips',
			  'panasonic',
			  'alcatel',
			  'lenovo',
			  'iphone',
			  'ipod',
			  'blackberry',
			  'meizu',
			  'android',
			  'netfront',
			  'symbian',
			  'ucweb',
			  'windowsce',
			  'palm',
			  'operamini',
			  'operamobi',
			  'openwave',
			  'nexusone',
			  'cldc',
			  'midp',
			  'wap',
			  'mobile',
			  'phone',
			);
			// 从HTTP_USER_AGENT中查找手机浏览器的关键字
			if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
			  return true;
			}
		  }
		  //协议法，因为有可能不准确，放到最后判断
		  if (isset($_SERVER['HTTP_ACCEPT'])) {
			// 如果只支持wml并且不支持html那一定是移动设备
			// 如果支持wml和html但是wml在html之前则是移动设备
			if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
			  return true;
			}
		  }
		  return false;
	}
	function iswechat(){
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false) {
			// 非微信浏览器禁止浏览
			return false;
		} else {
			// 微信浏览器，允许访问
			return true;
			// 获取版本号
			//preg_match('/.*?(MicroMessenger\/([0-9.]+))\s*/', $user_agent, $matches);
			//$wechatversion = $matches[2];
		}
	}
	function glb_fystyle($cur,$all,$tol)
	{
		$str = '
		<a class="pageaglb ';
		$url = seturlget('curpage');
		if($cur>1) $str .= 'pagea'; else $str .= 'pageadis';
		$str .= '" href="
		';
		$up = $cur-1;
		if($cur>1) $str .= $url.'&curpage='.$up; else $str .= 'javascript:;';
		$str .= '
		">上一页</a>
		';
		//  存在多页
		if($all>1){
			if($all>4){
				//  输出第一页
				$str .= '<a class="pageaglb ';
				if($cur==1) $str .= 'pageanow'; else $str .= 'pagea';
				$str .= '" href="'.$url.'&curpage=1">1</a>';
				if($cur>1 && $cur<$all){
					$pages = $cur-1;
					if($pages<2) $pages=2;
					$pagee = $cur+1;
					if($pagee>$all-2) $pagee=$all-1;
					if($pages>2) $str .= '...';
					for($i=$pages;$i<=$pagee;$i++){
						//  输出
						$str .= '<a class="pageaglb ';
						if($cur==$i) $str .= 'pageanow'; else $str .= 'pagea';
						$str .= '" href="'.$url.'&curpage='.$i.'">'.$i.'</a>';
					}
					if($pagee<$all-1) $str .= '...';
				}else{
					if($cur==1){
						for($i=2;$i<=3;$i++){
							//  输出
							$str .= '<a class="pageaglb ';
							if($cur==$i) $str .= 'pageanow'; else $str .= 'pagea';
							$str .= '" href="'.$url.'&curpage='.$i.'">'.$i.'</a>';
						}
						$str .= '...';
					}elseif($cur==$all){
						$str .= '...';
						$tmp_pages = $all-2;
						$tmp_pagee = $all-1;
						for($i=$tmp_pages;$i<=$tmp_pagee;$i++){
							//  输出
							$str .= '<a class="pageaglb ';
							if($cur==$i) $str .= 'pageanow'; else $str .= 'pagea';
							$str .= '" href="'.$url.'&curpage='.$i.'">'.$i.'</a>';
						}
					}
				}
				//输出最后一页
				$str .= '<a class="pageaglb ';
				if($cur==$all) $str .= 'pageanow'; else $str .= 'pagea';
				$str .= '" href="'.$url.'&curpage='.$all.'">'.$all.'</a>';
			}else{
				for($i=1;$i<=$all;$i++){
					//  输出
					$str .= '<a class="pageaglb ';
					if($cur==$i) $str .= 'pageanow'; else $str .= 'pagea';
					$str .= '" href="'.$url.'&curpage='.$i.'">'.$i.'</a>';
				}
			}
		}else{
			$str .= '<a class="pageaglb pageanow" href="'.$url.'&curpage=1">1</a>';
		}
		$str .= '
		<a class="pageaglb ';
		if($cur<$all) $str .= 'pagea'; else $str .= 'pageadis';
		$str .= '" href="
		';
		$down = $cur+1;
		if($cur<$all) $str .= $url.'&curpage='.$down; else $str .= 'javascript:;';
		$str .= '
		">下一页</a>
		第<input class="pagego" type="text" id="goPage" size="4" value="'.$cur.'" onkeyup="if (event.keyCode == 13){ if(!isNaN(this.value) && this.value>0 && this.value<='.$all.') location=\''.$url.'&curpage=\'+this.value; else alert(\'请正确填写页数!\'); }" />页
		';
		return $str;
	}
	//
	function mystr($str){
		if(get_magic_quotes_gpc()){
			$str = stripslashes(htmlspecialchars_decode($str));
		}
		return $str;
	}
	//  图片等比缩放
	/** 
	 * * 
	 *等比缩放 
	 * @param unknown_type $srcImage   源图片路径 
	 * @param unknown_type $toFile     目标图片路径 
	 * @param unknown_type $maxWidth   最大宽 
	 * @param unknown_type $maxHeight  最大高 
	 * @param unknown_type $imgQuality 图片质量 
	 * @return unknown 
	 */ 
	function imgresize($srcImage,$toFile,$maxWidth = 100,$maxHeight = 100,$imgQuality=100) 
	{ 
	   
		list($width, $height, $type, $attr) = getimagesize($srcImage); 
		if($width < $maxWidth  || $height < $maxHeight) return ; 
		switch ($type) { 
		case 1: $img = imagecreatefromgif($srcImage); break; 
		case 2: $img = imagecreatefromjpeg($srcImage); break; 
		case 3: $img = imagecreatefrompng($srcImage); break; 
		} 
		if($type!=2 && $imgQuality>9) $imgQuality = 9;
		$scale = min($maxWidth/$width, $maxHeight/$height); //求出绽放比例 
		 
		if($scale < 1) { 
		$newWidth = floor($scale*$width); 
		$newHeight = floor($scale*$height); 
		$newImg = imagecreatetruecolor($newWidth, $newHeight); 
switch ($type)
{
 case 3:
  // integer representation of the color black (rgb: 0,0,0)
  $background = imagecolorallocate($newImg, 0, 0, 0);
  // removing the black from the placeholder
  imagecolortransparent($newImg, $background);
  // turning off alpha blending (to ensure alpha channel information 
  // is preserved, rather than removed (blending with the rest of the 
  // image in the form of black))
  imagealphablending($newImg, false);
  // turning on alpha channel information saving (to ensure the full range 
  // of transparency is preserved)
  imagesavealpha($newImg, true);
  break;
 case 1:
  // integer representation of the color black (rgb: 0,0,0)
  $background = imagecolorallocate($newImg, 0, 0, 0);
  // removing the black from the placeholder
  imagecolortransparent($newImg, $background);
  break;
}
		imagecopyresampled($newImg, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height); 
		$newName = ""; 
		$toFile = preg_replace("/(.gif|.jpg|.jpeg|.png)/i","",$toFile); 
	 
		switch($type) { 
			case 1: if(imagegif($newImg, "$toFile$newName.gif", $imgQuality)) 
			return "$newName.gif"; break; 
			case 2: if(imagejpeg($newImg, "$toFile$newName.jpg", $imgQuality)) 
			return "$newName.jpg"; break; 
			case 3: if(imagepng($newImg, "$toFile$newName.png", $imgQuality)) 
			return "$newName.png"; break; 
			default: if(imagejpeg($newImg, "$toFile$newName.jpg", $imgQuality))
			return "$newName.jpg"; break; 
		} 
		imagedestroy($newImg); 
		} 
		imagedestroy($img); 
		return false; 
	}
	//  输出json
	function json_output($data){
		if(I('get.callback')) return I('get.callback').'('.json_encode($data).')';
	    else return json_encode($data);
	}
	//获取https的get请求结果  
	function getData($c_url){
		$curl = curl_init(); // 启动一个CURL会话  
		curl_setopt($curl, CURLOPT_URL, $c_url); // 要访问的地址  
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查  
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在  
		curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器  
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转  
		curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer  
	//    curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求  
	//    curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包  
		curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环  
		curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容  
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回  
		$tmpInfo = curl_exec($curl); // 执行操作  
		if (curl_errno($curl)) {  
		   echo 'Errno'.curl_error($curl);//捕抓异常  
		}  
		curl_close($curl); // 关闭CURL会话  
		return $tmpInfo; // 返回数据  
	}
	//  获取网页内容
	function curl($url,$fields){
		//open connection
		$ch = curl_init() ;
		//set the url, number of POST vars, POST data
		curl_setopt($ch, CURLOPT_URL,$url) ;
		curl_setopt($ch, CURLOPT_POST,count($fields)) ; // 启用时会发送一个常规的POST请求，类型为：application/x-www-form-urlencoded，就像表单提交的一样。  
		curl_setopt($ch, CURLOPT_POSTFIELDS,$fields); // 在HTTP中的“POST”操作。如果要传送一个文件，需要一个@开头的文件名  
		ob_start();
		curl_exec($ch);
		$result = ob_get_contents() ;
		ob_end_clean();
		//close connection  
		curl_close($ch);
		return $result;
	}
	//  文件下载至服务器
	function filedownload($src,$url){
		set_time_limit (24 * 60 * 60);
		$destination_folder = './'.$src;   // 文件夹保存下载文件。必须以斜杠结尾
		$newfname = $destination_folder . basename($url);
		$file = fopen ($url, "rb");
		if($file){
			$newf = fopen ($newfname, "wb");
			if($newf)
			while(!feof($file)){
				fwrite($newf, fread($file, 1024 * 8 ), 1024 * 8 );
			}
			fclose($file);
			if($newf) fclose($newf);
		}
	}
	//  排序
	function sortby($name,$str,$nml="id,asc"){
		//  保留原有url参数
		$url = '?';
		$get = $_GET;
		unset($get['sortby']);
		unset($get['_URL_']);
		$i=0;
		foreach($get as $v=>$k){
			$i++;
			if($i>1) $url .= '&';
			$url .= $v.'='.$k;
		}
		//  判断排序顺序
		$sort = 'asc';
		$class = '';
		$sortby = I('get.sortby') ? I('get.sortby') : $nml;
		$sortby = explode(',',$sortby);
		if($sortby[0]==$name){
			$sort = $sortby[1]=='asc' ? 'desc' : 'asc';
			$class = $sortby[1];
		}
		$url.='&sortby='.$name.','.$sort;
		return '<a href="'.$url.'" class="sortby_'.$class.'">'.$str.'</a>';
	}
	//半角和全角互相转换函数
	function convertStrType($str,$args2)
	{
	  $DBC=array(
	    '０','１','２','３','４','５','６','７','８','９',
	    'Ａ','Ｂ','Ｃ','Ｄ','Ｅ','Ｆ','Ｇ','Ｈ','Ｉ','Ｊ',
	    'Ｋ','Ｌ','Ｍ','Ｎ','Ｏ','Ｐ','Ｑ','Ｒ','Ｓ','Ｔ',
	    'Ｕ','Ｖ','Ｗ','Ｘ','Ｙ','Ｚ',
	    'ａ','ｂ','ｃ','ｄ','ｅ','ｆ','ｇ','ｈ','ｉ','ｊ',
	    'ｋ','ｌ','ｍ','ｎ','ｏ','ｐ','ｑ','ｒ','ｓ','ｔ',
	    'ｕ','ｖ','ｗ','ｘ','ｙ','ｚ',
	    '－','　','：','．','，','／','％','＃','！','＠',
	    '＆','（','）','＜','＞','＂','＇','？','［','］',
	    '｛','｝','＼','｜','＋','＝','＿','＾','￥','～','｀','。');
	  $SBC=array(//半角
	    '0','1','2','3','4','5','6','7','8','9',
	    'A','B','C','D','E','F','G','H','I','J',
	    'K','L','M','N','O','P','Q','R','S','T',
	    'U','V','W','X','Y','Z',
	    'a','b','c','d','e','f','g','h','i','j',
	    'k','l','m','n','o','p','q','r','s','t',
	    'u','v','w','x','y','z',
	    '-',' ',':','.',',','/','%','#','!','@',
	    '&','(',')','<','>','"','\'','?','[',']',
	    '{','}','\\','|','+','=','_','^','$','~','`','.');
	  if($args2==0)
	    return str_replace($SBC,$DBC,$str);//半角到全角
	  if($args2==1)
	    return str_replace($DBC,$SBC,$str);//全角到半角
	  else
	    return false;
	}
	//  循环数组找结果
	function getfors($str,$arr)
	{
		$a = explode('=',$str);
		$i=0;
		foreach($arr as $k){
			if($k[$a[0]]==$a[1]){
				$r[$i]=$k;
				$i++;
			}
		}
		return $r;
	}
	//  转到页面循环
	function dogopage($pid,$a,$id)
	{
		foreach($a as $k):
			if($k['syscode_parentid']==$pid){
				echo '<tr><td>';
				$radio_e = '';
				$CodeName = $k['syscode_name'];
				if($k['syscode_id']==$id):
					$radio_e = ' disabled="disabled"';
					$CodeName = '<span style="color: red;">'.$CodeName.'</span>';
					echo '<input type="hidden" name="level" value="'.$k['syscode_level'].'" />';
				endif;
				echo '<input'.$radio_e.' type="radio" id="xz'.$k['syscode_id'].'" name="radio" value="'.$k['syscode_parentid'].','.$k['syscode_sortno'].','.$k['syscode_level'].'" />';
				for($i=1;$i<=$k['syscode_level']-1;$i++){
					if($i==$k['syscode_level']-1) echo '┗'; else echo '　';
				}
				echo $CodeName.'</td></tr>';
				dogopage($k['syscode_id'],$a,$id);
			}
		endforeach;
	}
	//  翻页函数
	function page($r){
		I('get.curpage') ? $curpage = I('get.curpage') : $curpage = 1;
		$r['per'] ? $perpage = $r['per'] : $perpage = 20;
		$page['len'] = $r['len'];
		$page['per'] = $perpage;
		$page['cur'] = $curpage;
		$page['nex'] = $curpage+1;
		$page['pre'] = $curpage-1;
		$page['tol'] = ceil($r['len'] / $perpage);
		if($page['tol']<1) $page['tol']=1;
		$page['limits'] = ($curpage-1)*$perpage;
		$page['limit'] = $page['limits'].','.$perpage;
		$page['url'] = seturlget('curpage');
		return $page;
	}
	function seturlget($a,$b=''){
		$urls = $_GET;
		unset($urls['_URL_']);
		if($b!='') $urls[$a]=$b;
		else unset($urls[$a]);
		foreach($urls as $k=>$v){
			$str.='&'.$k.'='.htmlspecialchars($v);
		}
		return '?'.$str;
	}
	function seturlgets($str){
		$urls = $_GET;
		unset($urls['_URL_']);
		$arr = explode(';',$str);
		for($i=0;$i<count($arr);$i++){
			$arr2 = explode(',',$arr[$i]);
			$a = $arr2[0];
			$b = cstr($arr2[1]);
			if($b!='') $urls[$a]=$b;
			else unset($urls[$a]);
		}
		foreach($urls as $k=>$v){
			$rstr.='&'.$k.'='.htmlspecialchars($v);
		}
		return '?'.$rstr;
	}
	//  统计字符个数
	function len($string){
		return mb_strlen($string, 'utf-8');
	}
	//  从左获取字符个数
	function left($string, $count, $estr=''){
		$string = strip_tags($string);
		$str = mb_substr($string, 0, $count, 'utf-8');
		if(len($string)>$count) $str.=$estr;
		return $str;
	}
	function leftcn($str, $len) { //解决中文被截成乱码的问题 
		$arr = str_split($str); 
		$i = 0; 
		foreach ($arr as $chr) { 
			if (ord($chr) > 128) 
			$add = $add ? 0 : 1; 
			$i++; 
			if ($i == $len) 
			break; 
		} 
		return substr($str, 0, $len + $add); 
	}
	//  从右获取字符个数
	function right($value, $count){
		$value = mb_substr($value, (strlen($value) - $count), strlen($value), 'utf-8');
		return $value;
	}
	//  获取文件夹下文件列表
	function getDir($dir) {
	    $dirArray[]=NULL;
	    if (false != ($handle = opendir ( $dir ))) {
	        $i=0;
	        while ( false !== ($file = readdir ( $handle )) ) {
	            //去掉"“.”、“..”以及带“.xxx”后缀的文件
	            if ($file != "." && $file != ".."&&!strpos($file,".")) {
	                $dirArray[$i]=$file;
	                $i++;
	            }
	        }
	        //关闭句柄
	        closedir ( $handle );
	    }
	    return $dirArray;
	}
	//  二维码
	function generateQRfromGoogle($chl,$widhtHeight ='150',$EC_level='L',$margin='0')
	{
		 return '<img src="http://chart.apis.google.com/chart?chs='.$widhtHeight.'x'.$widhtHeight.'&cht=qr&chld='.$EC_level.'|'.$margin.'&chl='.$chl.'" alt="QR code" widhtHeight="'.$widhtHeight.'" widhtHeight="'.$widhtHeight.'" />';
	}
	//  当前时间
	function now($typ=0)
	{
		$now = date('Y-m-d H:i:s');
		if($typ==1) $now=date('Y-m-d');
		else if($typ==2) $now=date('H:i:s');
		return $now;
	}
	function tdtxt($a){
		if($a=='') return '&nbsp;'; else return $a;
	}
	//  生成GUID
	function create_guid() {
		$charid = strtoupper(md5('bindow.cn'.uniqid(mt_rand(), true)));
		$hyphen = chr(45);// "-"
		$uuid = chr(123)// "{"
		.substr($charid, 0, 8).$hyphen
		.substr($charid, 8, 4).$hyphen
		.substr($charid,12, 4).$hyphen
		.substr($charid,16, 4).$hyphen
		.substr($charid,20,12)
		.chr(125);// "}"
		return $uuid;
	}
	//  判断http状态
	function webConn($url,$port=80)
	{
		$fp = fsockopen($url,$port,$errno,$errstr,3);
		if(!$fp){
			$r = false;
			//echo "ERROR: $errno - $errstr<br />\n";
		}else{
			$r = true;
		}
		fclose($fp);
		return $r;
	}
	//  获取数组值
	function getValue($a,$b,$c,$d){
		foreach($a['Values'] as $k){
			if($k[$b]==$c){
				echo $k[$d];
			}
		}
	}
	// 数组排序
	function array_sort($arr,$keys,$type='asc'){
		$keysvalue = $new_array = array();
		foreach ($arr as $k=>$v){
			$keysvalue[$k] = $v[$keys];
		}
		if($type == 'asc' || $type == ''){
			asort($keysvalue);
		}else{
			arsort($keysvalue);
		}
		reset($keysvalue);
		foreach ($keysvalue as $k=>$v){
			$new_array[$k] = $arr[$k];
		}
		return $new_array;
	}
	//  绘制下拉框
	function drawSelect($a,$b,$c,$d,$e='',$f=''){
		echo '<select name="'.$a.'">';
		foreach($b['Values'] as $k){
			$nomal_s='';
			if($e=='value'){
				if($f==$k[$c]){
					$nomal_s = ' selected="selected"';
				}
			}else if($e=='text'){
				if($f==$k[$d]){
					$nomal_s = ' selected="selected"';
				}
			}
			echo '<option value="'.$k[$c].'"'.$nomal_s.'>'.$k[$d].'</option>';
		}
		echo '</select>';
	}
	//  获取随机码
	function generate_password( $length = 8 ) {
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		//$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_ []{}<>~`+=,.;:/?|';
		$password = '';
		for ( $i = 0; $i < $length; $i++ ){
			// $password .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
			$password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
		}
		return $password;
	}
	// import JS
	if ( !function_exists('import_js')) {
		function import_js($href) {
			return sprintf('<script type="text/javascript" src="/static/%s"></script>', $href);
		}
	}
	// import CSS
	function import_css($href) {
		return sprintf('<link rel="stylesheet" type="text/css" media="all" href="/static/%s" /> ', $href);
	}
	//保留查找关键字
	function input_text($str)
	{
		if(isset($str)){echo $str;}
	}
	//保留下拉框被选中状态
	function selected($str1,$str2)
	{
		if($str1==$str2){echo 'selected';}
	}
	//  带值获取当前url
	function GetCurUrl($urls='')
	{
		$url = $_SERVER["REQUEST_URI"];
		if($urls){
			indexOf('?',$url) ? $urls = '&'.$urls : $urls = '?'.$urls;
		}
		$strurl = get_current_url().$url.$urls;
		$strurl = str_replace('?', '/', $strurl);
		$strurl = str_replace('&', '/', $strurl);
		$strurl = str_replace('=', '/', $strurl);
		return urlencode($strurl);
		//urldecode
	}
	//  获取当前host
	function get_current_url()
	{
		return 'http'.'://'.$_SERVER['HTTP_HOST'];
	}
	//  提示信息，关闭窗口，刷新父页面
	function winclose($val,$f='refrash')
	{
		echo "<script>alert('".$val."');parent.global.ExitWin('".$f."');</script>";
	}
	//  提示信息，关闭窗口
	function winexit($val)
	{
		echo "<script>alert('".$val."');parent.window.close();</script>";
	}
	//  提示信息，返回上一页
	function winback($val)
	{
		echo "<script>alert('".$val."');location='".$_SERVER['HTTP_REFERER']."';</script>";
		exit;
	}
	//  提示信息，返回上一页
	function winback2($val,$url)
	{
		echo "<script>alert('".$val."');location='".$_SERVER['HTTP_REFERER'].$url."';</script>";
		exit;
	}
	//  提示信息，转到
	function wingo($val,$url)
	{
		echo "<script>alert('".$val."');location='".$url."';</script>";
		exit;
	}
	//  提示信息，转到
	function wingoto($url)
	{
		echo "<script>location='".$url."';</script>";
		exit;
	}
	function indexOf($needle,$haystack) {
		$array = explode($needle, $haystack);
		return count($array) > 1;
	}
//  日期操作函数
	//  获取制定月份第一天日期
	function getCurMonthFirstDay($date){
		return date('Y-m-01', strtotime($date));
	}
	//  获取制定月份最后一日期
	function getCurMonthLastDay($date){
		return date('Y-m-d', strtotime(date('Y-m-01', strtotime($date)) . ' +1 month -1 day'));
	}
	//  日期增减
	function DateAdd($part, $n, $date, $long=true) {
		$timelong = '';
		if($long) $timelong=' H:i:s';
		if($part=='m'){
			$sdate = strtotime($date);
			//得到年月
			$tmp_date=date("Y-m-1",$sdate);
			//得到日
			$tmp_day=date('j',$sdate);
			$tmp_time=date('H:i:s',$sdate);
			//运算
			$new_date = date("Y-m-d", strtotime($tmp_date ." +$n month"));
			$newsdate = strtotime($new_date.$tmp_time);
			//时间数组
			$arrday =array('1'=>31,'2'=>29,'3'=>31,'4'=>30,'5'=>31,'6'=>30,'7'=>31,'8'=>31,'9'=>30,'10'=>31,'11'=>30,'12'=>31);
			//闰年
			$yea=date('Y',$newsdate)%4;
			if($yea!=0) $arrday['2']=28;
			//切割新时间
			$new_mon=date('n',$newsdate);
			$new_day=$tmp_day;
			if($tmp_day*1>$arrday[$new_mon]*1) $new_day=$arrday[$new_mon];
			return date('Y-m-'.$new_day.$timelong,$newsdate);
		}
		switch($part)
		{
		case "y": $val = date("Y-m-d".$timelong, strtotime($date ." +$n year")); break;
		case "m": $val = date("Y-m-d".$timelong, strtotime($date ." +$n month")); break;
		case "w": $val = date("Y-m-d".$timelong, strtotime($date ." +$n week")); break;
		case "d": $val = date("Y-m-d".$timelong, strtotime($date ." +$n day")); break;
		case "h": $val = date("Y-m-d".$timelong, strtotime($date ." +$n hour")); break;
		case "n": $val = date("Y-m-d".$timelong, strtotime($date ." +$n minute")); break;
		case "s": $val = date("Y-m-d".$timelong, strtotime($date ." +$n second")); break;
		}
		return $val;
	}
	//  获取两日期间长度
	function DateDiff($part, $begin, $end)
	{
		$diff = strtotime($end) - strtotime($begin);
		switch($part)
		{
			case "y": $retval = bcdiv($diff, (60 * 60 * 24 * 365)); break;
			case "m": $retval = bcdiv($diff, (60 * 60 * 24 * 30)); break;
			case "w": $retval = bcdiv($diff, (60 * 60 * 24 * 7)); break;
			case "d": $retval = bcdiv($diff, (60 * 60 * 24)); break;
			case "h": $retval = bcdiv($diff, (60 * 60)); break;
			case "n": $retval = bcdiv($diff, 60); break;
			case "s": $retval = $diff; break;
		}
		return $retval;
	}
	//  获取星期
	function cnweek($date)
	{
		$weekarray=array("日","一","二","三","四","五","六"); 
		return $weekarray[date("w",strtotime($date))];
	}
	//eacape
	function escape($str)  
	{
		preg_match_all("/[\xc2-\xdf][\x80-\xbf]+|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}|[\x01-\x7f]+/e",$str,$r);
		//匹配utf-8字符，
		$str = $r[0];
		$l = count($str);
		for($i=0; $i<$l; $i++)
		{
			$value = ord($str[$i][0]);
			if($value < 223)
			{
				$str[$i] = rawurlencode(utf8_decode($str[$i]));
				//先将utf8编码转换为ISO-8859-1编码的单字节字符，urlencode单字节字符.
				//utf8_decode()的作用相当于iconv("UTF-8","CP1252",$v)。
			}
			else
			{
				$str[$i] = "%u".strtoupper(bin2hex(iconv("UTF-8","UCS-2",$str[$i])));
			}
		}
		return join("",$str);
	}
	//php unescape correspond to js escape
	function unescape($str)
	{
		$ret = '';
		$len = strlen($str);
		for ($i = 0; $i < $len; $i++)
		{
			if ($str[$i] == '%' && $str[$i+1] == 'u')
			{
				$val = hexdec(substr($str, $i+2, 4));
				if ($val < 0x7f) $ret .= chr($val);
				else if($val < 0x800) $ret .= chr(0xc0|($val>>6)).chr(0x80|($val&0x3f));
				else $ret .= chr(0xe0|($val>>12)).chr(0x80|(($val>>6)&0x3f)).chr(0x80|($val&0x3f));
				$i += 5;
			}
			else if ($str[$i] == '%')
			{
				$ret .= urldecode(substr($str, $i, 3));
				$i += 2;
			}
			else $ret .= $str[$i];
		}
		return $ret;
	}
	/*
	 *使用 PHP 解码 javascript escape() 编码过的字串为UTF-8
	 *例:1
	 *
	 *$tem=new UnEscape();
	 *echo $tem->getUtf8("%u624B%u673A%u95E8%u6237");
	 *echo $tem->getGb2312("%u624B%u673A%u95E8%u6237");
	 */
	  function UnEscapeToUtf8($ar){
		foreach($ar as $val){
			$val = intval(substr($val,2),16);
			if($val < 0x7F){ // 0000-007F
				$c .= chr($val);
			}elseif($val < 0x800) { // 0080-0800
				$c .= chr(0xC0 | ($val / 64));
				$c .= chr(0x80 | ($val % 64));
			}else{ // 0800-FFFF
				$c .= chr(0xE0 | (($val / 64) / 64));
				$c .= chr(0x80 | (($val / 64) % 64));
				$c .= chr(0x80 | ($val % 64));
			}
		}
		return $c;
		}
		class UnEscape{
		function getUtf8($value=""){
			$text = preg_replace_callback("/%u[0-9A-Za-z]{4}/",UnEscapeToUtf8,$value);
			return urldecode($text);
		}
		function getGb2312($value=""){
			$text = preg_replace_callback("/%u[0-9A-Za-z]{4}/",UnEscapeToUtf8,$value);
			$obj=new Gb2312Utf8();
			return $obj->utf8ToGb2312(urldecode($text));
		}
	}
	// 数组转json
	function array_to_json( $array ){
		if( !is_array( $array ) ){
			return false;
		}
		$associative = count( array_diff( array_keys($array), array_keys( array_keys( $array )) ));
		if( $associative ){
			$construct = array();
			foreach( $array as $key => $value ){
				// We first copy each key/value pair into a staging array,
				// formatting each key and value properly as we go.
				// Format the key:
				if( is_numeric($key) ){
					$key = "key_$key";
				}
				$key = "'".addslashes($key)."'";
				// Format the value:
				if( is_array( $value )){
					$value = array_to_json( $value );
				} else if( !is_numeric( $value ) || is_string( $value ) ){
					$value = "'".addslashes($value)."'";
				}
				// Add to staging array:
				$construct[] = "$key: $value";
			}
			// Then we collapse the staging array into the JSON form:
			$result = "{ " . implode( ", ", $construct ) . " }";
		} else { // If the array is a vector (not associative):
			$construct = array();
			foreach( $array as $value ){
				// Format the value:
				if( is_array( $value )){
					$value = array_to_json( $value );
				} else if( !is_numeric( $value ) || is_string( $value ) ){
					$value = "'".addslashes($value)."'";
				}
				// Add to staging array:
				$construct[] = $value;
			}
			// Then we collapse the staging array into the JSON form:
			$result = "[ " . implode( ", ", $construct ) . " ]";
		}
		return $result;
	}
