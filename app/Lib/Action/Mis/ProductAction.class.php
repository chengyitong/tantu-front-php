<?php

class ProductAction extends BaseAction {

    protected function _initialize() {

	}
	function img_api(){
		$r = D('a_product')->where('isuse=1 and id='.I('get.id'))->find();
		redirect($this->get_imgurl(I('get.type','download'),$r['imgkey'],'tantupix '.(30000000+$r['id']*1).'.'.$r['imgext']));
	}
	
	function uploadv(){
		$data['user'] = D('a_user')->where('')->select();
		$this->assign('data',$data);
		$this->display();
	}
	function get_folder(){
		$data['list'] = D('a_folder')->where('isuse=1 and uid='.I('get.uid'))->select();
		$data['count'] = count($data['list']);
		echo json_output($data);
	}
	function new_folder(){
		$d['uid'] = I('get.uid');
		$d['foldername'] = I('get.fname');
		$d['ctime'] = now();
		D('a_folder')->add($d);
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
    private function get_imgurl($type,$key,$name='test.jpg') {
    	require_once('./static/qiniu/jssdk/demo/src/qiniu6_1/rs.php');
    	require_once('./static/qiniu/jssdk/demo/src/qiniu6_1/auth_digest.php');
    	# @gist set_keys
    	$accessKey = qiniu_ak();//'ixJ6FoVYJvSK3eO8IXsKkTZiWMGGxRISMuQDeCgx';
    	$secretKey = qiniu_sk();//'W45phNfCsu-3zVZO2qf1mJZbRT_0McSQYoXTyFHB';
    	Qiniu_setKeys($accessKey, $secretKey);
    	$gpy = new Qiniu_RS_GetPolicy();
    	$url = qiniu_domain().$key;
    	if($type=='download') $url .= '?attname='.urlencode($name);
    	elseif($type=='exif') $url .= '?exif';
    	elseif($type=='info') $url .= '?imageInfo';
    	elseif($type=='stat') $url .= '?stat';
    	return $gpy->MakeRequest($url,null);
    }
	function add_product() {
		$d['uid'] = $_POST['uid'];
		$d['folderid'] = $_POST['fid'];
		$udata = explode('|;|', $_POST['udata']);
       	load('@.bd_update_user_data');

		foreach ($udata as $v) {
			$arr = explode('|,|',$v);
			if(!$arr[0]) continue;
			$imgkey = strtolower($arr[1]);
			$r = D('a_product')->where('imgkey=\''.$imgkey.'\'')->find();
			if($r) continue;
			//读取文件信息
			$json1 = file_get_contents($this->get_imgurl('stat',$imgkey));
			$arr1 = json_decode($json1,true);
			//读取图片信息
			$json2 = file_get_contents($this->get_imgurl('info',$imgkey));
			$arr2 = json_decode($json2,true);
			//读取exif
			$json3 = file_get_contents($this->get_imgurl('exif',$imgkey));
			$arr3 = json_decode($json3,true);
			
			$d['width'] = $arr2['width'];
			$d['height'] = $arr2['height'];
			$d['format'] = $arr2['format'];
			$d['rotate'] = 0;
			if($d['width']>0 && $d['width']==$d['height']) $d['rotate'] = 2;
			elseif($d['width']<$d['height']) $d['rotate'] = 1;
			//$tmp_arr1 = explode(' ',$arr3['Model']['val']);
			//if(count($tmp_arr1)>1){
			//	$d['brand'] = $tmp_arr1[0];//品牌
			//	$d['model'] = $tmp_arr1[1];//型号
			//}else{
				$d['brand'] = '';//品牌
				$d['model'] = $arr3['Model']['val'];//型号
			//}
			$d['jiaoju'] = $arr3['FocalLength']['val'];//属性：焦距
			$d['guangquan'] = $arr3['ApertureValue']['val'];//属性：光圈
			$d['kuaimen'] = $arr3['ShutterSpeedValue']['val'];//属性：快门
			$d['iso'] = $arr3['ISO Speed Ratings']['val'];//属性：ISO
			$d['baoguang'] = $arr3['ExposureTime']['val'];//属性：曝光
			$d['taketime'] = $arr3['DateTimeOriginal']['val'];//属性：拍照时间
			$d['jingtou'] = '';//属性：镜头
			
			$extarr = explode('.',$arr[0]);
			$d['name'] = str_replace('.'.$extarr[count($extarr)-1],'',$arr[0]);
			$d['imgkey'] = $imgkey;
			$d['size'] = $arr[2];
			if($d['size']*1>20*1024*1024){
				require_once('./static/qiniu/jssdk/demo/src/qiniu6_1/pfop.php');
				require_once('./static/qiniu/jssdk/demo/src/qiniu6_1/http.php');
				require_once('./static/qiniu/jssdk/demo/src/qiniu6_1/auth_digest.php');
				# @gist set_keys
				$accessKey = qiniu_ak();//'ixJ6FoVYJvSK3eO8IXsKkTZiWMGGxRISMuQDeCgx';
				$secretKey = qiniu_sk();//'W45phNfCsu-3zVZO2qf1mJZbRT_0McSQYoXTyFHB';
				Qiniu_setKeys($accessKey, $secretKey);
				
				$client = new Qiniu_MacHttpClient(null);
				$pfop = new Qiniu_Pfop();
				
				$pfop->Bucket = 'demo';
				$pfop->Key = $name;
				$savedKey = 'thumb_'.$name;
				/*
				$entry = Qiniu_Encode("$pfop->Bucket:$savedKey");
				$pfop->Fops = "vframe/jpg/offset/180/w/1000/h/1000/rotate/90|saveas/$entry";
				*/
				$fops = "imageView2/2/w/1200/h/1200";
				$saveas_Key = base64_encode("$pfop->Bucket:$savedKey");
				$fops = $fops.'|saveas/'.$saveas_Key;
				$pfop->Fops = $fops;
				
				list($ret,$err) = $pfop->MakeRequest($client);
				if($err!==null){
					//  err nodo
				}else{
					$d['thumbkey'] = $savedKey;
				}
			}
			$d['imgext'] = $extarr[count($extarr)-1];
			$d['status'] = 1;
			$d['ctime'] = now();
			$id = D('a_product')->add($d);
			$d2['productid'] = $id;
			D('a_product_number')->add($d2);
			$d3['productid'] = $id;
			$d3['stat'] = $json1;
			$d3['info'] = $json2;
			$d3['exif'] = $json3;
			$d3['ctime'] = now();
			D('a_product_api')->add($d3);
			
			//更新用户表的喜欢数
			updateCountAndLikesByUserId($d['uid']);
		}
	}

	public function index(){
		$w['isuse'] = 1;
		if(I('get.id')) $w['id'] = I('get.id');
		if(I('get.uid')) $w['uid'] = I('get.uid');
		if(I('get.banquan')) $w['banquan'] = I('get.banquan');
		if(I('get.status')!='') $w['status'] = I('get.status');
		if(I('get.haspass')!='') $w['haspass'] = I('get.haspass');
		if(I('get.folderid')) $w['folderid'] = I('get.folderid');
		if(I('get.name')) $w['name'] = array('like','%'.I('get.name').'%');
		if(I('get.desc')) $w['desc'] = array('like','%'.I('get.desc').'%');

		//从活动管理页面过来的
		if(I('get.event_id')){
			$event_product_ids = M("a_event_images")->where('event_id='.I('get.event_id'))->field('product_id')->select();
			$id_array =array();
			foreach ($event_product_ids as $key => $value) {
				$id_array[] = $value['product_id'];
			}
			$w['id'] = array('in', implode(",",array_values($id_array)));
		}

		$this->assign('where',$_GET);
        $page['len'] = D('a_product')->field('id')->where($w)->count();
        $page = page($page);
        $sort = I('get.sort','id desc');
        $data['list'] = D('a_product')->field('id,uid,folderid,imgkey,size,name,classids,tagids,colorids,status,isfree,ctime,haspass,passstr')->where($w)->order($sort)->limit($page['limit'])->select();
		
		$data['class'] = D('a_class')->where($w)->select();
		$data['color'] = D('a_color')->where($w)->select();
		$data['tags'] = D('a_tags')->where($w)->select();
        $this->assign('page', $page);
		$this->assign('data',$data);
		$this->display();
	}
	
	function del(){
		$d['isuse'] = 0;
		D('a_product')->where('id in ('.I('get.id').')')->save($d);

		//更新用户表的喜欢数和作品数
		load('@.bd_update_user_data');
		$productArray = explode(",",I('get.id'));
		if($productArray && sizeof($productArray)){
			foreach ($productArray as $key => $value) {	        			
				updateCountAndLikes($value);
			}
		}

		winback('删除成功!');
	}
	
	public function editv(){
		$data['class1'] = D('syscode')->field('syscode_id,syscode_name')->where('syscode_parentid=254')->select();
		//$data['imgs'] = D('a_productimg')->where('productid='.I('get.id'))->order('sortno desc,id asc')->select();
		$w['isuse'] = 1;
		$data['class'] = D('a_class')->where($w)->select();
		$data['color'] = D('a_color')->where($w)->select();
		$data['tags'] = D('a_tags')->where($w)->select();
		//$data['types'] = D('vw_a_products')->where('productid='.I('get.id'))->select();
		$data['r'] = D('a_product')->where('id='.I('get.id'))->find();
		$this->assign('data',$data);
		$this->display('Product/addv');

	}
	
	//  执行编辑保存
	public function edit(){
		$d = $_POST;
		$d['classids'] = ','.implode(',',$d['class']).',';
		//$d['tagids'] = ','.implode(',',$d['tags']).',';
		$d['colorids'] = ','.implode(',',$d['color']).',';
		
		$class_str = '';
		$i = 0;
		foreach($d['class'] as $v){
			$r = D('a_class')->field('classname')->where('id='.$v)->find();
			if($i>0) $class_str .= ' ';
			$class_str .= $r['classname'];
			$i++;
		}
		$d['classlist'] = $class_str;
		
		$color_str = '';
		$i = 0;
		foreach($d['color'] as $v){
			$r = D('a_color')->field('colorname')->where('id='.$v)->find();
			if($i>0) $color_str .= ' ';
			$color_str .= $r['colorname'];
			$i++;
		}
		$d['colorlist'] = $color_str;
		
		$d['taglist'] = I('post.tagids');
		
		$tags = I('post.tagids');
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

		load('@.bd_update_user_data');
		updateCountAndLikes($d['id']);

		if($outs) $ostr = '保存成功!'.$out;
		else $ostr = '保存成功!';
		
		if(I('get.do')=='js'){
			echo "<script>
			try{
				eval( 'window.parent.api_fsave_ok(\'".$ostr."\')' );
			}catch(e){}
			</script>";
		}else{
			winclose($ostr);
		}
	}
	
	public function color(){
		$w['isuse'] = 1;
		if(I('get.name')) $w['name'] = array('like','%'.I('get.name').'%');
		if(I('get.desc')) $w['desc'] = array('like','%'.I('get.desc').'%');
		$this->assign('where',$_GET);
        $page['len'] = D('a_color')->where($w)->count();
        $page = page($page);
		$data['list'] = D('a_color')->where($w)->order('sortno desc')->limit($page['limit'])->select();
        $this->assign('page', $page);
		$this->assign('data',$data);
		$this->display();
	}
	
		public function color_add(){
			$d = $_POST;
			$r = D('a_color')->field('id,isuse')->where($d)->find();
			if($r){
				winback('该颜色已存在!');exit;
			}
			$d['ctime'] = now();
			$id = D('a_color')->add($d);
			winback('添加成功!');
		}
		
		public function color_sortno(){
			$w['id'] = I('get.id');
			$d['sortno'] = I('get.sortno');
			D('a_color')->where($w)->save($d);
			echo '1';
		}
		
		public function color_upt(){
			$d = $_GET;
			foreach($d as $k=>$v){
				$d[$k] = unescape($v);
			}
			D('a_color')->where($w)->save($d);
			echo '1';
		}
		
		public function color_del(){
			$d['id'] = I('get.id');
			$d['isuse'] = 0;
			D('a_color')->where($w)->save($d);
			winback('删除成功!');
		}
		
	public function tags(){
		//$w['isuse'] = 1;
		if(I('get.isuse')!='') $w['isuse'] = I('get.isuse');
		if(I('get.name')) $w['tagname'] = array('like','%'.I('get.name').'%');
		$this->assign('where',$_GET);
	    $page['len'] = D('a_tags')->where($w)->count();
	    $page = page($page);
		$data['list'] = D('a_tags')->where($w)->order('id desc')->limit($page['limit'])->select();
		$data['class'] = D('a_class')->where('isuse=1')->select();
	    $this->assign('page', $page);
		$this->assign('data',$data);
		$this->display();
	}
	
		public function tags_add(){
			$d = $_POST;
			$r = D('a_tags')->field('id,isuse')->where($d)->find();
			if($r){
				winback('该标签已存在!');exit;
			}
			$d['ctime'] = now();
			$id = D('a_tags')->add($d);
			winback('添加成功!');
		}		
		public function tags_upt(){
			$d = $_GET;
			foreach($d as $k=>$v){
				$d[$k] = unescape($v);
			}
			
			if(I('post.class')){
				$class_str = '';
				$i = 0;
				foreach(I('post.class') as $v){
					$r = D('a_class')->field('classname')->where('id='.$v)->find();
					if($i>0) $class_str .= ' ';
					$class_str .= $r['classname'];
					$i++;
				}
				$d['classlist'] = $class_str;
				$d['classids'] = ','.implode(',',I('post.class')).',';
			}
			
			D('a_tags')->where($w)->save($d);
			
			if(I('get.do')=='js'){
				echo "<script>
				try{
					eval( 'window.parent.api_fsave_ok(\'保存成功！\')' );
				}catch(e){}
				</script>";
			}else echo '1';
		}
		
		public function tags_do(){
			$d['id'] = I('get.id');
			$d['isuse'] = I('get.to');
			D('a_tags')->where($w)->save($d);
			winback('操作成功!');
		}
			
	public function classv(){
		$w['isuse'] = 1;
		if(I('get.name')) $w['tagname'] = array('like','%'.I('get.name').'%');
		$this->assign('where',$_GET);
	    $page['len'] = D('a_class')->where($w)->count();
	    $page = page($page);
		$data['list'] = D('a_class')->where($w)->order('id desc')->limit($page['limit'])->select();
	    $this->assign('page', $page);
		$this->assign('data',$data);
		$this->display();
	}
	
		public function class_add(){
			$d = $_POST;
			$r = D('a_class')->field('id,isuse')->where($d)->find();
			if($r){
				winback('该分类已存在!');exit;
			}
			$d['ctime'] = now();
			$id = D('a_class')->add($d);
			winback('添加成功!');
		}		
		public function class_upt(){
			$d = $_GET;
			foreach($d as $k=>$v){
				$d[$k] = unescape($v);
			}
			D('a_class')->where($w)->save($d);
			echo '1';
		}
		
		public function class_do(){
			$d['id'] = I('get.id');
			$d['isuse'] = I('get.to');
			D('a_class')->where($w)->save($d);
			winback('删除成功!');
		}

}