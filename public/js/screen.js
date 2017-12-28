$(function(){
	'use strict';
	
	//	one page scroll

//	$('.nav li a').click(function () {
//		if(this.hash) {
//			var divlink = this.hash.substr(1),
//				$toElement = $("section[id= " + divlink + "]"),
//				toPosition = $toElement.position().top,
//				$clickedlink = $(".nav li a[href= '" + this.hash + "']"),
//				cparent = $clickedlink.closest('li');
//
//			$("body,html").animate({
//				scrollTop : toPosition
//			}, 1000, "easeOutExpo");
//
//			$('.nav li a').removeClass("active");
//			cparent.addClass("active");
//			return false;
//		}
//	});
//
//	if (location.hash) {
//		var loadedlink = location.hash;
//		window.scroll(0, 0);
//		$("a[href= '" + loadedlink + "']").click();
//	}
//
//	function onScroll(event) {
//		var scrollPos = $(document).scrollTop();
//		$('.nav li a').each(function () {
//			var currLink = $(this),
//				parent = currLink.closest('li'),
//				refElement = $(currLink.attr("href"));
//			if (refElement.position().top - 50 <= scrollPos && refElement.position().top - 50 + refElement.height() > scrollPos) {
//				$('.nav li').removeClass("active");
//				parent.addClass("active");
//			} else {
//				parent.removeClass("active");
//			}
//		});
//	}
//
//	$(document).on("scroll", onScroll);

	//	one page scroll
});