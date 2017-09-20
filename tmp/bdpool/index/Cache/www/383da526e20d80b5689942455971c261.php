<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" id="WebViewport" content="width=device-width,initial-scale=.45,target-densitydpi=device-dpi,minimum-scale=.45,maximum-scale=.45,user-scalable=0" />
    <meta name="keywords" content="免费图片,摄影图片,高清图库,图片网站<?php if($seo["keywords"] != null): ?>,<?php echo ($seo["keywords"]); endif; ?>">
    <meta name="description" content="探图网是一个以图会友的原创图片网站,创建于2017年,以免费提供最新最全最专业的原创摄影图片网站而闻名,拥有行业领先的海量高清图库,永久免费图片下载。<?php if($seo["description"] != null): echo ($seo["description"]); endif; ?>">
    <title>高清图库_正版摄影图片_免费提供优质原创图片下载的网站_探图官网<?php if($seo["title"] != null): ?>_<?php echo ($seo["title"]); endif; ?></title>
    <link rel="icon" href="/static/images/logo.gif">
    <link rel="stylesheet" href="/static/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/static/css/new.css?v=201709181023">
    <link rel="stylesheet" type="text/css" href="/static/css/style.css?v=201709181724">
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
                          <input class="header-input" onkeypress="onkey(this.value)" onkeydown="onkey(this.value)" id="searchcon2" placeholder="请输入关键词" type="text"/> 
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
#fmsel2 {
    max-height: 500px;
    overflow: auto;
}

.selpics_div {
    float: left;
    border: 1px solid #EEE;
    margin: 0 5px 5px 0;
    /*width: 264px;
  height: 90px;*/
    cursor: pointer;
}

.selpics_div:hover {
    border: 1px solid #333;
    background: #EEE;
}

.selpics_div img {
    max-height: 100px;
    max-width: 200px;
}

.cbanner {
    background-position: center;
    background-size: cover;
    background-clip: border-box;
}
</style>
<script>
$(function() {
    $("html,body").animate({ "scrollTop": "300px" }, 1000);
});
var doing = false,
    _uid = '<?php echo session("uid");?>';

function toupload() {
    if (doing || $('#userfile').val() == '') return false;
    doing = true;
    $('#fengmianModal2').modal('hide');
    loading.show(0, '图片上传中,请稍候...');
    $('#form_1').submit();
}

function selpic() {
    if (doing) return false;
    $('#fengmianModal2').modal('show');
    $('#fmsel1').show();
    $('#fmsel2').hide();
    //$('#userfile').val('').click();
}

function selpic2_back() {
    $('#fmsel1').show();
    $('#fmsel2').hide();
}

function selpic2_do1() {
    if (doing) return false;
    $('#fmsel1').hide();
    $('#fmsel2').show().html('Loading...');
    var str = '';
    $.getJSON('/user/getpics?callback=?', function(json) {
        if (json.list == null) { $('#fmsel2').show().html('您没有作品！'); return false; }
        $.each(json.list, function(index, data) {
            str += '<div class="selpics_div" onclick="selpic2_do1_chk(\'' + data.imgkey + '\')"><img src="' + data.imgurl + '"/></div>';
        });
        if (str != '') $('#fmsel2').show().html('<div style="text-align:left;margin-bottom:5px;"><input type="button" class="btn btn-default" onclick="selpic2_back()" value="返回" /></div>' + str + '<div style="clear:both;"></div>');
        else $('#fmsel2').show().html('您没有作品！');
    });
}

function selpic2_do1_chk(key) {
    $('#fengmianModal2').modal('hide');
    loading.show(0, '图片设置中,请稍候...');
    $.get('/camerist/setpics?uid=<?php echo session("uid");?>&key=' + key, function(data) {
        loading.hide();
        doing = false;
        $('.cbanner').css({ "background-image": "url(" + data + ")" });
    });
}

function selpic2_do2() {
    if (doing) return false;
    $('#userfile').val('').click();
}

function api_avatar_err(info) {
    alert(info);
    doing = false;
    loading.hide();
}

function api_avatar_ok(name) {
    loading.hide();
    doing = false;
    $('.cbanner').css({ "background-image": "url(/static/uploadfiles/albumimg/" + name + ")" });
}

function onkey() {
    if (window.event.keyCode == 13) location = '/index/search?kw=' + escape(global.trim($('#searchcon2').val()));
}

function fouc(id) {
    var user = $('#session').val();

    if (user) {
        $.get('/camerist/tofollow?uid=' + id, function(json) {
            location.reload();
        })
    } else {
        alert('请先登陆');
    }
}
</script>
<div class="cbanner" style="background-image:url(<?php echo imgurl_tt3($target_user['albumimg'],0);?>);">
</div>
<input type="hidden" id="session" value="<?php echo ($data["user"]["id"]); ?>" />
<div class="wrapper photographer" style="border-bottom: 1px solid #eee;">
    <div class="container" style="width: 100%;">
        <div class="photographer-nav row" style="position:relative;">
            <div class="col-lg-5 col-md-6 col-sm-12 col-xs-12">
                <div class="float">
                    <?php if($target_user['avatar']==''){if($target_user['avatarimg']==''){ ?><img width="120" style="border-radius:60px;" src="/static/images/tx.png" />
                    <?php }else{ ?><img src="/static/uploadfiles/images/<?php echo ($target_user["avatarimg"]); ?>" width="120" style="border-radius:60px;" />
                    <?php }}else{ ?><img src="/static/uploadfiles/images/<?php echo ($target_user["avatar"]); ?>" width="120" style="border-radius:60px;" />
                    <?php } ?>
                </div>
                <div class="float" style="margin:5px;">
                    <div class="clearfix" style="line-height: 30px;"><span style="font-size:22px;"><?php echo cstr($target_user['nickname'],'匿名');?></span> &nbsp;&nbsp;
                        <?php if($target_user['id']!=$data['user']['id']){ ?><span onClick="fouc(<?php echo ($target_user["id"]); ?>)" class="<?php if($target_user['followed']==1) echo 'fansbtn_2'; else echo 'fansbtn'; ?>"><?php if($target_user['followed']==1) echo '取消关注';else echo '关注'; ?></span>
                        <?php } ?>
                    </div>
                    <div style="color:#7e7e7e;padding:10px 0;">个性签名：
                        <?php if($target_user['desc']) echo $target_user['desc'];else echo '此家伙很懒没留下签名'; ?>
                    </div>
                    <div style="width:280px;">
                        <div class="float" style="width:20%;"><a href="http://www.jiathis.com/send/?webid=weixin&url=<?php echo GetCurUrl();?>&title=探图" target="_blank"><img src="/static/images/iconwechat.png" /></a></div>
                        <div class="float" style="width:20%;"><a href="http://www.jiathis.com/send/?webid=tsina&url=<?php echo GetCurUrl();?>&title=探图" target="_blank"><img src="/static/images/iconsina.png" /></a></div>
                        <div class="float" style="width:20%;"><a href="http://www.jiathis.com/send/?webid=qzone&url=<?php echo GetCurUrl();?>&title=探图" target="_blank"><img src="/static/images/iconqzone.png" /></a></div>
                        <div class="float" style="width:20%;"><a href="http://www.jiathis.com/send/?webid=renren&url=<?php echo GetCurUrl();?>&title=探图" target="_blank"><img src="/static/images/iconrenren.png" /></a></div>
                        <div class="float" style="width:20%;"><a href="http://www.jiathis.com/send/?webid=douban&url=<?php echo GetCurUrl();?>&title=探图" target="_blank"><img src="/static/images/icondou.png" /></a></div>
                        <div class="clear"></div>
                    </div>
                    <div style="margin:10px 0;"><span style="color:#f16172;"><?php echo ($target_user["visit"]); ?></span>访问数</div>
                </div>
            </div>
            <?php if($data['user']['id']==I('get.uid')): ?>
            <iframe name="ifrm1" src="/user/waits" style="position:absolute;top:-100px;left:-100px;width:0px;height:0px;" frameborder="0"></iframe>
            <form action="/camerist/abanner_up?uid=<?php echo I('get.uid');?>" target="ifrm1" method="post" id="form_1" enctype="multipart/form-data">
                <div class="floatright">
                    <div>
                        <button class="btn btn-default" type="button" onClick="selpic()">设置封面</button>&nbsp;<span style="font-size:.8em;">（建议上传图片尺寸1920*600,文件大小不超过20M）</span>
                        <input type="file" id="userfile" name="userfile" size="20" style="position: absolute;left: -100px;top: -100px;width: 0;height: 0;" onChange="toupload();" />
                    </div>
                </div>
            </form>
            <?php endif; ?>
                <div class="cheader-nav col-lg-7 col-md-6 col-sm-12 col-xs-12">
                    <ul>
                        <li onClick="location='/camerist/gallery?uid=<?php echo ($target_user["id"]); ?>';" <?php if($data['headmenu'] == 'gallery') echo 'class="ind_active"'; ?> data-url=""> 作品展示&nbsp;<?php echo ($data["image_count"]); ?> </li>
                        <li onClick="location='/camerist/album?uid=<?php echo ($target_user["id"]); ?>';" <?php if($data['headmenu'] == 'album') echo 'class="ind_active"'; ?> data-url="classid=1"> 专辑&nbsp;<?php echo ($data["folder_count"]); ?> </li>
                        <li onClick="location='/camerist/fans?uid=<?php echo ($target_user["id"]); ?>';" <?php if($data['headmenu'] == 'fans') echo 'class="ind_active"'; ?> data-url="classid=1"> 粉丝&nbsp;<?php echo ($data["fans_count"]); ?> </li>
                        <li onClick="location='/camerist/followers?uid=<?php echo ($target_user["id"]); ?>';" <?php if($data['headmenu'] == 'followers') echo 'class="ind_active"'; ?> data-url="classid=1"> 关注&nbsp;<?php echo ($data["followers_count"]); ?> </li>
                        <li onClick="location='/camerist/ziliao?uid=<?php echo ($target_user["id"]); ?>';" <?php if($data['headmenu'] == 'ziliao') echo 'class="ind_active"'; ?> data-url="classid=1"> 详细资料 </li>
                        <li onClick="location='/camerist/collection?uid=<?php echo ($target_user["id"]); ?>';" <?php if($data['headmenu'] == 'collection') echo 'class="ind_active"'; ?> data-url="classid=1"> 收藏 </li>
                    </ul>
                </div>
        </div>
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
                        <div class="center mod2151"> <img src="/static/images/logocol.png" width="150px"> </div>
                        <div class="mod21" style="height: auto;">
                            <h5>设置封面</h5>
                            <div id="fmsel1">
                                <div style="margin-bottom: 20px;">
                                    <div class="bgc form-control" style="margin: auto;width: 30%;text-align:center;cursor:pointer;" onClick="selpic2_do1()"> 选择专辑中图片 </div>
                                </div>
                                <div style="margin-bottom: 10px;">
                                    <div role="tabpanel" style="display: ;" class="tab-pane fade in active" id="demo" aria-labelledby="demo-tab">
                                        <div class="row">
                                            <input type="hidden" id="domain" value="<?php echo qiniu_domain();?>">
                                            <input type="hidden" id="uptoken_url" value="/static/qiniu/jssdk/demo/upload_token_6_1.php">
                                            <div class="col-md-12">
                                                <div id="container">
                                                    <div class="bgc form-control" id="pickfiles" style="margin: auto;width: 30%;text-align:center;cursor:pointer;" data-func="selpic2_do2"> 上传一张封面图 </div>
                                                    <div data-toggle="modal" data-target="#myModalupload" id="finishbtn"></div>
                                                </div>
                                            </div>
                                            <div style="clear:both;"></div>
                                            <div style="display:none" id="success" class="col-md-12">
                                                <div class="alert-success" style="padding: 10px;margin-top:10px;">
                                                    队列全部文件处理完毕
                                                </div>
                                            </div>
                                            <div class="col-md-12 ">
                                                <table class="table table-striped table-hover text-left" style="margin-top:40px;display:none">
                                                    <thead>
                                                        <tr>
                                                            <th class="col-md-4">Filename</th>
                                                            <th class="col-md-2">Size</th>
                                                            <th class="col-md-6">Detail</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="fsUploadProgress">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <script type="text/javascript" src="/static/qiniu/jssdk/demo/scripts/plupload/moxie.js"></script>
                                    <script type="text/javascript" src="/static/qiniu/jssdk/demo/scripts/plupload/plupload.dev.js"></script>
                                    <!-- <script type="text/javascript" src="/static/qiniu/jssdk/demo/scripts/plupload/js/plupload.full.min.js"></script> -->
                                    <script type="text/javascript" src="/static/qiniu/jssdk/demo/scripts/plupload/i18n/zh_CN.js"></script>
                                    <script type="text/javascript" src="/static/qiniu/jssdk/demo/scripts/ui.js?rad=1488361329"></script>
                                    <script type="text/javascript" src="/static/qiniu/jssdk/demo/scripts/qiniu.js"></script>
                                    <script type="text/javascript" src="/static/qiniu/jssdk/demo/scripts/highlight.js"></script>
                                    <script type="text/javascript" src="/static/qiniu/jssdk/demo/scripts/main2.js?rad=<?php echo time();?>"></script>
                                    <script type="text/javascript">
                                    hljs.initHighlightingOnLoad();
                                    </script>
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
<link rel="stylesheet" type="text/css" href="/static/css/jquery.flex-images.css">
<script type="text/javascript" src="/static/js/jquery.flex-images.min.js"></script>
<script type="text/javascript" src="/static/js/jquery.lazyload.min.js"></script>
<!--图片展示  -->
<div class="container container-whitespace container-feature" style="width: 100%;">
    <div class="flex-images" style="padding: 20px;">
      <p style="text-align: center;padding: 80px;">图片加载中...</p>
    </div>
    <div class="fanye" style="margin:10px;text-align: center;"></div>
</div>
<style>
img[src=""]{
opacity: 0;
}
</style>
<script>
$(function(){
	$('.seacon1col').click(function(){
		$('.seacon1col').html('');
		var icon = '<i class="glyphicon glyphicon-ok"></i>';
		$(this).html(icon);
	});	
});

    function addlikes(id){
        var storage = window.localStorage;
        if(!storage || !(storage.setItem('a', 123) , storage.getItem('a') == 123)){
            alert('当前浏览器不支持投票功能！');
        }
        else{
            var _likes_list = null;
            if(null!=storage.getItem('likes_list')){
                _likes_list = JSON.parse(storage.getItem('likes_list'));
            }

            var _date = new Date().toLocaleDateString();

            if(null!=_likes_list && undefined!=_likes_list[id] && _date==_likes_list[id]){
                return;
            }//existed
            else{
                $.getJSON('/index/imglike?id='+id,function(json){
                    $('#likes'+id).html(json.nums);
                    if(null==_likes_list){
                        _likes_list = {};
                    }                    
                    _likes_list[id]=_date;
                    storage.setItem('likes_list',JSON.stringify(_likes_list));
                });
            }
        }
    }

   var picid = 0;
   function addfav(id){
	var uid = $('#userid').val();
	if(uid){  
		$.getJSON('/index/has_fav?id='+id,function(json){
			
			if(json.status=='0'){
				$('#colModal').modal('show');
				picid=id;
			}
		});
	}else{
		$('#myModallogin').modal('show');
	}
   }
function fav(){
	<?php if(!$fav): ?>
	if(isNaN($('input[name="fid"]:checked').val())){
		alert('请选择收藏夹');return;
	}
	<?php endif; ?>
	location='/index/tofav?id='+picid+'&fcid='+$('input[name="fid"]:checked').val();	
}
	function newf(){
		if($('#foldername').val()==''){
			alert('请输入收藏夹名!');
			return;
		}
		$.getJSON('/user/new_favclass?fname='+$('#foldername').val(),function(json){
			alert('创建成功!');
			$('#flist').prepend('<div class="afid"><input id="fid'+json.id+'" type="radio" name="fid" value="'+json.id+'" />&nbsp;<label for="fid'+json.id+'">'+json.foldername+'</label></div>');
			$('#foldername').val('');
		});
	}
$(function(){
	imglist('');
});
	function imglist(url){
		var temp = '';
		url += '<?php echo ($data["sql"]); ?>';
		var i = 0;
		$.getJSON('/index/get_images?'+url,function(json){
			if(!json.r){
				$(".flex-images").html('<div style="margin:0 auto;padding:60px;">没有找到图片</div>');
			}else{
				if($('.alls')){
					$('.alls').html(json.total);
				}
				if($('.fanye')){
					$('.fanye').html(json.fanye);
				}
				$.each(json.r,function(index,data){
					var width = data.width;
					var height = data.height;
					if(height>width){
						
						var w = ((width*250)/height);
						var h = 250;
						
					}else if(height==width){
						var w = 250;
						var h = 250;
					}else{
						var w = ((width*250)/height);
						var h = 250;
					}
					if(null==data.nickname||''==data.nickname){
                        data.nickname="匿名";
                    }
					
					temp+= "<div class='item cell' data-w='"+width+"' data-h='"+height+"'><img class='lazy' src='' data-original='"+data.imgurl+"' style='width:100%;height:100%;cursor:pointer;' onclick=\"window.open('/index/detail?id="+data.id+"');\"><div class='discuz' style='position:absolute;bottom:0px;padding:5px 10px;width:100%;background:rgba(0,0,0,0.2);'><div  onclick=\"window.open('/camerist/gallery?uid="+data.uid+"');\" class='float' style='cursor:pointer;'><img src='/index/avatar_url/uid/"+data.uid+"' style='border-radius:12px;width:24px' />"+data.nickname+"</div><div style='float:right;margin-top:4px;'><img src='/static/images/iconheart.png' style='margin-top: -3px;width:13px;height:13px;cursor:pointer;' onClick=\"addlikes("+data.id+")\" />&nbsp;<span id='likes"+data.id+"'>"+data.likes+"</span>&nbsp;&nbsp;&nbsp;<img src='/static/images/iconeye.png' style='margin-top: -3px;width:18px;height:13px;' />&nbsp;"+data.visits+"&nbsp;&nbsp;&nbsp;<img src='/static/images/iconstar.png' style='margin-top: -3px;width:13px;height:13px;cursor:pointer;' onClick=\"addfav("+data.id+")\" id='fav"+data.id+"' />&nbsp;"+data.fav+" </div><div style='clear:both'></div></div></div>"
					//temp+= "<a href='/index/detail?id="+data.id+"'><div class='cell' style='width:"+w+"px; height: "+h+"px; background-image: url("+data.imgurl+");color:#fff;'><div class='discuz' style='position:absolute;bottom:5px;right:10px;'><img src='/static/images/iconheart.png' /> "+data.downloads+" <img src='/static/images/iconeye.png' /> "+data.visits+"  <img src='/static/images/iconstar.png' /> "+data.fav+" </div></div></a>"
				});
			  $(".flex-images").html(temp);
				$('.flex-images').flexImages({'rowHeight':290,'container':'.item','truncate':false });
    			$("img.lazy").lazyload({effect: "fadeIn"});
				$(window).scroll();
			  /*$("#freewall").html(temp);
			  var wall = new Freewall("#freewall");
			  wall.reset({
				selector: '.cell',

				animate: true,
				cellW: 20,
				cellH: 250,
				onResize: function() {
				  wall.fitWidth();
				}
			  });
			  wall.fitWidth();*/
				$('.cell').mouseover(function(){
					$(this).find('.discuz').show();
				}).mouseout(function(){
					$(this).find('.discuz').hide();
				});
			}
		});
	}
      // for scroll bar appear;
      $(window).trigger("resize");
</script>
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
        <div class="col-sm-2 col-xm-6">
            <p class="foottitle">联系我们</p>
            <div class="about">
                <p>020-37589885</p>
                <p><a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=2562199003&site=qq&menu=yes"><img src="/static/images/qq.jpg"></a></p>
            </div>
        </div>
        <div class="col-sm-2 col-xm-6" style="text-align: center;">
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