<?php

class ProductAction extends BaseAction {

    protected function _initialize() {

	}

	private function get_imgurl($type,$key,$name='test.jpg') {
		require_once('./static/qiniu/jssdk/demo/src/qiniu6_1/rs.php');
		require_once('./static/qiniu/jssdk/demo/src/qiniu6_1/auth_digest.php');
		# @gist set_keys
		$accessKey = qiniu_ak();//'ixJ6FoVYJvSK3eO8IXsKkTZiWMGGxRISMuQDeCgx';
		$secretKey = qiniu_sk();//'W45phNfCsu-3zVZO2qf1mJZbRT_0McSQYoXTyFHB';
		Qiniu_setKeys($accessKey, $secretKey);
		$gpy = new Qiniu_RS_GetPolicy();
		$url = qiniu_freedomain().$key;
		if($type=='download') $url .= '?attname='.$name;
		elseif($type=='exif') $url .= '?exif';
		elseif($type=='info') $url .= '?imageInfo';
		elseif($type=='stat') $url .= '?stat';
		return $gpy->MakeRequest($url,null);
	}
	function add_product() {
		if(!session('uid')){
			return;
		}//防止非法提交

		$d['uid'] = session('uid');
		$d['folderid'] = $_POST['fid'];
		$udata = explode('|;|', $_POST['udata']);
		$backstr = '';
		$succId = array();
		foreach ($udata as $v) {
			$arr = explode('|,|',$v);
			if(!$arr[0]) continue;
			$imgkey = strtolower($arr[1]);
			$r = D('a_product')->where('imgkey=\''.$imgkey.'\'')->find();
			if($r) continue;
			//读取文件信息
			$json1 = file_get_contents($this->get_imgurl('stat',$imgkey));
			$arr1 = json_decode($json1,true);
			//读取图片信息
			$json2 = file_get_contents($this->get_imgurl('info',$imgkey));
			$arr2 = json_decode($json2,true);
			//读取exif
			$json3 = file_get_contents($this->get_imgurl('exif',$imgkey));
			$arr3 = json_decode($json3,true);
			
			$d['width'] = $arr2['width'];
			$d['height'] = $arr2['height'];
			if($arr2['width']*1<600 || $arr2['height']*1<600){
				$bname = explode('.',$imgkey);
				if($backstr!='') $backstr .= ','.$bname[0];
				else $backstr .= $bname[0];
				continue;
			}
			$d['format'] = $arr2['format'];
			$d['rotate'] = 0;
			if($d['width']>0 && $d['width']==$d['height']) $d['rotate'] = 2;
			elseif($d['width']<$d['height']) $d['rotate'] = 1;
			//$tmp_arr1 = explode(' ',$arr3['Model']['val']);
			//if(count($tmp_arr1)>1){
			//	$d['brand'] = $tmp_arr1[0];//品牌
			//	$d['model'] = $tmp_arr1[1];//型号
			//}else{
				$d['brand'] = '';//品牌
				$d['model'] = $arr3['Model']['val'];//型号
			//}
			$d['jiaoju'] = $arr3['FocalLength']['val'];//属性：焦距
			$d['guangquan'] = $arr3['ApertureValue']['val'];//属性：光圈
			$d['kuaimen'] = $arr3['ShutterSpeedValue']['val'];//属性：快门
			$d['iso'] = $arr3['ISO Speed Ratings']['val'];//属性：ISO
			$d['baoguang'] = $arr3['ExposureTime']['val'];//属性：曝光
			$d['taketime'] = $arr3['DateTimeOriginal']['val'];//属性：拍照时间
			$d['jingtou'] = '';//属性：镜头
			
			$extarr = explode('.',$arr[0]);
			$d['name'] = str_replace('.'.$extarr[count($extarr)-1],'',$arr[0]);
			$d['imgkey'] = $imgkey;
			$d['size'] = $arr[2];
			if($d['size']*1>20*1024*1024){
				require_once('./static/qiniu/jssdk/demo/src/qiniu6_1/pfop.php');
				require_once('./static/qiniu/jssdk/demo/src/qiniu6_1/http.php');
				require_once('./static/qiniu/jssdk/demo/src/qiniu6_1/auth_digest.php');
				# @gist set_keys
				$accessKey = qiniu_ak();//'ixJ6FoVYJvSK3eO8IXsKkTZiWMGGxRISMuQDeCgx';
				$secretKey = qiniu_sk();//'W45phNfCsu-3zVZO2qf1mJZbRT_0McSQYoXTyFHB';
				Qiniu_setKeys($accessKey, $secretKey);
				
				$client = new Qiniu_MacHttpClient(null);
				$pfop = new Qiniu_Pfop();
				
				$pfop->Bucket = 'images';
				$pfop->Key = $imgkey;
				$savedKey = 'thumb_'.$imgkey;

				$fops = "imageView2/2/w/1200/h/1200";
				$saveas_Key = base64_encode("$pfop->Bucket:$savedKey");
				$fops = $fops.'|saveas/'.$saveas_Key;
				$pfop->Fops = $fops;
				
				list($ret,$err) = $pfop->MakeRequest($client);
				if($err!==null){
					//  err nodo
				}else{
					$d['thumbkey'] = $savedKey;
				}
			}
			$d['imgext'] = $extarr[count($extarr)-1];
			$d['status'] = 0;
			$d['haspass'] = -2;
			$d['ctime'] = now();

			$d['banquan'] = 1;
			//活动添加图片，直接为：版权保护\审核通过\上架 add by Loong 20170504
			if(I('get.event')){
				$d['haspass'] = 1;
				$d['status'] = 1;
			}

			$id = D('a_product')->add($d);
			$d2['productid'] = $id;
			D('a_product_number')->add($d2);
			$d3['productid'] = $id;
			$d3['stat'] = $json1;
			$d3['info'] = $json2;
			$d3['exif'] = $json3;
			$d3['ctime'] = now();
			D('a_product_api')->add($d3);

			$succId[] = $id;
		}

		//添加默认通过的作品需要更新用户表字段冗余
		if(I('get.event')){
			load('@.bd_update_user_data');
			updateCountAndLikesByUserId($d['uid']);
		}

		echo json_encode($succId);
	}

	//获取待编辑的产品数据
	function get_edit_data(){
		if(! session('uid')){
			return;
		}
        $w['isuse'] = 1;
        $data['class'] = D('a_class')->field('id,classname')->where($w)->select();
        $data['color'] = D('a_color')->field('id,colorname,colorvalue')->where($w)->select();
        $w['uid'] = session('uid');
        // $data['tags'] = D('a_tags')->where($w)->select();
        $data['folder'] = D('a_folder')->field('id,foldername')->where('isuse=1 and uid='.session('uid'))->order('id desc')->select();

        $this->ajaxReturn($data);
    }

    //标记删除商品
    function remove_product(){
    	if(!session('uid') || !I("get.id")){
			return;
		}

		$product_id = I("get.id");

		$where = "uid=".session('uid')." and id=".$product_id;
		$d['isuse'] = 0;
		D('a_product')->where($where)->save($d);

		//同时更新用户表冗余
		load('@.bd_update_user_data');
		updateCountAndLikesByUserId(session('uid'));
    }

    // 获取待处理（提交）的产品数据
    function get_todeal_products(){
    	if(!session('uid')){
			return;
		}

		$qiniu_domain = qiniu_domain();
		$img_url_pofix = '-slist?_=';

        $data['imgs'] = D('a_product')->where('haspass=-2 and isuse=1 and uid='.session('uid'))->order('id desc')->field(array('id','name','imgkey','concat(\''.$qiniu_domain.'\',imgkey,\''.$img_url_pofix.'\')'=>'imgurl'))->select();
        $this->ajaxReturn($data);
    }

    //保存编辑的商品信息
    function productionSave(){    	
        $imagesArr = json_decode(I('post.images'));
        if(count($imagesArr)==0){
            $data['msg'] = '未选择任何图片';
            $data['flag'] = 0;
            $this->ajaxReturn($data);
        }

        if(!session('uid')){
            $data['msg'] = '用户未登录';
            $data['flag'] = 0;
            $this->ajaxReturn($data);
        }

        //处理标签
        $tags = I("post.taglist");
        $arr = explode(" ",$tags);
        $tag_str = '';
        $outs = false;
        $out = '\n\r以下文字在标签栏位不允许使用：';
        for($i=0;$i<count($arr);$i++){
            if($arr[$i]!=''){
                $r = D('a_tags')->field('id,isuse')->where('tagname=\''.$arr[$i].'\'')->find();
                if($r){
                    if($r['isuse']) $tag_str .= ','.$r['id'];
                    else{
                        $outs = true;
                        $out .= $arr[$i].',';
                    }
                }else{
                    $tag_d['tagname'] = $arr[$i];
                    $tag_d['isuse'] = 1;
                    $tag_d['ctime'] = now();
                    $id = D('a_tags')->add($tag_d);
                    $tag_str .= ','.$id;
                }
            }
        }
        $tag_str .= ',';
        $d['tagids'] = $tag_str;
        $d['taglist'] = I("post.taglist");
        $d['name'] = I("post.name");

        $class = explode(',',I('post.classid'));
        $d['classids'] = ','.$class[0].',';
        $d['classlist'] = $class[1];
        $d['haspass'] = 1;//默认保存的都不用审核(新的保存都属于版权保护)
        $d['status'] = 1;
        $d['isuse'] = 1;
        $d['colorids'] = I('post.colorids');
        $d['colorlist'] = I('post.colorlist');
        $d['folderid'] = I('post.folderid');
        $d['desc'] = I('post.desc');

        
        $w = 'uid='.session('uid').' and id in(';
        $w .= implode(',', $imagesArr).')';

        D('a_product')->where($w)->save($d);

        //同时更新用户表冗余		
		load('@.bd_update_user_data');
		updateCountAndLikesByUserId(session('uid'));
        
        $data['msg'] = '保存成功';
        $data['flag'] = 1;
        $this->ajaxReturn($data);
    }
}