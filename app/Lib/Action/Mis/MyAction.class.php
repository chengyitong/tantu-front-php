<?php

class MyAction extends BaseAction {
    
    //  公共内容
    protected function _initialize() {
		load('@.bd_plugs');
    }
    /*
    
    企业邮箱  v1.0
    williamfu 2014.6.21
    
    */
	private function create_admin(){
		die('');
		$str = '&alias=admin@zhgethome.com';
		$str .= '&name=admin';
		$str .= '&mobile=0';
		$str .= '&Password='.md5('888888').'&md5=1';
		$str .= '&opentype=1';
		$api = A('Mis/Api');
		$api->strs = $str;
		$r = $api->qqmail_insert();
		die($r);
	}
	
    public function mail()
	{
		die('');
		$api = A('Mis/Api');
		redirect($api->qqmail_goto());
	}
    /*
    
    我的资料  v1.0
    williamfu 2014.7.20
    
    */
    public function info(){
	    $account = D('sysaccount')->relation('syscode')->where('sysaccount_id='.session('bdmis_uid'))->find();
	    $permission = D('syscode')->where('syscode_parentid=2')->select();
	    $this->assign('account',$account);
	    $this->assign('permission',$permission);
	    $this->display();
    }
    //  更新处理
    public function info_upt(){
	    $data = array(
					'sysaccount_tel' => I('post.tel'),
					'sysaccount_idcard' => I('post.idcard'),
					'sysaccount_modifierid' => $s_uid,
					'sysaccount_modifytime' => now(),
					);
		if(I('post.pwd')) $data['sysaccount_password'].=md5(I('post.pwd'));
		D('sysaccount')->where('sysaccount_id='.session('bdmis_uid'))->save($data);
		//更新qqmail
    	$id = session('bdmis_uid');
	    $uinfo = D('sysaccount')->relation('syscode')->where('sysaccount_id='.$id)->find();
		$str = '&alias='.$uinfo['sysaccount_username'].'@zhgethome.com';
		$str .= '&mobile='.I('post.tel');
		if(I('post.pwd')) $str .= '&Password='.I('post.pwd').'&md5=1';
		$api = A('Mis/Api');
		$api->strs = $str;
		$r = $api->qqmail_update();
		//  输出
		wingo('修改成功!','/my/info');
    }
	
    /*
    
    我的考勤  v1.0
    williamfu 2014.7.20
    
    */
	public function calendar(){
		load('@.bd_plugs');
		load('@.bd_date');
		$star = getCurMonthFirstDay(I('get.date',now(1)));
		$end = getCurMonthLastDay(I('get.date',now(1)));
		if(session('bdmis_rule')==8)
			$r = D('calendar')->field('bdmis_calendar.*,bdmis_sysaccount.sysaccount_name as name')->join('bdmis_sysaccount on bdmis_sysaccount.sysaccount_id=bdmis_calendar.accountid')->where('date >= \''.$star.'\' and date <= \''.$end.'\' and isuse=1')->order('date,msgtime')->select();
		else
			$r = D('calendar')->where('date >= \''.$star.'\' and date <= \''.$end.'\' and isuse=1 and accountid='.session('bdmis_uid'))->order('date,msgtime')->select();
		$data['r'] = array();
		foreach($r as $k){
			$d = str_replace('-','',$k['date']);
			$data['r'][$d][] = $k;
		}
		$this->assign('date',$data);
		$this->display();
	}
	
	public function calendar_addv(){
	    $data['u'] = D('sysaccount')->where('sysaccount_id='.session('bdmis_uid'))->find();
		$this->assign('data',$data);
		$this->display();
	}
	
	public function calendar_add(){
		$data['date'] = I('post.date');
		$data['title'] = I('post.title');
		$data['content'] = I('post.content');
		$data['msgtype'] = I('post.msg');
		$data['msgtime'] = I('post.time');
		$data['msgadds'] = I('post.mobile');
		$data['msgmail'] = I('post.email');
		$data['createtime'] = now();
		$data['accountid'] = session('bdmis_uid');
		D('calendar')->add($data);
		echo '<script>alert("添加成功!");parent.location.reload();</script>';
	}
	
	public function calendar_v(){
		if(session('bdmis_uid')==2)
			$data['r'] = D('calendar')->where('id='.I('get.id').' and isuse=1')->find();
		else
			$data['r'] = D('calendar')->where('id='.I('get.id').' and isuse=1 and accountid='.session('bdmis_uid'))->find();
		$this->assign('data',$data);
		$this->display();
	}
	
	public function calendar_edit(){
		$w['id'] = I('post.id');
		$w['accountid'] = session('bdmis_uid');
		$data['date'] = I('post.date');
		$data['title'] = I('post.title');
		$data['content'] = I('post.content');
		$data['msgtype'] = I('post.msg');
		$data['msgtime'] = I('post.time');
		$data['msgadds'] = I('post.mobile');
		$data['msgmail'] = I('post.email');
		D('calendar')->where($w)->save($data);
		echo '<script>alert("修改成功!");parent.location.reload();</script>';
	}
	
	public function calendar_del(){
		if(session('bdmis_uid')==2)
			D('calendar')->where('id='.I('get.id'))->save(array('isuse'=>0));
		else
			D('calendar')->where('id='.I('get.id').' and accountid='.session('bdmis_uid'))->save(array('isuse'=>0));
		$data['status'] = 'success';
		echo json_output($data);
	}
	
}