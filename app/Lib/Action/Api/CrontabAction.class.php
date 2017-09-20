<?php
class CrontabAction extends Action {
    protected function _initialize() {
    }
	public function excute(){
		
    }
	function update_data(){
		$starttime = explode(' ',microtime());
		$user = D('a_user')->where('isuse=1')->select();
		foreach($user as $k){
			$d['id'] = $k['id'];
			$d['count_imgs'] = D('a_product')->where('status=1 and haspass=1 and isuse=1 and uid='.$k['id'])->count();
			$likes = D('a_product_number')->field('sum(bdmis_a_product_number.likes) as sum_likes')->join('bdmis_a_product on bdmis_a_product_number.productid=bdmis_a_product.id')->where('status=1 and haspass=1 and isuse=1 and uid='.$k['id'])->find();
			if($likes['sum_likes']) $d['count_likes'] = $likes['sum_likes'];
			else $d['count_likes'] = 0;
			D('a_user')->save($d);
		}
		$endtime = explode(' ',microtime());
		$thistime = $endtime[0]+$endtime[1]-($starttime[0]+$starttime[1]);
		$thistime = round($thistime,3);
		$back['result'] = '1';
		$back['runtimes'] = $thistime;
		$log['txt'] = 'sync data to user';
		$log['ctime'] = now();
		//D('log')->add($log);
		echo json_output($back);
	}
}