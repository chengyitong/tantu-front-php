<?php
class LoginAction extends Action {
    public function index(){
    	if(session('bdmis_uid')){
	    	$this->redirect('/');
    	}
    	$this->display();
    }
    public function in(){
    	$w['sysaccount_username'] = array('eq',I('post.uid'));
    	$w['sysaccount_password'] = array('eq',md5(I('post.pwd')));
    	$u = D('sysaccount')->where($w)->find();
		if(!$u){
			$this->error('用户名或密码不正确!');
		}else{
			if(!$u['sysaccount_isuse']){
				$this->error('该账户已停用!');
			}
			//最后登录时间
			D('sysaccount')->where(array('sysaccount_id'=>$u['sysaccount_id']))->save(array('sysaccount_lastlogin'=>now()));
			session('bdmis_uid',$u['sysaccount_id']);
			session('bdmis_name',$u['sysaccount_name']);
			session('bdmis_rule',$u['sysaccount_permissionid']);
			session('bdmis_time',now());
			$this->redirect('/');
		}
    }
    public function out(){
    	//  清除所有会话
	    session(null);
	    //跳转地址或直接退出
	    if(I('get.url')!=''){
			redirect(I('get.url'));
		}else{
			echo '
				<script>
					window.opener = null;
					window.open("","_self","");
					window.close();
				</script>
				';
		}
    }
    public function servertime(){
    	if(!session('bdmis_uid')){
    		$arr['status'] = 0;
    		$arr['errinfo'] = '登录超时, 请重新登录!';
		    echo json_encode($arr);
    	}else{
		    $c = A('Mis/Index');
		    $r = $c->online();
		    if($r){
			    //  被强制下线
			    $arr['status'] = 0;
	    		$arr['errinfo'] = '系统强制退出!';
			    echo json_encode($arr);
		    }else{
				//  返回服务器时间
	    		$arr['status'] = 1;
			    $arr['now'] = date('Y-m-d H:i');
			    echo json_encode($arr);
		    }
		}
    }
}