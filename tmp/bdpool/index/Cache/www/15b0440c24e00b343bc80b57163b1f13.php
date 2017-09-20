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

<style type="text/css">
.navbar-wrapper {
    background: #f16172;
}
/*样式追加*/

/*重写 tab start*/

.nav-tabs {
    border: none;
}

.nav-tabs>li {
    margin: 0;
}

.nav-tabs>li.active>a,
.nav-tabs>li.active>a:hover,
.nav-tabs>li.active>a:focus {
    cursor: default;
    background-color: transparent;
    border: 1px solid #f16172;
    color: #f16172;
    border-radius: 4px;
}

.nav-tabs>li>a {
    margin: 0 5px;
    border-radius: 4px;
    padding: 6px 12px;
}
.nav-tabs>li>a:hover {
    background: none;
    border: 1px solid #f16172;
}

/*重写 tab end*/

</style>
<script type="text/javascript">
$(function() {
    $('.top .red-button-select').click(function() {
        $('.top .red-button-select').addClass('red-button-default');
        $(this).removeClass('red-button-default');
    });

    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        var is_my_tab = '#tab_myevents'==$(e.target).attr('href');
        var is_end_tab = '#tab_end'==$(e.target).attr('href');
        if((is_my_tab && '' == document.getElementById("my_event_content").innerHTML)
            ||(is_end_tab && ''== document.getElementById("end_event_content").innerHTML)){
                var _url = is_my_tab ? '/event/get_my_events' : '/event/get_end_events';
                var _tips = is_my_tab ? 'tips_nothing_my' : 'tips_nothing_end';
                var _target = is_my_tab ? 'my_event_content' : 'end_event_content';
                $.getJSON(_url,function(res){
                    if(0==res['flag']){
                        $('#'+_tips).show();
                    }
                    else{
                        $('#'+_tips).hide();
                        var tmp = "";
                        $(res['events']).each(function(index,event){
                            console.log(event);
                            tmp +="<div class='event-main-cell' onclick=\"eventNavigating('"+event['id']+"','"+event['link']+"')\"";
                            if(1 == index%2){
                                tmp += " style='margin-left: 0.35%;'";
                            }
                            tmp += ">";
                            tmp += "<img src='"+event['subject_banner_list']+"' />";
                            tmp += "<div class='event-main-text-container'>";
                            tmp += "<h2>"+event['subject']+"</h2>";
                            if(event['link']!=null && event['link']!="" && event['link']!="null"){
                                tmp += "<p style='margin-top: 20px;'>奖品："+event['award']+"</p>";
                                tmp += "<p style='margin-bottom: 30px'>已投稿："+event['product_count']+"</p>";
                                if(event['dayLeft']>=0){
                                    tmp += "<p style='background-color: black;display: inline; padding: 3px 5px 3px 5px; margin-top: 30px;'>距结束时间：<span style='color:#f16172'>"+event['dayLeft']+"天</span></p>";
                                }
                                else{
                                    tmp += "<div class='btncon' style='margin:50px auto 0 auto; background-color: grey'>已结束</div>";
                                }
                            }
                            tmp += "</div></div> ";
                        });
                        $('#'+_target).html(tmp);
                    }
                });
            }
    });
});

function eventNavigating(id, link){
    if(link!=null && link!="" && link!="null"){
      window.open(link,'_blank');
    }
    else{
      location.href='/event/event/id/'+id;
    }
}

</script>
<div class="event-main">
    <div class="top" style="width: 360px">
        <ul id="myTab" class="nav nav-tabs">
            <li class="active"><a href="#tab_processing" data-toggle="tab">正在进行</a></li>
            <li><a href="#tab_myevents" data-toggle="tab">我的参赛</a></li>
            <li><a href="#tab_end" data-toggle="tab">已结束</a></li>
        </ul>
    </div>
</div>
<div style="color: white;overflow: hidden; margin-bottom: 20px;min-height: 400px;padding-top: 10px;">
    <div id="myTabContent" class="tab-content">
        <div class="tab-pane fade in active" id="tab_processing">
            <div class="event-main-list-container">
                <?php if(is_array($event_list_processing)): $k = 0; $__LIST__ = $event_list_processing;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><div class="event-main-cell" onclick="eventNavigating('<?php echo ($vo["id"]); ?>','<?php echo ($vo["link"]); ?>')" <?php if($k%2 == 0): ?>style="margin-left: 0.35%;"<?php endif; ?> >
                        <img src="<?php echo ($vo["subject_banner_list"]); ?>" />
                        <div class="event-main-text-container">
                            <h2><?php echo ($vo["subject"]); ?></h2>
                            <?php if($vo["link"] ==null || $vo["link"] ==''): ?><p style="margin-top: 20px;">奖品：<?php echo ($vo["award"]); ?></p>
                            <p style="margin-bottom: 30px">已投稿：<?php echo ($vo["product_count"]); ?></p>
                            <p style="background-color: black;display: inline; padding: 3px 5px 3px 5px; margin-top: 30px;">距结束时间：<span style="color:#f16172"><?php echo ($vo["dayLeft"]); ?>天</span></p><?php endif; ?>
                        </div>
                    </div><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        </div>
        <div class="tab-pane fade in active" id="tab_myevents">
            <div class="center-block" id='tips_nothing_my' style="color: #f16172; margin: 50px 0 0 200px;display: none;">暂无相关数据</div>
            <div class="event-main-list-container" id="my_event_content"></div>
        </div>
        <div class="tab-pane fade in active" id="tab_end">
            <div class="center-block" id='tips_nothing_end' style="color: #f16172; margin: 50px 0 0 200px;display: none;">暂无相关数据</div>
            <div class="event-main-list-container" id="end_event_content"></div>
        </div>
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

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-height:364px; max-width:582px; width:582px; height:364px; margin-top:185px;">
        <div class="modal-content" style=" width:100%; height:100%">
            <div class="modal-header" style="border:0px">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            </div>
            <div id="threg">
                <div class="modal-body">
                    <div class="mod2">
                        <div class="center mod2151"> <img src="/static/images/logocol.png" width="150px"> </div>
                        <div class="mod21">
                            <h5>您将成为探图网第<?php echo ($data["count"]); ?>名用户</h5>
                        </div>
                        <div class="clearfix mod22a">
                            <div class="form-group">
                                <label for="regmobile" class="col-sm-4 control-label">用户名:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="regmobile" placeholder="邮箱/手机/用户名" style="border-radius:0;background:#eaeaea;border:#c3c3c3;">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="regpassword" class="col-sm-4 control-label">密码:</label>
                                <div class="col-sm-8">
                                    <input type="password" class="form-control" onkeydown="toreg();" id="regpassword" placeholder="设置密码" style="border-radius:0;background:#eaeaea;border:#c3c3c3;">
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
                            <div class="floatright">使用第三方登录：&nbsp;<a href="/static/qq.php?go=qqlogin"><img src="/static/images/qqlogin2.png" /></a></div>
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
        <div class="modal-content modwh100">
            <div class="modal-header modborder0">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            </div>
            <div class="modal-body">
                <div class="mod2">
                    <div class="center mod2151"> <img src="/static/images/logocol.png" width="151px" height="23"> </div>
                    <div class="mod21">
                        <h5>用户登录</h5>
                    </div>
                    <div class="clearfix mod22a">
                        <div class="">
                            <label for="mobile" class="col-sm-4 control-label">用户名:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="mobile" placeholder="输入用户名" style="border-radius:0;background:#eaeaea;border:#c3c3c3;">
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
                            <div class="checkbox " style="padding-left:36px;height: 30px;margin-top:0;margin-bottom:0;">
                                <label>
                                    <input type="checkbox"> 下次自动登录
                                </label>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="floatright">一键登录：&nbsp;<a href="/static/qq.php"><img src="/static/images/qqlogin2.png" /></a></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer clearfix mod3">
                <h6 class="float">完成此注册，即表明您同意了我们的</h6>
                <h6 class="float mod3col">使用条款和隐私策略</h6>
            </div>
        </div>
    </div>
</div>
<!-- /.carousel <script type="text/javascript" src="http://qzonestyle.gtimg.cn/qzone/openapi/qc_loader.js" data-appid="APPID" data-redirecturi="http://ttwww.bindow.cn/" charset="utf-8"></script>-->
<style>
.control-label {
    color: #999999;
    text-align: right;
    line-height: 34px;
}
</style>
<script>
$(function() {
    imglist('');
    $("img.lazy").lazyload({ effect: "fadeIn" });
});

function imglist(url) {
    var temp = '';
    url += '<?php echo ($data["sql"]); ?>';
    var i = 0;
    $.getJSON('/index/get_images?' + url, function(json) {
        if (!json.r) {
            $(".flex-images").html('<div style="margin:0 auto;padding:60px;">没有找到图片</div>');
        } else {
            if ($('.alls')) {
                $('.alls').html(json.total);
            }
            if ($('.fanye')) {
                $('.fanye').html(json.fanye);
            }
            $.each(json.r, function(index, data) {
                var width = data.width;
                var height = data.height;
                if (height > width) {

                    var w = ((width * 250) / height);
                    var h = 250;

                } else if (height == width) {
                    var w = 250;
                    var h = 250;
                } else {
                    var w = ((width * 250) / height);
                    var h = 250;
                }

                if (null == data.nickname || '' == data.nickname) {
                    data.nickname = "匿名";
                }

                temp += "<a href='/index/detail?id=" + data.id + "' target='_blank'><div class='item cell' data-w='" + width + "' data-h='" + height + "'><img class='lazy' src='" + data.imgurl + "' style='width:100%;height:100%;'><div class='discuz' style='position:absolute;bottom:0px;padding:5px 10px;width:100%;background:rgba(0,0,0,0.2);'><div style='float:left'><img src='/index/avatar_url/uid/" + data.uid + "' style='border-radius:12px;width:24px;height:24px;' />" + data.nickname + "</div><div style='float:right;margin-top:4px;'><img src='/static/images/iconheart.png' style='margin-top: -3px;width:13px;height:13px;' />&nbsp;" + data.likes + "&nbsp;&nbsp;<img src='/static/images/iconeye.png' style='margin-top: -3px;width:18px;height:13px;' />&nbsp;" + data.visits + "&nbsp;&nbsp;<img src='/static/images/iconstar.png' style='margin-top: -3px;width:13px;height:13px;' />&nbsp;" + data.fav + "&nbsp;&nbsp;</div><div style='clear:both'></div></div></div></a>"
                //temp+= "<a href='/index/detail?id="+data.id+"'><div class='cell' style='width:"+w+"px; height: "+h+"px; background-image: url("+data.imgurl+");color:#fff;'><div class='discuz' style='position:absolute;bottom:5px;right:10px;'><img src='/static/images/iconheart.png' /> "+data.downloads+" <img src='/static/images/iconeye.png' /> "+data.visits+"  <img src='/static/images/iconstar.png' /> "+data.fav+" </div></div></a>"
            });
            $(".flex-images").html(temp);
            $('.flex-images').flexImages({ 'rowHeight': 290, 'container': '.item', 'truncate': false });
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
            $('.cell').mouseover(function() {
                $(this).find('.discuz').show();
            }).mouseout(function() {
                $(this).find('.discuz').hide();
            });
        }
    });
}
// for scroll bar appear;
$(window).trigger("resize");
$(function() {
    $('.ula ul li').click(function() {
        var num = $(this).index();
        for (var i = 0; i < 8; i++) {
            $('.ula ul li').eq(i).children('img').attr("src", "/static/images/item" + i + ".png");
        }
        $(this).children('img').attr("src", "/static/images/item_c" + num + ".png");
        $(this).siblings().removeClass('ind_active');
        $(this).addClass('ind_active');
        imglist($(this).attr('data-url'));
    });
    $('.carousel-caption').fadeIn(1000);
    $('.go-top').click(function() {
        $('html, body').animate({ scrollTop: 0 }, 'slow');
        return false;
    });
})

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
    $.get("/index/loging?m1=" + mobile + "&p1=" + pass + "&remember=" + remember, function(data) {
        var arr = data.split(':');
        if (arr[0] == 1) location.reload();
        else alert(arr[1]);
    });
}

function reging() {
    var mobile = $('#regmobile').val();
    var pass = $('#regpassword').val();
    if (!mobile) {
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
    $.get("/index/reging?m1=" + mobile + "&p1=" + pass, function(data) {
        var arr = data.split(':');
        if (arr[0] == 1) window.open("/user/my");
        else alert(arr[1]);
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
</script>
<div style="display:none;">
    <script src="https://s95.cnzz.com/z_stat.php?id=1261057114&web_id=1261057114" language="JavaScript"></script>
</div>
</body>

</html>