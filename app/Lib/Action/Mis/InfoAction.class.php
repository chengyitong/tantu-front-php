<?php
//  MIS主控制器
class InfoAction extends BaseAction {
    
    //  公共内容
    protected function _initialize() {
		load('@.bd_plugs');
    }
	
	private function push_one($data){
		require_once(dirname(__FILE__) . '/' . 'notification/android/AndroidUnicast.php');
		
		try {
			$unicast = new AndroidUnicast();
			$unicast->setAppMasterSecret("g96qxpvz3ycarwlzjyh7bq1ahg36ocu7");
			$unicast->setPredefinedKeyValue("appkey",           "57b2cb5ce0f55a3f150023db");
			$unicast->setPredefinedKeyValue("timestamp",        strval(time()));
			// Set your device tokens here
			$unicast->setPredefinedKeyValue("device_tokens",    $data['tokens']); 
			$unicast->setPredefinedKeyValue("ticker",           $data['ticker']);
			$unicast->setPredefinedKeyValue("title",            $data['title']);
			$unicast->setPredefinedKeyValue("text",             $data['text']);
			$unicast->setPredefinedKeyValue("after_open",       "go_app");
			// Set 'production_mode' to 'false' if it's a test device. 
			// For how to register a test device, please see the developer doc.
			$unicast->setPredefinedKeyValue("production_mode", "true");
			// Set extra fields
			$unicast->setExtraField("type", $data['type']);
			$unicast->setExtraField("name", $data['name']);
			$unicast->setExtraField("value", $data['value']);
			//print("Sending unicast notification, please wait...\r\n");
			$unicast->send();
			//print("Sent SUCCESS\r\n");
			return true;
		} catch (Exception $e) {
			//print("Caught exception: " . $e->getMessage());
			return false;
		}
	}
	
	private function push_pub($data){
		require_once(dirname(__FILE__) . '/../Api/' . 'notification/android/AndroidBroadcast.php');
		
		try {
			$unicast = new AndroidBroadcast();
			$unicast->setAppMasterSecret("g96qxpvz3ycarwlzjyh7bq1ahg36ocu7");
			$unicast->setPredefinedKeyValue("appkey",           "57b2cb5ce0f55a3f150023db");
			$unicast->setPredefinedKeyValue("timestamp",        strval(time()));
			// Set your device tokens here
			$unicast->setPredefinedKeyValue("ticker",           $data['ticker']);
			$unicast->setPredefinedKeyValue("title",            $data['title']);
			$unicast->setPredefinedKeyValue("text",             $data['text']);
			$unicast->setPredefinedKeyValue("after_open",       "go_app");
			// Set 'production_mode' to 'false' if it's a test device. 
			// For how to register a test device, please see the developer doc.
			$unicast->setPredefinedKeyValue("production_mode", "true");
			// Set extra fields
			$unicast->setExtraField("type", $data['type']);
			$unicast->setExtraField("name", $data['name']);
			$unicast->setExtraField("value", $data['value']);
			//print("Sending unicast notification, please wait...\r\n");
			$unicast->send();
			//print("Sent SUCCESS\r\n");
			return true;
		} catch (Exception $e) {
			//print("Caught exception: " . $e->getMessage());
			return false;
		}
	}
	
	public function dopushpub(){
		$data = $_GET;
		$data['value'] = 'http://api.hotelmobile.top/apptest/content?table='.I('get.table').'&id='.I('get.id');
		$this->push_pub($data);
		winclose('推送成功!');
	}
    
    public function adv(){
    	$w['isuse'] = 1;
		if(I('get.classid')) $w['classid'] = array('like','%'.I('get.classid').'%');
		if(I('get.type')) $w['type'] = array('like','%'.I('get.type').'%');
		$this->assign('where',$_GET);
        $page['len'] = D('vw_a_banner')->where($w)->count();
        $page = page($page);
		$data['list'] = D('vw_a_banner')->where($w)->limit($page['limit'])->select();
		$data['class'] = D('syscode')->where('syscode_parentid=212')->order('syscode_sortno asc')->select();
        $this->assign('page', $page);
		$this->assign('data',$data);
		
    	//  读取视图
    	$this->display();
    }
	public function adv_add(){
		$d = $_POST;
		$d['ctime'] = now();
    	D('a_banner')->add($d);
		winback('操作成功!');
	}
    
	public function uptadv(){
		$w['id'] = I('get.id');
		$d = $_POST;
    	D('a_banner')->where($w)->save($d);
		winback('操作成功!');
	}
    
    public function ad_editv(){
		$w['id'] = I('get.id');
	    $data['r'] = D('a_banner')->where($w)->find();
		$data['class'] = D('syscode')->where('syscode_parentid=212')->order('syscode_sortno asc')->select();
	    $this->assign('data',$data);
	    $this->display();
    }
    
    public function ad_del(){
		$w['id'] = I('get.id');
		$d['isuse'] = 0;
    	D('a_banner')->where($w)->save($d);
		winback('操作成功!');
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
	
	public function hotel(){
		$data['hotel'] = D('a_hotelinfo_list')->select();
		if(I('get.name_cn')) $w['name_cn'] = array('like','%'.I('get.name_cn').'%');
		$w['isuse'] = I('get.isuse',1);
		$this->assign('where',$w);
        $page['len'] = D('a_hotelinfo_list')->where($w)->count();
        $page = page($page);
		$data['list'] = D('a_hotelinfo_list')->where($w)->limit($page['limit'])->select();
        $this->assign('page', $page);
		$this->assign('data',$data);
		
    	//  读取视图
    	$this->display();
    }
	
	public function hotel_add(){
		$d = $_POST;
		$d['ctime'] = now();
    	D('a_hotelinfo_list')->add($d);
		winback('操作成功!');
	}
	
    public function hotel_editv(){
		$w['id'] = I('get.id');
	    $data['r'] = D('a_hotelinfo_list')->where($w)->find();
	    $this->assign('data',$data);
	    $this->display();
    }
	
	public function hotel_del(){
		$d['isuse'] = 0;
    	D('a_hotelinfo_list')->where('id='.I('get.id'))->save($d);
		winback('操作成功!');
	}
	
	public function bank(){
		if(I('get.name_cn')) $w['name_cn'] = array('like','%'.I('get.name_cn').'%');
		$w['isuse'] = I('get.isuse',1);
		$this->assign('where',$w);
        $page['len'] = D('a_bank_list')->where($w)->count();
        $page = page($page);
		$data['list'] = D('a_bank_list')->where($w)->limit($page['limit'])->select();
        $this->assign('page', $page);
		$this->assign('data',$data);
		
    	//  读取视图
    	$this->display();
    }
	
	public function bank_add(){
		$d = $_POST;
		$d['ctime'] = now();
    	D('a_bank_list')->add($d);
		winback('操作成功!');
	}
	
    public function bank_editv(){
		$w['id'] = I('get.id');
	    $data['r'] = D('a_bank_list')->where($w)->find();
	    $this->assign('data',$data);
	    $this->display();
    }
	
	public function bank_del(){
		$d['isuse'] = 0;
    	D('a_bank_list')->where('id='.I('get.id'))->save($d);
		winback('操作成功!');
	}
	
	public function fun(){
		if(I('get.classid')) $w['classid'] = I('get.classid');
		if(I('get.name_cn')) $w['name_cn'] = array('like','%'.I('get.name_cn').'%');
		$w['isuse'] = I('get.isuse',1);
		$this->assign('where',$w);
        $page['len'] = D('vw_a_fun_list')->where($w)->count();
        $page = page($page);
		$data['list'] = D('vw_a_fun_list')->where($w)->limit($page['limit'])->select();
		$data['class'] = D('syscode')->where('syscode_parentid=194')->order('syscode_sortno asc')->select();
        $this->assign('page', $page);
		$this->assign('data',$data);
		
    	//  读取视图
    	$this->display();
    }
	
	public function fun_add(){
		$d = $_POST;
		$d['ctime'] = now();
    	D('a_fun_list')->add($d);
		winback('操作成功!');
	}
	
    public function fun_editv(){
		$w['id'] = I('get.id');
	    $data['r'] = D('a_fun_list')->where($w)->find();
		$data['class'] = D('syscode')->where('syscode_parentid=194')->order('syscode_sortno asc')->select();
	    $this->assign('data',$data);
	    $this->display();
    }
	
	public function fun_del(){
		$d['isuse'] = 0;
    	D('a_fun_list')->where('id='.I('get.id'))->save($d);
		winback('操作成功!');
	}
	
	public function scenic(){
		if(I('get.name_cn')) $w['name_cn'] = array('like','%'.I('get.name_cn').'%');
		$w['isuse'] = I('get.isuse',1);
		$this->assign('where',$w);
        $page['len'] = D('a_scenic_list')->where($w)->count();
        $page = page($page);
		$data['list'] = D('a_scenic_list')->where($w)->limit($page['limit'])->select();
        $this->assign('page', $page);
		$this->assign('data',$data);
		
    	//  读取视图
    	$this->display();
    }
	
	public function scenic_add(){
		$d = $_POST;
		$d['ctime'] = now();
    	D('a_scenic_list')->add($d);
		winback('操作成功!');
	}
	
    public function scenic_editv(){
		$w['id'] = I('get.id');
	    $data['r'] = D('a_scenic_list')->where($w)->find();
	    $this->assign('data',$data);
	    $this->display();
    }
	
	public function scenic_del(){
		$d['isuse'] = 0;
    	D('a_scenic_list')->where('id='.I('get.id'))->save($d);
		winback('操作成功!');
	}
	
	public function exhibition(){
		if(I('get.name_cn')) $w['name_cn'] = array('like','%'.I('get.name_cn').'%');
		$w['isuse'] = I('get.isuse',1);
		$this->assign('where',$w);
        $page['len'] = D('a_exhibition_list')->where($w)->count();
        $page = page($page);
		$data['list'] = D('a_exhibition_list')->where($w)->limit($page['limit'])->select();
        $this->assign('page', $page);
		$this->assign('data',$data);
		
    	//  读取视图
    	$this->display();
    }
	
	public function exhibition_add(){
		$d = $_POST;
		$d['ctime'] = now();
    	D('a_exhibition_list')->add($d);
		winback('操作成功!');
	}
	
    public function exhibition_editv(){
		$w['id'] = I('get.id');
	    $data['r'] = D('a_exhibition_list')->where($w)->find();
	    $this->assign('data',$data);
	    $this->display();
    }
	
	public function exhibition_del(){
		$d['isuse'] = 0;
    	D('a_exhibition_list')->where('id='.I('get.id'))->save($d);
		winback('操作成功!');
	}
	
	public function share(){
		if(I('get.name_cn')) $w['name_cn'] = array('like','%'.I('get.name_cn').'%');
		$w['isuse'] = I('get.isuse',1);
		$this->assign('where',$w);
        $page['len'] = D('a_share_list')->where($w)->count();
        $page = page($page);
		$data['list'] = D('a_share_list')->where($w)->limit($page['limit'])->select();
		$data['class'] = D('syscode')->where('syscode_parentid=194')->order('syscode_sortno asc')->select();
        $this->assign('page', $page);
		$this->assign('data',$data);
		
    	//  读取视图
    	$this->display();
    }
}