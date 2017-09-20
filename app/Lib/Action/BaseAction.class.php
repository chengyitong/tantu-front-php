<?php
// 基础
class BaseAction extends Action {
    protected function _initialize() {
    	if(!session('bdmis_uid')){
			echo '<script>parent.location="/login";</script>';
			exit;
		}
    	//  版本信息
    	$this->assign('version','V1.0');
    	//  授权商
    	$this->assign('storename','探图');
    	//  有效期
    	$this->assign('usetodate','2099-12-31');
    	//  session信息
    	$this->assign('s_uid',session('bdmis_uid'));
    	$this->assign('s_name',session('bdmis_name'));
    	$this->assign('s_rule',session('bdmis_rule'));
    }
}