<?php
//  首页
class EventAction extends Action {
    //公共的用户登录操作
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

        $data['user'] = $this->user;
        $this->assign('data',$data);
    }

    public function index(){
        $this->assign('event_list_processing',$this->get_status_events(0));
        $this->display();
    }

    private function get_status_events($status){
        $w = '';
        if(0==$status){
            $w = 'datediff(end_time,curdate())>=0';
        }
        else{
             $w = 'datediff(end_time,curdate())<0';
        }
        return  M('a_event')->field('id,datediff(end_time,curdate()) AS dayLeft,product_count,user_count,subject,subject_banner_list,award, link')->where($w)->order('sortno desc')->select();
    }

    //我的参赛
    public function get_my_events(){
        $data['flag'] = 0;
        if (session('uid')) {
            $Model = new Model();
            $event_list = $Model->query("select id,datediff(end_time,curdate()) AS dayLeft,product_count,user_count,subject,subject_banner_list,award,link from bdmis_a_event where id in(select event_id from bdmis_a_event_images where user_id=".session('uid').")");
            $data['flag'] = sizeof($event_list);            
            $data['events'] = $event_list;
        }
        
        $this->ajaxReturn($data);
    }

    //已结束（的活动）
    public function get_end_events(){        
        $event_list = $this->get_status_events(1);
        $data['flag'] = sizeof($event_list);            
        $data['events'] = $event_list;
        $this->ajaxReturn($data);
    }

    public function event(){    	
    	$event_id = I('get.id');
    	$event = M('a_event')->field('id,datediff(end_time,curdate()) AS dayLeft,product_count,user_count,subject,subject_banner_detail,description,link,award_result')->where('id='.$event_id)->find();
        if(isset($event['link']) && $event['link']!==''){            
            header('location:'.$event['link']);exit;
        }//直接重定向
        else{
            $serialize = unserialize($event['description']);
            $this->assign('desc_list',$serialize);
            $this->assign('event',$event);

            $seo = array('title'=>'活动:'.$event['subject']);
            if($serialize && 0 < count($serialize)){
                $k = trim($serialize[0]['content']);
                $seo['description'] = preg_replace('/(\s|&nbsp;)+/', ' ', strip_tags(htmlspecialchars_decode($k)));
            }
            $this->assign('seo',$seo);
            $this->display();
        }
    }

    //用户参赛投稿：关联图片
    public function submit_event_product(){
        $this->add_products_to_event(I('get.id'), I('post.images'));
    }

    private function add_products_to_event($event_id, $arr){
        if(session('uid') && $arr && $event_id){
            $arr = json_decode($arr);
            
            $models = array();
            foreach ($arr as $key => $value) {
                $models[] = array('event_id'=>$event_id, 'product_id'=>$value,'user_id'=>session('uid'));
            }

            $eventModel = M('a_event');
            $eventModel->id = $event_id;
            $eventModel->product_count = array('exp','product_count+'.count($arr));

             //当前用户是否已计算在参加人数内
            $_count = M('a_event_images')->where('user_id='.session('uid').' and event_id='.$event_id)->count();

            if(count($arr)+$_count >20){
                $data['flag'] = 0;
                $data['msg'] = '一个活动大赛只允许一个用户最多20张作品参赛!';
            }
            else{            
                $data['count']=$_count;
                if(!$_count){
                    $eventModel->user_count = array('exp','user_count+1');
                }

                M('a_event')->save();//更新活动表
                // $data['sql1']=M('a_event')->getlastsql(); //only for debug
                
                $data['models'] = $models;
                $data['flag'] = M('a_event_images')->addAll($models);//更新活动用户关联表
            }
        }
        else{
            $data['msg'] = '非法提交!';
            $data['flag'] = 0;
        }
        $this->ajaxReturn($data);
    }

    //保存并加入活动
    public function save_and_add_to_event(){
        if(!I('get.id')){
            $data['msg'] = '非法提交';
            $data['flag'] = 0;
            $this->ajaxReturn($data);
        }

        //1 - 批量保存产品信息
        R('Product/productionSave');

        //2 - 保存并返回信息
        $this->add_products_to_event(I('get.id'), I('post.images'));
    }

    //获取活动关联的图片
    public function get_event_images(){
        if(!I('get.event_id') || !I('get.event_type')){
            return;
        }

        $event_id = I('get.event_id');
        $event_type = I('get.event_type');

        $w ='event_id='.$event_id;
        if('3'==$event_type){
            $w.='user_id='.session('uid');
        }

        $data_count = D('a_event_images')->where($w)->count();
        $data["total"] = $data_count;
        $page['len'] = $data_count;
        $page['per'] = I('get.per',60);
        $page = page($page);

        $order = " order by t2.id desc";
        $sql = "SELECT t1.* FROM `bdmis_a_product` t1 left join `bdmis_a_event_images` t2 on t1.id = t2.product_id";
        if('2'==$event_type){
            $sql .=" left join `bdmis_a_product_number` t3 on t1.id = t3.productid";
            $order = " order by likes desc,t2.id desc";
        }//热门
        
        //仅取(非标记删除的)图片
        $where = " where t1.isuse=1 and t2.event_id=".$event_id;
        if('3'==$event_type){
            $where .=" and t1.uid=".session('uid');
        }//我的

        $limit = " limit ".$page['limit'];

        $sql = $sql. $where . $order . $limit;
        $r = D()->query($sql);
        // $data['sql'] = D()->getlastsql();
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

        $data["r"] = $r;

        if(I('get.fy')=='t'){
            $data['fanye'] = glb_fystyle(I('get.curpage',1),$page['tol'],$page['len']);
        }
        echo json_output($data);
    }
}