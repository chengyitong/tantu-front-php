<?php
//  MIS主控制器
class StoreAction extends BaseAction {
    //  MIS主界面
    public function banner(){
    	$w['status'] = 1;
		if(I('get.id')) $w['id'] = array('like','%'.I('get.id').'%');
		if(I('get.devicetoken')) $w['devicetoken'] = array('like','%'.I('get.devicetoken').'%');
		$this->assign('where',$_GET);
        $page['len'] = D('a_mobile')->where($w)->count();
        $page = page($page);
		$data['list'] = D('a_mobile')->where($w)->limit($page['limit'])->select();
        $this->assign('page', $page);
		$this->assign('data',$data);
		
    	//  读取视图
    	$this->display();
    }
	
	public function banner_add(){
		$d = $_POST;
		$d['ctime'] = now();
    	D('a_weibanner')->add($d);
		winback('操作成功!');
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
}