<?php
//  MIS主控制器
class IndexAction extends BaseAction {
    //  MIS主界面
    public function index(){
    	load('@.bd_plugs');
    	//  读取菜单数据
    	$w['syscode_isuse'] = array('eq',1);
    	$w['syscode_no'] = array('like','SYSMenu%');
		$result = D('syscode')->where($w)->order('syscode_sortno')->select();
		//  生成菜单json
		$menu_str = '{"menus":[';
		$i = 0;
		foreach($result as $menu):
			//  获取菜单
			if($menu['syscode_level']==2 && $menu['syscode_isuse']==1):
				$bt = explode(',',$menu['syscode_remark2']);
				if(in_array(session('bdmis_rule'),$bt)):
					$i++;
					if($i>1) $menu_str .= ',';
					$menu_str .= '{"menuid":"'.$menu['syscode_id'].'","icon":"","menuname":"'.$menu['syscode_name'].'<span class=\'menutip mtip'.$menu['syscode_id'].'\'></span>","menus":[';
						$ii = 0;
						foreach($result as $menu2):
							if($menu2['syscode_level']==3 && $menu2['syscode_isuse']==1):
								if($menu2['syscode_parentid']==$menu['syscode_id']):
									$qx = explode(',',$menu2['syscode_remark2']);
									if(in_array(session('bdmis_rule'),$qx)):
										$ii++;
										if($ii>1) $menu_str .= ',';
										$menu_str .= '{"menuid":"'.$menu2['syscode_id'].'","menuname":"'.$menu2['syscode_name'].'","icon":"","tipid":"mtip'.$menu2['syscode_id'].'","url":"'.$menu2['syscode_content'].'"}';
									endif;
								endif;
							endif;
						endforeach;
						$menu_str .= ']}';
				endif;
			endif;
		endforeach;
		$menu_str .= ']};';
    	//  输出menu
    	$this->assign('menu',$menu_str);
		//  获取权限名称
		$rulename = '未分类';
		$result = D('syscode')->where('syscode_id='.session('bdmis_rule'))->find();
		if($result) $rulename = $result['syscode_name'];
    	$this->assign('rulename',$rulename);
    	//  读取视图
    	$this->display();
    }
    //  更新在线时间并返回现在时间
    public function online(){
		//  更新在线时间
		$r = D('sysaccount')->where(array('sysaccount_id'=>session('bdmis_uid')))->find();
		$t = $r['sysaccount_t'];
		D('sysaccount')->where(array('sysaccount_id'=>session('bdmis_uid')))->save(array('sysaccount_onlinetime'=>now(),'sysaccount_t'=>0));
		return $t;
    }
    //  获取提醒消息数量
    public function msg(){
	    $data['number'] = D('message')->where('hasread=0')->count();
	    echo json_encode($data);
    }
	public function msgread(){
		$w['id'] = I('get.id');
		$d['hasread'] = 1;
    	D('message')->where($w)->save($d);
		winback('操作成功!');
	}
    //  消息页面
    public function msginfo(){
	    $pages['len'] = D('message')->count();
	    $page = page($pages);
	    $this->assign('page',$page);
	    $data['r'] = D('message')->limit($page['limit'])->order('id desc')->select();
	    $this->assign('data',$data);
	    $this->display();
    }
    //  消息删除
    public function msgdel(){
    	$w['id'] = I('get.id');
    	D('message')->where($w)->delete();
    	$r['status'] = 'success';
    	echo json_encode($r);
    }
    //  默认窗体
    public function main(){
	    $this->display();
    }
    //  弹出窗口
    public function iu(){
    	$data['url'] = I('get.url');
    	$data['title'] = I('get.title');
    	$this->assign('data',$data);
	    $this->display();
    }
	
	//  修改数据库
	public function do_edit(){
    	D(I('get.table'))->save($_POST);
		winback('修改成功!');
	}
	
	//  图片管理
	function images(){
		$data['imgs'] = D('a_images')->where('type=\''.I('get.type').'\' and targetid='.I('get.id'))->order('sortno desc,id asc')->select();
    	$this->assign('data',$data);
	    $this->display();
	}
	//  上传
	function uploadfilev(){
		$data['type'] = I('get.type');
		$data['id'] = I('get.id');
		$this->assign('data',$data);
		$this->display();
	}
	//  执行上传图片
	function uploadfile(){
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
				$data['type'] = I('post.type');
				$data['id'] = I('post.id');
				imgresize('./static/uploadfiles/images_tmp/'.$info[0]['savename'],'./static/uploadfiles/images_tmp/'.$info[0]['savename'],1000,1000);
				$this->assign('data',$data);
				$this->display();
			}
		 }
	}
	function backtoupload(){
		@unlink('.'.I('get.url'));
		redirect('/index/uploadfilev?w='.I('get.w').'&h='.I('get.h').'&type='.I('get.type').'&id='.I('get.id'));
	}
	/*
	##############################
	####	保存上傳圖片
	####	1.0
	##############################
	*/
	public function uploadfile_save(){
		$x = $_POST['x'];//客户端选择区域左上角x轴坐标  
		$y = $_POST['y'];//客户端选择区域左上角y轴坐标  
		$w = $_POST['w'];//客户端选择区 的宽  
		$h = $_POST['h'];//客户端选择区 的高  
		$filename = '.'.$_POST['pic'];//图片的路径
		$farr = explode('.',$filename);
		$ext = strtolower($farr[count($farr)-1]);
		if($ext=='png') $im = imagecreatefrompng($filename);// 读取需要处理的图片  
		elseif($ext=='gif') $im = imagecreatefromgif($filename);// 读取需要处理的图片  
		else $im = imagecreatefromjpeg($filename);// 读取需要处理的图片 
		$newim = imagecreatetruecolor(I('post.ww'), I('post.hh'));//产生新图片 100 100 为新图片的宽和高  
		
		imagecopyresampled($newim, $im, 0, 0, $x, $y, I('post.ww'), I('post.hh'), $w, $h);
		//                  [1]    [2] [3][4] [5] [6] [7]  [8]  [9] [10]  
		//[5]  客户端选择区域左上角x轴坐标  
		//[6]  客户端选择区域左上角y轴坐标  
		//[7]  生成新图片的宽  
		//[8]  生成新图片的高  
		//[9]  客户端选择区 的宽  
		//[10] 客户端选择区 的高
		$uploadfile = str_replace('/images_tmp/','/images/',$filename);
		imagejpeg($newim, $uploadfile);
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
		$d['type'] = I('post.type');
		$d['targetid'] = I('post.id');
		$d['name'] = $fname[1];
		$d['ctime'] = now();
		D('a_images')->add($d);
		echo $uploadfile;
	}
	//  删除图片
	function uploadfile_del(){
		$r = D('a_images')->where('id='.I('get.id'))->find();
		unlink('./static/uploadfiles/images/'.$r['name']);
		D('a_images')->where('id='.I('get.id'))->delete();
		winback('删除成功!');
	}
}