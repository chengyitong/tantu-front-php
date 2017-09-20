<?
function loadplug_fancybox(){
return '<script type="text/javascript" src="/static/plugs/fancybox/bd.easing-1.3.pack.js"></script>
<script type="text/javascript" src="/static/plugs/fancybox/bd.fancybox-1.3.4.pack.js"></script>
<script type="text/javascript" src="/static/plugs/fancybox/bd.mousewheel-3.0.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="/static/plugs/fancybox/bd.fancybox-1.3.4.css" />
<script>
$(function(){
	$(".sexybox_img").fancybox( {
		"transitionIn"		: "elastic",
		"transitionOut"		: "elastic",
		"titlePosition" 	: "over",
		"type":"image"
	})
	.css("cursor","pointer");
	$(".sexybox_div").fancybox( {
		"onComplete" : function(){
			$("#fancybox-overlay").height($(document).height());
			if(typeof fancybox_open === "function") fancybox_open();
		},
		"onClosed" : function(){
			if(typeof fancybox_close === "function") fancybox_close();
		}
	})
	.css("cursor","pointer");
	$(".sexybox_iframe").fancybox( {
		"transitionIn"		: "elastic",
		"transitionOut"		: "elastic",
		"type":"iframe",
		"scrolling": "auto",
		"onComplete" : function(){
			$("#fancybox-overlay").height($(document).height());
			if(typeof fancybox_open === "function") fancybox_open();
		},
		"onClosed" : function(){
			if(typeof fancybox_close === "function") fancybox_close();
		}
	})
	.css("cursor","pointer");
});
</script>
';
}
function loadplug_tooltip(){
return '<script type="text/javascript" src="/static/plugs/tipsy/jquery.tipsy.js"></script>
<link rel="stylesheet" type="text/css" href="/static/plugs/tipsy/tipsy.css" />
<script>
$(function() {
$(".tooltip_e").tipsy( { opacity: 1, gravity: "w" , fade : true, live: true, html: true } );
$(".tooltip_w").tipsy( { opacity: 1, gravity: "e" , fade : true, live: true, html: true } );
$(".tooltip_s").tipsy( { opacity: 1, gravity: "n" , fade : true, live: true, html: true } );
$(".tooltip_n").tipsy( { opacity: 1, gravity: "s" , fade : true, live: true, html: true } );
});
</script>
';
}
function loadplug_wdate(){
return '<script type="text/javascript" src="/static/plugs/My97DatePicker/WdatePicker.js"></script>
';
}
function loadplug_jgrowl(){
return '<script type="text/javascript" src="/static/plugs/jgrowl/jquery.jgrowl.js"></script>
<script type="text/javascript" src="/static/plugs/jgrowl/jquery.jgrowl_minimized.js"></script>
<script type="text/javascript" src="/static/plugs/jgrowl/jquery.jgrowl_google.js"></script>
<link rel="stylesheet" type="text/css" href="/static/plugs/jgrowl/jquery.jgrowl.css" />
';
}
function loadplug_editor($obj,$w,$h){
$w = $w ? $w : '100%';
$h = $h ? $h : '200px';
return '<script charset="utf-8" src="/static/plugs/kindeditor/kindeditor-min.js"></script>
<script charset="utf-8" src="/static/plugs/kindeditor/lang/zh_CN.js"></script>
<script>
KindEditor.ready(function(K) {
	var editor = K.create("textarea[name=\''.$obj.'\']", {
		resizeType : 1,
		//pasteType : 1,
		syncType : "form",
		width: "'.$w.'",
		height: "'.$h.'",
		newlineTag : "br",
		allowPreviewEmoticons : false,
		uploadJson : "/static/plugs/kindeditor/php/upload_json.php",
		fileManagerJson : "/static/plugs/kindeditor/php/file_manager_json.php",
		allowFileManager : true,
		afterBlur: function(){this.sync();}
		//items : [
		//	"fontname", "fontsize", "|", "textcolor", "bgcolor", "bold", "italic", "underline",
		//	"removeformat", "|", "justifyleft", "justifycenter", "justifyright", "|", "emoticons", "image", "media", "source"]
	});
});
</script>';
}
//  code editor
function loadplug_editarea($obj,$ftype,$h){
$h = $h ? $h : '475';
return '<script language="Javascript" type="text/javascript" src="/static/plugs/editarea/edit_area_full.js"></script>
<script language="Javascript" type="text/javascript">
	// initialisation
	editAreaLoader.init({
		id: "'.$obj.'"	// id of the textarea to transform		
		,start_highlight: true	// if start with highlight
		,allow_resize: "both"
		,allow_toggle: false
		,word_wrap: true
		,plugins: "charmap"
		,charmap_default: "arrows"
		,language: "zh"
		,toolbar: "save, search, go_to_line, undo, redo, |, select_font, charmap, change_smooth_selection, highlight, reset_highlight, word_wrap, |, help"
		//new_document, load , syntax_selection, |
		,syntax_selection_allow: "css,html,js,php,python,vb,xml,c,cpp,sql,basic,pas,brainfuck"
		,syntax: "'.$ftype.'"
		//,load_callback: "my_load"
		,save_callback: "my_save"
		,min_height: '.$h.'
	});
</script>';
}