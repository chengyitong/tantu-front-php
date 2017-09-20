<?php
//  Api主控制器
class ApiAction extends BaseAction {
    protected function _initialize() {
    	$id = session('bdmis_uid');
	    $this->uinfo = D('sysaccount')->relation('syscode')->where('sysaccount_id='.$id)->find();
    }
    public function qqmail_msgs(){
		$uinfo = $this->uinfo;
		$email = $uinfo['sysaccount_username'].'@zhgethome.com';
		$api = A('Api/Qqmail');
		$api->email = $email;
		echo $api->get_msgs();
    }
	public function qqmail_goto(){
		$uinfo = $this->uinfo;
		$email = $uinfo['sysaccount_username'].'@zhgethome.com';
		$api = A('Api/Qqmail');
		$api->email = $email;
		return $api->get_mailurl();
	}
	public function qqmail_insert(){
		$api = A('Api/Qqmail');
		$str = 'action=2';
		$api->str = $str.$this->strs;
		return $api->sync();
	}
	public function qqmail_update(){
		$api = A('Api/Qqmail');
		$str = 'action=3';
		$api->str = $str.$this->strs;
		return $api->sync();
	}
	public function qqmail_delete(){
		$api = A('Api/Qqmail');
		$str = 'action=1';
		$api->str = $str.$this->strs;
		return $api->sync();
	}
}