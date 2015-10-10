</body>
<script type="text/javascript">
(function() {
	if ( $.browser.msie && $.browser.version < 8)
	{
		$('#browserType').html('<strong>您当前使用的是IE浏览器'+$.browser.version+'版本，建议使用高版本IE、Firefox或Chrome获得更佳体验！</strong>')
		$('#browserAlert').css('display','block');		
	}
	
	var $backToTopTxt = "", $backToTopEle = $('<div class="backToTop"></div>').appendTo($("body"))
	.text($backToTopTxt).attr("title", $backToTopTxt).click(function() {
		$("html, body").animate({ scrollTop: 0 }, 120);
	}), $backToTopFun = function() {
		var st = $(document).scrollTop(), winh = $(window).height();
		(st > 0)? $backToTopEle.show(): $backToTopEle.hide();
		//IE6下的定位
		if (!window.XMLHttpRequest) {
			$backToTopEle.css("top", st + winh - 166);
		}
	};
	$(window).bind("scroll", $backToTopFun);
	$(function() { $backToTopFun(); });
	
	var curNavName = $("#controlTop").val();
	$("#"+curNavName).addClass("active").siblings().removeClass("active");
})();
</script>
</html>