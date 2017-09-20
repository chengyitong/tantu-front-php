<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">

<head>
    <!-- <meta name="viewport" id="WebViewport" content="width=1200px,initial-scale=.2,target-densitydpi=device-dpi,minimum-scale=.2,maximum-scale=1,user-scalable=1" /> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="keywords" content="免费图片,摄影图片,高清图库,图片网站<?php if($seo["keywords"] != null): ?>,<?php echo ($seo["keywords"]); endif; ?>">
    <meta name="description" content="探图网是一个以图会友的原创图片网站,创建于2017年,以免费提供最新最全最专业的原创摄影图片网站而闻名,拥有行业领先的海量高清图库,永久免费图片下载。<?php if($seo["description"] != null): echo ($seo["description"]); endif; ?>">
    <title>高清图库_正版摄影图片_免费提供优质原创图片下载的网站_探图官网<?php if($seo["title"] != null): ?>_<?php echo ($seo["title"]); endif; ?></title>
    <link rel="icon" href="/static/images/logo.gif">
    <link rel="stylesheet" href="/static/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/static/css/new.css?v=201709181023">
    <link rel="stylesheet" type="text/css" href="/static/css/style.css?v=201709181023">
    <link rel="stylesheet" type="text/css" href="/static/css/jquery.flex-images.css">
    <script type="text/javascript" src="/static/js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="/static/js/jquery.flex-images.min.js"></script>
    <script src="/static/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/static/js/jquery.lazyload.min.js"></script>
    <script src="/static/js/global.js"></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- Custom styles for this template -->
    <link href="/static/css/carousel.css" rel="stylesheet">
    <script type="text/javascript">
      var gotype = '&free=1';
      function chosemenu(id){
        var _text = "全部";
        switch(id){
          case 0:
            _text = "全部";//全部 仅包括 图片搜索
          break;
           case 1:
            _text = "免费图片";
          break;
           case 2:
            _text = "版权图片";
          break;
           case 3:
            _text = "摄影师";
          break;
        }

        $('#common_search_btn_text').text(_text);   
      }

      function onkey(val){
        if(window.event.keyCode==13) {
          dosearch(val);
        }
      }

      function dosearch(val){
          var _selectText = $('#common_search_btn_text').text();
          var _val = escape(global.trim(val));
          var _url = '/index/search?kw='+_val;

          if('摄影师'==_selectText){
              _url = '/index/searchcamerist?kw='+_val;
          }
          else if('免费图片'==_selectText){
              _url +="&free=1";
          }
          else if('版权图片'==_selectText){
              _url +="&bq=1";
          }
          window.location.href = _url;
      }

    </script>
</head>

<body>
    <input type="hidden" value="<?php echo ($data["user"]["id"]); ?>" id="session" />
    <div class="navbar-wrapper nav-wrap-bg">
        <div class="container" style="width: 100%;">
            <div class="header">
                <div class="logo float">
                    <a href="/"><img src="/static/images/logo.png"></a>
                </div>
                <ul class="header-nav float head_search">
                    <li class="float head_index"><a href="/">首页</a></li>
                    <li class="float head_free dropdown">
                        <span class="cursor" class="dropdown-toggle" data-toggle="dropdown">图片<b class="caret"></b></span>
                         <ul class="dropdown-menu" role="menu">
                            <li><a href="/index/search?bq=1">最新图片</a></li>
                            <li><a href="/index/search?free=1">热门图片</a></li>                            
                        </ul>
                    </li>
                    <li class="float head_free"><a href="/index/searchcamerist">摄影师</a></li>
                    <li class="float head_free"><a href="/event/index">活动赛事</a></li>
                    <li class="float">
                        <div class="input-group" style="color:white;border:1px solid white;padding:0;height: 28px;background-color:transparent;position:relative; margin-top: -2px;border-radius: 4px;" onKeyDown="onkey()">
                          <!-- <div class="input-group-btn"> -->
                            <button type="button" class="btn btn-default dropdown-toggle" style="border: 0;background-color: transparent;color: white;" data-toggle="dropdown">
                              <span id="common_search_btn_text">全部</span>
                              <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                              <li><a href="#" onclick="chosemenu(0)">全部</a></li>
                              <li><a href="#" onclick="chosemenu(1)">免费图片</a></li> 
                              <li><a href="#" onclick="chosemenu(2)">版权图片</a></li>         
                              <li><a href="#" onclick="chosemenu(3)">摄影师</a></li>                                    
                            </ul>
                          <!-- </div> -->
                          <input class="header-input" onkeypress="onkey(this.value)" onkeydown="onkey(this.value)" id="searchcon2" placeholder="请输入关键词" type="text" style="width: 190px;" /> 
                          <img src="/static/images/searchs_main.png" style="width:20px;position:relative;right:6px;cursor: pointer;" onClick="javascript:dosearch($('#searchcon2').val());"/>      
                        </div>
                    </li>
                </ul>
                <div class="floatright" style="margin-top:5px;position:relative;line-height: 30px;padding-bottom: 5px;">
                    <?php if($data['user']==0): ?>
                    <div style="margin-top:5px;"><span class="cursor" style="margin-right: 10px;" data-toggle="modal" data-target="#myModal">免费注册</span><span class="cursor" id="headlogin" data-toggle="modal" data-target="#myModallogin">登录</span></div>
                    <?php else: ?>
                    <span class="cursor" onClick="location='/user/homepage';">
              <?php if($data['user']['avatar']==''){if($data['user']['avatarimg']==''){ ?><img src="/static/images/tx1.png" width="40" style="border-radius:20px;" /><?php }else{ ?><img src="<?php echo ($data["user"]["avatarimg"]); ?>" width="40" style="border-radius:20px;" /><?php }}else{ ?><img src="/static/uploadfiles/images/<?php echo ($data["user"]["avatar"]); ?>" width="40" style="border-radius:20px;" /><?php } ?>
              <?php if($message['noread_count']>0){ ?><div style="position:absolute;top:2px;left:35px;background:#F03;width:8px;height:8px;border-radius:4px;"></div><?php } ?>
            </span>
            <div class="category-all">
                <span class="cursor" class="dropdown-toggle dropdown" data-toggle="dropdown">&nbsp;<?php echo ($data["user"]["nickname"]); ?></span>                        
                <ul class="dropdown-menu" role="menu">
                    <li><a href="/camerist/gallery?uid=<?php echo ($data["user"]["id"]); ?>">个人空间</a></li>
                    <li><a href="/user/homepage_pending">上传作品</a></li>
                    <li><a href="/user/homepage_pending">我的作品</a></li>
                    <li><a href="/user/album">我的专辑</a></li>
                    <li><a href="/user/collection">我的收藏</a></li>
                    <li onClick="location='/user/message';" style="margin-left: 20px;color:#333;cursor:pointer;<?php if($message['noread_count']>0) echo 'list-style-image: url(/static/images/dot.png);'; ?>">消息
                        <?php if($message['noread_count']!='') echo ' ['.$message['noread_count'].']'; ?>
                    </li>
                    <li><a href="/user/my">资料设置</a></li>
                    <li><a class="cursor" onClick="if(confirm('确定退出登录吗？')){location='/index/logout';}">退出</a></li>
                </ul>                        
            </div>
            <button class="btn btn-sm btn-danger" style="background-color: transparent; border: white solid 1px; width: 65px; height: 30px;padding: 0; font-size: 14px; margin-left: 30px;" onClick="location='/user/homepage_pending'">发布</button>
            <?php endif; ?>
                </div>
            </div>
        </div>
    </div>


<style>
#fmsel2{
	max-height: 500px;
	overflow: auto;
}
.selpics_div{
	float: left;
	border: 1px solid #EEE;
	margin: 0 5px 5px 0;
	width: 264px;
	height: 40px;
	cursor: pointer;
}
.selpics_div:hover{
	border: 1px solid #333;
	background: #EEE;
}
.selpics_div img{
	max-height: 100px;
	max-width: 100px;
}
.pcon1wh{
	background-position:center;background-size:cover;
}
</style>
<script>
var doing = false;
function toupload2(){
	if(doing || $('#userfile2').val()=='') return false;
	doing = true;
	$('#fengmianModal2').modal('hide');
	loading.show(0,'图片上传中,请稍候...');
	$('#form_2').submit();
}
function selpic2(){
	if(doing) return false;
	$('#fengmianModal2').modal('show');
	$('#fmsel1').show();
	$('#fmsel2').hide();
	//$('#userfile2').val('').click();
}
function selpic2_back(){
	$('#fmsel1').show();
	$('#fmsel2').hide();
}
function selpic2_do1(){
	if(doing) return false;
	$('#fmsel1').hide();
	$('#fmsel2').show().html('Loading...');
	var str = '';
	$.getJSON('/user/getpics?callback=?',function(json){
		if(!json.list){
			$('#fmsel2').show().html('您没有作品！');
			return;
		}
		$.each(json.list, function(index, data){
			str += '<div style="background:url('+data.imgurl+');background-position:center;" class="selpics_div" onclick="selpic2_do1_chk(\''+data.imgkey+'\')"></div>';
		});
		if(str!='') $('#fmsel2').show().html('<div style="text-align:left;margin-bottom:5px;"><input type="button" class="btn btn-default" onclick="selpic2_back()" value="返回" /></div>'+str+'<div style="clear:both;"></div>');
		else $('#fmsel2').show().html('您没有作品！');
	});
}
function selpic2_do1_chk(key){
	$('#fengmianModal2').modal('hide');
	loading.show(0,'图片设置中,请稍候...');
	$.get('/user/setpics?key='+key,function(data){
		loading.hide();
		doing = false;
		$('.pcon1wh').css({"background-image":"url("+data+")"});
	});
}
function selpic2_do2(){
	if(doing) return false;
	$('#userfile2').val('').click();
}
function api_avatar_err2(info){
	alert(info);
	doing = false;
	loading.hide();
}
function api_avatar_ok2(name){
	loading.hide();
	doing = false;
	$('.pcon1wh').css({"background-image":"url(/static/uploadfiles/albumimg/"+name+")"});
}
</script>
<div class="h50"></div>
<div class="container-fluid conpersoninit text-center">
  <div class="pcon1wh" <?php if($data['user']['usertopimg']){ $usertopimg = explode('_',$data['user']['usertopimg']); if(count($usertopimg)>1) echo 'style="background-image:url('.imgurl_t3($data['user']['usertopimg'],0).');"'; else echo 'style="background-image:url(/static/uploadfiles/albumimg/'.$data['user']['usertopimg'].');"'; }else if($data['banner']['imgname']) echo 'style="background-image:url(/static/uploadfiles/sys/'.$data['banner']['imgname'].');"'; ?>></div>
  
  <iframe name="ifrm2" src="/user/waits" style="position:absolute;top:-100px;left:-100px;width:0px;height:0px;" frameborder="0"></iframe>
  <form action="/user/abanner_up?uid=<?php echo I('get.uid');?>" target="ifrm2" method="post" id="form_2" enctype="multipart/form-data">
  <div class="floatright" style="padding: 10px;">
  	<div><button class="btn btn-default" type="button" onClick="selpic2()">设置封面</button>&nbsp;<span style="font-size:.8em;">（建议上传图片尺寸1920*260，文件大小不超过20M）</span><input type="file" id="userfile2" name="userfile2" size="20" style="position: absolute;left: -100px;top: -100px;width: 0;height: 0;" onChange="toupload2();" /></div>
  </div>
  </form>
  
  <div class="pcon1icon"><?php if($data['user']['avatar']==''){if($data['user']['avatarimg']==''){ ?><img style="background:#fff;" class="img-circle" src="/static/images/tx.png" /><?php }else{ ?><img style="background:#fff;" src="<?php echo ($data["user"]["avatarimg"]); ?>" width="40" class="img-circle" /><?php }}else{ ?><img src="/static/uploadfiles/images/<?php echo ($data["user"]["avatar"]); ?>" class="img-circle" /><?php } ?> </div>
  <div class="pa--20">
    <h5><?php echo ($data["user"]["nickname"]); ?></h5>
  </div>
</div>
<div class="container-fluid conpersoninit bg1">
  <div class="pcon2">
    <ul>
      <a href="/user/homepage" ><li <?php if($data['headmenu'] == 'homepage') echo 'class="select"'; ?>>我的首页</li></a>
      <a href="/user/homepage_pending" ><li <?php if($data['headmenu'] == 'homepage_pending') echo 'class="select"'; ?>>作品管理</li></a>
      <a href="/user/album" ><li <?php if($data['headmenu'] == 'album') echo 'class="select"'; ?>>专辑管理</li></a>
      <a href="/user/collection" ><li <?php if($data['headmenu'] == 'collection') echo 'class="select"'; ?>>收藏</li></a>
      <a href="/user/message" ><li style="position:relative;" <?php if($data['headmenu'] == 'message') echo 'class="select"'; ?>>消息<?php if($message['noread_count']>0){ ?><div style="position:absolute;top:22px;right: 10px;background:#F03;width:8px;height:8px;border-radius:4px;"></div><?php } ?></li></a>
      <a href="/user/my" ><li <?php if($data['headmenu'] == 'ziliao') echo 'class="select"'; ?>>资料设置</li></a>
      <a href="/camerist/gallery?uid=<?php echo ($data["user"]["id"]); ?>" target="_blank" ><li style="margin-left: 50px;" <?php if($data['headmenu'] == 'gallery') echo 'class="select"'; ?>>我的空间</li></a>
    </ul>
  </div>
</div>
<div class="container1200 center bg2  " <?php if($data['headmenu'] != 'ziliao') echo 'style="display:none;"'; ?>>
  <div class="pcon3">
    <ul>
      	<a href="/user/my" ><li <?php if($data['menu'] == 'my') echo 'class="select1"'; ?>>基本资料</li></a>
        <!--<a href="/user/account" ><li <?php if($data['menu'] == 'account') echo 'class="select1"'; ?>>账户资料</li></a>-->
        <a href="/user/authent" ><li <?php if($data['menu'] == 'authent') echo 'class="select1"'; ?>>认证信息</li></a>
        <!--<a href="/user/binding" ><li <?php if($data['menu'] == 'binding') echo 'class="select1"'; ?>>手机绑定</li></a>-->
        <a href="/user/modify" ><li <?php if($data['menu'] == 'modify') echo 'class="select1"'; ?>>修改密码</li></a>
    </ul>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="fengmianModal2" tabindex="-1" role="dialog" aria-labelledby="fengmianModalLabel2" aria-hidden="true">
  <div class="modal-dialog" style="max-width:882px; width:882px;margin-top:185px;">
    <div class="modal-content" style=" width:100%; height:100%">
      <div class="modal-header" style="border:0px">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
      </div>
      <div id="threg">
          <div class="modal-body">
            <div class="mod2" style="height:auto;width:850px;">
              <div class="center mod2151" > <img src="/static/images/logocol.png" width="150px" > </div>
              
              <div class="mod21" style="height: auto;">
                <h5>设置封面</h5>
                <div id="fmsel1">
	              <div style="margin-bottom: 20px;">
	              	<div  class="bgc form-control" style="margin: auto;width: 30%;text-align:center;cursor:pointer;" onClick="selpic2_do1()"> 选择专辑中图片 </div>
	              	</div>
	              <div style="margin-bottom: 10px;">
	              	<div  class="bgc form-control" style="margin: auto;width: 30%;text-align:center;cursor:pointer;" onClick="selpic2_do2()"> 上传一张封面图 </div>
	              	</div>
	             </div>
                <div id="fmsel2" style="display: none;">
	              	
	             </div>
              </div>
              
            </div>
          </div>
      </div>
    </div>
  </div>
</div>

<div class="container1200 center padding_b"> 
  <div class="pcon42 left">
    <div class="hpage2" style="border-bottom: solid 1px #cfcfcf">
	    <a href="/user/homepage_pending"><div class="hpage2txt <?php if($data['w']=='-2') echo 'hpselect'; ?>"><div class="hpage2txt2"> <p>待处理作品</p><p><?php echo ($data["num1"]); ?></p></div></div></a>
	    <a href="/user/homepage_pending?status=0"><div class="hpage2txt <?php if($data['w']=='0') echo 'hpselect'; ?>"><div class="hpage2txt2"><p>待审核作品</p><p><?php echo ($data["num2"]); ?></p></div></div></a>
	    <a href="/user/homepage_pending?status=-1"><div class="hpage2txt <?php if($data['w']=='-1') echo 'hpselect'; ?>"><div class="hpage2txt2"><p>未通过作品</p><p><?php echo ($data["num3"]); ?></p></div></div></a>
	    <a href="/user/homepage_pending?status=1"><div class="hpage2txt <?php if($data['w']=='1') echo 'hpselect'; ?>"><div class="hpage2txt2"><p>已发布作品</p><p><?php echo ($data["num4"]); ?></p></div></div></a>
	    <div class="clearfix"></div>
    </div>

	<!-- <div style="margin: 0 auto;"> -->
		<style type="text/css">
.upload-container {
    float: left;
    width: 665px;
    border-right: solid 2px #cfcfcf;
    padding-bottom: 20px;
    min-height: 645px;
}

.upload-image {
    width: 188px;
    height: 188px;
    margin: 20px 10px 20px 15px;
    border: solid 1px #cfcfcf;
    display: inline-block;
}

.upload-image-selected {
    border-color: #269edc;
}

.upload-image-selected .checkbox-state {
    background-color: #269edc;
}

.upload-image>span {
    display: block;
    margin-top: 100px;
    width: 150px;
    text-align: center;
    overflow: hidden;
    height: 20px;
    white-space: nowrap;
    text-overflow: ellipsis;
}

.upload-loading {
    color: white;
    margin-top: 60px;
    height: 30px;
    width: 100%;
    background-color: rgba(0, 0, 0, 0.4);
    /* For IE 8*/
    -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#66000000, endColorstr=#66000000)";
}

.upload-loading>span {
    margin: 8px;
    width: 180px;
    display: block;
    position: absolute;
    text-align: center;
}

.upload-loading-percentage {
    background-color: #269edc;
    height: 100%;
    width: 0;
    color: white;
}

.checkbox-state {
    background-color: white;
    width: 32px;
    height: 30px;
    display: inline-block;
    float: left;
}

.remove-img {
    width: 32px;
    height: 30px;
    float: right;
    cursor: pointer;
}

.img-operate {
    display: none;
}

.edit_container {
    float: left;
    width: 295px;
    margin: 20px 5px;
}

.edit-row-content {
    width: 210px;
    border-radius: 4px;
    border: 1px solid #bec5cf;
    height: 31px;
}
</style>
<form style="display: inline-block;">
<div id="upload_container" class="upload-container">
    <input type="hidden" id="domain" value="<?php echo qiniu_domain();?>">
    <input type="hidden" id="uptoken_url" value="/static/qiniu/jssdk/demo/upload_token_6_1.php">
    <div style="margin: 20px auto 5px 15px;">
        <input type="checkbox" id="chk_select_all" />
        <label for="chk_select_all">本页全选</label>
        <span id="lbl_select_count" style="margin-left:5px;color: grey;font-size: 12px;">已选择0张</span></div>
    <div class="upload-image" style="cursor: pointer;" id="pickfiles">
        <img style="display: block;margin: 58px 60px auto auto;" src="/static/images/select_images.jpg" />
    </div>
</div>
<div class="edit_container">
    <div class="float-left" style="max-width:600px; min-width:295px; width:100%;">
        <div>
            <input id="btnSaveImages" class="btn bgc" type="button" style="width:300px; height:42px; border-radius:4px;margin: 10px 0 10px 5px;" value="发布图片" />
        </div>
        <div style="margin:10px auto">
            <input type="hidden" value="1" name="status" />
            <div class="float text-right" style="width:80px;">
                <font color="#FF0000">*</font>属性：</div>
            <div style="height: 20px;position:relative;top: -5px;">
                <input style="position: relative;" type="radio" id="banquan1" value="1" name="banquan" checked="checked" />
                <label for="banquan1" style="position: relative;">版权保护</label><img src="/static/images/p3.png" style="margin-left:7px" title="版权保护作品&#10;只作平台展示&#10;交流作用。" />
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
        <div style="margin:10px auto ;padding-left: 37px;">
            <a href="/index/content?id=470" target="_blank" style="color:#09F">图片标题标签撰写参考标准</a>
        </div>
        <div style="margin:10px auto ; height:31px">
            <div class="float text-right" style="width:80px;padding-top:7px;">标题：</div>
            <div>
                <input type="text" name="name" id="name" class="default edit-row-content" value="" style="margin-top: 0px; height:31px" />
            </div>
            <div class="clearfix"></div>
        </div>
        <div style="margin:10px auto">
            <div class="float text-right" style="width:80px;padding-top:7px">分类：</div>
            <div style="position:relative">
                <select type="text" class="default edit-row-content" name="classid" id="classid" onchange="mtype(this.value)" style="height:31px;">
                    <option value="0">请选择</option>
                </select>
            </div>
            <div class="float-left" style="display: none;">
                <div style="border:1px solid #c8d6e3; min-height:30px; width:310px">
                    <div style="padding:10px">
                        <div class="clearfix"></div>
                    </div>
                    <input type="hidden" id="typec_ids" name="classids" value="" />
                    <input type="hidden" id="typec_names" name="classlist" value="" />
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div style="margin:10px auto ; height:31px">
            <div class="float text-right" style="width:80px; padding-top:7px">关键词：</div>
            <div class="float-left">
                <!-- /input-group -->
                <div style="border: 1px solid #c3c3c3;width:310px;cursor:text;padding:5px 0 0 5px;border-radius: 4px;border: 1px solid #bec5cf;display:none;" onclick="$('#a').focus();">
                    <div style="float:left;overflow:hidden;width:30px;" id="da">
                        <input type="text" id="a" onkeydown="aa()" onkeyup="aa()" style="border:0;padding:0;border-radius:0;height:auto;outline:none;-webkit-appearance: none;width:100%;" />
                    </div>
                    <div style="clear:both;"></div>
                </div>
                <div id="taglist" class="default" style="padding: 10px 10px 5px;word-wrap: break-word;width: 310px;min-height:80px;border-radius: 4px;border: 1px solid #92b9f8;display:none;">
                    <div class="clearboth"></div>
                </div>
                <textarea name="taglist" id="ipt_taglist" class="edit-row-content" style="height:80px;cursor:text;padding:5px 0 0 5px;"></textarea>
                <br /><span style="font-size:11px;">（关键词之间用空格分开）</span>
                <div style="min-height:30px; width:200px;display: none;margin-top: 5px;" id="kwsel">
                    <div style="padding:10px">
                        <h5 class="float-left" align="left" style="margin-top:0;">关键词推荐</h5>
                        <h5 class="float-right" style="margin-top:0;color: blue;cursor: pointer;" onclick="mtype($('#classid').val())">换一批</h5>
                        <div class="clearfix"></div>
                        <div id="tags">
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
        <div style="margin:10px auto">
            <div class="float text-right" style="width:80px;padding-top:7px">颜色：</div>
            <div class="float-left">
                <div style="min-height:30px; width:310px">
                    <div style="padding:0 10px" id="color_list_container">
                        <div class="clearfix"></div>
                    </div>
                    <input type="hidden" id="color_ids" name="colorids" value="" />
                    <input type="hidden" id="color_names" name="colorlist" value="" />
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div style="margin:10px auto 0;">
            <div class="float text-right" style="width:80px; padding-top:7px">专辑：</div>
            <div style="position:relative">
                <select onchange="mnew(this.value)" type="text" class="default edit-row-content" name="folderid" id="folderid">
                    <option value="new" style="color:#999;display: none;">新建专辑</option>
                </select>
                <div style="padding: 8px;" align="right">[<span style="cursor: pointer;" data-toggle="modal" data-target="#myalbum">新建专辑</span>]</div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div style="margin:10px auto ;">
            <div class="float text-right" style="width:80px; padding-top:7px">作品简介：</div>
            <div>
                <textarea class="default edit-row-content" name="desc" id="desc" style="min-height:80px;"></textarea>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
  </form>
<br />
<!--Modal-->
<div class="modal fade" id="myalbum" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog mod1">
        <div class="modal-content modwh100">
            <div class="modal-header modborder0" style="background-color:#eceff1">
                <button type="button" class="close" onclick="closeMyalbum()"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">创建专辑</h4>
            </div>
            <div class="modal-body" style="overflow: hidden;">
                <div class="mod2">
                    <div class="clearfix almod22a">
                        <div style="margin:0 auto;width:300px;">
                            <label for="mobile" class="control-label">
                                <font color="red">*</font>专辑名:</label>
                            <div>
                                <input class="form-control" id="foldername" type="text">
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="">
                            <div class="clearfix"></div>
                        </div>
                        <div class="" style="margin: 40px 100px;">
                            <div>
                                <div class="alsumitbgc form-control" style="text-align:center;cursor:pointer;width:122px;margin:0 auto;" onclick="newf2()"> 确 定 </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/static/qiniu/jssdk/demo/scripts/plupload/moxie.js"></script>
<script type="text/javascript" src="/static/qiniu/jssdk/demo/scripts/plupload/plupload.dev.js"></script>
<script type="text/javascript" src="/static/qiniu/jssdk/demo/scripts/plupload/i18n/zh_CN.js"></script>
<script type="text/javascript" src="/static/qiniu/jssdk/demo/scripts/ui.js?rad=<?php echo time();?>"></script>
<script type="text/javascript" src="/static/qiniu/jssdk/demo/scripts/qiniu.js"></script>
<script type="text/javascript" src="/static/qiniu/jssdk/demo/scripts/highlight.js"></script>
<script type="text/javascript" src="/static/qiniu/jssdk/demo/scripts/upload_config.js?rad=<?php echo time();?>"></script>
<script type="text/javascript">
hljs.initHighlightingOnLoad();

var edit_select_images = new Array();
var m_selected_class_name = "upload-image-selected";
var edit_prop_data;

$(function() {
    //check-all
    $('#chk_select_all').change(function() {
        if ($(this).prop('checked') == true) {
            doAllUploadImagesSelected();
        } else {
            edit_select_images = new Array();
            $.each($("." + m_selected_class_name), function(i, item) {
                $(item).removeClass(m_selected_class_name);
            });
        }
        updateSelectTips();
    });

    //colors
    $('.color').click(function() {
        if ($(this).hasClass('color_chose')) {
            $(this).removeClass('color_chose');
            $(this).html('');
        } else {
            $(this).addClass('color_chose');
            $(this).html('<i class="glyphicon glyphicon-ok"></i>');

        }
        var ids = ',',
            names = '';
        $('.color_chose').each(function() {
            ids += $(this).data('id') + ',';
            names += $(this).attr('data-name') + ' ';

        });
        $('#color_ids').val(ids);
        $('#color_names').val(names);
    });

    //get init datas
    if (!edit_prop_data) {
        $.getJSON('/product/get_edit_data', function(data) {
            edit_prop_data = data;
            if (edit_prop_data.class.length > 0) {
                var _html = "";
                $.each(edit_prop_data.class, function(i, item) {
                    _html += '<option value="' + item.id + ',' + item.classname + '">' + item.classname + ' </option>';
                });
                $("#classid").append(_html);
            }

            if (edit_prop_data.color.length > 0) {
                var _html = "";
                $.each(edit_prop_data.color, function(i, item) {
                    _html += '<div class="seacon1col color" data-id="' + item.id + '" data-name="' + item.colorname + '" style="cursor: pointer;background-color:' + item.colorvalue + ';" onClick="onColorClick(this)"></div>';
                });
                $("#color_list_container").prepend(_html);
            }

            if (edit_prop_data.color.length > 0) {
                var _html = "";
                $.each(edit_prop_data.folder, function(i, item) {
                    _html += '<option value="' + item.id + '">' + item.foldername + '</option>';
                });
                $("#folderid").prepend(_html);
            }
        });
    }

    //get to deal products
    $.getJSON('/product/get_todeal_products', function(data) {
        if(data.imgs) {
          $.each(data.imgs, function(index, item) {
            appendProductImage(item.imgurl, item.imgkey, item.name, item.id);
          });
        }        
    });


    mtype($('#classid').val());
    $(".close").click(function() {
        $('#folderid').removeAttr("data-toggle", "data-target");
    });
    $("#myalbum").click(function() {
        $('#folderid').removeAttr("data-toggle", "data-target");
    });
});

function onColorClick(e) {
    var _this = e;
    if ($(_this).hasClass('color_chose')) {
        $(_this).removeClass('color_chose');
        $(_this).html('');
    } else {
        $(_this).addClass('color_chose');
        $(_this).html('<i class="glyphicon glyphicon-ok"></i>');

    }
    var ids = ',',
        names = '';
    $('.color_chose').each(function() {
        ids += $(this).data('id') + ',';
        names += $(this).attr('data-name') + ' ';

    });
    $('#color_ids').val(ids);
    $('#color_names').val(names);
}

//change image check state
function onSelectImage(e) {
    var _id = $(e).children(":input[id^='product_img_']").val();
    if ('' == _id) {
        return;
    }

    _id = parseInt(_id);

    var _index = edit_select_images.indexOf(_id);
    if ($(e).hasClass(m_selected_class_name)) {
        $(e).removeClass(m_selected_class_name);

        if (_index > -1) {
            edit_select_images.splice(_index, 1);
        }
    } else {
        $(e).addClass(m_selected_class_name);
        if (_index == -1) {
            edit_select_images.push(_id);
        }
    }

    updateSelectTips();
}

//update tips text
function updateSelectTips() {
    $('#lbl_select_count').text('已选择' + edit_select_images.length + '张');
}

//关键词
var naa = false;

function aa() {
    if (event.keyCode == "32") {
        if (!naa) return;
        naa = false;
        var val = $.trim($('#a').val());
        if (val == '') return;
        goon = true;
        $('.tags').each(function() {
            if ($(this).html() == val) {
                alert('您输入的关键词已存在!');
                goon = false;
            }
        });
        if (goon) tag_js(val);
        $('#a').val('');
        $('#da').width(30);
    } else {
        naa = true;
        var w = $('#a').textWidth();
        $('#da').width(w + 30);
    }
}

function closeMyalbum() {
    $('#myalbum').modal('hide');
}

function isAllImagesSelected(){
  return (edit_select_images.length == $(":input[id^='product_img_']").filter(function() {return !!this.value;}).length);
}

function doAllUploadImagesSelected(){
  edit_select_images = new Array();
  $.each($(":input[id^='product_img_']"), function(i, item) {
      if ($(item).val() !== '') {
          edit_select_images.push(parseInt($(item).val()));
          $(item).parent().addClass(m_selected_class_name);
      }
  });
}

function mnew(val) {
    if (val == 'new') {
        $('#folderid').attr({
            "data-toggle": "modal",
            "data-target": "#myalbum"
        });
    } else {
        $('#folderid').removeAttr("data-toggle", "data-target");
    }
}

function mtype(val) {
    var str = val.split(",");
    var strs = '';
    $.getJSON('/user/get_tags?cid=' + str[0], function(json) {
        if (json.count > 0) {
            $.each(json.list, function(index, data) {
                strs += '<div class="float-left chosetag" onClick="chosetag(this)" style="padding:5px; margin:5px; background-color:#f9f9f9;cursor:default;border:#dddddd 1px solid;" data-id="' + data.id + '" data-name="' + data.tagname + '"><span style="font-weight:bold;color:#F60;">+</span>' + data.tagname + '</div>';

            });
            $('#tags').html(strs);
            $('#kwsel').show();
        } else $('#kwsel').hide();
    });

}

function chosetag(obj) {
    var tagname = $(obj).data('name');
    var tagid = $(obj).data('id');
    var goon = true;
    if (goon) tag_js(tagname);
    $('#a').focus();
}

function tag_js(tagname) {
    $('#da').before('<div class="tags" data-name="' + tagname + '" onclick="tagdel(this)">' + tagname + '</div>');
    tag_rejs(tagname);
}

function tag_rejs(tagname) {
    var str = $('#ipt_taglist').val();
    str += tagname + ' ';
    $('#ipt_taglist').val(str);
}

function tagdel(obj) {
    //if(confirm('确定移除此项?')){
    $(obj).remove();
    tag_rejs();
    //}
}

function newf2() {
    if ($('#foldername').val() == '') {
        alert('请输入专辑名!');
        return;
    }
    $.getJSON('/user/new_folder?fname=' + $('#foldername').val(), function(json) {
        alert('创建成功!');
        $('#folderid').prepend('<option value="' + json.id + '">' + json.foldername + '</option>');
        $('#foldername').val('');
        global.n_select_value('folderid', json.id);
        $('#folderid').removeAttr("data-toggle", "data-target");
        $(".close").click();
    });
}

</script>

	<!-- </div> -->
  </div>
</div>
<div class="footer clearfix">
    <div class="container row footer-inner">
        <div class="col-sm-6">
            <p class="foottitle">关于</p>
            <div class="about">
                <p style="line-height: 22px;">探图是一个以图会友的原创图片社区，致力于摄影作品的发现、分享、售卖，是世界各地优秀摄影师和平民艺术家的聚集地。</p>
            </div>
        </div>
        <div class="col-sm-2">
            <p class="foottitle">关于探图</p>
            <div class="about">
                <a href="/index/content?id=314" target="_blank">
                    <p>关于我们</p>
                </a>
                <a href="/index/content?id=315" target="_blank" style="display: none;">
                    <p>授权协议</p>
                </a>
                <a href="/index/content?id=325" target="_blank">
                    <p>服务条款</p>
                </a>
            </div>
        </div>
        <div class="col-sm-2">
            <p class="foottitle">联系我们</p>
            <div class="about">
                <p>020-37589885</p>
                <p><a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=2562199003&site=qq&menu=yes"><img src="/static/images/qq.jpg"></a></p>
            </div>
        </div>
        <div class="col-sm-2" style="text-align: center;">
            <img src="/static/images/qq_qrcode.jpg" width="80">
        </div>
    </div>
    <div class="copyright clearfix">
        Copyright © 2010-2017 探图版权所有 粤ICP备16107621号-1
    </div>
</div>
<!--<div class="nav_fixed">
  <div class="middle">
    <div class="feedback"> <a href="#" target="_blank"> <img src="/static/images/write_icon.png" width="28px" height="28px"> <i>
      <svg class="svg-icon icon-advice">
        <use xlink:href="#icon-advice"></use>
      </svg>
      </i> 
      </a> </div>
    <div class="favor"> <a href=""> <img src="/static/images/sc.png" style="position: relative;right: 2px;"></a> </div>
    <div class="history"> <a href=""> <img src="/static/images/time_icon.png" width="28px" height="28px"> <i>
      <svg class="svg-icon icon-history">
        <use xlink:href="#icon-history"></use>
      </svg>
      </i> 
      </a> </div>
  </div>
  <div class="bottom">
    <div class="con_weixin"> <img src="/static/images/wechat.png" height="28px" width="28px"> <i>
      <svg class="svg-icon icon-weixin">
        <use xlink:href="#icon-weixin"></use>
      </svg>
      </i> 
      <div class="inner"></div>
    </div>
    <div class="con_qq"> <img src="/static/images/qq_icon.png" width="28px" height="28px">  </div>
    <div class="go-top"> <img src="/static/images/up.png" width="28px" height="28px"> </div>
  </div>
</div>-->
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width:582px; width:582px;margin-top:185px;">
        <div class="modal-content" style=" width:100%; height:100%">
            <div class="modal-header" style="border:0px">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            </div>
            <div id="threg">
                <div class="modal-body">
                    <div class="mod2" style="height:auto;">
                        <div class="center mod2151"> <img src="/static/images/logocol.png" width="150px"> </div>
                        <div class="mod21">
                            <h5>用户注册</h5>
                        </div>
                        <div class="clearfix mod22a" style="height:auto;">
                            <div class="form-group">
                                <label for="regmobile" class="col-sm-4 control-label">邮箱/手机:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="regmobile" onKeyUp="chkmailmobile()" placeholder="请输入正确的邮箱或手机号" style="border-radius:0;background:#eaeaea;border:#c3c3c3;">
                                </div>
                            </div>
                            <div class="form-group" id="divcode" style="display:none;">
                                <label for="regpassword" class="col-sm-4 control-label">验证码:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" onkeydown="tocode();" id="code" style="border-radius:0;background:#eaeaea;border:#c3c3c3;">
                                </div>
                                <div style="position:absolute;top:120px;right:40px;">
                                    <input type="button" class="form-control bgc" id="sendsms" value="获取验证码" onclick="send()" style="border-radius:0;">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="regmobile" class="col-sm-4 control-label">用户名:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="username" placeholder="4~16个字符，字母/数字/中文/下划线" style="border-radius:0;background:#eaeaea;border:#c3c3c3;">
                                    <div style="position:absolute;right:-108px;top:8px">注册后不可修改</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="regpassword" class="col-sm-4 control-label">密码:</label>
                                <div class="col-sm-8">
                                    <input type="password" class="form-control" id="regpassword" placeholder="设置密码" style="border-radius:0;background:#eaeaea;border:#c3c3c3;">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="regpassword2" class="col-sm-4 control-label">确认密码:</label>
                                <div class="col-sm-8">
                                    <input type="password" class="form-control" onkeydown="toreg();" id="regpassword2" placeholder="再次输入密码" style="border-radius:0;background:#eaeaea;border:#c3c3c3;">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-4 col-sm-8">
                                    <div class="bgc form-control" style="text-align:center;cursor:pointer;" onclick="reging()"> 注 册 </div>
                                </div>
                            </div>
                            <div style="margin-left: 95px;text-align:center;padding-bottom:6px;border-bottom:1px solid #CCC;margin-bottom:10px;">已拥有帐号？去<span style="color:#f16172;" onclick="tologin()" class="cursor loging">登录</span></div>
                            <div class="floatright">一键登录：&nbsp;<a href="/static/qq.php"><img src="/static/images/qqlogin2.png" /></a></div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <h6 class="float">完成此注册，即表明您同意了我们的</h6>
                    <h6 class="float mod4col"><a href="/index/content?id=325" target="_blank">使用条款和隐私策略</a></h6>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModallogin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog mod1">
        <div class="modal-content modwh100" style="height:auto;">
            <div class="modal-header modborder0">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            </div>
            <div class="modal-body">
                <div class="mod2" style="height:auto;">
                    <!--<div class="center" style="font-size:20px;text-align:center;" > <span class="userreg fontcolor" style="cursor:pointer">会员登录</span>&nbsp;&nbsp;|&nbsp;&nbsp;<span class="telreg" style="cursor:pointer">手机快速登录</span> </div>-->
                    <div class="center mod2151"> <img src="/static/images/logocol.png" width="150px"> </div>
                    <div class="mod21">
                        <h5>用户登录</h5>
                    </div>
                    <div class="clearfix mod22a">
                        <div class="">
                            <label for="mobile" class="col-sm-4 control-label">帐号:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="mobile" placeholder="输入邮箱/手机/用户名" style="border-radius:0;background:#eaeaea;border:#c3c3c3;">
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="">
                            <label for="password" class="col-sm-4 control-label">密码:</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" id="password" onkeydown="tochk();" placeholder="输入密码" style="border-radius:0;background:#eaeaea;border:#c3c3c3;">
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="">
                            <div class="col-sm-offset-4 col-sm-8">
                                <div class="bgc form-control" style="text-align:center;cursor:pointer;" onclick="loging()"> 登 录 </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div style="margin-left:95px;">
                            <div class="checkbox " style="padding-left:36px;height: 30px;margin-bottom:0;">
                                <label>
                                    <input type="checkbox"> 下次自动登录
                                </label>
                                <div class="floatright" style="color:#09F;"><a href="/index/forget" target="_blank">忘记密码</a></div>
                            </div>
                            <div class="clearfix"></div>
                            <div style="text-align:center;padding-bottom:6px;border-bottom:1px solid #CCC;margin-bottom:10px;">还没有帐号？赶快去<span class="cursor reging" onclick="toregin()" style="color:#f16172;">注册</span></div>
                        </div>
                        <!--<div class="telreg_panel" style="margin-top:-15px;">
                  <div class="form-group">
                        <label for="regmobile" class="col-sm-4 control-label">手机号:</label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control" id="regtel" onkeyup="this.value=this.value.replace(/\D/g,'')" style="border-radius:0;background:#eaeaea;border:#c3c3c3;">
                        </div>
                    </div>
                  <div class="form-group">
                        <label for="regpassword" class="col-sm-4 control-label">验证码:</label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control" onkeydown="tocode();" id="code" style="border-radius:0;background:#eaeaea;border:#c3c3c3;">
                        </div>
                    </div>
                        <div style="position:absolute;top:96px;right:40px;">
                          <input type="button" class="form-control bgc" id="sendsms" value="获取验证码" onclick="send()" style="border-radius:0;">
                        </div>
                  <div class="form-group">
                    <div class="col-sm-offset-4 col-sm-8">
                        <div  class="bgc form-control" style="text-align:center;cursor:pointer;" onclick="smsreging()"> 登 录 </div>
                    </div>
                  </div>
                </div> -->
                        <div class="clearfix"></div>
                        <div class="floatright">一键登录：&nbsp;<a href="/static/qq.php"><img src="/static/images/qqlogin2.png" /></a></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer clearfix mod3">
                <h6 class="float">完成此注册，即表明您同意了我们的</h6>
                <h6 class="float mod3col"><a href="/index/content?id=325" target="_blank">使用条款和隐私策略</a></h6>
                <h6 class="float-right mod3col"></h6>
            </div>
        </div>
    </div>
</div>
<!--Modal-->
<div class="modal fade" id="colModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-height:364px; max-width:582px; width:582px; height:364px; margin-top:185px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><h5>收藏图片</h5></h4>
            </div>
            <div class="modal-body">
                <div id="flist" style="width:547px; height:200px;  border:1px solid #bbbbbb; position:relative">
                    <div style="height:150px;overflow:auto;">
                        <?php if(is_array($data["favclass"])): $i = 0; $__LIST__ = $data["favclass"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="afid">
                                <input id="fid<?php echo ($vo["id"]); ?>" type="radio" name="fid" value="<?php echo ($vo["id"]); ?>" />&nbsp;
                                <label for="fid<?php echo ($vo["id"]); ?>"><?php echo ($vo["foldername"]); ?></label>
                            </div><?php endforeach; endif; else: echo "" ;endif; ?>
                    </div>
                    <div style="position:absolute; height:50px; background-color:#f8f8f8; padding:10px 15px ;bottom:0px ; width:100%">
                        <input id="foldername" type="text" placeholder="请输入收藏文件夹名" style="line-height:29px; width:80%; border:1px solid #cccccc;border-radius: 3px;padding-left:5px;" />
                        <button style="text-align:center; line-height:29px; background-color:#000; color:#FFF; width:20%; position:absolute; right:10px;border-radius: 5px; " onClick="newf()">创建收藏夹</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary allbgc" onClick="fav()" style="border:0px">确定</button>
                <input id="picid" type="hidden" value="<?php echo ($data["r"]["id"]); ?>" />
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<input id="userid" type="hidden" value="<?php echo ($data["user"]["id"]); ?>" />
<!-- /.carousel <script type="text/javascript" src="http://qzonestyle.gtimg.cn/qzone/openapi/qc_loader.js" data-appid="APPID" data-redirecturi="http://ttwww.bindow.cn/" charset="utf-8"></script>-->
<style>
.control-label {
    color: #999999;
    text-align: right;
    line-height: 34px;
}

.col-sm-8 {
    margin-bottom: 10px;
    padding-left: 0;
    padding: 0 2px;
}

.col-sm-4 {
    padding-right: 4px;
}

.fontcolor {
    color: #f16172;
}

.telreg_panel {
    display: none
}

.afid {
    height: 36px;
    padding: 10px 15px;
}

.afid:hover {
    background: #f8f8f8;
}
</style>
<script>
function fav() { <
    php >
        if (!$fav): < /php>
    if (isNaN($('input[name="fid"]:checked').val())) {
        alert('请选择收藏夹');
        return;
    } <
    php > endif; < /php>
    location = '/index/tofav?id=' + $('#picid').val() + '&fcid=' + $('input[name="fid"]:checked').val();
}

function newf() {
    if ($('#foldername').val() == '') {
        alert('请输入收藏夹名!');
        return;
    }
    $.getJSON('/user/new_favclass?fname=' + $('#foldername').val(), function(json) {
        alert('创建成功!');
        $('#flist').prepend('<div class="afid"><input id="fid' + json.id + '" type="radio" name="fid" value="' + json.id + '" />&nbsp;<label for="fid' + json.id + '">' + json.foldername + '</label></div>');
        $('#foldername').val('');
    });
}
// for scroll bar appear;
$(window).trigger("resize");

$(function() {
    $('.userreg').click(function() {
        $('.telreg').removeClass('fontcolor');
        $('.userreg').addClass('fontcolor');
        $('.telreg_panel').hide();
        $('.userreg_panel').show();
    });
    $('.telreg').click(function() {
        $('.userreg').removeClass('fontcolor');
        $('.telreg').addClass('fontcolor');
        $('.userreg_panel').hide();
        $('.telreg_panel').show();
    });

    $('.carousel-caption').fadeIn(1000);
    $('.go-top').click(function() {
        $('html, body').animate({ scrollTop: 0 }, 'slow');
        return false;
    });
    $('#btn1').click(function() {
        location = '/index/search?kw=&' + $(this).attr('data-url');
    });

});


function loging() {
    var mobile = $('#mobile').val();
    var pass = $('#password').val();
    var remember = 1;

    if (!mobile) {
        alert('请输入用户名');
        return false;
    }
    if (!pass) {
        alert('请输入密码');
        return false;
    }
    if ($('#remember').is("checked")) {
        remember = 60;
    }
    $.get("/index/loging?m1=" + global.trim(mobile) + "&p1=" + global.trim(pass) + "&remember=" + global.trim(remember), function(data) {
        var arr = data.split(':');
        if (arr[0] == 1) location.reload();
        else alert(arr[1]);
    });
}

function showthreg() {
    $('#threg').show();
    $('#reg').hide();

}

function showreg() {
    $('#reg').show();
    $('#threg').hide();

}

function reging() {
    var mobile = $('#regmobile').val();
    var code = $('#code').val();
    var username = $('#username').val();
    var pass = $('#regpassword').val();
    var pass2 = $('#regpassword2').val();
    if (!isNaN(mobile) && mobile.length == 11) {
        if (code.length != 4 || isNaN(code)) {
            alert('请输入4位数字验证码');
            return false;
        }
    } else if (mobile.indexOf('@') < 0 || mobile.indexOf('.') < 0) {
        alert('请输入正确的邮箱');
        return false;
    }
    if (!username) {
        alert('请输入用户名');
        return false;
    }
    if (!pass) {
        alert('请输入密码');
        return false;
    }
    if (pass != pass2) {
        alert('两次输入密码不一致');
        return false;
    }

    $.get("/index/reging?m1=" + global.trim(mobile) + "&u1=" + global.trim(username) + "&p1=" + global.trim(pass) + '&code=' + global.trim(code), function(data) {
        var arr = data.split(':');
        alert(arr[1]);
        if (arr[0] == 1) location.reload();
    });
}

function tochk(e) {

    if (window.event.keyCode == 13) {

        loging();

    }

}

function toreg(e) {

    if (window.event.keyCode == 13) {

        reging();

    }

}

function tocode(e) {

    if (window.event.keyCode == 13) {

        smsreging();

    }

}

function tologin() {
    $('#myModal').modal('hide');
    $('#myModallogin').modal('show');
}

function toregin() {
    $('#myModallogin').modal('hide');
    $('#myModal').modal('show');
}

function chkmailmobile() {
    var val = $('#regmobile').val();
    if (!isNaN(val) && val.length == 11) {
        $('#divcode').show();
    } else {
        $('#divcode').hide();
    }
}

function send() {
    var tel = $('#regmobile').val();
    if (tel.length != 11 || isNaN(tel)) {
        alert('请输入正确的手机号码');
        return;
    }
    $.getJSON('/index/sms_vcode?mobile=' + global.trim(tel), function(data) {

        if (data.rstatus == 0) {
            alert(data.info);
        } else {
            var i = 60;
            $("#sendsms").attr("disabled", "disabled");

            var _time = setInterval(function() {
                i = i - 1;
                $("#sendsms").val("（" + i + "）秒").css("color", "#333");
                if (i == 0) {
                    $("#sendsms").removeAttr("disabled");
                    $("#sendsms").val("获取验证码").css("color", "#fff");
                    clearInterval(_time);
                }
            }, 1000);
        }
    });
}

function smsreging() {

    var code = $('#code').val();
    var tel = $('#regmobile').val();
    if (tel.length != 11 || isNaN(tel)) {
        alert('请输入正确的手机号码');
        return;
    }
    if (!code) {
        alert('请输入验证码');
        return false;
    }

    $.get("/index/reging_sms?m1=" + global.trim(tel) + "&code=" + global.trim(code), function(data) {
        var arr = data.split(':');
        if (arr[0] == 1) location.reload();
        else alert(arr[1]);
    });
}
</script>
<div style="display:none;">
    <script src="https://s95.cnzz.com/z_stat.php?id=1261057114&web_id=1261057114" language="JavaScript"></script>
</div>
</body>

</html>

<script type="text/javascript">
var upload_save_img_url = "/product/add_product";

$(function() {
    //defined button click from `upload_component`
    $('#btnSaveImages').click(function(){
        if (edit_select_images.length == 0) {
            alert('未选择任何图片');
            return;
        }

        $('#btnSaveImages').button('loading');
        $.post('/product/productionSave', {
                'images': JSON.stringify(edit_select_images),
                'name': $('#name').val(),
                'classid': $('#classid').val(),
                'taglist': $('#ipt_taglist').val(),
                'colorids': $('#color_ids').val(),
                'colorlist': $('#color_names').val(),
                'folderid': $('#folderid').val(),
                'desc': $('#desc').val()
            },
            function(res) {
              $('#btnSaveImages').button('reset');
              if(0==res.flag){
                alert(res.msg);
              }
              else{             
                  $(".upload-image.upload-image-selected").remove();
                  edit_select_images = new Array();
              }
            }, 'json');
    });	
});
</script>