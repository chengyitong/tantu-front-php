<?php
class SysAction extends BaseAction {
    /*
    综合配置  v1.0
    williamfu 2013.9.6
    */
    public function code()
	{
		load('@.bd_plugs');
		if(I('get.do')=='sel'){
			$level_data = D('syscode')->where('syscode_id='.I('get.id'))->find();
			$sql['syscode_parentid'] = array('eq',I('get.id'));
			$sql['syscode_level'] = $level_data['syscode_level']+1;
		}else{
			$sql['syscode_level'] = 1;
		}
		//  获取数据
		$data['code'] = D('syscode')->where($sql)->order('syscode_sortno')->select();
		//  底部信息
		$page['len'] = count($data['code']);
		$page['hide'] = 1;
		$this->assign('page',$page);
		if(I('get.do')=='sel'){
			$data['syscodeLevel'] = $sql['syscode_level'];
			$data['syscodeparentid'] = I('get.id');
			if($level_data['syscode_level']>0) $backurl = '?do=sel&id='.$level_data['syscode_parentid'];
			$data['backurl'] = $backurl;
		}else{
			$data['syscodeLevel'] = 1;
			$data['syscodeparentid'] = 0;
			$data['backurl'] = '';
		}
		$this->assign('data',$data);
		$this->display();
	}
	//  行政处理
	public function code_new()
	{
		// 回调页面
		$return_url = I('get.url');
		/*
		if(I('post.syscodeValue')=='type:sc'){
			$sctit = split('?table=',I('post.syscodecontent'));
			D()->query('create table bdmis_'.$sctit[1].'(syscode_id int(11) not null auto_increment primary key) select * from bdmis_sc where 1=2;');
		}
		*/
		// 新增数据
		$data['syscode_no'] = I('post.syscodeNo');
		$data['syscode_name'] = I('post.syscodeName');
		$data['syscode_value'] = I('post.syscodeValue');
		$data['syscode_level'] = I('post.syscodeLevel');
		$data['syscode_content'] = I('post.syscodecontent');
		$data['syscode_parentid'] = I('post.syscodeparentid');
		$data['syscode_isuse'] = I('post.syscodeisuse');
		$data['syscode_sortno'] = I('post.syscodeSortNo');
		$data['syscode_creatorid'] = session('bdmis_uid');
		$data['syscode_createtime'] = now();
		
		if($data['syscode_parentid']=='326'){
			$data['syscode_sortno'] = 1;
			D('syscode')->where('syscode_parentid=326 and syscode_sortno>0')->setInc('syscode_sortno');
		}
		
		D('syscode')->add($data);
		// 跳转页面
	    $this->success('新增成功!');
	}
	//  更新页面
	public function code_uptv()
	{
		load('@.bd_plugs');
		$syscode_arr = D('syscode')->where('syscode_id='.I('get.id'))->find();
		$this->assign('syscode',$syscode_arr);
		$this->display();
	}
	//  更新处理
	public function code_upt()
	{
		$htmlData = '';
		$remark = I('post.syscoderemark3');
		if(!empty($remark)){
			if(get_magic_quotes_gpc()){
				$htmlData = stripslashes(I('post.syscoderemark3'));
			}else{
				$htmlData = I('post.syscoderemark3');
			}
		}
		if($htmlData!=''){
			$htmlData = str_replace('http'.'://'.$_SERVER['HTTP_HOST'].'/static/plugs/kindeditor/attached/','/static/plugs/kindeditor/attached/',$htmlData);
			$htmlData = str_replace('/static/plugs/kindeditor/attached/','http'.'://'.$_SERVER['HTTP_HOST'].'/static/plugs/kindeditor/attached/',$htmlData);
		}
		// 修改数据
		$data['syscode_no'] = I('post.syscodeno');
		$data['syscode_name'] = I('post.syscodename');
		$data['syscode_desc'] = I('post.syscodedesc');
		$data['syscode_content'] = I('post.content1');
		$data['syscode_isuse'] = I('post.syscodeisuse');
		$data['syscode_value'] = I('post.syscodevalue');
		$data['syscode_remark1'] = I('post.syscoderemark1');
		$data['syscode_remark2'] = I('post.syscoderemark2');
		$data['syscode_remark3'] = $htmlData;
		$data['syscode_candelete'] = I('post.syscodecandelete');
		$data['syscode_modifierID'] = session('bdmis_uid');
		$data['syscode_modifyTime'] = now();
		D('syscode')->where('syscode_id='.I('post.syscodeid'))->save($data);
		winclose('修改成功!');
	}
	//  json删除处理
	public function code_del()
	{
		$return_url = I('get.url');
		$this->delpage(I('get.id','','intval'));
		$data['status'] = 'success';
		echo json_output($data);
	}
	//  循环删除下级
	private function delpage($id)
	{
		$r = D('syscode')->where('syscode_id='.$id)->find();
		/*
		if($r['syscode_value']=='type:sc'){
			$sctit = split('?table=',$r['syscode_content']);
			D()->query('drop table '.$sctit[1].';');
		}
		*/
		D('syscode')->where('syscode_parentid='.$r['syscode_parentid'].' and syscode_sortno>'.$r['syscode_sortno'])->setDec('syscode_sortno');
		D('syscode')->delete($id);
		$result = D('syscode')->where('syscode_parentid='.$id)->select();
		foreach($result as $aa)
		{
			$this->delpage($aa['syscode_id']);
		}
	}
	//  上传
	//  ?table=&fieldid=&id=&src=&field=&rsl=
		function uploadfilev(){
			$w[I('get.fieldid')] = I('get.id');
			$data['r'] = D(I('get.table'))->where($w)->find();
			$data['img'] = D('sysimages')->where('id='.$data['r']['syscode_imgid'])->find();
			$data['table'] = I('get.table');
			$data['src'] = I('get.src');
			$data['id'] = I('get.id');
			$data['fieldid'] = I('get.fieldid');
			$data['field'] = I('get.field');
			$data['rsl'] = I('get.rsl');
			$this->assign('data',$data);
			$this->display();
		}
		function choosefilev(){
			$data['r'] = D('sysimages')->where('isdel=0')->select();
			$data['table'] = I('get.table');
			$data['src'] = I('get.src');
			$data['id'] = I('get.id');
			$data['fieldid'] = I('get.fieldid');
			$data['field'] = I('get.field');
			$data['rsl'] = I('get.rsl');
			$this->assign('data',$data);
			$this->display();
		}
		function choosefile(){
			$w[I('post.fieldid')] = I('post.id');
			$d[I('post.field')] = I('post.sel');
			D(I('post.table'))->where($w)->save($d);
			winclose('提交成功!');
		}
		function uploadfile(){
			import('ORG.Net.UploadFile');
			$upload = new UploadFile();// 实例化上传类
			$upload->maxSize  = 3145728 ;// 设置附件上传大小
			$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
			$upload->savePath =  '.'.I('post.src');// 设置附件上传目录
			 if(!$upload->upload()) {// 上传错误提示错误信息
				$this->error($upload->getErrorMsg());
			 }else{// 上传成功 获取上传文件信息
				$info =  $upload->getUploadFileInfo();
				$dd['imgname'] = $info[0]['savename'];
				$dd['ctime'] = now();
				$imgid = D('sysimages')->add($dd);
				
				$w[I('post.fieldid')] = I('post.id');
				$d[I('post.field')] = $imgid;
				D(I('post.table'))->where($w)->save($d);
				winclose('上传成功!');
			 }
		}
		function uploadfile_del(){
			//unlink('.'.I('post.src').I('post.name'));
			$w[I('post.fieldid')] = I('post.id');
			$d[I('post.field')] = 0;
			$data['r'] = D(I('post.table'))->where($w)->save($d);
			winclose('删除成功!');
		}
	//  上下换位
	public function csort()
	{
		$type = I('get.do');
		$id = I('get.id');
		$return_url = I('get.url');
		// 读取数据
		$sql['syscode_id'] = $id;
		$page = D('syscode')->where($sql)->find();
		// 获取当前ID及排序No
		$syscode_id = $page['syscode_id'];
		$syscode_pid = $page['syscode_parentid'];
		$syscode_sno = $page['syscode_sortno'];
		// 判断排序调整类型
		if($type=='up'){
			$syscode_tosno = $syscode_sno - 1;
		}else{
			$syscode_tosno = $syscode_sno + 1;
		}
		// 读取目标数据
		$sql = array(
					'syscode_sortno' => $syscode_tosno,
					'syscode_level' => $page['syscode_level'],
					'syscode_parentid' => $syscode_pid,
					);
		$topage = D('syscode')->where($sql)->order('syscode_sortno')->select();
		// 获取目标ID
		$tosyscode_id = $topage[0]['syscode_id'];
		// 更新当前排序No
		$data = array(
					'syscode_sortno' => $syscode_tosno,
					);
		$where = array(
					'syscode_id' => $syscode_id,
					);
		D('syscode')->where($where)->save($data);
		// 更新目标排序No
		$data = array(
					'syscode_sortno' => $syscode_sno,
					);
		$where = array(
					'syscode_id' => $tosyscode_id,
					);
		D('syscode')->where($where)->save($data);
		// 跳转页面
		redirect($return_url);
	}
	//  转到页面
	public function gopage()
	{
		$data['id'] = I('get.id');
		$data['code'] = D('syscode')->order('syscode_sortno')->select();
		$this->assign($data);
		$this->display();
	}
	//  ajax获取当前及下级id
	public function getzcd($id)
	{
		if(!isset($id)) $id = I('get.id');
		$r = D('syscode')->where('syscode_parentid='.$id)->select();
		foreach($r as $aa)
		{
			$hh.=$aa['syscode_id'].',';
			$this->getzcd($aa['syscode_id']);
		}
		echo $hh;
	}
	//  更新转到
	public function gopage_upt()
	{
		$id = I('post.id');
		$radio = I('post.radio');
		$radio1 = explode(",",$radio);
		$level = I('post.level');
		if($level!=$radio1[2])
		{
			if($radio1[2]>$level){
				$rlevel = $radio1[2] - $level;
				$sql = 'Inc,'.$rlevel;
			}else{
				$rlevel = $level - $radio1[2];
				$sql = 'Dec,'.$rlevel;
			}
			$this->douptpaixu($id,$sql);
		}
		$r1 = D('syscode')->where('syscode_id='.$id)->find();
		$r2 = D('syscode')->where('syscode_parentid='.$r1['syscode_parentid'].' and syscode_sortno>'.$r1['syscode_sortno'])->setDec('syscode_sortno');
		if(I('post.qh')==1)
		{
			D('syscode')->where('syscode_parentid='.$radio1[0].' and syscode_sortno>='.$radio1[1])->setInc('syscode_sortno');
			$data['syscode_parentid'] = $radio1[0];
			$data['syscode_sortno'] = $radio1[1];
			D('syscode')->where('syscode_id='.$id)->save($data);
		}else
		{
			D('syscode')->where('syscode_parentid='.$radio1[0].' and syscode_sortno>'.$radio1[1])->setInc('syscode_sortno');
			$data['syscode_parentid'] = $radio1[0];
			$data['syscode_sortno'] = $radio1[1]+1;
			D('syscode')->where('syscode_id='.$id)->save($data);
		}
		winclose('修改成功!');
	}
	//  循环更新转到level
	private function douptpaixu($id,$sql)
	{
		$str = explode(',',$sql);
		if($str[0]=='Inc') D('syscode')->where('syscode_id='.$id)->setInc('syscode_level',intval($str[1]));
		else D('syscode')->where('syscode_id='.$id)->setDec('syscode_level',intval($str[1]));
		$r = D('syscode')->where('syscode_parentid='.$id)->select();
		foreach($result as $aa)
		{
			$this->douptpaixu($aa['syscode_id'],$sql);
		}
	}
    /*
    文件管理  v1.0
    williamfu 2013.11.8
    */
    public function files(){
    	//  加载文件获取插件
		load('@.bd_files');
		//  排序
		I('get.sortby') ? $sortby=I('get.sortby') : $sortby='filename,asc';
		//  获取文件列表
		$data = files_getlist('',I('get.path'),$sortby);
		//  视图
		$this->assign('data',$data);
		$canedit = array('php','html','css','js','txt');
		$this->assign('canedit',$canedit);
		$this->display();
    }
    //  文件编辑
    public function files_edit(){
    	//  检查传值
    	if(I('get.path')=='') die('bad request');
    	//  加载插件
    	load('@.bd_plugs');
    	//  文件路径
    	$cfile = I('get.path');
    	//  读取文件
    	$cfilehandle=fopen($cfile,"r");
    	if(!$cfilehandle){
    		echo 'File can not found';
    		exit;
    	}
    	$editfile=fread($cfilehandle,filesize($cfile));
    	fclose($cfilehandle);
    	//  视图
    	$this->assign('editfile',$editfile);
    	$this->display();
    }
    //  保存编辑文件
    public function files_save() {
    	//  保存文件
    	$cfilehandle=fopen(I('get.path'),"wb");
    	flock($cfilehandle, 2);
    	fputs($cfilehandle,stripslashes(str_replace("\x0d\x0a", "\x0a", $_GET['content'])));
    	fclose($cfilehandle);
    	//  输出
    	echo $_GET['callback']."({'info':'保存成功!'})";
    }
    //  新建文件夹
    public function files_newf(){
    	//  加载文件获取插件
		load('@.bd_files');
		//  路径
		$path = I('post.path').I('post.fname');
		//  新建函数
		if(I('post.ftype')=='folder'){
		    $r = createFolder($path);
		    if($r) $this->success('文件夹 '.I('post.fname').' 创建成功!');
		    else $this->error('文件夹名称重复!');
		}else{
			$r = createFile($path);
			$this->success('文件 '.I('post.fname').' 创建成功!');
		}
    }
    //  删除
    public function files_del(){
    	//  加载文件获取插件
		load('@.bd_files');
		//  删除函数
	    $r = deleteFolder(I('get.id'));
	    if($r) $data['status'] = 'success';
	    else $data['status'] = 'false';
		echo json_output($data);
    }
    //  上传文件
    public function files_uploadv(){
	    $this->display();
    }
    public function files_upload(){
	    /**
		 * upload.php
		 *
		 * Copyright 2013, Moxiecode Systems AB
		 * Released under GPL License.
		 *
		 * License: http://www.plupload.com/license
		 * Contributing: http://www.plupload.com/contributing
		 */
		#!! IMPORTANT: 
		#!! this file is just an example, it doesn't incorporate any security checks and 
		#!! is not recommended to be used in production environment as it is. Be sure to 
		#!! revise it and customize to your needs.
		// Make sure file is not cached (as it happens for example on iOS devices)
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		/* 
		// Support CORS
		header("Access-Control-Allow-Origin: *");
		// other CORS headers if any...
		if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
			exit; // finish preflight CORS requests here
		}
		*/
		// 5 minutes execution time
		@set_time_limit(5 * 60);
		// Uncomment this one to fake upload time
		// usleep(5000);
		// Settings
		$targetDir = '.'.I('get.path');//ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload";
		//$targetDir = 'uploads';
		$cleanupTargetDir = true; // Remove old files
		$maxFileAge = 5 * 3600; // Temp file age in seconds
		// Create target dir
		if (!file_exists($targetDir)) {
			@mkdir($targetDir);
		}
		// Get a file name
		if (isset($_REQUEST["name"])) {
			$fileName = $_REQUEST["name"];
		} elseif (!empty($_FILES)) {
			$fileName = $_FILES["file"]["name"];
		} else {
			$fileName = uniqid("file_");
		}
		$filePath = $targetDir . $fileName;
		// Chunking might be enabled
		$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
		$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
		// Remove old temp files	
		if ($cleanupTargetDir) {
			if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
				die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
			}
			while (($file = readdir($dir)) !== false) {
				$tmpfilePath = $targetDir . $file;
				// If temp file is current file proceed to the next
				if ($tmpfilePath == "{$filePath}.part") {
					continue;
				}
				// Remove temp file if it is older than the max age and is not the current file
				if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge)) {
					@unlink($tmpfilePath);
				}
			}
			closedir($dir);
		}	
		// Open temp file
		if (!$out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb")) {
			die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
		}
		if (!empty($_FILES)) {
			if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
				die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
			}
			// Read binary input stream and append it to temp file
			if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
				die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
			}
		} else {	
			if (!$in = @fopen("php://input", "rb")) {
				die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
			}
		}
		while ($buff = fread($in, 4096)) {
			fwrite($out, $buff);
		}
		@fclose($out);
		@fclose($in);
		// Check if file has been uploaded
		if (!$chunks || $chunk == $chunks - 1) {
			// Strip the temp .part suffix off 
			rename("{$filePath}.part", $filePath);
		}
		// Return Success JSON-RPC response
		die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
    }
	/*
    帐户管理  v1.0
    williamfu 2013.9.7
    */
    public function account(){
		load('@.bd_plugs');
		//  获取条件		
		if(I('get.username')) $where['sysaccount_username'] = array('eq',I('get.username'));
		if(I('get.name')) $where['sysaccount_name'] = array('eq',I('get.name'));
		if(I('get.tel')) $where['sysaccount_tel'] = array('eq',I('get.tel'));
		$this->assign('where',$_GET);
		//  底部信息
		$pages['len'] = D('sysaccount')->relation('syscode')->where($where)->count();
		$page = page($pages);
		$this->assign('page',$page);
		//  获取数据
	    $data['account'] = D('sysaccount')->relation('syscode')->where($where)->limit($page['limit'])->select();
	    //  view
	    $this->assign('data',$data);
	    $this->display();
    }
    //  新增页面
    public function account_newv(){
	    $permission = D('syscode')->where('syscode_parentid=2')->select();
	    $data['type'] = '新增';
	    $this->assign('permission',$permission);
	    $this->assign('data',$data);
	    $this->display('Sys:account_info');
    }
    //  新增处理
    public function account_new(){
	    $account = D('sysaccount')->where('sysaccount_username=\''.I('post.username').'\'')->find();
	    if($account){
		    $this->error('该用户已存在!');
	    }else{
		    $data = array(
						'sysaccount_username' => I('post.username'),
						'sysaccount_name' => I('post.name'),
						'sysaccount_password' => md5(I('post.pwd')),
						'sysaccount_tel' => I('post.tel'),
						'sysaccount_idcard' => I('post.idcard'),
						'sysaccount_creatorid' => $s_uid,
						'sysaccount_createtime' => now(),
						'sysaccount_isuse' => I('post.isuse'),
						'sysaccount_typeid' => 1,
						'sysaccount_permissionid' => I('post.rule'),
						);
			D('sysaccount')->add($data);
			//更新qqmail
			$str = '&alias='.I('post.username').'@zhgethome.com';
			$str .= '&name='.I('post.name');
			$str .= '&mobile='.I('post.tel');
			$str .= '&Password='.I('post.pwd').'&md5=1';
			$status = I('post.isuse');
			if($status=='0') $status = '2';
			$str .= '&opentype='.$status;
			$api = A('Mis/Api');
			$api->strs = $str;
			$r = $api->qqmail_insert();
			//  输出
			winclose('新增成功!');
	    }
    }
    //  更新页面
    public function account_uptv(){
	    $id = I('get.id');
	    $account = D('sysaccount')->relation('syscode')->where('sysaccount_id='.$id)->find();
	    $permission = D('syscode')->where('syscode_parentid=2')->select();
	    $data['type'] = '修改';
	    $this->assign('account',$account);
	    $this->assign('permission',$permission);
	    $this->assign('data',$data);
	    $this->display('Sys:account_info');
    }
    //  更新处理
    public function account_upt(){
	    $data = array(
					'sysaccount_username' => I('post.username'),
					'sysaccount_name' => I('post.name'),
					'sysaccount_tel' => I('post.tel'),
					'sysaccount_idcard' => I('post.idcard'),
					'sysaccount_isuse' => I('post.isuse'),
					'sysaccount_modifierid' => $s_uid,
					'sysaccount_modifytime' => now(),
					);
		if(I('post.pwd')) $data['sysaccount_password'].=md5(I('post.pwd'));
		if(I('post.rule')) $data['sysaccount_permissionid'].=I('post.rule');
		D('sysaccount')->where('sysaccount_id='.I('post.id'))->save($data);
		//更新qqmail
		$str = '&alias='.I('post.username').'@zhgethome.com';
		$str .= '&name='.I('post.name');
		$str .= '&mobile='.I('post.tel');
		if(I('post.pwd')) $str .= '&Password='.I('post.pwd').'&md5=1';
		$status = I('post.isuse');
		if($status=='0') $status = '2';
		$str .= '&opentype='.$status;
		$api = A('Mis/Api');
		$api->strs = $str;
		$r = $api->qqmail_update();
		//  输出
		winclose('修改成功!');
    }
    //  ajax获取权限
    public function account_permission(){
	    $account = D('sysaccount')->relation('syscode')->where('sysaccount_id='.session('bdmis_uid'))->find();
	    $id = I('get.id');
	    $permission = D('syscode')->where('syscode_parentid='.$id)->order('syscode_sortno')->select();
	    $r = '所属权限：<select name="rule">';
		foreach($permission as $aa)
		{
			if($aa['syscode_name']=='超级管理员' && $account['syscode']['syscode_name']!='超级管理员') continue;
			$r .= '<option value="'.$aa['syscode_id'].'">'.$aa['syscode_name'].'</option>';
		}
		$r .= '</select>';
		echo $r;
    }
    //  删除处理
    public function account_del(){
		if(I('get.id')<=1) echo json_output(array('status'=>'error'));
	    $uinfo = D('sysaccount')->relation('syscode')->where('sysaccount_id='.I('get.id'))->find();
	    D('sysaccount')->where(array('sysaccount_id'=>I('get.id')))->delete();
	    $data['status'] = 'success';
		//更新qqmail
		$str = '&alias='.$uinfo['sysaccount_username'].'@zhgethome.com';
		$str .= '&name=delete';
		$str .= '&opentype=2';
		$api = A('Mis/Api');
		$api->strs = $str;
		$r = $api->qqmail_update();
		//  输出
	    echo json_output($data);
    }
    //  强制下线
    public function account_t(){
	    D('sysaccount')->where(array('sysaccount_id'=>I('get.id')))->save(array('sysaccount_t'=>1));
	    $this->success('操作成功!');
    }
	/*
    权限管理  v1.0
    williamfu 2013.9.16
    */
    function permission(){
	    $r = D('syscode')->where('syscode_parentid=2')->order('syscode_sortno')->select();
	    $this->assign('data',$r);
	    $this->display();
    }
    //  权限更新页面
    function permission_uptv(){
		$data['menu'] = D('syscode')->where('syscode_no like \'SYSMenu%\'')->order('syscode_sortno')->select();
		$data['res'] = D('syscode')->where('syscode_parentid = '.I('get.id'))->order('syscode_sortno')->select();
		$data['type'] = D('syscode')->where('syscode_id = '.I('get.id'))->order('syscode_sortno')->find();
	    $this->assign($data);
	    $this->display();
    }
    //  权限更新
    function permission_upt(){
    	$menu = I('post.menuid');
    	$storelevel = I('post.level');
    	$userlevel = I('post.user_txt');
    	foreach($menu as $k=>$v):
			$data = array(
						'syscode_remark1' => $storelevel[$k],
						'syscode_remark2' => $userlevel[$k],
						);
    		D('syscode')->where('syscode_id='.$v)->save($data);
		endforeach;
		// 回调页面
		$return_url = I('get.url');
		// 跳转页面
		$this->success('修改成功!');
    }
}