<?php
//  首页
function getIP(){
	if (getenv("HTTP_CLIENT_IP"))
	$ip = getenv("HTTP_CLIENT_IP");
	else if(getenv("HTTP_X_FORWARDED_FOR"))
	$ip = getenv("HTTP_X_FORWARDED_FOR");
	else if(getenv("REMOTE_ADDR"))
	$ip = getenv("REMOTE_ADDR");
	else $ip = "Unknow";
	return $ip;
}
class CameristAction extends Action {
    protected function _initialize() {
        if (session('uid')) {
            $u = D('a_user')->where('id='.session('uid'))->find();
			$avatar = D('a_images')->where("type='user_avatar' and targetid=".$u['id'])->order('id desc')->find();
			$u['avatar'] = $avatar['name'];
			$this->user = $u;
        }else{
			if(cookie('fguid')){
				$u = D('a_user')->where('fguid=\''.cookie('fguid').'\' and ftime>\''.now().'\'')->find();
				if($u){
					$avatar = D('a_images')->where("type='user_avatar' and targetid=".$u['id'])->order('id desc')->find();
					$u['avatar'] = $avatar['name'];
					session('uid',$u['id']);
					$this->user = $u;
				}else{
					cookie('fguid',null);
					$this->user = 0;
				}
			}else
				$this->user = 0;
		}
		if(!I('get.uid')) redirect('/');
		//  用户访问记录
		$r = D('a_user_visit')->where('tuid='.I('get.uid').' and ip=\''.get_ip().'\' and (ctime>=\''.now(1).' 00:00:00\' and ctime<=\''.now(1).' 23:59:59\')')->find();
		if(!$r){
			$visitd['ip'] = get_ip();
			$visitd['uid'] = cstr(session('uid'),0);
			$visitd['tuid'] = I('get.uid');
			$visitd['ctime'] = now();
			//D('a_user_visit')->add($visitd);
		}
			D('a_user')->where('id='.I('get.uid'))->setInc('visit');
		//  摄影师数据
	    $target_user = D('a_user')->where('id='.I('get.uid'))->find();
		$target_user_avatar = D('a_images')->where("type='user_avatar' and targetid=".$target_user['id'])->order('id desc')->find();
		$target_user['avatar'] = $target_user_avatar['name'];
		//  关注信息
		$ffw['uid'] = session('uid');
		$ffw['tuid'] = I('get.uid');
		$ffw['isuse'] = 1;
		$target_user['followed'] = D('a_user_ff')->where($ffw)->count();
		//  输出
		$this->assign('target_user',$target_user);
		if(I('get.p')) dump($target_user);
		if($this->user){
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

        //获取最新作品的tag作为关键字
        $lastestTag = D('a_product')->where('taglist is not null and uid='.$target_user['id'])->order('id desc')->getField('taglist');
        $lastestTag = str_replace(' ', ',', trim($lastestTag));
        $this->assign('seo',array('title'=>'摄影师:'.$target_user['nickname'],'description'=>$target_user['introduce'], 'keywords'=>$lastestTag));
    }
	
    function Camerist() {
		$w['status'] = 1;
		$data['user'] = $this->user;
        $this->assign('data', $data);
        $this->display();
    }
    function ziliao() {
		$w['status'] = 1;
        $data['headmenu'] = 'ziliao';
		$data['user'] = $this->user;
		$uid = I('get.uid');
		$data['fans_count'] = D('a_user_ff')->where('isuse=1 and tuid='.$uid)->count();
		$data['followers_count'] = D('a_user_ff')->where('isuse=1 and uid='.$uid)->count();
		$data['image_count'] = D('a_product')->where('haspass=1 and status=1 and isuse=1 and uid='.$uid)->count();
		$data['folder_count'] = D('a_folder')->where('isuse=1 and uid='.$uid)->count();
		if(I('get.p')) die(dump($data));
        $this->assign('data', $data);
        $this->display();
    }
    
    public function album() {
		$data['user'] = $this->user;
		$data['banner'] = $this->banner;
        $data['headmenu'] = 'album';
        $uid = I('get.uid');
        $data['folder'] = D('a_folder')->where('isuse=1 and uid='.$uid)->order('id desc')->select();
        foreach ($data['folder'] as $key => $value) {
        	$data['folder'][$key]['img_count'] = D('a_product')->where('status=1 and haspass=1 and isuse=1 and uid='.$uid.' and folderid='.$value['id'])->count();
        	$r = D('a_product')->field('imgkey,size')->where('folderid='.$value['id'].' and isuse=1 and id='.$value['topimgid'])->find();
        	if($value['topimgid']==0 || !$r){
        		//$data['folder'][$key]['topimg'] = '/static/images/userhead.jpg';
        		$data['folder'][$key]['topimg'] = '/static/uploadfiles/xie.png';
        		if($data['folder'][$key]['img_count']>0){
        			$w = 'isuse=1 and `status`=1 and isfree=1 and uid='.$uid.' and folderid='.$value['id'];
        			$img = D('a_product')->field('imgkey,size')->where($w)->order('id desc')->find();
        			if($img) $data['folder'][$key]['topimg'] = imgurl_t1($img['imgkey'],$img['size']);
        		}
        	}else $data['folder'][$key]['topimg'] = imgurl_t1($r['imgkey'],$r['size']);
        }
        $data['fans_count'] = D('a_user_ff')->where('isuse=1 and tuid='.$uid)->count();
        $data['followers_count'] = D('a_user_ff')->where('isuse=1 and uid='.$uid)->count();
        $data['image_count'] = D('a_product')->where('haspass=1 and status=1 and isuse=1 and uid='.$uid)->count();
        $data['folder_count'] = D('a_folder')->where('isuse=1 and uid='.$uid)->count();
        if(I('get.p')) die(dump($data));
        $this->assign('data', $data);
        $this->display();
    }
    public function album_list() {
    	$data['user'] = $this->user;
    	$data['banner'] = $this->banner;
        $data['headmenu'] = 'album';
        $uid = I('get.uid');
        $data['folder'] = D('a_folder')->where('isuse=1 and uid='.$uid.' and id='.I('get.id'))->order('id desc')->find();
        $data['sql'] = 'per=20&fy=t&id='.I('get.id').'&uid='.I('get.uid').'&folderid='.I('get.id').'&curpage='.I('get.curpage',1);
        $data['fans_count'] = D('a_user_ff')->where('isuse=1 and tuid='.$uid)->count();
        $data['followers_count'] = D('a_user_ff')->where('isuse=1 and uid='.$uid)->count();
        $data['image_count'] = D('a_product')->where('haspass=1 and status=1 and isuse=1 and uid='.$uid)->count();
        $data['folder_count'] = D('a_folder')->where('isuse=1 and uid='.$uid)->count();
        if(I('get.p')) die(dump($data));
        $this->assign('data', $data);
        $this->display();
    }
    function tofollow(){
    	$data['user'] = $this->user;
    	if(!$data['user']) winback('请先登录!');
    	if($data['user']['id']==I('get.uid')) winback('不能关注自己！');
    	$d['uid'] = session('uid');
    	$d['tuid'] = I('get.uid');
    	$w = $d;
    	$w['isuse'] = 1;
    	$r = D('a_user_ff')->where($w)->find();
    	if($r){
    		$dd['isuse'] = 0;
    		$dd['mtime'] = now();
    		D('a_user_ff')->where($w)->save($dd);
			$id = D('a_user_ff')->where($w)->find();
    		echo json_output($id);
    		//redrict('/camerist/');
    	}else{
	    	$d['ctime'] = now();
	    	D('a_user_ff')->add($d);
			$id = D('a_user_ff')->where($w)->find();
    		echo json_output($id);
	    	//winback('关注成功!');
			$u = D('a_user')->where('id='.session('uid'))->find();
			$msg['uids'] = ','.I('get.uid').',';
			$msg['title'] = '关注提醒';
			$msg['msgs'] = '用户【<a href="/camerist/gallery?uid='.session('uid').'">'.cstr($u['nickname'],'匿名').'</a>】关注了你！';
			$msg['ctime'] = now();
			D('a_msg')->add($msg);
    	}
    }
    public function fans() {
		$data['user'] = $this->user;
		$data['banner'] = $this->banner;
        $data['headmenu'] = 'fans';
        $uid = I('get.uid');
        $data['fans'] = D('a_user_ff')->field('bdmis_a_user_ff.id as ffid,bdmis_a_user.*')->where('bdmis_a_user_ff.isuse=1 and bdmis_a_user_ff.tuid='.$uid)->join('bdmis_a_user on bdmis_a_user.id=bdmis_a_user_ff.uid')->order('bdmis_a_user_ff.id desc')->select();
        foreach($data['fans'] as $k=>$v){
        	$r = D('a_images')->where("type='user_avatar' and targetid=".$v['id'])->order('id desc')->find();
        	$data['fans'][$k]['avatar'] = $r['name'];
        	$ffw['uid'] = session('uid');
        	$ffw['tuid'] = $v['id'];
        	$ffw['isuse'] = 1;
        	$data['fans'][$k]['followed'] = D('a_user_ff')->where($ffw)->count();
        	$data['fans'][$k]['fans_count'] = D('a_user_ff')->where('isuse=1 and tuid='.$v['id'])->count();
        	$data['fans'][$k]['img_count'] = D('a_product')->where('status=1 and haspass=1 and isuse=1 and uid='.$v['id'])->count();
        }
        $data['fans_count'] = count($data['fans']);
        $data['followers_count'] = D('a_user_ff')->where('isuse=1 and uid='.$uid)->count();
        $data['image_count'] = D('a_product')->where('haspass=1 and status=1 and isuse=1 and uid='.$uid)->count();
        $data['folder_count'] = D('a_folder')->where('isuse=1 and uid='.$uid)->count();
        if(I('get.p')) die(dump($data));
        $this->assign('data', $data);
        $this->display();
    }
    public function followers() {
		$data['user'] = $this->user;
		$data['banner'] = $this->banner;
        $data['headmenu'] = 'followers';
        $uid = I('get.uid');
        $data['followers'] = D('a_user_ff')->field('bdmis_a_user_ff.id as ffid,bdmis_a_user.*')->where('bdmis_a_user_ff.isuse=1 and bdmis_a_user_ff.uid='.$uid)->join('bdmis_a_user on bdmis_a_user.id=bdmis_a_user_ff.tuid')->order('bdmis_a_user_ff.id desc')->select();
        foreach($data['followers'] as $k=>$v){
        	$r = D('a_images')->where("type='user_avatar' and targetid=".$v['id'])->order('id desc')->find();
        	$data['followers'][$k]['avatar'] = $r['name'];
        	$ffw['uid'] = session('uid');
        	$ffw['tuid'] = $v['id'];
        	$ffw['isuse'] = 1;
        	$data['followers'][$k]['followed'] = D('a_user_ff')->where($ffw)->count();
        	$data['followers'][$k]['fans_count'] = D('a_user_ff')->where('isuse=1 and tuid='.$v['id'])->count();
        	$data['followers'][$k]['img_count'] = D('a_product')->where('status=1 and haspass=1 and isuse=1 and uid='.$v['id'])->count();
        }
        $data['followers_count'] = count($data['followers']);
        $data['fans_count'] = D('a_user_ff')->where('isuse=1 and tuid='.$uid)->count();
        $data['image_count'] = D('a_product')->where('haspass=1 and status=1 and isuse=1 and uid='.$uid)->count();
        $data['folder_count'] = D('a_folder')->where('isuse=1 and uid='.$uid)->count();
        if(I('get.p')) die(dump($data));
        $this->assign('data', $data);
        $this->display();
    }
    public function gallery() {
		$data['user'] = $this->user;
		$data['banner'] = $this->banner;
        $data['headmenu'] = 'gallery';
        $uid = I('get.uid');
        $data['sql'] = 'per=40&fy=t&uid='.I('get.uid').'&curpage='.I('get.curpage',1);
        $data['fans_count'] = D('a_user_ff')->where('isuse=1 and tuid='.$uid)->count();
        $data['followers_count'] = D('a_user_ff')->where('isuse=1 and uid='.$uid)->count();
        $data['image_count'] = D('a_product')->where('haspass=1 and status=1 and isuse=1 and uid='.$uid)->count();
        $data['folder_count'] = D('a_folder')->where('isuse=1 and uid='.$uid)->count();
		if($data['user']){
			$data['favclass'] = D('a_favclass')->where('isuse=1 and uid='.session('uid'))->order('id desc')->select();
			foreach ($data['favclass'] as $key => $value) {
				$data['favclass'][$key]['img_count'] = D('a_user_fav')->where('isuse=1 and uid='.session('uid').' and favclassid='.$value['id'])->count();
				$r = D('a_product')->field('imgkey,size')->where('isuse=1 and id='.$value['topimgid'])->find();
				if($value['topimgid']==0 || !$r) $data['favclass'][$key]['topimg'] = '/static/images/ablumbg.png';
				else $data['favclass'][$key]['topimg'] = imgurl_t1($r['imgkey'],$r['size']);
			}
		}
        if(I('get.p')) die(dump($data));
        $this->assign('data', $data);
        $this->display();
    }
    public function goods() {
		$data['user'] = $this->user;
		$data['banner'] = $this->banner;
        $data['headmenu'] = 'goods';
        $uid = I('get.uid');
        $data['fans_count'] = D('a_user_ff')->where('isuse=1 and tuid='.$uid)->count();
        $data['followers_count'] = D('a_user_ff')->where('isuse=1 and uid='.$uid)->count();
        
        if(I('get.p')) die(dump($data));
        $this->assign('data', $data);
        $this->display();
    }
    
    //  获取本人所有图片
	function setpics(){
		$d['albumimg'] = I('get.key');
    	D('a_user')->where('id='.session('uid'))->save($d);
    	echo imgurl_tt3(I('get.key'),0);
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
    			eval( 'window.parent.api_avatar_err(\'".$upload->getErrorMsg()."\')' );
    		}catch(e){}
    		</script>No";
    		exit;
    	 }else{// 上传成功 获取上传文件信息
    		$info =  $upload->getUploadFileInfo();
    		list($width, $height, $type, $attr) = getimagesize('./static/uploadfiles/albumimg/'.$info[0]['savename']);
    		/*if($width<1900 || $height<600){
    			unlink('./static/uploadfiles/albumimg/'.$info[0]['savename']);
    			//winback('图标分辨率最低标准为宽度：'.I('get.minw').'px, 高度：'.I('get.minh').'px');
    			echo "<script>
    			try{
    				eval( 'window.parent.api_avatar_err(\'图标分辨率最低标准为宽度：1900px, 高度：600px\')' );
    			}catch(e){}
    			</script>No";
    			exit;
    		}else{*/
    			$u = D('a_user')->field('albumimg')->where('id='.session('uid'))->find();
    			if($u['albumimg']){
    				unlink('./static/uploadfiles/albumimg/'.$u['albumimg']);
    				unlink('./static/uploadfiles/albumimg/t_'.$u['albumimg']);
    			}
    			$d['albumimg'] = $info[0]['savename'];
    			D('a_user')->where('id='.session('uid'))->save($d);
    			//$data['imgurl'] = '/static/uploadfiles/images_tmp/'.$info[0]['savename'];//上传后的文件名地址 
    			imgresize('./static/uploadfiles/albumimg/'.$info[0]['savename'],'./static/uploadfiles/albumimg/'.$info[0]['savename'],1920,1200);
    			imgresize('./static/uploadfiles/albumimg/'.$info[0]['savename'],'./static/uploadfiles/albumimg/t_'.$info[0]['savename'],500,150);
    			echo "<script>
    			try{
    				eval( 'window.parent.api_avatar_ok(\'".$info[0]['savename']."\')' );
    			}catch(e){}
    			</script>Yes";
    			exit;
    		//}
    	 }
    }
    
    //  收藏
    public function collection() {
    	$data['user'] = $this->user;
    	$data['banner'] = $this->banner;
        $data['headmenu'] = 'collection';
        $uid = I('get.uid');
        $data['list'] = D('a_favclass')->where('isuse=1 and uid='.$uid)->order('id desc')->select();
        foreach ($data['list'] as $key => $value) {
        	/*
        	$data['list'][$key]['img_count'] = D('a_user_fav')->where('isuse=1 and uid='.session('uid').' and favclassid='.$value['id'])->count();
        	$r = D('a_product')->field('imgkey,size')->where('isuse=1 and id='.$value['topimgid'])->find();
        	if($value['topimgid']==0 || !$r) $data['list'][$key]['topimg'] = '/static/images/ablumbg.png';
        	else $data['list'][$key]['topimg'] = imgurl_t1($r['imgkey'],$r['size']);
        	*/
        	$data['list'][$key]['img_count'] = D('a_user_fav')->where('isuse=1 and uid='.$uid.' and favclassid='.$value['id'])->count();
        	$r = D('a_product')->field('imgkey,size')->where('isuse=1 and id='.$value['topimgid'])->find();
        	if($value['topimgid']==0 || !$r){
        		//$data['folder'][$key]['topimg'] = '/static/images/userhead.jpg';
        		$data['list'][$key]['topimg'] = '/static/uploadfiles/xie.png';
        		if($data['list'][$key]['img_count']>0){
        			$imgid = D('a_user_fav')->where('isuse=1 and uid='.$uid.' and favclassid='.$value['id'])->order('id desc')->find();
        			//$w = 'isuse=1 and `status`=1 and isfree=1 and uid='.session('uid').' and folderid='.$value['id'];
        			$img = D('a_product')->field('imgkey,size')->where('id='.$imgid['productid'])->find();
        			if($img) $data['list'][$key]['topimg'] = imgurl_t1($img['imgkey'],$img['size']);
        		}
        	}else $data['list'][$key]['topimg'] = imgurl_t1($r['imgkey'],$r['size']);
        }
        $data['fans_count'] = D('a_user_ff')->where('isuse=1 and tuid='.$uid)->count();
        $data['followers_count'] = D('a_user_ff')->where('isuse=1 and uid='.$uid)->count();
        $data['image_count'] = D('a_product')->where('haspass=1 and status=1 and isuse=1 and uid='.$uid)->count();
        $data['folder_count'] = D('a_folder')->where('isuse=1 and uid='.$uid)->count();
        if(I('get.p')) die(dump($data));
        $this->assign('data', $data);
        $this->display();
    }
    public function collection_list() {
    	$data['user'] = $this->user;
    	$data['banner'] = $this->banner;
        $data['headmenu'] = 'collection';
        $uid = I('get.uid');
        $data['folder'] = D('a_favclass')->where('isuse=1 and uid='.session('uid').' and id='.I('get.id'))->order('id desc')->find();
        if(!$data['folder']['show']) wingo('该收藏夹未开放！','/camerist/collection?uid='.$uid);
        $data['fav'] = D('a_user_fav')->field('bdmis_a_user_fav.*,bdmis_a_product.name,bdmis_a_product.imgkey,bdmis_a_product.size')->where('bdmis_a_user_fav.favclassid='.I('get.id').' and bdmis_a_user_fav.isuse=1 and bdmis_a_user_fav.uid='.session('uid'))->join('bdmis_a_product on bdmis_a_product.id=bdmis_a_user_fav.productid')->order('bdmis_a_user_fav.id desc')->select();
        foreach ($data['fav'] as $key => $value) {
        	$data['fav'][$key]['topimg'] = imgurl_t1($value['imgkey'],$value['size']);
        }
        $data['fans_count'] = D('a_user_ff')->where('isuse=1 and tuid='.$uid)->count();
        $data['followers_count'] = D('a_user_ff')->where('isuse=1 and uid='.$uid)->count();
        $data['image_count'] = D('a_product')->where('haspass=1 and status=1 and isuse=1 and uid='.$uid)->count();
        $data['folder_count'] = D('a_folder')->where('isuse=1 and uid='.$uid)->count();
        if(I('get.p')) die(dump($data));
        $this->assign('data', $data);
        $this->display();
    }
}
