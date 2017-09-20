//  智能输入数字
$(function(){
	$('#uid').focus();
});
function onKeyPressBlockNumbers(e)
{
	var key = window.event ? e.keyCode:e.which;
	var keychar = String.fromCharCode(key);
	reg = /\d/;
	return !reg.test(keychar);
}
function onKeypress(e){
	if(typeof (window.event) == 'undefined') 
		key = e.which;
	else
		key = window.event.keyCode;
	return key;
}
function closeWindows(){
	if(confirm("确定退出系统？")){
		window.opener = null;
		window.open("","_self","");
		window.close();
	}
}
function topwd(e){
	var key;
	key = onKeypress(e);
	if (key==13) {
		if($('#uid').val()!="") $('#pwd').focus();
	}
}
function tochk(e){
	var key;
	key = onKeypress(e);
	if (key==13) {
		if($('#pwd').value!="") chk();
	}
}
function chk(){
	if($('#uid').val()==""){
		alert("用户名不得为空!");
		$('#uid').focus();
		return false;
	}
	if($('#pwd').val()==""){
		alert("密码不得为空!");
		$('#pwd').focus();
		return false;
	}
	pwd = hex_md5($('#pwd').val());
	$('#pwd').val(pwd);
	$('#form1').submit();
	return;
}
