<?php
//  首页
class UserAction extends Action {
    protected function _initialize() {
        if (session('uid')) {
            $u = D('a_user')->where('id='.session('uid'))->find();
			$avatar = D('a_images')->where("type='user_avatar' and targetid=".$u['id'])->order('id desc')->find();
			$u['avatar'] = $avatar['name'];
			$this->user = $u;
			if($u==0) wingo('请先登录！', '/');
        }else{
			if(cookie('fguid')){
				$u = D('a_user')->where('fguid=\''.cookie('fguid').'\' and ftime>\''.now().'\'')->find();
				if($u){
					$avatar = D('a_images')->where("type='user_avatar' and targetid=".$u['id'])->order('id desc')->find();
					$u['avatar'] = $avatar['name'];
					session('uid',$u['id']);
					$this->user = $u;
					if($u==0) wingo('请先登录！', '/');
				}else{
					cookie('fguid',null);
					$this->user = 0;
					if($u==0) wingo('请先登录！', '/');
				}
			}else
				$this->user = 0;
				if($u==0) wingo('请先登录！', '/');
		}
        $this->banner = D('vw_syscode')->where('syscode_id=334')->find();
		$user = $this->user;
		// message count
	    $msg = D('a_msg')->where('(uids=\'0\' || uids like \'%,'.session('uid').',%\') and ctime>\''.$user['ctime'].'\'')->order('id desc')->select();
	    $read_count = 0;
	    $noread_count = 0;
	    foreach($msg as $k=>$v){
	    	$d = '';
		    $d['msgid'] = $v['id'];
		    $d['uid'] = session('uid');
	    	$r = D('a_msg_read')->where($d)->find();
	    	if($r){
	    		if($r['hasread']){
	    			if($r['isuse']) $read_count++;
	    		}else{
	    			if($r['isuse']) $noread_count++;
	    		}
	    	}else{
	    		$noread_count++;
	    		$d['ctime'] = now();
	    		D('a_msg_read')->add($d);
	    	}
	    }
	    $msg['read_count'] = $read_count;
	    $msg['noread_count'] = $noread_count;
		$this->assign('message',$msg);
    }
    public function my() {
		$data['banner'] = $this->banner;
		$data['user'] = $this->user;
        $data['headmenu'] = 'ziliao';
        $data['menu'] = 'my';
        $this->assign('data', $data);
        $this->display();
    }
	function qqunbind(){
		$d['3rdpartyid'] = 0;
		$d['3rdpartykey'] = '';
		D('a_user')->where('id='.session('uid'))->save($d);
		wingo('取消绑定成功','/user/my');
	}
	//  上传
	function avatar(){
		$data['type'] = 'user_avatar';
		$data['id'] = session('uid');
		$this->assign('data',$data);
		$this->display();
	}
	function waits(){
		die('waits');
	}
	//  执行上传头像
	function avatar_up(){
		import('ORG.Net.UploadFile');
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 3145728 ;// 设置附件上传大小
		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$upload->savePath =  './static/uploadfiles/images_tmp/';// 设置附件上传目录
		 if(!$upload->upload()) {// 上传错误提示错误信息
			echo "<script>
			try{
				eval( 'window.parent.api_avatar_err(\'".$upload->getErrorMsg()."\')' );
			}catch(e){}
			</script>No";
			exit;
		 }else{// 上传成功 获取上传文件信息
			$info =  $upload->getUploadFileInfo();
			list($width, $height, $type, $attr) = getimagesize('./static/uploadfiles/images_tmp/'.$info[0]['savename']);
			if($width<160 || $height<160){
				@unlink('./static/uploadfiles/images_tmp/'.$info[0]['savename']);
				//winback('图标分辨率最低标准为宽度：'.I('get.minw').'px, 高度：'.I('get.minh').'px');
    			echo "<script>
    			try{
    				eval( 'window.parent.api_avatar_err(\'图标分辨率最低标准为宽度：160px, 高度：160px\')' );
    			}catch(e){}
    			</script>";
    			exit;
			}else{
				//$data['imgurl'] = '/static/uploadfiles/images_tmp/'.$info[0]['savename'];//上传后的文件名地址 
				imgresize('./static/uploadfiles/images_tmp/'.$info[0]['savename'],'./static/uploadfiles/images_tmp/'.$info[0]['savename'],500,500);
    			echo "<script>
    			try{
    				eval( 'window.parent.api_avatar_ok(\'".$info[0]['savename']."\')' );
    			}catch(e){}
    			</script>";
    			exit;
			}
		 }
	}
	//  剪裁头像页面
	function avatar_cut(){
		$data['imgurl'] = '/static/uploadfiles/images_tmp/'.I('get.fname');//上传后的文件名地址 
		$this->assign('data',$data);
		$this->display('User/avatarcut');
	}
	//  执行剪裁头像
	function avatarcut(){
		import('ORG.Net.UploadFile');
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 3145728 ;// 设置附件上传大小
		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$upload->savePath =  './static/uploadfiles/images_tmp/';// 设置附件上传目录
		 if(!$upload->upload()) {// 上传错误提示错误信息
			$this->error($upload->getErrorMsg());
		 }else{// 上传成功 获取上传文件信息
			$info =  $upload->getUploadFileInfo();
			list($width, $height, $type, $attr) = getimagesize('./static/uploadfiles/images_tmp/'.$info[0]['savename']);
			if($width<I('get.minw') || $height<I('get.minh')){
				@unlink('./static/uploadfiles/images_tmp/'.$info[0]['savename']);
				winback('图标分辨率最低标准为宽度：'.I('get.minw').'px, 高度：'.I('get.minh').'px');
			}else{
				$data['imgurl'] = '/static/uploadfiles/images_tmp/'.$info[0]['savename'];//上传后的文件名地址 
				imgresize('./static/uploadfiles/images_tmp/'.$info[0]['savename'],'./static/uploadfiles/images_tmp/'.$info[0]['savename'],500,500);
				$this->assign('data',$data);
				$this->display();
			}
		 }
	}
	function backavatar(){
		@unlink('.'.I('get.url'));
		echo "<script>
    			try{
    				eval( 'window.parent.api_fancybox_close()' );
    			}catch(e){}
    			</script>";
		//redirect('/user/avatar');
	}
	/*
	##############################
	####	保存上傳圖片
	####	1.0
	##############################
	*/
	public function avatar_save(){
		$x = $_POST['x'];//客户端选择区域左上角x轴坐标  
		$y = $_POST['y'];//客户端选择区域左上角y轴坐标  
		$w = $_POST['w'];//客户端选择区 的宽  
		$h = $_POST['h'];//客户端选择区 的高  
		$filename = '.'.$_POST['pic'];//图片的路径
		$farr = explode('.',$filename);
		$ext = strtolower($farr[count($farr)-1]);
		// 读取需要处理的图片  
		switch($ext) { 
			case 'gif': $im = imagecreatefromgif($filename); break; 
			case 'png': $im = imagecreatefrompng($filename); break; 
			default: $im = imagecreatefromjpeg($filename); break; 
		} 
		$newim = imagecreatetruecolor(160, 160);//产生新图片 100 100 为新图片的宽和高  
switch ($ext)
{
 case 'png':
  // integer representation of the color black (rgb: 0,0,0)
  $background = imagecolorallocate($newim, 0, 0, 0);
  // removing the black from the placeholder
  imagecolortransparent($newim, $background);
  // turning off alpha blending (to ensure alpha channel information 
  // is preserved, rather than removed (blending with the rest of the 
  // image in the form of black))
  imagealphablending($newim, false);
  // turning on alpha channel information saving (to ensure the full range 
  // of transparency is preserved)
  imagesavealpha($newim, true);
  break;
 case 'gif':
  // integer representation of the color black (rgb: 0,0,0)
  $background = imagecolorallocate($newim, 0, 0, 0);
  // removing the black from the placeholder
  imagecolortransparent($newim, $background);
  break;
}
		imagecopyresampled($newim, $im, 0, 0, $x, $y, 160, 160, $w, $h);
		//                  [1]    [2] [3][4] [5] [6] [7]  [8]  [9] [10]  
		//[5]  客户端选择区域左上角x轴坐标  
		//[6]  客户端选择区域左上角y轴坐标  
		//[7]  生成新图片的宽  
		//[8]  生成新图片的高  
		//[9]  客户端选择区 的宽  
		//[10] 客户端选择区 的高
		$uploadfile = str_replace('/images_tmp/','/images/',$filename);
		switch($ext) { 
			case 'gif': imagegif($newim, $uploadfile); break; 
			case 'png': imagepng($newim, $uploadfile); break; 
			default: imagejpeg($newim, $uploadfile); break; 
		} 
		imagedestroy($im);
		imagedestroy($newim);
		@unlink($filename);
		$fname = explode('/images/',$uploadfile);
		/*
		$fext = explode('.',$fname[1]);
		$thumb = $fname[0].'/product/'.$fext[0].'_thumb.'.$fext[1];
		copy($uploadfile,$thumb);
		imgresize($thumb,$thumb,250,250);
		*/
		//  save to database
		$d['type'] = 'user_avatar';
		$d['targetid'] = session('uid');
		$d['name'] = $fname[1];
		$d['ctime'] = now();
		D('a_images')->add($d);
		echo $uploadfile;
	}
    function refix_user() {
		$user = $this->user;
		if($user==0) wingo('请先登录！', '/');
		//$d = $_POST;
		$d['nickname'] = I('post.nickname');
		$d['qq'] = I('post.qq');
		$d['tel'] = I('post.tel');
		$d['desc'] = I('post.desc');
		$d['birth'] = I('post.birth');
		$d['gander'] = I('post.gander');
		$d['address'] = I('post.address');
		$d['introduce'] = I('post.introduce');
		$d['realname_show'] = I('post.realname_show');
		$d['email_show'] = I('post.email_show');
		$d['mobile_show'] = I('post.mobile_show');
		$d['qq_show'] = I('post.qq_show');
		if($d['realname_show']=='1') $d['realname_show'] = 0;
		else $d['realname_show'] = 1;
		if($d['mobile_show']=='1') $d['mobile_show'] = 0;
		else $d['mobile_show'] = 1;
		if($d['qq_show']=='1') $d['qq_show'] = 0;
		else $d['qq_show'] = 1;
		if($d['email_show']=='1') $d['email_show'] = 0;
		else $d['email_show'] = 1;
		$d['shenfen'] = ','.implode(',',$_POST['sf']).',';
        $id = D('a_user')->where('id='.$user['id'])->save($d);
		wingo('保存成功','/user/my');
	}
	function sms_vcode(){
		if(!I('get.mobile')){
			$r['rstatus'] = 0;
			$r['info'] = '请输入手机号码！';
			die(json_output($r));
		}else{
	    	$mobile = I('get.mobile');
			if(!session('uid')){
				$r['rstatus'] = 0;
				$r['info'] = '请先登录！';
				die(json_output($r));
				exit;
			}
			$u = D('a_user')->where('isuse=1 and mobile=\''.$mobile.'\'')->find();
			if($u['id']==session('uid')){
				$r['rstatus'] = 0;
				$r['info'] = '不得验证当前使用的号码！';
				die(json_output($r));
				exit;
			}
			if($u){
				$r['rstatus'] = 0;
				$r['info'] = '该号码已经被其他用户验证！';
				die(json_output($r));
				exit;
			}
	    	$code = rand(1000,9999);
	    	session('smscode',$code);
	    	$json = file_get_contents('http://api.bindow.cn/sms/sent?sn=sms_sdk2118&sp=46cc468df60c961d8da2326337c7aa58&md5=f&tel='.$mobile.'&msg=您的验证码是：'.$code);
			$r = json_decode($json,1);
			die(json_output($r));
			//  旧方案
	    	load('@.bd_sms');
	    	$auth = I('get.auth');
		    	// 防伪，放入前端Function
		    	$auth = md5(time());
		    	session('sms_auth',$auth);
	    	$code = rand(1000,9999);
	    	session('smscode',$code);
	    	$mobile = I('get.mobile');
	    	$r = qcloudsms_sent_vcode($auth,$mobile,$code);
	    }
		die(json_output($r));
	}
	function refix_mobile() {
		$user = $this->user;
		if($user==0) wingo('请先登录！', '/');
		if(session('smscode')!=I('post.code')){
			wingo('短信验证码错误!','/user/binding');
		}
		session('smscode',null);
		$d = $_POST;
		$d['mobile_v'] = 1;
	    $id = D('a_user')->where('id='.$user['id'])->save($d);
		wingo('绑定成功!','/user/my');
	}
	function refix_mail(){
		if(!session('uid')){
			winback( '请先登录！');
		}
		$r = D('a_user')->where('id!='.session('uid').' and email=\''.I('post.mail').'\' and isuse=1 and email_v=1')->find();
		if($r) winback( '此邮箱已注册！');
		$d['email'] = I('post.mail');
		$d['email_code'] = md5(time());
		D('a_user')->where('id='.session('uid'))->save($d);
		$mail_api = $this->mail_reg($d['email'],main_domain().'index/mail_v?code='.$d['email_code']);
		$mail = explode('|',$mail_api);
		if($mail[0]=='0') winback($mail[1]);
		wingo('请查收邮件，绑定成功即可邮箱登录！','/user/my');
	}
	private function mail_reg($to,$link){
		require_once("./static/plugs/Mailer/phpmailer.php");
		$subject = '探图网注册账号验证码';
        $address = $to;
        $mailer = new PHPMailer();
        $mailer->Host = 'smtp.exmail.qq.com';//'smtp.tg-cyber.com';
		$mailer->Port = 465;
        $mailer->IsSMTP();
        $mailer->SMTPAuth = true;
        $mailer->IsHTML(true);
        $mailer->From = 'customer@tantupix.com';//'tgc_william.fu@tg-cyber.com';
        $mailer->AddAddress($address);
        $mailer->FromName = 'TantuPix';
        $mailer->Username = 'customer@tantupix.com';//'tgc_william.fu';
        $mailer->Password = 'bingo37589885GZ';
        $mailer->SMTPDebug = false;   //设置SMTPDebug为true，就可以打开Debug功能，根据提示去修改配置
        $mailer->SMTPSecure = "ssl";
        $mailer->CharSet = 'UTF-8';
        $mailer->Subject = $subject;
		$content = '<html><head></head><body>感谢您注册探图网<br>点击以下链接完成邮箱绑定，成功绑定后可使用邮箱登录及找回密码！<br><br>'.$link.'</body></html>';
        //$mailer->Body = $content;
		//$body = $content;
		//$mailer->Body = str_replace('<{body}>',$body,$this->Body);
		//$mailer->Body = preg_replace('/\\\\/','', $this->Body); //Strip backslashes
		$mailer->Body = $content;
        $send = $mailer->Send();
        if ($send) {
			return '1|';
        } else {
            return "0|错误原因: " . $mailer->ErrorInfo;
        }
	}
    function refix_pwd() {
		$user = $this->user;
		if($user==0) wingo('请先登录！', '/');
		$w['password'] = I('post.password');
		$r = D('a_user')->where('id='.$user['id'])->find();
		if($r['password']==md5(I('post.password')) || $r['password']==''){
			if($r['isuse']!=1){
				wingo('您的帐户已失效!','/');
			}
			$d['password'] = md5(I('post.password3'));
			$d['lastlogin'] = now();
			D('a_user')->where('id='.$r['id'])->save($d);
			wingo('密码修改成功','/user/modify');
		}
		else wingo('旧密码输入错误!','/user/modify');
	}
    public function authent() {
		$data['user'] = $this->user;
		$data['banner'] = $this->banner;
        $data['headmenu'] = 'ziliao';
        $data['menu'] = 'authent';
        $this->assign('data', $data);
        $this->display();
    }
    	public function upavatar(){
    		$user = $this->user;
    		import('ORG.Net.UploadFile');
    		$upload = new UploadFile();// 实例化上传类
    		$upload->maxSize  = 3145728 ;// 设置附件上传大小
    		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
    		$upload->savePath =  './static/uploadfiles/images/';// 设置附件上传目录
    		 if(!$upload->upload()) {// 上传错误提示错误信息
    			//$this->error($upload->getErrorMsg());
    			//D('log')->add(array('ctime'=>now(),'txt'=>'user_avatar:uid='.$id.'&err='.$upload->getErrorMsg()));
    		}else{// 上传成功 获取上传文件信息
    			$w['id'] = $user['id'];
    			$r = D('a_user')->where($w)->find();
    			if($r['avatarimg']!=''){
    				@unlink('./static/uploadfiles/images/'.$r['avatarimg']);
    			}
    			$info =  $upload->getUploadFileInfo();
    			//$d['avatarimg'] = $info[0]['savename'];
    			//D('a_user')->where($w)->save($d);
				imgresize('./static/uploadfiles/images/'.$info[0]['savename'],'./static/uploadfiles/images/'.$info[0]['savename'],160,160);
				$d['type'] = 'user_avatar';
				$d['targetid'] = $user['id'];
				$d['name'] = $info[0]['savename'];
				$d['ctime'] = now();
				D('a_images')->add($d);
    			echo "<script>
    			try{
    				eval( 'window.parent.api_upload1(\'".$d['name']."\')' );
    			}catch(e){}
    			</script>";
    			exit;
    			//redirect('/user/ziliao');
    		}
    	}
    	public function sfz1(){
    		$user = $this->user;
    		import('ORG.Net.UploadFile');
    		$upload = new UploadFile();// 实例化上传类
    		$upload->maxSize  = 3145728 ;// 设置附件上传大小
    		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
    		$upload->savePath =  './static/uploadfiles/authend/';// 设置附件上传目录
    		 if(!$upload->upload()) {// 上传错误提示错误信息
    			//$this->error($upload->getErrorMsg());
    			//D('log')->add(array('ctime'=>now(),'txt'=>'user_avatar:uid='.$id.'&err='.$upload->getErrorMsg()));
    		}else{// 上传成功 获取上传文件信息
    			$w['id'] = $user['id'];
    			$r = D('a_user')->where($w)->find();
    			if($r['idcardimgb']!=''){
    				@unlink('./static/uploadfiles/authend/'.$r['idcardimgb']);
    			}
    			$info =  $upload->getUploadFileInfo();
    			$d['idcardimgb'] = $info[0]['savename'];
    			D('a_user')->where($w)->save($d);
    			echo "<script>
    			try{
    				eval( 'window.parent.api_upload1(\'".$d['idcardimgb']."\')' );
    			}catch(e){}
    			</script>";
    			exit;
    			//redirect('/user/ziliao');
    		}
    	}
		public function sfz0(){
			$user = $this->user;
			import('ORG.Net.UploadFile');
			$upload = new UploadFile();// 实例化上传类
			$upload->maxSize  = 3145728 ;// 设置附件上传大小
			$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
			$upload->savePath =  './static/uploadfiles/authend/';// 设置附件上传目录
			 if(!$upload->upload()) {// 上传错误提示错误信息
				//$this->error($upload->getErrorMsg());
				//D('log')->add(array('ctime'=>now(),'txt'=>'user_avatar:uid='.$id.'&err='.$upload->getErrorMsg()));
			}else{// 上传成功 获取上传文件信息
				$w['id'] = $user['id'];
				$r = D('a_user')->where($w)->find();
				if($r['idcardimga']!=''){
					@unlink('./static/uploadfiles/authend/'.$r['idcardimga']);
				}
				$info =  $upload->getUploadFileInfo();
				$d['idcardimga'] = $info[0]['savename'];
				D('a_user')->where($w)->save($d);
				echo "<script>
				try{
					eval( 'window.parent.api_upload0(\'".$d['idcardimga']."\')' );
				}catch(e){}
				</script>";
				exit;
				//redirect('/user/ziliao');
			}
		}
    public function account() {
		$data['user'] = $this->user;
		$data['banner'] = $this->banner;
        $data['headmenu'] = 'ziliao';
        $data['menu'] = 'account';
        $data['cashtype'] = D('syscode')->where('syscode_parentid=299')->select();
        $data['cashbank'] = D('vw_syscode')->where('syscode_parentid=300')->select();
        $this->assign('data', $data);
        $this->display();
    }
    public function binding() {
		$data['user'] = $this->user;
		$data['banner'] = $this->banner;
        $data['headmenu'] = 'ziliao';
        $data['menu'] = 'binding';
        $this->assign('data', $data);
        $this->display();
    }
    public function modify() {
		$data['user'] = $this->user;
		$data['banner'] = $this->banner;
        $data['headmenu'] = 'ziliao';
        $data['menu'] = 'modify';
        $this->assign('data', $data);
        $this->display();
    }
//2期	
    public function album() {
		$data['user'] = $this->user;
		$data['banner'] = $this->banner;
        $data['headmenu'] = 'album';
        $data['folder'] = D('a_folder')->where('isuse=1 and uid='.session('uid'))->order('id desc')->select();
        foreach ($data['folder'] as $key => $value) {
        	$data['folder'][$key]['img_count'] = D('a_product')->where('isuse=1 and uid='.session('uid').' and folderid='.$value['id'])->count();
        	$r = D('a_product')->field('imgkey,size')->where('folderid='.$value['id'].' and isuse=1 and id='.$value['topimgid'])->find();
        	if($value['topimgid']==0 || !$r){
        		//$data['folder'][$key]['topimg'] = '/static/images/userhead.jpg';
        		$data['folder'][$key]['topimg'] = '/static/uploadfiles/xie.png';
        		if($data['folder'][$key]['img_count']>0){
        			$w = 'isuse=1 and `status`=1 and isfree=1 and uid='.session('uid').' and folderid='.$value['id'];
        			$img = D('a_product')->field('imgkey,size')->where($w)->order('id desc')->find();
        			if($img) $data['folder'][$key]['topimg'] = imgurl_t1($img['imgkey'],$img['size']);
        		}
        	}else $data['folder'][$key]['topimg'] = imgurl_t1($r['imgkey'],$r['size']);
        }
        if(I('get.p')) die(dump($data));
        $this->assign('data', $data);
        $this->display();
    }
    public function album_list() {
    	$data['user'] = $this->user;
    	$data['banner'] = $this->banner;
        $data['headmenu'] = 'album';
        $uid = session('uid');
        $data['folder'] = D('a_folder')->where('isuse=1 and uid='.$uid.' and id='.I('get.id'))->order('id desc')->find();
        $data['folder_list'] = D('a_folder')->where('isuse=1 and uid='.session('uid'))->order('id desc')->select();
        $data['sql'] = 'haspass=none&per=20&fy=t&id='.I('get.id').'&uid='.I('get.uid').'&folderid='.I('get.id').'&curpage='.I('get.curpage',1);
        $data['fans_count'] = D('a_user_ff')->where('isuse=1 and tuid='.$uid)->count();
        $data['followers_count'] = D('a_user_ff')->where('isuse=1 and uid='.$uid)->count();
        if(I('get.p')) die(dump($data));
        $this->assign('data', $data);
        $this->display();
    }
    function album_topimg(){
    	$w['id'] = I('get.id');
    	$d['topimgid'] = I('get.pid');
    	D('a_folder')->where($w)->save($d);
    	$r['status'] = 1;
    	echo json_output($r);
    }
    function img_moveto() {
    	$d['folderid'] = I('get.fid');
    	D('a_product')->where('uid='.session('uid').' and id in ('.I('get.ids').')')->save($d);
    	$r['status'] = 1;
    	echo json_output($r);
    }
    function img_del() {
    	//标记删除
    	$d['isuse'] = 0;
		D('a_product')->where('uid='.session('uid').' and id in ('.I('get.ids').')')->save($d);
		
		//删除关联表		
		//删除活动关联表
		$eventids = D('a_event_images')->where("user_id = ".session('uid')." and product_id in (".I('get.ids').")")->getField('event_id',true);
		D('a_event_images')->where("user_id = ".session('uid')." and product_id in (".I('get.ids').")")->delete();

		//更新活动表数据
		foreach ($eventids as $key => $value) {
			D()->execute('update bdmis_a_event t1, (select count(event_id) c1, count(distinct(user_id)) c2 from bdmis_a_event_images where event_id='.$value.')t2 set t1.product_count=t2.c1,t1.user_count = t2.c2 where t1.id ='.$value);
		}

		$r['status'] = 1;
		echo json_output($r);
    }
    function album_upt(){
    	$d['foldername'] = I('get.name');
    	D('a_folder')->where('id='.I('get.id'))->save($d);
    	$data['status'] = 1;
    	$data['info'] = '修改成功！';
    	echo json_output($data);
    }
    //  获取本人所有图片
	function getpics(){
        $uid = session('uid');
        $data['list'] = D('a_product')->field('imgkey')->where('haspass=1 and isuse=1 and uid='.$uid)->order('id desc')->select();
        foreach($data['list'] as $k=>$v){
    		$data['list'][$k]['imgurl'] = imgurl_t1($v['imgkey'],$v['size']);
    	}
    	echo json_output($data);
	}
	function setpics(){
		$u = D('a_user')->field('usertopimg')->where('id='.session('uid'))->find();
		if($u['usertopimg']){
			unlink('./static/uploadfiles/albumimg/'.$u['usertopimg']);
			unlink('./static/uploadfiles/albumimg/t_'.$u['usertopimg']);
		}
		$d['usertopimg'] = I('get.key');
    	D('a_user')->where('id='.session('uid'))->save($d);
    	echo imgurl_t3(I('get.key'),0);
	}
    function abanner_up(){
    	import('ORG.Net.UploadFile');
    	$upload = new UploadFile();// 实例化上传类
    	$upload->maxSize  = 314572800;// 设置附件上传大小
    	$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
    	$upload->savePath =  './static/uploadfiles/albumimg/';// 设置附件上传目录
    	 if(!$upload->upload()) {// 上传错误提示错误信息
    		//$this->error($upload->getErrorMsg());
    		echo "<script>
    		try{
    			eval( 'window.parent.api_avatar_err2(\'".$upload->getErrorMsg()."\')' );
    		}catch(e){}
    		</script>No";
    		exit;
    	 }else{// 上传成功 获取上传文件信息
    		$info =  $upload->getUploadFileInfo();
    		list($width, $height, $type, $attr) = getimagesize('./static/uploadfiles/albumimg/'.$info[0]['savename']);
    		/*if($width<1900 || $height<260){
    			unlink('./static/uploadfiles/albumimg/'.$info[0]['savename']);
    			//winback('图标分辨率最低标准为宽度：'.I('get.minw').'px, 高度：'.I('get.minh').'px');
    			echo "<script>
    			try{
    				eval( 'window.parent.api_avatar_err(\'图标分辨率最低标准为宽度：1900px, 高度：600px\')' );
    			}catch(e){}
    			</script>No";
    			exit;
    		}else{*/
    			$u = D('a_user')->field('usertopimg')->where('id='.session('uid'))->find();
    			if($u['usertopimg']){
    				unlink('./static/uploadfiles/albumimg/'.$u['usertopimg']);
    				unlink('./static/uploadfiles/albumimg/t_'.$u['usertopimg']);
    			}
    			$d['usertopimg'] = $info[0]['savename'];
    			D('a_user')->where('id='.session('uid'))->save($d);
    			//$data['imgurl'] = '/static/uploadfiles/images_tmp/'.$info[0]['savename'];//上传后的文件名地址 
    			imgresize('./static/uploadfiles/albumimg/'.$info[0]['savename'],'./static/uploadfiles/albumimg/t_'.$info[0]['savename'],1920,600);
    			echo "<script>
    			try{
    				eval( 'window.parent.api_avatar_ok2(\'".$info[0]['savename']."\')' );
    			}catch(e){}
    			</script>Yes";
    			exit;
    		//}
    	 }
    }
    public function homepage() {
		$data['user'] = $this->user;
		$data['banner'] = $this->banner;
        $data['headmenu'] = 'homepage';
        $data['menu'] = 'modify';
        $data['fav'] = D('a_user_fav')->field('bdmis_a_user_fav.*,bdmis_a_product.name,bdmis_a_product.imgkey,bdmis_a_product.size')->where('bdmis_a_user_fav.isuse=1 and bdmis_a_product.isuse=1 and bdmis_a_product.status=1 and bdmis_a_product.haspass=1 and bdmis_a_user_fav.uid='.session('uid'))->join('bdmis_a_product on bdmis_a_product.id=bdmis_a_user_fav.productid')->order('bdmis_a_user_fav.id desc')->limit(6)->select();
        foreach($data['fav'] as $k=>$v){
        	$data['fav'][$k]['imgurl'] = imgurl_t1($v['imgkey'],$v['size']);
        }
        $data['fav_count'] = count($data['fav']);
        $data['visit'] = D('a_product_visit')->field('bdmis_a_product_visit.*,bdmis_a_product.name,bdmis_a_product.imgkey,bdmis_a_product.size')->where('bdmis_a_product_visit.isuse=1 and bdmis_a_product.isuse=1 and bdmis_a_product.status=1 and bdmis_a_product.haspass=1 and bdmis_a_product_visit.uid='.session('uid'))->join('bdmis_a_product on bdmis_a_product.id=bdmis_a_product_visit.productid')->order('bdmis_a_product_visit.id desc')->limit(6)->select();
        //die(D()->getlastsql());
        foreach($data['visit'] as $k=>$v){
        	$data['visit'][$k]['imgurl'] = imgurl_t1($v['imgkey'],$v['size']);
        }
        $data['visit_count'] = count($data['visit']);
        $data['num1'] = D('a_product')->where('haspass=-2 and isuse=1 and uid='.session('uid'))->count();
        $data['num2'] = D('a_product')->where('haspass=0 and isuse=1 and uid='.session('uid'))->count();
        $data['num3'] = D('a_product')->where('haspass=-1 and isuse=1 and uid='.session('uid'))->count();
        $data['num4'] = D('a_product')->where('haspass=1 and isuse=1 and uid='.session('uid'))->count();
        if(I('get.p')) die(dump($data));
        $this->assign('data', $data);
        $this->display();
    }
    public function homepage_pending() {
		$data['user'] = $this->user;
		$data['banner'] = $this->banner;
        $data['headmenu'] = 'homepage_pending';
        $haspass = I('get.status','-2');
        $data['w'] = $haspass;
        $page['len'] = D('a_product')->where('haspass='.$haspass.' and isuse=1 and uid='.session('uid'))->count();
        $page['per'] = I('get.per',20);
        $page = page($page);
        $data['imgs'] = D('a_product')->where('haspass='.$haspass.' and isuse=1 and uid='.session('uid'))->order('id desc')->limit($page['limit'])->select();
        foreach($data['imgs'] as $k=>$v){
        	$data['imgs'][$k]['imgurl'] = imgurl_t1($v['imgkey'],$v['size']);
        }
        $data['num1'] = D('a_product')->where('haspass=-2 and isuse=1 and uid='.session('uid'))->count();
        $data['num2'] = D('a_product')->where('haspass=0 and isuse=1 and uid='.session('uid'))->count();
        $data['num3'] = D('a_product')->where('haspass=-1 and isuse=1 and uid='.session('uid'))->count();
        $data['num4'] = D('a_product')->where('haspass=1 and isuse=1 and uid='.session('uid'))->count();
        $data['fanye'] = glb_fystyle(I('get.curpage',1),$page['tol'],$page['len']);
        if(I('get.p')) die(dump($data));
        $this->assign('data', $data);
        if($haspass==='-2'){
          $this->display('homepage_pending_process');
        }
        else{
          $this->display();
        }
    }
    public function production() {
    	$data['user'] = $this->user;
    	$data['banner'] = $this->banner;
        $data['headmenu'] = 'homepage_pending';
        $data['r'] = D('a_product')->where('id='.I('get.id').' and isuse=1 and uid='.session('uid'))->find();
        if(!$data['r']) wingo('没有找到改图片资料!','/user/homepage_pending');
        $data['r']['imgurl'] = imgurl_t1($data['r']['imgkey'],$data['r']['size']);
        if($data['r']['thumbimg']){
        	$data['r']['upthumb'] = 1;
        	$data['r']['imgurl'] = '/static/uploadfiles/imgthumb/'.$data['r']['thumbimg'];
        }
        $data['r']['imgtype'] = cstr($data['r']['imgtype'],'1');
        $data['r']['shouquan'] = cstr($data['r']['shouquan'],'1');
        $data['r']['banquantype'] = cstr($data['r']['banquantype'],'1');
        $data['taglist'] = explode(' ',trim($data['r']['taglist']));
        $w['isuse'] = 1;
        $data['class'] = D('a_class')->where($w)->select();
        $data['color'] = D('a_color')->where($w)->select();
        $w['uid'] = session('uid');
        $data['tags'] = D('a_tags')->where($w)->select();
        $data['folder'] = D('a_folder')->where('isuse=1 and uid='.session('uid'))->order('id desc')->select();
        $data['num1'] = D('a_product')->where('haspass=-2 and isuse=1 and uid='.session('uid'))->count();
        $data['num2'] = D('a_product')->where('haspass=0 and isuse=1 and uid='.session('uid'))->count();
        $data['num3'] = D('a_product')->where('haspass=-1 and isuse=1 and uid='.session('uid'))->count();
        $data['num4'] = D('a_product')->where('haspass=1 and isuse=1 and uid='.session('uid'))->count();
        if(I('get.p')) die(dump($data));
        $this->assign('data', $data);
        $this->display();
    }

     public function production_muti() {
    	$data['user'] = $this->user;
    	$data['banner'] = $this->banner;
        $data['headmenu'] = 'homepage_pending';

        $data['r']=null;
        // $data['r'] = D('a_product')->where('id in ('.I('get.id').') and isuse=1 and uid='.session('uid'))->select();
        // if(!$data['r']) wingo('没有找到改图片资料!','/user/homepage_pending');

        // $data['r']['imgurl'] = imgurl_t1($data['r']['imgkey'],$data['r']['size']);
        // if($data['r']['thumbimg']){
        // 	$data['r']['upthumb'] = 1;
        // 	$data['r']['imgurl'] = '/static/uploadfiles/imgthumb/'.$data['r']['thumbimg'];
        // }
        // $data['r']['imgtype'] = cstr($data['r']['imgtype'],'1');
        // $data['r']['shouquan'] = cstr($data['r']['shouquan'],'1');
        // $data['r']['banquantype'] = cstr($data['r']['banquantype'],'1');
        // $data['taglist'] = explode(' ',trim($data['r']['taglist']));
        $w['isuse'] = 1;
        $data['class'] = D('a_class')->where($w)->select();
        $data['color'] = D('a_color')->where($w)->select();
        $w['uid'] = session('uid');
        $data['tags'] = D('a_tags')->where($w)->select();
        $data['folder'] = D('a_folder')->where('isuse=1 and uid='.session('uid'))->order('id desc')->select();
        $data['num1'] = D('a_product')->where('haspass=-2 and isuse=1 and uid='.session('uid'))->count();
        $data['num2'] = D('a_product')->where('haspass=0 and isuse=1 and uid='.session('uid'))->count();
        $data['num3'] = D('a_product')->where('haspass=-1 and isuse=1 and uid='.session('uid'))->count();
        $data['num4'] = D('a_product')->where('haspass=1 and isuse=1 and uid='.session('uid'))->count();
        if(I('get.p')) die(dump($data));
        $this->assign('data', $data);
        $this->display('production');
    }


    	function get_tags(){
    		$data['list'] = D('a_tags')->where('classids like \'%,'.I('get.cid').',%\'')->order('RAND()')->limit(10)->select();
    		$data['count'] = count($data['list']);
    		echo json_output($data);
    	}
	    function imgthumb_up(){
	    	import('ORG.Net.UploadFile');
	    	$upload = new UploadFile();// 实例化上传类
	    	$upload->maxSize  = 3145728 ;// 设置附件上传大小
	    	$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
	    	$upload->savePath =  './static/uploadfiles/imgthumb/';// 设置附件上传目录
	    	 if(!$upload->upload()) {// 上传错误提示错误信息
	    		//$this->error($upload->getErrorMsg());
	    		echo "<script>
	    		try{
	    			eval( 'window.parent.api_avatar_err(\'".$upload->getErrorMsg()."\')' );
	    		}catch(e){}
	    		</script>No";
	    		exit;
	    	 }else{// 上传成功 获取上传文件信息
	    		$info =  $upload->getUploadFileInfo();
    			$u = D('a_product')->field('thumbimg')->where('uid='.session('uid').' and id='.I('get.id'))->find();
    			if(!$u){
	    			echo "<script>
	    			try{
	    				eval( 'window.parent.api_avatar_err(\'No Image\')' );
	    			}catch(e){}
	    			</script>No";
	    			exit;
    			}
    			if($u['thumbimg']) unlink('./static/uploadfiles/imgthumb/'.$u['thumbimg']);
    			$d['thumbimg'] = $info[0]['savename'];
    			$d['haspass'] = -2;
    			D('a_product')->where('id='.I('get.id'))->save($d);
    			//$data['imgurl'] = '/static/uploadfiles/images_tmp/'.$info[0]['savename'];//上传后的文件名地址 
    			imgresize('./static/uploadfiles/imgthumb/'.$info[0]['savename'],'./static/uploadfiles/imgthumb/'.$info[0]['savename'],600,400);
    			echo "<script>
    			try{
    				eval( 'window.parent.api_avatar_ok(\'".$info[0]['savename']."\')' );
    			}catch(e){}
    			</script>Yes";
    			exit;
	    	 }
	    }
	//  执行编辑保存
	public function production_save(){
		if (!session('uid')) {
			return;
		}
		$is_mutiple = (FALSE!==strpos(I('post.id'),','));
		$arr = explode(',', I('post.id'));
		foreach ($arr as $key => $value) {
			$d = $_POST;
			$d['id']= $value;
			$d['haspass'] = 1;//直接为审核通过
			$d['banquan1'] = I('post.banquan1','0');
			$d['banquan2'] = I('post.banquan2','0');
			$class = explode(',',I('post.classid'));
			$d['classids'] = ','.$class[0].',';
			$d['classlist'] = $class[1];
			$tags = $d['taglist'];
			$arr = explode(" ",$tags);
			$tag_str = '';
			$outs = false;
			$out = '\n\r以下文字在标签栏位不允许使用：';
			for($i=0;$i<count($arr);$i++){
				if($arr[$i]!=''){
					$r = D('a_tags')->field('id,isuse')->where('tagname=\''.$arr[$i].'\'')->find();
					if($r){
						if($r['isuse']) $tag_str .= ','.$r['id'];
						else{
							$outs = true;
							$out .= $arr[$i].',';
						}
					}else{
						$tag_d['tagname'] = $arr[$i];
						$tag_d['isuse'] = 1;
						$tag_d['ctime'] = now();
						$id = D('a_tags')->add($tag_d);
						$tag_str .= ','.$id;
					}
				}
			}
			$tag_str .= ',';
			$d['tagids'] = $tag_str;
			D('a_product')->save($d);
		}

		if(!$is_mutiple){
			if($outs) $ostr = '保存成功!'.$out;
			else $ostr = '保存成功!';
			$go = D('a_product')->field('id')->where('id<'.$d['id'].' and haspass='.I('get.haspass').' and isuse=1 and uid='.session('uid'))->order('id desc')->find();
			if($go) wingo($ostr,'/user/production?id='.$go['id'].'&haspass='.I('get.haspass').'&page='.I('get.page'));
			else wingo($ostr,'/user/homepage_pending?status='.I('get.haspass').'&curpage='.I('get.page'));
		}
		else{
			wingo('保存成功','/user/homepage_pending?status='.I('get.haspass').'&curpage='.I('get.page'));
		}
	}

    public function upload() {
		$data['user'] = $this->user;
		$data['banner'] = $this->banner;
        $data['headmenu'] = 'upload';
        $r = D('a_folder')->where('def=1 and uid='.session('uid'))->find();
        if(!$r){
        	$d['uid'] = session('uid');
        	$d['foldername'] = '默认专辑';
        	$d['ctime'] = now();
        	$d['def'] = 1;
        	$data['fid'] = D('a_folder')->add($d);
        }else $data['fid'] = $r['id'];
        $this->assign('data', $data);
        $this->display();
    }
    function get_folder(){
    	$data['list'] = D('a_folder')->where('isuse=1 and uid='.session('uid'))->select();
    	$data['count'] = count($data['list']);
    	echo json_output($data);
    }
    function new_folder(){
    	$d['uid'] = session('uid');
    	$d['foldername'] = I('get.fname');
    	$d['ctime'] = now();
    	$data['id'] = D('a_folder')->add($d);
    	$data['foldername'] = I('get.fname');
    	$data['status'] = 1;
    	echo json_output($data);
    }
    function folder_del(){
    	$r = D('a_product')->where('isuse=1 and folderid='.I('get.id'))->count();
    	if($r>0){
    		$data['status'] = 0;
    		$data['info'] = '该相册内还有图片，不得删除！';
    	}else{
    		$d['isuse'] = 0;
    		D('a_folder')->where('id='.I('get.id'))->save($d);
    		$data['status'] = 1;
    		$data['info'] = '删除成功！';
    	}
    	echo json_output($data);
    }
	//  收藏
	public function collection() {
		$data['user'] = $this->user;
		$data['banner'] = $this->banner;
	    $data['headmenu'] = 'collection';
	    $data['list'] = D('a_favclass')->where('isuse=1 and uid='.session('uid'))->order('id desc')->select();
	    foreach ($data['list'] as $key => $value) {
	    	/*
	    	$data['list'][$key]['img_count'] = D('a_user_fav')->where('isuse=1 and uid='.session('uid').' and favclassid='.$value['id'])->count();
	    	$r = D('a_product')->field('imgkey,size')->where('isuse=1 and id='.$value['topimgid'])->find();
	    	if($value['topimgid']==0 || !$r){
	    		$data['list'][$key]['topimg'] = '/static/uploadfiles/xie.png';
	    	}else $data['list'][$key]['topimg'] = imgurl_t1($r['imgkey'],$r['size']);
	    	*/
        	$data['list'][$key]['img_count'] = D('a_user_fav')->where('isuse=1 and uid='.session('uid').' and favclassid='.$value['id'])->count();
        	$r = D('a_product')->field('imgkey,size')->where('isuse=1 and id='.$value['topimgid'])->find();
        	if($value['topimgid']==0 || !$r){
        		//$data['folder'][$key]['topimg'] = '/static/images/userhead.jpg';
        		$data['list'][$key]['topimg'] = '/static/uploadfiles/xie.png';
        		if($data['list'][$key]['img_count']>0){
        			$imgid = D('a_user_fav')->where('isuse=1 and uid='.session('uid').' and favclassid='.$value['id'])->order('id desc')->find();
        			//$w = 'isuse=1 and `status`=1 and isfree=1 and uid='.session('uid').' and folderid='.$value['id'];
        			$img = D('a_product')->field('imgkey,size')->where('id='.$imgid['productid'])->find();
        			if($img) $data['list'][$key]['topimg'] = imgurl_t1($img['imgkey'],$img['size']);
        		}
        	}else $data['list'][$key]['topimg'] = imgurl_t1($r['imgkey'],$r['size']);
	    }
	    if(I('get.p')) die(dump($data));
	    $this->assign('data', $data);
	    $this->display();
	}
	public function collection_list() {
		$data['user'] = $this->user;
		$data['banner'] = $this->banner;
	    $data['headmenu'] = 'collection';
	    $data['folder'] = D('a_favclass')->where('isuse=1 and uid='.session('uid').' and id='.I('get.id'))->order('id desc')->find();
	    $data['fav'] = D('a_user_fav')->field('bdmis_a_user_fav.*,bdmis_a_product.name,bdmis_a_product.imgkey,bdmis_a_product.size')->where('bdmis_a_user_fav.favclassid='.I('get.id').' and bdmis_a_user_fav.isuse=1 and bdmis_a_user_fav.uid='.session('uid'))->join('bdmis_a_product on bdmis_a_product.id=bdmis_a_user_fav.productid')->order('bdmis_a_user_fav.id desc')->select();
	    foreach ($data['fav'] as $key => $value) {
	    	$data['fav'][$key]['topimg'] = imgurl_t1($value['imgkey'],$value['size']);
	    }
	    if(I('get.p')) die(dump($data));
	    $this->assign('data', $data);
	    $this->display();
	}
	function get_favclass(){
		$data['list'] = D('a_favclass')->where('isuse=1 and uid='.session('uid'))->select();
		$data['count'] = count($data['list']);
		echo json_output($data);
	}
	function new_favclass(){
		$d['uid'] = session('uid');
		$d['foldername'] = I('get.fname');
		$d['ctime'] = now();
		$data['id'] = D('a_favclass')->add($d);
		$data['foldername'] = I('get.fname');
		$data['status'] = 1;
		echo json_output($data);
	}
	function favclass_del(){
		$r = D('a_user_fav')->where('isuse=1 and favclassid='.I('get.id'))->count();
		if($r>0){
			$data['status'] = 1;
			//$data['info'] = '该收藏夹内还有图片，不得删除！';
			foreach($r as $k=>$v){
				$d['isuse'] = 0;
				D('a_user_fav')->where('uid='.session('uid').' and id='.$v['id'])->save($d);
				$data['info'] = '删除成功！';
			}
		}else{
			$d['isuse'] = 0;
			D('a_favclass')->where('id='.I('get.id'))->save($d);
			$data['status'] = 1;
			$data['info'] = '删除成功！';
		}
		echo json_output($data);
	}
	function favclass_upt(){
		$d['foldername'] = I('get.name');
		$d['show'] = I('get.show');
		D('a_favclass')->where('id='.I('get.id'))->save($d);
		$data['status'] = 1;
		$data['info'] = '修改成功！';
		echo json_output($data);
	}
	function fav_topimg(){
		$w['id'] = I('get.id');
		$d['topimgid'] = I('get.pid');
		D('a_favclass')->where($w)->save($d);
		$r['status'] = 1;
		echo json_output($r);
	}
	//  消息
	public function message() {
		$data['user'] = $this->user;
		$data['banner'] = $this->banner;
	    $data['headmenu'] = 'message';
	    $w = '(uids=\'0\' || uids like \'%,'.session('uid').',%\') and ctime>\''.$data['user']['ctime'].'\'';
	    if(I('get.kw')) $w .= ' and (title like \'%'.I('get.kw').'%\' || msgs like \'%'.I('get.kw').'%\')';
	    $msg = D('a_msg')->where($w)->order('id desc')->select();
	    $read_count = 0;
	    $noread_count = 0;
	    foreach($msg as $k=>$v){
	    	$d = '';
		    $d['msgid'] = $v['id'];
		    $d['uid'] = session('uid');
	    	$r = D('a_msg_read')->where($d)->find();
	    	if($r){
	    		if($r['hasread']){
	    			if($r['isuse']) $read_count++;
	    		}else{
	    			if($r['isuse']) $noread_count++;
	    		}
	    	}else{
	    		$noread_count++;
	    		$d['ctime'] = now();
	    		D('a_msg_read')->add($d);
	    	}
	    }
	    $data['read_count'] = $read_count;
	    $data['noread_count'] = $noread_count;
	    $w = 'isuse=1 and uid='.session('uid');
	    if(I('get.kw')) $w .= ' and (title like \'%'.I('get.kw').'%\' || msgs like \'%'.I('get.kw').'%\')';
	    $data['list'] = D('a_msg_read')->field('bdmis_a_msg_read.*,bdmis_a_msg.uids,bdmis_a_msg.title,bdmis_a_msg.msgs,bdmis_a_msg.ctime as msgctime')->where($w)->join('bdmis_a_msg on bdmis_a_msg.id=bdmis_a_msg_read.msgid')->order('msgctime desc')->select();
	    if(I('get.p')) die(dump($data));
	    $this->assign('data', $data);
	    $this->display();
	}
	public function message_detail() {
		$data['user'] = $this->user;
		$data['banner'] = $this->banner;
	    $data['headmenu'] = 'message';
	    $data['r'] = D('a_msg')->where('(uids=\'0\' || uids like \'%,'.session('uid').',%\') and id='.I('get.mid'))->find();
        $w['id'] = I('get.id');
        $w['uid'] = session('uid');
        $d['hasread'] = 1;
    	$r = D('a_msg_read')->where($w)->save($d);
	    $this->assign('data', $data);
	    $this->display();
	}
	public function message_del(){
		$d['isuse'] = 0;
		D('a_msg_read')->where('id in ('.I('get.ids').')')->save($d);
		$r['status'] = 1;
		echo json_output($r);
	}
	//  认证提交
    function refix_authent() {
		$user = $this->user;
		if($user==0) wingo('请先登录！', '/');
		$d = $_POST;
		$d['isverify'] = 1;
		if(I('post.idcend')) $d['idcardedate'] = I('post.idcend');
        $id = D('a_user')->where('id='.$user['id'])->save($d);
		wingo('保存成功','/user/authent');
	}
    function refix_account() {
		$user = $this->user;
		if($user==0) wingo('请先登录！', '/');
		$d = $_POST;
        $id = D('a_user')->where('id='.$user['id'])->save($d);
		wingo('保存成功','/user/account');
	}
}
