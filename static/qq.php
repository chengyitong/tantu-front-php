<?php
session_start();
if(isset($_GET['go']) && $_GET['go']!='') $_SESSION['qq_go'] = $_GET['go'];
//if($_GET['go']!='') setcookie('qq_go',$_GET['go'],time()+3600);
//if(!isset($_SESSION["qq_go"])) die('请先开启浏览器cookie功能！');
?>
<html>
     <head>
        <title>QQ登录跳转</title>
        <script src="http://lib.sinaapp.com/js/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
        
        <script type="text/javascript">
        
        //切割字符串转换参数表
        function toParamMap(str){
             var map = {};
             var segs = str.split("&");
             for(var i in segs){
                 var seg = segs[i];
                 var idx = seg.indexOf('=');
                 if(idx < 0){
                     continue;
                 }
                 var name = seg.substring(0, idx);
                 var value = seg.substring(idx+1);
                 map[name] = value;
             }
             return map;
         }
        
        //隐式获取url响应内容(JSONP)
        function openImplict(url){
            var script = document.createElement('script');
            script.src = url;
            document.body.appendChild(script);        
        }
        
        //获得openid的回调
        function callback(obj)
        {
           var openid = obj.openid;
           $("#openid").val(openid);
           
           //跳转服务端登录url
           var resulturl = "/index/"+getQueryString('go'); 
           var accessToken = $("#accessToken").val();
           
           //向服务端传输access_token及openid参数
           document.location.href=resulturl + "?access_token=" + accessToken + "&openid=" + openid;
        }

        function getQueryString(name) {
            var reg = new RegExp('(^|&)' + name + '=([^&]*)(&|$)', 'i');
            var r = window.location.search.substr(1).match(reg);
            if (r != null) {
                return unescape(r[2]);
            }
            return '';
        }
        
        
        </script>
     </head>
     
     <body>
     <input type="hidden" id="accessToken" value="" />
     <input type="hidden" id="expire" value="" />
     <input type="hidden" id="openid" value="" />
     <!-- 执行脚本 -->
     <script type="text/javascript">
     
     //应用的APPID
     var appID = "101362993";     

     //登录授权后的回调地址，设置为当前url
     var redirectURI = "http://www.tantupix.com/static/qq.php?go="+getQueryString('go');

     //初始构造请求
     if (window.location.hash.length == 0)
     {
        var path = 'https://graph.qq.com/oauth2.0/authorize?response_type=token&state=<?=md5(uniqid(rand(), TRUE));?>&';
        var queryParams = ['client_id=' + appID,
                           'redirect_uri=' + redirectURI,
                           'scope=' + 'get_user_info'];

        var query = queryParams.join('&');
        var url = path + query;
        window.location.href= url;
     }
     //在成功授权后回调时location.hash将带有access_token信息，开始获取openid
     else
     {
        //获取access token
        var accessToken = window.location.hash.substring(1);
        var map = toParamMap(accessToken);
        
        //记录accessToken
        $("#accessToken").val(map.access_token);
        $("#expire").val(map.expires_in);
        
        //使用Access Token来获取用户的OpenID
        var path = "https://graph.qq.com/oauth2.0/me?";
        var queryParams = ['access_token='+map.access_token, 'callback=callback'];
        var query = queryParams.join('&');
        var url = path + query;
        openImplict(url);
     }
     
     </script>
     </body>
</html>