<?php

class EventAction extends BaseAction {

    protected function _initialize() {

	}

	//增加新活动
	public function Add(){
		$this->display();
	}

	//活动列表
	public function Get_list(){
		$pageIndex = I('get.p')?:'1';
		$pageSize = I('get.pageSize')?:'20';

		$sql='';
		if(I('get.dtp_input1')){
			$sql.=" start_time>='".I('get.dtp_input1')."'";
		}

		if(I('get.dtp_input2')){
			if(''!=$sql){
				$sql.=" and ";
			}
			$sql.=" end_time<='".I('get.dtp_input2')."'";
		}

		if(I('get.txtTitle')){
			if(''!=$sql){
				$sql.=" and ";
			}
			$sql.=" (subject like '%".I('get.txtTitle')."%' or award like '%".I('get.txtTitle')."%')";
		}

		$list = M("a_event")->where($sql)->order('id')->page($pageIndex,$pageSize)->select();
		$this->assign('list',$list);

		import("ORG.Util.Page");// 导入分页类
		$count      = M("a_event")->count();// 查询满足要求的总记录数
		$Page       = new Page($count,$pageSize);// 实例化分页类 传入总记录数和每页显示的记录数		
		$Page->setConfig('theme','%first% %upPage% %prePage% %linkPage% %nextPage% %end% %totalRow% %header% %nowPage%/%totalPage% 页');
		$show = $Page->show();// 分页显示输出
		$show = $this->bootstrap_page_style($show);
		$this->assign('page',$show);// 赋值分页输出
		$this->display();
	}

	//编辑活动
	public function Edit(){
		$eventId = $_GET['id'];
		dump($eventId);

		$event_model = M("a_event"); 
		$data = $event_model->where('id='.$eventId)->find();

		$serialize = unserialize($data['description']);
		$this->assign('desc_list',$serialize);
		$this->assign('eventId',$eventId);
		$this->assign('data',$data);
		$this->display('add');
	}

	//保存活动(增加或修改)
	public function saveEvent(){
		$arr_desc = array();
		$sortno = intval(I('post.txtSortno'));
		if(!($sortno>-1)){
			$this->error('显示顺序必须为非负整数！');
			return;
		}

		foreach (I('post.txt_desc_Title') as $k => $v) {
			$arr_desc[] = array('title'=> $v, 'content'=>I('post.keditor_desc_content')[$k]);
		}

		$eventArray = array(
			'subject' =>I('post.txtTitle'),
			'start_time' =>I('post.dtp_input1'),
			'end_time' =>I('post.dtp_input2'),
			'award' =>I('post.txtAward'),
			'description' => serialize($arr_desc),
			'sortno' => $sortno,
			'link' => I('post.txtLink'),
			'award_result' => I('post.keditor_AwardResult')
		);

		if(''!=$_FILES['fileIndex']['name'] 
			|| ''!=$_FILES['fileDetail']['name'] 
			|| ''!=$_FILES['fileList']['name'] 
			){
			//处理上传文件
			$savePath = '/static/uploadfiles/event/';
			import('ORG.Net.UploadFile');
			$upload = new UploadFile();// 实例化上传类
			$upload->maxSize  = 5242880 ;// 设置附件上传大小
			$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
			$upload->savePath =  '.'.$savePath;// 设置附件上传目录
			if(!$upload->upload()) {// 上传错误提示错误信息
				$this->error($upload->getErrorMsg());
			}else{// 上传成功 获取上传文件信息
				$info =  $upload->getUploadFileInfo();
			}

			foreach ($info as $i => $value) {
				if('fileIndex'==$value['key']){
					$eventArray['subject_banner_index'] = $savePath.$value['savename'];
				}

				if('fileDetail'==$value['key']){
					$eventArray['subject_banner_detail'] = $savePath.$value['savename'];
				}

				if('fileList'==$value['key']){
					$eventArray['subject_banner_list'] = $savePath.$value['savename'];
				}
			}
		}

		$opt = false;
		if(null == I('post.eventId')){
			$opt = D('a_event')->add($eventArray);
		}
		else{			
			$eventArray['id'] =  I('post.eventId');
			$opt = D('a_event')->save($eventArray);
		}

		if(false === $opt){
			$this->error('保存失败！');			
		}
		else{
			$this->success('保存成功！','/event/get_list');
		}
	}

	//删除活动
	public function delEvent(){
		$eventId = I('get.event_id');
		$event_model = M("a_event"); 
		$event_model->startTrans();
		$event_model->where('id='.$eventId)->delete();
		M("a_event_images")->where('event_id='.$eventId)->delete(); 
		$event_model->commit();
		$this->ajaxReturn(array('status'=>1,'msg'=>'删除成功'),"json");
	}

	/**
	 * Thinkphp默认分页样式转Bootstrap分页样式
	 * @author H.W.H
	 * @param string $page_html tp默认输出的分页html代码
	 * @return string 新的分页html代码
	 */
	function bootstrap_page_style($page_html){
	    if ($page_html) {
	        $page_show = '<ul class="pagination">'.$page_html;
	        $page_show = str_replace('<a','<li><a',$page_show);
	        $page_show = str_replace('</a>','</a></li>',$page_show);
	        $page_show = str_replace("<span class='current'>",'<li class="active"><a>',$page_show);
	        $page_show = str_replace('</span>','</a></li>',$page_show);
	        $page_show = $page_show.'</ul>';
	    }
	    return $page_show;
	}
}