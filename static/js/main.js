
$(function(){
	/*$('.sexybox_iframe2').fancybox( {
		'transitionIn'		: 'elastic',
		'transitionOut'		: 'elastic',
		'type':'iframe',
		'scrolling': 'no',
		'width': 400,
		'height':250,
	});*/
	$('.menu_c').click(function(){
		$('.menu_list').toggle();								
	});
	$('.user_c').click(function(){
		$('.user_list').toggle();								
	});
});
var loading_div_w = 0;
var loading_div_h = 0;
var Loading = function(){
	this.show = function(){
		$('.loadbg').show();
		loading_div();
	}
	this.hide = function(){
		$('.loadbg').hide();
	}
}
var loading = new Loading();
$(window).resize(function(){
	loading_div();
});
$(window).scroll(function(){
	loading_div();
});
function loading_div(){
	if($('.loadbg')){
		$('.loadbg').css('height',0);
		$('.loadbg').css('height',$(document).height());
		var scrolltop = $(window).scrollTop();
		loading_div_w = $(window).width()/2-$('.loadbg div').width()/2;
		loading_div_h = $(window).height()/2-$('.loadbg div').height()/2+scrolltop;
		$('.loadbg div').css({'left':loading_div_w,'top':loading_div_h});
	}
}
/**
 * mdata保存、读取数据
 * @param String key  保存数据的key值
 * @param String value  保存的数据
 */
function mdata(key,val){
    var s = getLocVal('mdata');
    var r = '';
    if(s!='') var data = eval('('+s+')');
    else var data = {};
    if(val==undefined){
        var r = data[key];
        if(r==undefined || r==null) r='';
    }
    else if(val==null) eval("delete data."+key+";"); 
    else{
        data[key] = val;
        var jsondata = JSON.stringify(data);
        setLocVal('mdata',jsondata);
    }
    return r;
}
/**
 * localStorage保存数据
 * @param String key  保存数据的key值
 * @param String value  保存的数据
 */
function setLocVal(key,value){
    window.localStorage[key] = value;
}
/**
 * 根据key取localStorage的值
 * @param Stirng key 保存的key值
 */
function getLocVal(key){
    if(window.localStorage[key])
        return window.localStorage[key];
    else
        return "";
}