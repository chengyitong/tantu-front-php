/**
 * @package Xslider - A slider plugin for jQuery
 * @version 0.5
 * @author xhowhy <http://x1989.com> 
 	$('.slider').each(function(){
		$(this).Xslider({
			affect:'none',
			ctag: 'div',
			widths: $(this).width(),
			space: $(this).data('speed'),
			hoverpause: false
		});
	});
 **/
;(function($){
	$.fn.Xslider = function(options){var settings ={
			affect: 'scrollx', //��  �crollx|scrolly|fade|none
			speed: 1200, //�函�漲
			space: 6000, //�園�湧�
			auto: true, //�芸皛
			trigger: 'mouseover', //閫血�鈭辣 瘜冽��皂ouseover隞�hover
			conbox: '.conbox', //�捆摰孵id�lass
			ctag: 'a', //�捆�倌 暺恕銝�<a>
			switcher: '.switcher', //�閫血��甬d�lass
			stag: 'a', //��冽�蝑� 暺恕銝榮
			current:'cur', //敶���冽撘�蝘�
			rand:false, //�臬���暺恕撟餌�曄�
			widths: 0,
			hoverpause: true,
		};
		settings = $.extend({}, settings, options);
		var index = 1;
		var last_index = 0;
		var $conbox = $(this).find(settings.conbox),$contents = $conbox.find(settings.ctag);
		var $switcher = $(this).find(settings.switcher),$stag = $switcher.find(settings.stag);
		var widths = settings.widths;
		if(settings.rand) {index = Math.floor(Math.random()*$contents.length);slide();}
		if(settings.affect == 'fade'){$.each($contents,function(k, v){(k === 0) ? $(this).css({'position':'absolute','z-index':9,'width':widths}):$(this).css({'position':'absolute','z-index':1,'opacity':0,'width':widths});
			});
		}
		// set width
		$contents.css({'width':widths}).eq(0).css({'width':widths});
		function slide(){if (index >= $contents.length) index = 0;
			$stag.removeClass(settings.current).eq(index).addClass(settings.current);
			switch(settings.affect){case 'scrollx':
					$conbox.width($contents.length*$contents.width());
					$conbox.stop().animate({left:-$contents.width()*index},settings.speed);
					break;
				case 'scrolly':
					$contents.css({display:'block'});
					$conbox.stop().animate({top:-$contents.height()*index+'px'},settings.speed);
					break;
				case 'fade':
					$contents.eq(last_index).stop().animate({'opacity': 0}, settings.speed/2).css('z-index',1)
							 .end()
							 .eq(index).css('z-index',9).stop().animate({'opacity': 1}, settings.speed/2)
					break;
				case 'none':
					//$contents.hide().eq(index).show();
					$contents.css({'display':'none','width':widths}).eq(index).css({'display':'block','width':widths});
					break;
			}
			last_index = index;
			index++;
		};
		if(settings.auto) var Timer = setInterval(slide, settings.space);
		$stag.bind(settings.trigger,function(){_pause()
			index = $(this).index();
			slide();
			_continue()
		});
		if(settings.hoverpause) $conbox.hover(_pause,_continue);
		function _pause(){
			clearInterval(Timer);
		}
		function _continue(){
			if(settings.auto)Timer = setInterval(slide, settings.space);
		}	
	}
})(jQuery);