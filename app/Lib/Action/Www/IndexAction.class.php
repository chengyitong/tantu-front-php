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
class IndexAction extends Action {
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
    }
	function avatar_url(){
                session_write_close(); //
		$src = get_avatar(I('get.uid'));
		$image = file_get_contents($src);
		header('Content-Type: image/jpeg');
		echo $image;
	}
    private function get_imgurl($type,$key,$name) {
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
	function img_api(){
		$u = $this->user;
		if(!$u) winback('请先登录！');
		$r = D('a_product')->where('isfree=1 and status=1 and haspass=1 and isuse=1 and id='.I('get.id'))->find();
		if(!$r) wingo('Bad request!','/');
		if($r['banquan']=='1') wingo('该图受版权保护，不得下载！','/index/detail?id='.I('get.id'));
		if(I('get.type','download')=='download'){
			$d['date'] = now(1);
			$d['uid'] = $u['id'];
			$d['productid'] = I('get.id');
			$limit = D('a_product_download_limit')->where($d)->find();
			if(!$limit){
				D('a_product_download_limit')->add($d);
			}else{
				if($limit['dtimes']>=cstr($u['download'],6)){
					winback('下载失败，每日只允许免费下载6张图片！');
				}
				D('a_product_download_limit')->where($d)->setInc('dtimes');
			}
			D('a_product_number')->where('productid='.I('get.id'))->setInc('downloads');
		}
		redirect($this->get_imgurl(I('get.type','download'),$r['imgkey'],'tantupix '.(30000000+$r['id']*1).'.'.$r['imgext']));
	}
    function getimg(){
    	echo $this->get_imgurl('exif');
    }
    function index() {
		$w['status'] = 1;
        $data['banner'] = D('vw_syscode')->where('syscode_parentid=333')->select();
		$data['user'] = $this->user;
		$data['class'] = D('a_class')->where('isuse=1')->select();
		$data['count'] = D('a_user')->count();
		
		if(I('get.kw')) $w['nickname'] = array('like','%'.unescape(trim(I('get.kw'))).'%');
		$page['len'] = D('a_user')->where($w)->count();
		$page['per'] = 18;
		$page = page($page);
		$data['user_list'] = D('a_user')->field('bdmis_a_user.*,(visit+count_likes) as renqi')->where('isuse=1 and id not in (12,19)')->order('renqi desc,count_imgs desc')->limit($page['limit'])->select();
		foreach($data['user_list'] as $k=>$v){
			$r = D('a_images')->where("type='user_avatar' and targetid=".$v['id'])->order('id desc')->find();
			$data['user_list'][$k]['avatar'] = $r['name'];
			$ffw['uid'] = session('uid');
			$ffw['tuid'] = $v['id'];
			$ffw['isuse'] = 1;
			$data['user_list'][$k]['followed'] = D('a_user_ff')->where($ffw)->count();
			$data['user_list'][$k]['fans_count'] = D('a_user_ff')->where('isuse=1 and tuid='.$v['id'])->count();
			$data['user_list'][$k]['followers_count'] = D('a_user_ff')->where('isuse=1 and uid='.$v['id'])->count();
		}
		if($data['user']){
			$data['favclass'] = D('a_favclass')->where('isuse=1 and uid='.session('uid'))->order('id desc')->select();
			foreach ($data['favclass'] as $key => $value) {
				$data['favclass'][$key]['img_count'] = D('a_user_fav')->where('isuse=1 and uid='.session('uid').' and favclassid='.$value['id'])->count();
				$r = D('a_product')->field('imgkey,size')->where('isuse=1 and id='.$value['topimgid'])->find();
				if($value['topimgid']==0 || !$r) $data['favclass'][$key]['topimg'] = '/static/images/ablumbg.png';
				else $data['favclass'][$key]['topimg'] = imgurl_t1($r['imgkey'],$r['size']);
			}
		}

        //取活动数据
        $events = M('a_event')->field('id,product_count,award,datediff(end_time,curdate()) AS dayLeft,subject_banner_index,link')->order('sortno desc')->limit(3)->select();
        $this->assign('events',$events);
        $this->assign('data', $data);
        $this->display();
    }
    function searchcamerist(){
    	$w['status'] = 1;
    	$data['user'] = $this->user;
    	$w['isuse'] = 1;
    	if(I('get.kw')) $w['nickname'] = array('like','%'.unescape(trim(I('get.kw'))).'%');
    	$page['len'] = D('a_user')->where($w)->count();
		$page['len'] = $page['len']*1-2;
    	$page['per'] = 20;
    	$page = page($page);
		$w['_string'] = 'id not in (12,19)';
    	$data['user_list'] = D('a_user')->field('bdmis_a_user.*,(visit+count_likes) as renqi')->where($w)->order('renqi desc,count_imgs desc')->limit($page['limit'])->select();
    	foreach($data['user_list'] as $k=>$v){
    		$r = D('a_images')->where("type='user_avatar' and targetid=".$v['id'])->order('id desc')->find();
    		$data['user_list'][$k]['avatar'] = $r['name'];
    		$ffw['uid'] = session('uid');
    		$ffw['tuid'] = $v['id'];
    		$ffw['isuse'] = 1;
    		$data['user_list'][$k]['followed'] = D('a_user_ff')->where($ffw)->count();
    		$data['user_list'][$k]['fans_count'] = D('a_user_ff')->where('isuse=1 and tuid='.$v['id'])->count();
    		$data['user_list'][$k]['followers_count'] = D('a_user_ff')->where('isuse=1 and uid='.$v['id'])->count();
    	}
    	$data['fanye'] = glb_fystyle(I('get.curpage',1),$page['tol'],$page['len']);
    	if(I('get.p')) die(dump($data));
    	$this->assign('data', $data);
    	$this->display();
    }
    function has_fav(){
    	if(!session('uid')) $d['status'] = -1;
    	else{
    		$fav = D('a_user_fav')->where('isuse=1 and uid='.session('uid').' and productid='.I('get.id'))->find();
	    	if($fav) $d['status'] = 1;
	    	else $d['status'] = 0;
    	}
    	echo json_output($d);
    }
    function get_images(){        
    	if(I('get.haspass')=='none') $w = 'isuse=1';
    	else $w = 'haspass=1 and isuse=1 and `status`=1';
    	
        //活动查询列表处理
        // if(false){
        if(I('get.event_id') && I('get.event_type')){
            //查询活动id 列表
            $sub_where = 'event_id='.I('get.event_id');

            if('3'==I('get.event_type')){
                $sub_where .= ' and user_id='.session('uid');
            }//'我的投稿'

            $event_product_ids = M("a_event_images")->where($sub_where)->field('product_id')->select();
            $id_array =array();
            foreach ($event_product_ids as $key => $value) {
                $id_array[] = $value['product_id'];
            }

            $data['id_array'] = $id_array;
            if('4'==I('get.event_type')){
                if(!empty($id_array)){
                    $w .= ' and id not in('. implode(",",array_values($id_array)).')';
                }     
            }//取用户除当前活动已选择除外的所有图片
            else{
                $w .= ' and id in('. implode(",",array_values($id_array)).')';     
            }//取当前活动的所有图片            
        }

        if('4'==I('get.event_type')){
                $w .= ' and uid='.session('uid');
        }//'我的投稿'

        if(I('get.free')) $w .= ' and banquan=2';
    	if(I('get.bq')) $w .= ' and banquan='.I('get.bq');
    	$str_kw = unescape(trim(I('get.kw')));
    	if($str_kw){
    		$arr = explode(' ',$str_kw);
    		for($i=0;$i<count($arr);$i++){
    			if($i==0) $w .= ' and (';
    			else $w .= ' or ';
    			$w .= 'CONCAT(`name`,classlist,taglist,colorlist) like \'%'.$arr[$i].'%\'';
    		}
    		if(is_numeric($str_kw) && $str_kw>30000000){
    			$w .= ' or id='.($str_kw*1-30000000);
    		}
    		$w .= ')';
    	}
    	
    	if(I('get.uid')) $w .= ' and uid='.I('get.uid');
    	if(I('get.folderid')) $w .= ' and folderid='.I('get.folderid');
    	if(I('get.classid')) $w .= ' and classids like \'%,'.I('get.classid').',%\'';
    	if(I('get.colorid')) $w .= ' and colorids like \'%,'.I('get.colorid').',%\'';
    	if(I('get.rotateid')) $w .= ' and rotate like \'%,'.I('get.rotateid').',%\'';
    	if(I('get.wh')){
    		$wh_arr = explode(',',I('get.wh'));
    		if($wh_arr[0]!='') $w .= ' and width=\''.$wh_arr[0].'\'';
    		if($wh_arr[1]!='') $w .= ' and height=\''.$wh_arr[1].'\'';
    	}
    	if(I('get.nid')) $w .= ' and id != '.I('get.nid');
    	
    	if(I('get.type')=='tj'){
            $sql = "select id from bdmis_syscode t1 left join bdmis_a_product t2 on t1.syscode_value=t2.id";
            if(isset($w) && ''!==$w){
                $sql .= " and ".$w;
            }
            $sql .=" where syscode_parentid=326 order by syscode_sortno asc limit 9999"; //假定最大值，防止万一
            $ret_tj_array = D()->query($sql);
            $page['len']=count($ret_tj_array);
    		$data['type'] = 'tj';
    	}else{
    		$sql = 'select count(id) as cnt from bdmis_a_product where '.$w.' and match (`name`,classlist,taglist,colorlist) against (\''.$str_kw.'\' in boolean mode)';
    		if(I('get.tags')!=''){
    			$sql = 'select count(id) as cnt from bdmis_a_product where '.$w.' and match (tagids) against (\''.trim(str_replace(',',' ',I('get.tags'))).'\' in boolean mode)';
    			$rsql = D()->query($sql);
    			$page['len'] = $rsql['cnt'];
    		}else $page['len'] = D('a_product')->field('id')->where($w)->count();//$rsql['cnt'];
    		$data['type'] = '';
    	}
		$data['total'] = $page['len'];
    	$page['per'] = I('get.per',60);
    	$page = page($page);
    	if(I('get.type')=='tj'){

            $ret_tj_array = array_reduce($ret_tj_array, function ($result, $value) {
                return array_merge($result, array_values($value));
            }, array());
            $id_list = implode(",",array_chunk($ret_tj_array,$page['per'],false)[$page['cur']-1]);

            $sql = 'select tb1.id,imgkey,visits,downloads,width,height,uid,nickname,likes from  bdmis_a_product tb1 left join bdmis_a_product_number tb2 on tb1.id = tb2.productid left join  bdmis_a_user tb3 on tb1.uid = tb3.id where tb1.id in (';
            $sql .= $id_list;
            $sql .= ')';
            $sql .= ' order by FIELD(tb1.id,' . $id_list . ')';
            $r = D()->query($sql);
    	}else{
    		$sql = 'select * from bdmis_a_product where '.$w.' and match (`name`,classlist,taglist,colorlist) against (\''.$str_kw.'\' in boolean mode) limit '.$page['limit'];
    		
    		if(I('get.tags')!=''){
    			$sql = 'select * from bdmis_a_product where '.$w.' and match (tagids) against (\''.trim(str_replace(',',' ',I('get.tags'))).'\' in boolean mode) limit '.$page['limit'];
    			$r = D()->query($sql);
    		}else $r = D('a_product')->where($w)->order('id desc')->limit($page['limit'])->select();//D()->query($sql);
    	}

    	foreach($r as $k=>$v){
    		$r[$k]['imgurl'] = imgurl_t1($v['imgkey'],$v['size']);
    		if(!$r[$k]['width'] && !$r[$k]['height']){
    			$wh = getimagesize(imgurl_t1($v['imgkey'],$v['size']));
        		$r[$k]['width'] = $wh[0];
        		$r[$k]['height'] = $wh[1];
    			$sd['width'] = $wh[0];
    			$sd['height'] = $wh[1];
    			D('a_product')->where('id='.$v['id'])->save($sd);
        	}
        	$u = D('a_user')->field('username,nickname')->where('id='.$v['uid'])->find();
        	$r[$k]['username'] = $u['username'];
        	$r[$k]['nickname'] = $u['nickname'];
        	$c = D('a_product_number')->field('likes,visits,downloads')->where('productid='.$v['id'])->find();
        	$r[$k]['likes'] = $c['likes'];
    		$r[$k]['visits'] = $c['visits'];
    		$r[$k]['downloads'] = $c['downloads'];
    		$r[$k]['fav'] = D('a_user_fav')->where('isuse=1 and productid='.$v['id'])->count();
    	}
    	$data['r'] = $r;
    	if(I('get.fy')=='t') $data['fanye'] = glb_fystyle(I('get.curpage',1),$page['tol'],$page['len']);
    	echo json_output($data);
    }

    function get_event_images(){
        return $this->get_images();
    }

    function get_images_bak(){
    	$w = 'isuse=1 and status=1 and isfree=1';
    	$str_kw = unescape(trim(I('get.kw')));
    	if($str_kw){
    		//if(I('get.type')=='is') $kw_w = 'tagname = \''.$str_kw.'\'';
    		//else
    		$kw_w = 'tagname like \'%'.$str_kw.'%\'';
    		$kw = D('a_tags')->where($kw_w)->select();
    		$cs_w = 'classname like \'%'.$str_kw.'%\'';
    		$cs = D('a_class')->where($cs_w)->select();
    		if($kw || $cs) $w .= ' and (';
    		if($kw){
    			$kw_i = 0;
    			foreach($kw as $k=>$v){
    				if($kw_i>0) $w_or_s = ' or ';
    				$w .= $w_or_s.'tagids like \'%,'.$v['id'].',%\'';
    				$kw_i++;
    			}
    		}
    		if($cs){
    			if($kw) $w .= ' or ';
	    		$cs_i = 0;
	    		foreach($cs as $k=>$v){
	    			if($cs_i>0) $w_or_s = ' or ';
	    			$w .= $w_or_s.'classids like \'%,'.$v['id'].',%\'';
	    			$cs_i++;
	    		}
    		}
    		if(!$kw && !$cs){
    			$w .= ' and (`name` like \'%'.$str_kw.'%\' or `desc` like \'%'.$str_kw.'%\')';
    		}else{
    			$w .= ' or `name` like \'%'.$str_kw.'%\' or `desc` like \'%'.$str_kw.'%\')';
    		}
    	}
    	if(I('get.classid')) $w .= ' and classids like \'%,'.I('get.classid').',%\'';
    	if(I('get.colorid')) $w .= ' and colorids like \'%,'.I('get.colorid').',%\'';
    	if(I('get.rotateid')) $w .= ' and rotate like \'%,'.I('get.rotateid').',%\'';
    	if(I('get.wh')){
    		$wh_arr = explode(',',I('get.wh'));
    		if($wh_arr[0]!='') $w .= ' and width=\''.$wh_arr[0].'\'';
    		if($wh_arr[1]!='') $w .= ' and height=\''.$wh_arr[1].'\'';
    	}
    	if(I('get.tags')=='xx'){
    		$arr = explode(',',I('get.tags'));
    		$tag_i = 0;
    		for($i=0;$i<(count($arr)-1);$i++){
    			if($arr[$i]!=''){
    				$w_or_s = ' or ';
    				if($tag_i==0) $w_or_s = ' and (';
    				$w .= $w_or_s.'tagids like \'%,'.$arr[$i].',%\'';
    				$tag_i++;
    			}
    		}
    		$w .= ')';
    	}
    	if(I('get.nid')) $w .= ' and id != '.I('get.nid');
    	$r['sql'] = $w;
    	$data['w'] = $w;
    	
    	if(I('get.type')=='tj'){
    		//$page['len'] = D('vw_a_product')->field('id')->join('right join bdmis_syscode on (bdmis_vw_a_product.id=bdmis_syscode.syscode_value and bdmis_syscode.syscode_parentid=326)')->where($w)->order('bdmis_syscode.syscode_sortno')->count();
    		$page['len'] = D('vw_a_product_tj')->field('id')->where($w)->count();
    		$data['type'] = 'tj';
    	}else{
    		if(I('get.tags')!=''){
    			$sql = 'select count(id) as cnt from bdmis_vw_a_product where '.$w.' and match (tagids) against (\''.trim(str_replace(',',' ',I('get.tags'))).'\' in boolean mode)';
    			$rsql = D()->query($sql);
    			$page['len'] = $rsql['cnt'];
    		}else $page['len'] = D('vw_a_product')->field('id')->where($w)->order('id desc')->count();
    		$data['type'] = '';
    	}
    	$data['sql'] = D()->getlastsql();
    	$page['per'] = I('get.per',60);
    	$page = page($page);
    	if(I('get.type')=='tj'){
    		//$r = D('vw_a_product')->field('id,imgkey,visits,downloads,width,height')->join('right join bdmis_syscode on (bdmis_vw_a_product.id=bdmis_syscode.syscode_value and bdmis_syscode.syscode_parentid=326)')->where($w)->order('bdmis_syscode.syscode_sortno')->limit($page['limit'])->select();
    		$r = D('vw_a_product_tj')->field('id,imgkey,visits,downloads,width,height,uid,nickname,likes')->where($w)->limit($page['limit'])->select();
    	}else{
    		if(I('get.tags')!=''){
    			$sql = 'select * from bdmis_vw_a_product where '.$w.' and match (tagids) against (\''.trim(str_replace(',',' ',I('get.tags'))).'\' in boolean mode) limit '.$page['limit'];
    			$r = D()->query($sql);
    		}else $r = D('vw_a_product')->field('id,imgkey,visits,downloads,width,height,uid,nickname,likes')->where($w)->order('id desc')->limit($page['limit'])->select();
    	}
    	//$r = D('vw_a_product')->field('id,imgkey,visits,downloads')->where($w)->order('id desc')->select();
    	foreach($r as $k=>$v){
    		$r[$k]['imgurl'] = imgurl_t1($v['imgkey'],$v['size']);
    		if(!$r[$k]['width'] && !$r[$k]['height']){
    			$wh = getimagesize(imgurl_t1($v['imgkey'],$v['size']));
        		$r[$k]['width'] = $wh[0];
        		$r[$k]['height'] = $wh[1];
    			$sd['width'] = $wh[0];
    			$sd['height'] = $wh[1];
    			D('a_product')->where('id='.$v['id'])->save($sd);
        	}
    		//$r[$k]['visits'] = $v['visits'];
    		//$r[$k]['downloads'] = $v['downloads'];
    		$r[$k]['fav'] = D('a_user_fav')->where('isuse=1 and productid='.$v['id'])->count();
    	}
    	$data['r'] = $r;
    	if(I('get.fy')=='t') $data['fanye'] = glb_fystyle(I('get.curpage',1),$page['tol'],$page['len']);
    	echo json_output($data);
    }
    function search() {
    	if(I('get.wh')){
	    	$wh_arr = explode(',',I('get.wh'));
	    	$data['rw'] = $wh_arr[0];
	    	$data['rh'] = $wh_arr[1];
	    }
    	$data['sql'] = '&fy=t&kw='.I('get.kw').'&type='.I('get.type','like').'&classid='.I('get.classid').'&colorid='.I('get.colorid').'&rotateid='.I('get.rotateid').'&wh='.I('get.wh').'&curpage='.I('get.curpage',1).'&tags='.I('get.tags').'&free='.I('get.free').'&bq='.I('get.bq');
    	
		$data['user'] = $this->user;
		if($data['user']){
			$data['favclass'] = D('a_favclass')->where('isuse=1 and uid='.session('uid'))->order('id desc')->select();
			foreach ($data['favclass'] as $key => $value) {
				$data['favclass'][$key]['img_count'] = D('a_user_fav')->where('isuse=1 and uid='.session('uid').' and favclassid='.$value['id'])->count();
				$r = D('a_product')->field('imgkey,size')->where('isuse=1 and id='.$value['topimgid'])->find();
				if($value['topimgid']==0 || !$r) $data['favclass'][$key]['topimg'] = '/static/images/ablumbg.png';
				else $data['favclass'][$key]['topimg'] = imgurl_t1($r['imgkey'],$r['size']);
			}
		}
		if($_GET['tags']) $data['tags'] = D('a_tags')->where('id ='.$_GET['tags'])->find();
		$w['isuse'] = 1;
		$data['class'] = D('a_class')->where($w)->select();
		$data['color'] = D('a_color')->where($w)->select();
        $this->assign('data', $data);
        $this->display();
    }
    function imglike(){
    	D('a_product_number')->where('productid='.I('get.id'))->setInc('likes');
        //更新用户表的喜欢数
        load('@.bd_update_user_data');
        updateCountAndLikes(I('get.id'));

    	$r = D('a_product_number')->field('likes')->where('productid='.I('get.id'))->find();
    	$d['nums'] = $r['likes'];
    	echo json_output($d);
    }
    //  收藏与取消收藏
    function tofav(){
    	$data['user'] = $this->user;
    	if(!$data['user']) winback('请先登录!');
    	$r = D('a_user_fav')->where('uid='.session('uid').' and productid='.I('get.id'))->order('id desc')->find();
    	if($r){
    		if($r['isuse']){
    			$d['isuse'] = 0;
    			D('a_user_fav')->where('uid='.session('uid').' and productid='.I('get.id'))->save($d);
    			winback('取消收藏成功!');
    		}
    	}
    	if(!is_numeric(I('get.fcid'))) winback('请选择收藏夹!');
		$d['uid'] = session('uid');
		$d['productid'] = I('get.id');
		$d['favclassid'] = I('get.fcid');
		$d['ctime'] = now();
		D('a_user_fav')->add($d);
		winback('收藏成功!');
    }
    function detail() {
    	$data['user'] = $this->user;
		
		D('a_product_number')->where('productid='.I('get.id'))->setInc('visits');
		$favd['uid'] = session('uid');
		$favd['productid'] = I('get.id');
		D('a_product_visit')->where($favd)->save(array('isuse'=>0));
		$favd['ip'] = get_ip();
		$favd['ctime'] = now();
		D('a_product_visit')->add($favd);
		
		if($data['user']){
			$data['favclass'] = D('a_favclass')->where('isuse=1 and uid='.session('uid'))->order('id desc')->select();
			foreach ($data['favclass'] as $key => $value) {
				$data['favclass'][$key]['img_count'] = D('a_user_fav')->where('isuse=1 and uid='.session('uid').' and favclassid='.$value['id'])->count();
				$r = D('a_product')->field('imgkey,size')->where('isuse=1 and id='.$value['topimgid'])->find();
				if($value['topimgid']==0 || !$r) $data['favclass'][$key]['topimg'] = '/static/images/ablumbg.png';
				else $data['favclass'][$key]['topimg'] = imgurl_t1($r['imgkey'],$r['size']);
			}
		}
		
		$w['status'] = 1;
		$d['isuse'] = 1;
		$data['tags'] = D('a_tags')->where($d)->select();
		$data['r'] = D('vw_a_product')->where('isuse=1 and `status`=1 and id='.I('get.id'))->find();
        
		if(!$data['r']) redirect('/');
		if($data['r']['haspass']!=1){
			if(session('uid')!=$data['r']['uid']) redirect('/');
		}
		//  整体上下页
		if($data['user']['id']==$data['r']['uid']){
			$data['preid'] = D('a_product')->field('id')->where('isuse=1 and `status`=1 and uid='.$data['r']['uid'].' and id<'.I('get.id'))->order('id desc')->find();
			if(!$data['preid']){
				$data['preid'] = D('a_product')->field('id')->where('isuse=1 and `status`=1 and uid='.$data['r']['uid'])->order('id desc')->find();
			}
			$data['nextid'] = D('a_product')->field('id')->where('isuse=1 and `status`=1 and uid='.$data['r']['uid'].' and id>'.I('get.id'))->order('id asc')->find();
			if(!$data['nextid']){
				$data['nextid'] = D('a_product')->field('id')->where('isuse=1 and `status`=1 and uid='.$data['r']['uid'])->order('id asc')->find();
			}
		}else{
			$data['preid'] = D('a_product')->field('id')->where('haspass=1 and isuse=1 and `status`=1 and uid='.$data['r']['uid'].' and id<'.I('get.id'))->order('id desc')->find();
			if(!$data['preid']){
				$data['preid'] = D('a_product')->field('id')->where('isuse=1 and `status`=1 and uid='.$data['r']['uid'])->order('id desc')->find();
			}
			$data['nextid'] = D('a_product')->field('id')->where('haspass=1 and isuse=1 and `status`=1 and uid='.$data['r']['uid'].' and id>'.I('get.id'))->order('id asc')->find();
			if(!$data['nextid']){
				$data['nextid'] = D('a_product')->field('id')->where('isuse=1 and `status`=1 and uid='.$data['r']['uid'])->order('id asc')->find();
			}
		}
		//foreach($data['r'] as $k=>$v){
			//  get tag data
			$arr = explode(',',$data['r']['tagids']);
			$tag_i = 0;
			foreach($arr as $v){
				if($v!=''){
					$r = D('a_tags')->field('id,tagname')->where('isuse=1 and id='.$v)->find();
					if($r){
						$tags[$tag_i] = $r;
						$tag_i++;
					}
				}
			}
			$data['r']['tag_data'] = $tags;
			//  get tag data
			$arr = explode(',',$data['r']['classids']);
			$class_i = 0;
			foreach($arr as $v){
				if($v!=''){
					$r = D('a_class')->field('id,classname')->where('isuse=1 and id='.$v)->find();
					if($r){
						$class[$class_i] = $r;
						$class_i++;
					}
				}
			}
			$data['r']['class_data'] = $class;
			//  get color data
			$arr = explode(',',$data['r']['colorids']);
			$color_i = 0;
			foreach($arr as $v){
				if($v!=''){
					$r = D('a_color')->field('id,colorname,colorvalue')->where('isuse=1 and id='.$v)->find();
					if($r){
						$color[$color_i] = $r;
						$color_i++;
					}
				}
			}
			$data['r']['color_data'] = $color;
			$data['sql'] = '&per=16&nid='.I('get.id').'&tags='.$data['r']['tagids'];
			//echo $data['sql'];
		//}
		//dump($data['r']);
		
		$data['fav'] = D('a_user_fav')->where('isuse=1 and productid='.I('get.id'))->count();
		$data['r']['timgurl'] = imgurl_t1($data['r']['imgkey'],$data['r']['size']);
		$data['r']['imgurl'] = imgurl_t2($data['r']['imgkey'],$data['r']['size']);
		
		$data['commend'] = D('a_product_commend')->where('parentid=0 and isuse=1 and productid='.I('get.id'))->order('id desc')->select();
		
		$data['visit'] = D('a_product_visit')->where('isuse=1 and productid='.I('get.id'))->order('id desc')->limit(20)->select();
		
		//  关注信息
		$ffw['uid'] = session('uid');
		$ffw['tuid'] = $data['r']['uid'];
		$ffw['isuse'] = 1;
		$data['followed'] = D('a_user_ff')->where($ffw)->count();
		
        if(I('get.p')) die(dump($data));
        $this->assign('data', $data);

        $lastestTag = str_replace(' ', ',', trim($data['r']['taglist']));
        $this->assign('seo',array('title'=>'作品:'.$data['r']['name'],'keywords'=>$lastestTag));
        $this->display();
    }
	function detail_commend(){
		if(!session('uid')) winback('请先登录!');
		$d['productid'] = I('get.pid');
		$d['uid'] = session('uid');
		$d['commendtxt'] = I('post.commendtxt');
		$d['ctime'] = now();
		D('a_product_commend')->add($d);
		wingo('留言发布成功!','/index/detail?id='.I('get.pid').'#commend');
	}
	function detail_commend_re(){
		if(!session('uid')) winback('请先登录!');
		$d['parentid'] = I('get.id');
		$d['productid'] = I('get.pid');
		$d['uid'] = session('uid');
		$d['commendtxt'] = I('post.commendtxt');
		$d['ctime'] = now();
		D('a_product_commend')->add($d);
		wingo('回复成功!','/index/detail?id='.I('get.pid').'#commend');
	}
	function loging(){
		//$url = I('post.url');
		$r = D('a_user')->where('(username=\''.I('get.m1').'\' or (mobile_v=1 and mobile=\''.I('get.m1').'\') or (email_v=1 and email=\''.I('get.m1').'\')) and password=\''.md5(I('get.p1')).'\'')->find();
		if($r){
			if($r['isuse']!=1){
				die( '0:您的帐户已失效!');
			}
			$fguid = create_guid();
			$rdays = I('get.remember',1);
			if($rdays>90) $rdays = 90;
			if($rdays<1) $rdays = 1;
			$ftime = dateadd('d',$rdays,now());
			$d['fguid'] = $fguid;
			$d['ftime'] = $ftime;
			$d['lastlogin'] = now();
			D('a_user')->where('id='.$r['id'])->save($d);
			session('uid',$r['id']);
			cookie('fguid',$fguid,7*24*60*60);
			cookie('username',I('get.m1'),90*24*60*60);
			echo '1:';
		}
		else echo '0:用户名或密码错误!';
	}
	function qqlogin(){
		$data = file_get_contents('https://graph.qq.com/user/get_user_info?access_token='.I('get.access_token').'&oauth_consumer_key=101362993&openid='.I('get.openid').'&format=json');
		$arr = json_decode($data,1);
		//die(dump($arr));
		$w['3rdpartyid'] = 288;
		$w['3rdpartykey'] = I('get.openid');
		$r = D('a_user')->where($w)->find();
		if($r){
			$fguid = create_guid();
			$rdays = 30;
			$ftime = dateadd('d',$rdays,now());
			$d['fguid'] = $fguid;
			$d['ftime'] = $ftime;
			$d['lastlogin'] = now();
			D('a_user')->where('id='.$r['id'])->save($d);
			session('uid',$r['id']);
			cookie('fguid',$fguid,$rdays*24*60*60);
			cookie('username',I('post.username'),90*24*60*60);
			redirect('/user/my');
		}else{
			$fguid = create_guid();
			$ftime = dateadd('d',1,now());
			$d['3rdpartyid'] = 288;
			$d['3rdpartykey'] = I('get.openid');
			$d['nickname'] = $arr['nickname'];
			if($arr['gender']=='男') $d['gander'] = 1;
			elseif($arr['gender']=='女') $d['gander'] = 2;
			$d['address'] = $arr['province'].'|;|'.$arr['city'];
			$d['birth'] = $arr['year'].'-01-01';
			$d['avatarimg'] = $arr['figureurl_qq_2'];
			$d['fguid'] = $fguid;
			$d['ftime'] = $ftime;
			$d['lastlogin'] = now();
			$d['ctime'] = now();
			$d['isuse'] = 1;
			$d['username'] = '';
			$d['password'] = '';
			$id = D('a_user')->add($d);
			session('uid',$id);
			cookie('fguid',$fguid,24*60*60);
			//cookie('username',I('post.username'),90*24*60*60);
			redirect('/user/my');
		}
	}
	function qqbind(){
		$data = file_get_contents('https://graph.qq.com/user/get_user_info?access_token='.I('get.access_token').'&oauth_consumer_key=101362993&openid='.I('get.openid').'&format=json');
		$arr = json_decode($data,1);
		//die(dump($arr));
		$w['3rdpartyid'] = 288;
		$w['3rdpartykey'] = I('get.openid');
		$r = D('a_user')->where($w)->find();
		if($r){
			wingo('该QQ帐号已经被绑定','/user/my');
		}else{
			$d['3rdpartyid'] = 288;
			$d['3rdpartykey'] = I('get.openid');
			if(session('uid')!='') D('a_user')->where('id='.session('uid'))->save($d);
			elseif(cookie('fguid')!='') D('a_user')->where('fguid='.cookie('fguid'))->save($d);
			else wingo('绑定失败：您的登录已超时，请重新登录后再绑定','');
			wingo('绑定成功','/user/my');
		}
	}
	function mail_test(){
		require_once("./static/plugs/Mailer/phpmailer.php");
		$subject = '探图网注册账号验证码';
        $address = I('get.to');
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
		$content = '<html><head></head><body>感谢您注册探图网<br>您的验证码是：1234</body></html>';
        //$mailer->Body = $content;
		//$body = $content;
		//$mailer->Body = str_replace('<{body}>',$body,$this->Body);
		//$mailer->Body = preg_replace('/\\\\/','', $this->Body); //Strip backslashes
		$mailer->Body = $content;
        $send = $mailer->Send();
        if ($send) {
			echo '1|';
        } else {
            echo "0|错误原因: " . $mailer->ErrorInfo;
        }
	}
	function sms_vcode(){
		if(!I('get.mobile')){
			$r['rstatus'] = 0;
			$r['info'] = '请输入手机号码！';
			die(json_output($r));
		}else{
	    	$mobile = I('get.mobile');
			$u = D('a_user')->where('isuse=1 and mobile=\''.$mobile.'\'')->find();
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
	function sms_fvcode(){
		if(!I('get.mobile')){
			$r['rstatus'] = 0;
			$r['info'] = '请输入手机号码！';
			die(json_output($r));
		}else{
	    	$mobile = I('get.mobile');
			$u = D('a_user')->where('isuse=1 and mobile=\''.$mobile.'\'')->find();
			if(!$u){
				$r['rstatus'] = 0;
				$r['info'] = '该号码未注册！';
				die(json_output($r));
				exit;
			}
	    	$code = rand(1000,9999);
			session('fixfield','mobile');
	    	session('account',$mobile);
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
	function mail_vcode(){
		$u = D('a_user')->where('email=\''.I('get.mail').'\' and isuse=1 and email_v=1')->find();
		if(!$u){
			$r['rstatus'] = 0;
			$r['info'] = '此邮箱未注册';
			die(json_output($r));
		}
		$code = rand(1000,9999);
		session('fixfield','email');
		session('account',I('get.mail'));
		session('smscode',$code);
		$mail_api = $this->mail_forgot(I('get.mail'),$code);
		$mail = explode('|',$mail_api);
		if($mail[0]=='0'){
			$r['rstatus'] = 0;
			$r['info'] = $mail[1];
		}else{
			$r['rstatus'] = 1;
			$r['info'] = '';
		}
		die(json_output($r));
	}
	function refix_pwd() {
		if(!session('account')){
			die('0:验证码已超时，请重新发送！');
		}
		if(session('smscode')!=I('get.code')){
			die('0:短信验证码错误！');
		}
		if(I('get.pwd')=='') die('0:请输入密码！');
		$r = D('a_user')->where(session('fixfield').'=\''.session('account').'\'')->find();
		if(!$r) die('0:没有该手机用户！');
		$d['password'] = md5(I('get.pwd'));
	    $id = D('a_user')->where('id='.$r['id'])->save($d);
		
		session('smscode',null);
		session('fixfield',null);
		session('account',null);
		die('1:');
	}
	function reging_sms(){
		//$url = I('post.url');
		if(session('smscode')!=I('get.code')){
			die('0:短信验证码错误！');
		}
		session('smscode',null);
		$r = D('a_user')->where('(username=\''.I('get.m1').'\' or mobile=\''.I('get.m1').'\')')->find();
		if($r){
			if($r['isuse']!=1){
				die( '0:您的帐户已失效!');
			}
			$fguid = create_guid();
			$rdays = I('get.remember',1);
			if($rdays>90) $rdays = 90;
			if($rdays<1) $rdays = 1;
			$ftime = dateadd('d',$rdays,now());
			$d['fguid'] = $fguid;
			$d['ftime'] = $ftime;
			$d['lastlogin'] = now();
			D('a_user')->where('id='.$r['id'])->save($d);
			session('uid',$r['id']);
			cookie('fguid',$fguid,7*24*60*60);
			cookie('username',I('get.m1'),90*24*60*60);
			echo '1:';
		}else{
			
			$fguid = create_guid();
			$ftime = dateadd('d',1,now());
			$d['fguid'] = $fguid;
			$d['ftime'] = $ftime;
			$d['lastlogin'] = now();
			$d['ctime'] = now();
			$d['isuse'] = 1;
			$d['nickname'] = getRandChar(6);
			$d['mobile'] = I('get.m1');
			$d['mobile_v'] = 1;
			$d['password'] = '';
			D('a_user')->add($d);
			session('uid',$r['id']);
			cookie('fguid',$fguid,7*24*60*60);
			cookie('username',I('get.m1'),90*24*60*60);
			echo '1:';
		}
	}
	function reging(){
		//$url = I('post.url');
		if(I('get.m1')=='' || I('get.u1')=='' || I('get.p1')==''){
			die('0:请填写注册资料！');
		}
		$okstr = '注册成功！';
		//if(!ctype_alnum(I('get.m1'))) die('0:用户名只允许英文或数字的组合！');
		if(strlen(I('get.m1'))==11 && is_numeric(I('get.m1'))){
			if(session('smscode')!=I('get.code')){
				die('0:短信验证码错误！');
			}
			session('smscode',null);
			
			$r = D('a_user')->where('mobile=\''.I('get.m1').'\' and isuse=1')->find();
			if($r) die( '0:此手机已注册。');
			$d['mobile'] = I('get.m1');
			$d['mobile_v'] = 1;
		}else{
			$r = D('a_user')->where('email=\''.I('get.m1').'\' and isuse=1')->find();
			if($r) die( '0:此邮箱已注册。');
			$d['email'] = I('get.m1');
			$d['email_code'] = md5(time());
			$mail_api = $this->mail_reg($d['email'],main_domain().'index/mail_v?code='.$d['email_code']);
			$mail = explode('|',$mail_api);
			if($mail[0]=='0') die('0:'.$mail[1]);
			$okstr = '注册成功，请查收邮件，绑定成功即可邮箱登录！';
		}
		$r = D('a_user')->where('username=\''.I('get.u1').'\'')->find();
		if($r) die( '0:此用户已注册。');
			
		$fguid = create_guid();
		$ftime = dateadd('d',1,now());
		$d['fguid'] = $fguid;
		$d['ftime'] = $ftime;
		$d['lastlogin'] = now();
		$d['ctime'] = now();
		$d['isuse'] = 1;
		$d['username'] = I('get.u1');
		$d['password'] = md5(I('get.p1'));
		$d['nickname'] = I('get.u1');//getRandChar(6);
		D('a_user')->add($d);
		session('uid',$r['id']);
		cookie('fguid',$fguid,7*24*60*60);
		cookie('username',I('get.u1'),90*24*60*60);
		echo '1:'.$okstr;
	}
	function mail_v(){
		$r = D('a_user')->where('email_code=\''.I('get.code').'\'')->find();
		if(!$r) wingo('该链接已过期，请重新绑定！','/index');
		$d['email_v'] = 1;
		$d['email_code'] = '';
		D('a_user')->where('email_code=\''.I('get.code').'\'')->save($d);
		wingo('邮箱绑定成功!','/index');
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
	private function mail_forgot($to,$code){
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
		$content = '<html><head></head><body>探图网找回密码<br>您的验证码是：'.$code.'</body></html>';
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
	 public function otherlogin($type = null) {
        empty($type) && $this->error('参数错误');

        //加载ThinkOauth类并实例化一个对象
        $name = ucfirst(strtolower($type)) . 'SDK';
        $names = "Common\OauthSDK\sdk" . "\\" . $name;
        $oauth = new $names();

        //跳转到授权页面
        redirect($oauth->getRequestCodeURL());
    }

	
    function logout() {
        session('uid', null);
		cookie('fguid',null);
        redirect('/');
    }
	
    function content() {
		$data['user'] = $this->user;
        $data['r'] = D('syscode')->where('syscode_id='.I('get.id','','intval'))->find();
        $this->assign('data', $data);
        $this->display();
    }
    function forget() {
		$data['user'] = $this->user;
        $this->assign('data', $data);
        $this->display();
    }
}
