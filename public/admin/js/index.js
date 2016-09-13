;var index = {
	init:function(){
		this.listnerBinder()
	},
	listnerBinder:function(){
		//双击关闭
		$(document).on('dblclick', '.J_menuTab', function(event) {
			if ($(this).attr('data-type')=='home') return false;
			$(this).find('.fa-times-circle').click()
		});
        $('.change-lang').click(function(){
            var lang = $(this).data('lang');
            $.cookie('site_lang', lang,{ path: "/"}); // 存储 cookie
            window.location.reload()
        });		

	},
}
index.init()