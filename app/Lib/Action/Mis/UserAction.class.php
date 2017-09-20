<?php
//  MIS主控制器
class UserAction extends BaseAction {
    //  公共内容
    protected function _initialize() {
		load('@.bd_plugs');
    }
	
    //  MIS主界面
    public function index(){
		I('get.sortby') ? $sortby=I('get.sortby') : $sortby='id,asc';
		$sortby_arr = str_replace(',',' ',$sortby);
    	$w['isuse'] = 1;
		if(I('get.id')) $w['id'] = array('like','%'.I('get.id').'%');
		if(I('get.mobile')){
			$w['mobile'] = array('like',I('get.mobile').'%');
		}
		if(I('get.nickname')){
			$w['nickname'] = array('like','%'.I('get.nickname').'%');
		}

		$this->assign('where',$_GET);
        $page['len'] = D('a_user')->where($w)->count();
        $page = page($page);
		$data['list'] = D('a_user')->field('*,(select count(id) from bdmis_a_product where isuse=1 and uid=bdmis_a_user.id) as products,(select count(id) from bdmis_a_product where haspass=0 and isuse=1 and uid=bdmis_a_user.id) as waits')->where($w)->limit($page['limit'])->order($sortby_arr)->select();
        $this->assign('page', $page);
		$this->assign('data',$data);
		
    	//  读取视图
    	$this->display();
    }
	
	public function add(){
		$d = $_POST;
//		$guid = create_guid();
//		$guid = str_replace('{','',$guid);
//		$guid = str_replace('}','',$guid);
//		$guid = str_replace('-','',$guid);
//		$d['fguid'] = $guid;
        $d['password'] = md5('888888');
		$d['ctime'] = now();
    	D('a_user')->add($d);
		winback('操作成功!');
	}
	public function editv(){
    	$w['status'] = 1;
    	$w['typeid'] = 248;
		$w['id'] = I('get.id');
	    $data['r'] = D('a_user')->where($w)->find();
		$this->assign('data',$data);
    	$this->display();
	}
	public function upt(){
		$d = $_POST;
		if(I('post.pwd')!='') $d['password'] = md5(I('post.pwd'));
		$w['id'] = I('get.id');
	    $data['r'] = D('a_user')->where($w)->save($d);
		winback('操作成功!');
	}
	public function del(){
    	D('a_user')->where('id='.I('get.id'))->save(array('isuse'=>0));
		winback('操作成功!');
	}
	
	function ff(){
		$data['r'] = D('a_user')->where('id='.I('get.id'))->find();
		
		$w['isuse'] = 1;
		if(I('get.type')=='follow') $w['uid'] = I('get.id');
		else $w['tuid'] = I('get.id');
		$page['len'] = D('a_user_ff')->where($w)->count();
		$page = page($page);
		$data['list'] = D('a_user_ff')->where($w)->limit($page['limit'])->select();
		$this->assign('page', $page);
		$this->assign('data',$data);
		$this->display();
	}
	
	function msg(){
		if(I('get.uids')) $w['uids'] = I('get.uids');
		if(I('get.msgs')) $w['msgs'] = array('like','%'.I('get.msgs').'%');
		$page['len'] = D('a_msg')->where($w)->count();
		$page = page($page);
		$this->assign('where',$_GET);
		$data['list'] = D('a_msg')->where($w)->limit($page['limit'])->select();
		$this->assign('page', $page);
		$this->assign('data',$data);
		$this->display();
	}
	function msg_add(){
		$d = $_POST;
		$d['ctime'] = now();
		D('a_msg')->add($d);
		winback('发布成功!');
	}
	
	function album(){
		$data['r'] = D('a_user')->where('id='.I('get.uid'))->find();
		$w['isuse'] = 1;
		if(I('get.uid')) $w['uid'] = I('get.uid');
		$page['len'] = D('a_folder')->where($w)->count();
		$page = page($page);
		$this->assign('where',$_GET);
		$data['list'] = D('a_folder')->where($w)->limit($page['limit'])->select();
		$this->assign('page', $page);
		$this->assign('data',$data);
		$this->display();
	}
}