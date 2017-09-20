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


<!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
<!--[if lt IE 9]><script src="../../assets//static/js/ie8-responsive-file-warning.js"></script><![endif]-->
<link rel="stylesheet" type="text/css" href="/static/css/jquery.flex-images.css">
<script charset="utf-8" src="/static/plugs/kindeditor/kindeditor-min.js"></script>
<script charset="utf-8" src="/static/plugs/kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript" src="/static/js/jquery.flex-images.min.js"></script>
<script type="text/javascript" src="/static/js/jquery.lazyload.min.js"></script>

<script>

KindEditor.ready(function(K) {
	var editor = K.create("textarea[name='commendtxt']", {
		resizeType : 1,
		//pasteType : 1,
		syncType : "form",
		width: "100%",
		height: "100px",
		newlineTag : "br",
		allowPreviewEmoticons : false,
		uploadJson : "/static/plugs/kindeditor/php/upload_json.php",
		fileManagerJson : "/static/plugs/kindeditor/php/file_manager_json.php",
		allowFileManager : true,
		afterBlur: function(){this.sync();},
		items : ["emoticons"]
	});
});

/******禁右键*****/
<?php if($data['r']['banquan']==1){ ?>
function iEsc(){ return false; }
function iEsc2(){ <?php if($data['r']['banquan']==1): ?>$("#banquanbaohu").fadeIn();setTimeout("$('#banquanbaohu').fadeOut()",1500);<?php endif; ?>return false; }
function iRec(){ return true; }
function DisableKeys() {
if(event.ctrlKey || event.shiftKey || event.altKey)  {
window.event.returnValue=false;
iEsc();}
}
document.ondragstart=iEsc;
document.onkeydown=DisableKeys;
document.oncontextmenu=iEsc2;
if (typeof document.onselectstart !="undefined"){
	document.onselectstart=iEsc;
}else
<?php } ?>
/*****end******/
function onkey(){
	if(window.event.keyCode==13) window.open('/index/search?kw='+escape($('#searchcon').val()));	
}
function fav2(){
	<?php if(!$fav): ?>
	if(isNaN($('input[name="fid"]:checked').val())){
		alert('请选择收藏夹');return;
	}
	<?php endif; ?>
	location='/index/tofav?id=<?php echo ($data["r"]["id"]); ?>&fcid='+$('input[name="fid"]:checked').val();	
}

function fouc(id,obj){
	var user = $('#session').val();
		
	if(user){
		$.get('/camerist/tofollow?uid='+id,function(json){
			location.reload();
		})
	}else{
		alert('请先登陆');
	}
}
</script>

<?php $fav = D('a_user_fav')->where('isuse=1 and uid='.session('uid').' and productid='.$data['r']['id'])->find(); ?>

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="http://cdn.boot/static/css.com/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="http://cdn.boot/static/css.com/respond./static/js/1.4.2/respond.min.js"></script>
    <![endif]-->

<style>
.yScrollList{width:1198px;background:#ffffff;margin:25px auto 0;}
.yScrollListTitle{width:100%;height:38px;line-height:38px;border-bottom:1px solid #dcdcdc;}
.yScrollListTitle h1{cursor:pointer;font-size:16px;background:#fff;color:#666666;height:38px;border-left:1px solid #dcdcdc;border-right:1px solid #dcdcdc;float:left;position:relative;left:-1px;width:110px;text-align:center;}
.yScrollListTitle h1.ytitleh12{left:-2px;}
.yScrollListTitle h1.yth1click{height:42px;border-top:2px solid #e9630a;top:-5px;}
.yScrollListIn{width:100%;height:200px;position:relative;}
.yScrollListInList{display:none;width:1190px;height:200px;position:absolute;padding:0 30px;left:0;top:0;overflow:hidden;}
.yScrollListInList .yScrollListbtn{cursor:pointer;position:absolute;width:20px;height:180px;top:12px;}
.yScrollListInList .yScrollListbtnr{right:0px;background:url(/static/images/per_right.png) no-repeat 0 0;}
.yScrollListInList .yScrollListbtnl{left:0px;background:url(/static/images/per_left.png) no-repeat 0 0;}
.yScrollListInList ul{width:2238px;height:224px;position:absolute;top:0;left:30px;overflow:hidden;}
.yScrollListInList ul li{height:224px;float:left;}
.yScrollListInList ul li img{height: 180px;display:block;margin-left:10px;margin-top:12px;}
.yScrollListInList ul li p{text-align:center;font-size:12px;color:#666666;line-height:18px;padding:7px 10px 0;width:140px;height:36px;overflow:hidden;}
.yScrollListInList ul li p:hover{color:#e9630a;text-decoration:underline;}
.yScrollListInList ul li span{color:#e9630a;font-size:12px;text-align:center;display:block;line-height:24px;}

#fancybox-overlay{background-color: #000 !important;opacity: .9 !important;}
#fancybox-content{border:none !important;}
body {
  min-width:1200px;
}
</style>

<!--detal1-->
<div class="container1200 center  clearfix">
  <div class="d1 float" style="margin-top:80px;">
    <a class="sexybox_img" href="<?php echo ($data["r"]["imgurl"]); ?>">
    <div class="d1-3">
       <img id="photo" src="<?php echo ($data["r"]["imgurl"]); ?>" alt="<?php echo ($data["r"]["name"]); ?>" title="<?php echo ($data["r"]["name"]); ?>" style="cursor:pointer;background:url(<?php echo ($data["r"]["timgurl"]); ?>);background-size:cover;">
    	<?php if($data['r']['banquan']==1){ ?><div id="banquanbaohu" style="display: none;position:absolute;top:20px;right:20px;background:#333;border-radius:4px;color:#fff;padding:5px 10px;">© 版权保护</div><?php } ?>
    </div>
    </a>
    <div class="d1-4 clearfix">
        <span class="tag-1 float">标签:</span>
        <span class="tags float">
      	<?php if(is_array($data["r"]["tag_data"])): $i = 0; $__LIST__ = $data["r"]["tag_data"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo_tag): $mod = ($i % 2 );++$i;?><a href="/index/search?kw=<?php echo escape($vo_tag['tagname']);?>&type=is" target="_blank"><?php echo ($vo_tag["tagname"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>           

        </span>
        
    </div>
  </div>
  
  <div class="d2 float-right" style="margin-top:80px;">
  <div class="d22">
    <a href="/camerist/gallery?uid=<?php echo ($data["r"]["uid"]); ?>">
    <div class="d2-1">
    	<img src="/index/avatar_url/uid/<?php echo ($data["r"]["uid"]); ?>" width="40" style="border-radius:20px;" />
		<h5><div class="float" style="white-space:nowrap;width:110px; overflow:hidden; text-overflow:ellipsis;"><?php echo cstr($data['r']['nickname'],'匿名');?></div> &nbsp;&nbsp;<?php if($data['r']['uid']!=$data['user']['id']){ ?><span onClick="fouc(<?php echo ($data["r"]["uid"]); ?>)" class="radius userfocus" style="cursor:pointer;"><?php if($data['followed']==1) echo '取消关注';else echo '关注'; ?></span><?php } ?></h5>
		<div class="clearfix"></div>
    </div>
    </a>
   </div>
   <div class="d23"> 
   	<div class="d2-4">
     <div class="d2-2">
     <?php if($data['r']['banquan']==1){ ?>
      <button class="btn d2btn bgc disabled">版权保护</button>
     	
     <?php }else{if($data['user']==0){ ?>
      <button class="btn d2btn  bgc" data-toggle="modal" data-target="#mydetail">免费下载</button>
      <?php }else{ ?>
      <button class="btn d2btn  bgc" onClick="location='/index/img_api?id=<?php echo ($data["r"]["id"]); ?>&rand='+Math.random();">免费下载</button>

      <?php }} ?>
      <!--<div data-toggle="modal" data-target="#mydetail" >123</div>-->
   		</div>
    </div>
    <?php if($data['user']['id']){ ?>
    <div class="d2-2">
    	<button class="btn d2btn1 btn-default" <?php if($fav) echo ' onclick="fav2()"'; else echo ' data-toggle="modal" data-target="#colModal"'; ?>>★ <?php if($fav) echo '取消'; ?>收藏</button>
    </div>
    <?php } ?>
    
     <?php if($data['r']['banquan']!=1){ ?>
    <div class="d2-3"> 
      <p class="top">
        <a href="/index/content?id=444" rel="nofollow" target="_blank">知识共享CC0协议</a>  / <a href="/index/content?id=324" rel="nofollow" target="_blank">常见问题解答</a>
      </p>
      <!--<p class="bottom" style="cursor: pointer;">
        <i></i><span>允许商用</span>
        <small style="color: #f16172;"><a href="/index/content?id=322" target="_blank">授权范围最广的全用途授权</a></small>
      </p>-->
    </div> 
      <?php } ?>
   </div>
   <div class="d23"> 
    <div class="d2-4">
   <script>
    function addlikes(){
        var id = <?php echo ($data["r"]["id"]); ?>;
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
                    $('#likes').html(json.nums);
                    if(null==_likes_list){
                        _likes_list = {};
                    }                    
                    _likes_list[id]=_date;
                    storage.setItem('likes_list',JSON.stringify(_likes_list));
                });
            }
        }
    }

   </script>
      <div class="radius detial_like" onClick="addlikes()">
      	<img src="/static/images/iconheart2.png" width="20" /><br />喜欢(<span id="likes"><?php echo ($data["r"]["likes"]); ?></span>)
      </div>
         <!--<div class="radius detial_like" style="background: blue;" onClick="location='/index/tofav?id=<?php echo ($data["r"]["id"]); ?>';">
         	<img src="/static/images/iconheart2.png" width="20" /><br /><?php $fav = D('a_user_fav')->where('isuse=1 and uid='.session('uid').' and productid='.$data['r']['id'])->find(); if($fav) echo '已'; ?>收藏
         </div>-->
        <div class="d1-2" style="padding-left:0;">
          <span class="look"><i><img src="/static/images/iconeyes2.png" /></i>&nbsp;<?php echo ($data["r"]["visits"]); ?> 浏览<b></b></span>
          <span class="collect"><i><img src="/static/images/iconstar2.png" style="margin-top:-2px;"/></i>&nbsp;<?php echo ($data["fav"]); ?> 收藏</span>
          <span class="download"><i><img src="/static/images/icondownload2.png" /></i>&nbsp;<?php echo ($data["r"]["downloads"]); ?> 下载 </span>
        </div>
        <div class="d1-2" style="padding-left:0;">
          <span class="look"><i class="glyphicon glyphicon-time"></i>&nbsp;发布时间: <?php echo ($data["r"]["ctime"]); ?></span>
        </div>
      
  	<div class="share-wrap clearfix">
  		<a href="http://www.jiathis.com/send/?webid=weixin&url=<?php echo GetCurUrl();?>&title=探图" target="_blank"><img src="/static/images/iconwechat.png" /></a>
      <a href="http://www.jiathis.com/send/?webid=tsina&url=<?php echo GetCurUrl();?>&title=探图" target="_blank"><img src="/static/images/iconsina.png" /></a>
      <a href="http://www.jiathis.com/send/?webid=qzone&url=<?php echo GetCurUrl();?>&title=探图" target="_blank"><img src="/static/images/iconqzone.png" /></a>
      <a href="http://www.jiathis.com/send/?webid=renren&url=<?php echo GetCurUrl();?>&title=探图" target="_blank"><img src="/static/images/iconrenren.png" /></a>
      <a href="http://www.jiathis.com/send/?webid=douban&url=<?php echo GetCurUrl();?>&title=探图" target="_blank"><img src="/static/images/icondou.png" /></a>
  	</div>
      
    </div>
   </div>
   <div class="d23"> 
    <div class="d2-4">
      <h5>作品描述</h5>
      <div>
      	<p><?php echo ($data["r"]["name"]); ?></p>
      	<p><?php echo ($data["r"]["desc"]); ?></p>
      </div>
   		
    </div>
   </div>
   <div class="d23"> 
    <div class="d2-4">
   
      <p>编号<span><?php $id = $data['r']['id']+30000000;echo $id; ?></span></p>
      <p>分类
      	<?php if(is_array($data["r"]["class_data"])): $i = 0; $__LIST__ = $data["r"]["class_data"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo_cla): $mod = ($i % 2 );++$i;?><span style="color: #f16172;">
              <a target="_blank" class="a-cate" href="/index/search?classid=<?php echo ($vo_cla["id"]); ?>"><?php echo ($vo_cla["classname"]); ?></a>
          </span><?php endforeach; endif; else: echo "" ;endif; ?>
          </p>
      <?php if($data['r']['width']) echo '<p>尺寸<span>'.$data['r']['width'].' × '.$data['r']['height'].'</span></p>'; ?>
      <p>大小<span><?php $size = $data['r']['size']/1024;if(round($size,2)>1024){echo sprintf('%.2f', $size/1024).'MB';}else{echo round($size,2).'KB';} ?></span></p>
      <p>格式<span><?php echo ($data["r"]["imgext"]); ?></span></p>
    </div>
   </div>
   <div class="d24">
    <div class="d2-5">
      <h5>EXIF信息</h5>
      <?php if($data['r']['brand']) echo '<p>品牌<span>'.$data['r']['brand'].'</span></p>'; ?>
      <?php if($data['r']['model']) echo '<p>型号<span>'.$data['r']['model'].'</span></p>'; ?>
      <?php if($data['r']['jiaoju']) echo '<p>焦距<span>'.$data['r']['jiaoju'].'</span></p>'; ?>
      <?php if($data['r']['guangquan']) echo '<p>光圈<span>'.$data['r']['guangquan'].'</span></p>'; ?>
      <?php if($data['r']['kuaimen']) echo '<p>快门<span>'.$data['r']['kuaimen'].'</span></p>'; ?>
      <?php if($data['r']['iso']) echo '<p>iso<span>'.$data['r']['iso'].'</span></p>'; ?>
      <?php if($data['r']['baoguang']) echo '<p>曝光<span>'.$data['r']['baoguang'].'</span></p>'; ?>
      <?php if($data['r']['jingtou']) echo '<p>镜头<span>'.$data['r']['jingtou'].'</span></p>'; ?>
      <?php if($data['r']['isfree']==0) echo '<p>费用<span></span></p>'; ?>
      <?php if($data['r']['taketime']!='0000-00-00 00:00:00' || !$data['r']['taketime']) echo '<p>拍摄时间<span>'.$data['r']['taketime'].'</span></p>'; ?>
    </div>
   </div>
  </div>
  <div class="clearfix"></div>
</div>

<div class="container1200 center">
  <h4>类似图片<span style="font-size:.6em;color:#09F;margin-left:10px;"><a href="/index/search?kw=" target="_blank">查看全部>></a></span></h4>
</div>

<div class="yScrollList">
	<div class="yScrollListIn">
		<div class="yScrollListInList yScrollListInList1" >
			<ul style="margin-left: -40px;">
            	
			</ul>
			<div class="yScrollListbtn yScrollListbtnl"></div>
			<div class="yScrollListbtn yScrollListbtnr"></div>
		</div>
	</div>
</div>

<div class="container container-whitespace container-feature">
  <div id="freewall" class="free-wall" style="margin-left:0px; margin-right:0px;"></div>
  <div class="flex-images"></div>
</div>

<!-- 评论 -->
<div class="container1200 center">
<a href="/index/detail?id=<?php echo ($data["preid"]["id"]); ?>"><div style="position:absolute;top:320px;left:0;width:45px;height:80px;text-align:center;background:#CCC;"><img style="margin-top:15px;" src="/static/images/toleft.png" height="50" /></div></a>
<a href="/index/detail?id=<?php echo ($data["nextid"]["id"]); ?>"><div style="position:absolute;top:320px;right:0;width:45px;height:80px;text-align:center;background:#CCC;"><img style="margin-top:15px;" src="/static/images/toright.png" height="50" /></div></a>
	<div class="float-left" style="
	    max-width: 900px;
	    width: 100%;
	">
		<a name="commend"></a>
		<h5>全部留言（<?php echo count($data['commend']);?>）</h5>
		<?php if(!$data['commend']): ?>
		<div class="text-center" style="padding: 20px;"><h2>快来抢沙发咯~</h2></div>
		<?php endif; ?>
		<?php if(!session('uid')): ?>
			<div class="float-left" style="padding:15px;width:72px"><img src="/static/images/dperson.png" width="42"></div>
			<div class="float-left" style="
      padding: 10px;
			    height: 69px;
			    border: 1px solid #e3e3e3;
			    width: 827px;
			    border-radius: 10px;
			    "><h5 style="padding: 5px;">登陆后才可以评论，请<span style="color: #1f9bdb;" class="cursor" data-toggle="modal" data-target="#myModallogin">点击这里</span>，进行登陆</h5>
			</div>
			<div class="clearfix"></div>
		<?php else: ?>
		<form action="/index/detail_commend?pid=<?php echo ($data["r"]["id"]); ?>" id="formly" method="post">
			<textarea name="commendtxt" id="commendtxt" style="visibility:hidden;"></textarea>
			<div style="padding: 10px;text-align: right;">
				<input type="button" class="btn btn-info" onClick="if($('#commendtxt').val()!='') $('#formly').submit();" value="发布留言" />
			</div>
		</form>
		<?php endif; ?>
		<div>
			<?php if(is_array($data["commend"])): $i = 0; $__LIST__ = $data["commend"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; $u = D('a_user')->where('id='.$vo['uid'])->find(); ?>
				<div style="/* width:1200px; */position:relative;">
					<div class="float-left" style="padding:15px; width:86px;height:86px"><img width="52" src="/index/avatar_url/uid/<?php echo ($vo["uid"]); ?>"></div>
					<div class="float-left">
						<h4><?php echo cstr($u['nickname'],$u2['username']);?></h4>
						<h5><?php echo htmlspecialchars_decode($vo['commendtxt']);?></h5>
					</div>
					<div style="position: absolute;right:10px;"><h5><?php echo ($vo["ctime"]); ?></h5></div>
					<div style="position: absolute;right:10px;bottom:10px;"><h5 style="color:#ff7c8b;cursor: pointer;" onClick="<?php if(!session('uid')): ?>alert('请先登录!');<?php else: ?>$('#divre<?php echo ($vo["id"]); ?>').toggle();<?php endif; ?>">回复</h5></div>
					<div class="clearfix"></div>
				</div>
				<div>
					<?php $re = D('a_product_commend')->where('isuse=1 and parentid='.$vo['id'].' and productid='.$data['r']['id'])->order('id desc')->select(); ?>
					<?php if(is_array($re)): $i = 0; $__LIST__ = $re;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($i % 2 );++$i; $u2 = D('a_user')->where('id='.$vo2['uid'])->find(); ?>
					<div style="padding-left: 80px;margin-bottom: 10px;">
						<img width="42" src="/index/avatar_url/uid/<?php echo ($vo2["uid"]); ?>">
						<?php echo cstr($u2['nickname'],$u2['username']);?>&nbsp;回复：
						<?php echo htmlspecialchars_decode($vo2['commendtxt']);?>
					</div><?php endforeach; endif; else: echo "" ;endif; ?>
				</div>
				<form action="/index/detail_commend_re?id=<?php echo ($vo["id"]); ?>&pid=<?php echo ($data["r"]["id"]); ?>" id="formre<?php echo ($vo["id"]); ?>" method="post">
				<div id="divre<?php echo ($vo["id"]); ?>" style="display: none;padding-left: 80px;">
					<textarea name="commendtxt" id="commendtxt<?php echo ($vo["id"]); ?>" style="visibility:hidden;"></textarea>
					<div style="padding: 10px;text-align: right;">
						<input type="button" class="btn btn-info" onClick="if($('#commendtxt<?php echo ($vo["id"]); ?>').val()!='') $('#formre<?php echo ($vo["id"]); ?>').submit();" value="确认回复" />
					</div>
				</div>
				</form><?php endforeach; endif; else: echo "" ;endif; ?>
		</div>
	</div>
</div>

<!-- 最近访问 -->
<div class="float-left" style="padding: 10px;max-width: 300px;/* border:  3px solid #f8f8f8; */width: 100%;min-height: 100px;"><h2>最近访问</h2>
  <?php if(is_array($data["visit"])): $i = 0; $__LIST__ = $data["visit"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; $u = D('a_user')->where('id='.$vo['uid'])->find(); ?>
	  <div class="float-left text-center" style="margin:5px;width:50px;height:80px; overflow: -moz-hidden-unscrollable;white-space: nowrap;">
	  <a href="/camerist/gallery?uid=<?php echo ($vo["uid"]); ?>" target="_blank">
	  <div style="width:100%; height:50px;">
	  	<img src="/index/avatar_url/uid/<?php echo ($vo["uid"]); ?>" width="50" />
	  </div>
	  <h6 style="color:#83b9dc;margin: 2px;width:50px;white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="<?php echo cstr($u['nickname'],'匿名');?>"><?php echo cstr($u['nickname'],$u['username']);?></h6>
	  </a>
	  <p style="font-size: xx-small;"><?php $looktime = datediff('n',$vo['ctime'],now()); if($looktime<60) echo $looktime.'分钟前'; else{ $looktime = floor($looktime/60); if($looktime<24) echo $looktime.'小时前'; else{ $looktime = floor($looktime/24); if($looktime<99) echo $looktime.'日前'; else{ echo '来访过'; } } } ?></p>
	  </div><?php endforeach; endif; else: echo "" ;endif; ?>
</div>
  
<div class="clearfix"></div></div>
<div class="modal fade" id="mydetail" tabindex="-1" role="dialog" aria-labelledby="mydetail" aria-hidden="true">
  <div class="modal-dialog" style="max-height:227px; max-width:430px; width:430px; height:227px; margin-top:185px; opacity:0.9; background:#FFF">
    <div class="modal-content" style=" width:100%; height:100%">
      <div class="modal-header" style="border:0px">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
      </div>
      <div id="threg">
          <div class="modal-body">
            <div >
              <div class="center mod2151" > <img src="/static/images/logocol.png" width="150px" > </div>
              <div class="text-center" style="margin-top:15px">
                <h4>海量正版高清大图注册免费下载</h4>
              </div>
              <div class="clearfix ">
                  <div>
                    <div style="width:150px; margin:10px auto">
                		<div  class="bgc form-control" style="text-align:center;cursor:pointer; border-radius:4px" data-toggle="modal" data-target="#myModal">免费注册</div>
                    </div>
                  </div>
              </div>
            </div>
          </div>
          <div class="modal-footer" style="border:0px" >
            <h6 class="float-right mod4col" data-toggle="modal" data-target="#myModallogin" style="cursor:pointer;">登录</h6>
            <h6 class="float-right">已有账号？</h6>
            
          </div>
      </div>
    </div>
  </div>
</div>


<script>

$(function(){
	imglist('');
	$(".sexybox_img").fancybox( {
		"transitionIn"		: "fade",
		"transitionOut"		: "fade",
		"titlePosition" 	: "over",
		"type":"image"
	})
});

	function imglist(url){
		var temp = '';
		url += '<?php echo ($data["sql"]); ?>';
		$.getJSON('/index/get_images?'+url,function(json){
			if(!json.r){
				$(".yScrollListInList").css('display','none');
				$(".yScrollList").css('height','auto');
				$(".yScrollListIn").css('height','auto');
				
			}else{
				if($('#alls')){
					$('#alls').html(json.r.length);
				}
				if($('.fanye')){
					$('.fanye').html(json.fanye);
				}
				$(".yScrollListInList").css('display','block');
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
					temp+= "<li><a href='/index/detail?id="+data.id+"' target='_blank'><img class='lazy' src='"+data.imgurl+"' ></a></li>"
				});
			  	$(".yScrollListInList1 ul").html(temp);
			  
				$(".yScrollListTitle h1").click(function  () {
					var index=$(this).index(".yScrollListTitle h1");
					$(this).addClass("yth1click").siblings().removeClass("yth1click");
					$($(".yScrollListInList")[index]).show().siblings().hide();
				})
				//$(".yScrollListInList1 ul").css("width","auto");
				$(".yScrollListInList1 ul").css({width:$(".yScrollListInList1 ul li").length*(160+84)+"px"});
				var numwidth=(160+84)*5;
				$(".yScrollListInList .yScrollListbtnl").click(function(){
					var obj=$(this).parent(".yScrollListInList").find("ul");
					if (!(obj.is(":animated"))) {
						var lefts=parseInt(obj.css("left").slice(0,-2));
						if(lefts<30){
							obj.animate({left:lefts+numwidth},1000);
						}
					}
				})
				$(".yScrollListInList .yScrollListbtnr").click(function(){
					var obj=$(this).parent(".yScrollListInList").find("ul");
					var objcds=-(30+(Math.ceil(obj.find("li").length/5)-2)*numwidth);
					if (!(obj.is(":animated"))) {
						var lefts=parseInt(obj.css("left").slice(0,-2));
						if(lefts>objcds){
							obj.animate({left:lefts-numwidth},1000);
						}
					}
				});
			}
		});
	}
      // for scroll bar appear;
      $(window).trigger("resize");
      $(function(){
		$('.carousel-caption').fadeIn(1000);
		$('.go-top').click(function(){ 
		  $('html, body').animate({scrollTop:0}, 'slow'); 
		  return false; 
		});
      })
</script>
	<!-- Add mousewheel plugin (this is optional) -->
	<script type="text/javascript" src="../lib/jquery.mousewheel.pack.js?v=3.1.3"></script>
	<!-- Add fancyBox main JS and CSS files -->
	<script type="text/javascript" src="/static/js/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
	<link rel="stylesheet" type="text/css" href="/static/js/fancybox/source/jquery.fancybox.css?v=2.1.5" media="screen" />
    <style>
    .fancybox-skin{padding:0 !important;}
	.fancybox-inner{width:100% !important;height:100% !important}
    </style>
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