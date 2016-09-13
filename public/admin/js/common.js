;var common = {
	init:function(){
		this.listnerBinder();
	},
    /*公用事件*/
    listnerBinder:function(){
        $(document).on('click','.btn-toggle',function(e){
            $(this).addClass('active').siblings('.btn-toggle').removeClass('active')
        });
        $(document).on('keyup', '.numberInput,.number-input', function(e) {
            var key=window.event?e.keyCode:e.which;
            if (key == 8 || key == 9 || key == 46 || key == 37 || key == 39 ) {
                return;
            }//释放方向键
            this.value = this.value.replace(/\D/g,'');
        });//数字输入
        $(document).on('click','.close-layer',function(){
            layer.closeAll();
        })
        $(document).on('keydown',function(e){
            if (e.keyCode==116) {
                e.preventDefault()
                if (self==top) {
                    if (document.querySelectorAll('iframe').length==0) {
                        window.location.reload();
                        return false;
                    }
                    var index = $(".J_menuTab.active").index()
                    var src = $('.J_mainContent iframe').eq(index).attr("src") 
                    $('.J_mainContent iframe').eq(index).attr("src",src);

                }else{
                    var index = $(parent.document.querySelector('.J_menuTab.active')).index();
                    var dom =  $(parent.document.querySelectorAll('.J_mainContent iframe')[index])
                    dom.attr("src",dom.attr("src"))
                }
            }
        });
        $(document).on('focus','.domain-input',function(){
            if ($(this).val()!="") return false;
            $(this).val("http://")
        })
    },
	/*公用正则表达式*/
	commonReg: {
        email: /^([a-zA-Z0-9]+[_|\_|\.|-]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/,//邮件正则
        number: /^\d+$/,//数字正则
        phone: /^(13|14|15|18|17)[0-9]{9}$/,
        telphone:/^0\d{2,3}-?\d{8}$/,
        authCode: /^[0-9a-zA-Z]{4}$/,  //图形验证码
        phoneAuthCode: /^[0-9a-zA-Z]{6}$/,  //手机验证码
        pass: /^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,20}$/,//密码正则
        name: /^[\u4E00-\u9FA5]{2,5}$/,//姓名正则
        identify: /^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/,//身份证正则
        bankCard: /^[0-9]{4,40}$/,//银行卡正则
        validTime: /^[1-9]\d{3}((0\d)|(1[0-2]))$/,
        safe: /[~#$￥ˇ<>（）\s\?\*\&\\\|\/\[\]\{\}\\^%]/,
        decimalLimit:/^\d+(?:\.\d{2})?$/,//两位小数
        negativeDecimalLimit:/^(-|\d)+(?:\.\d{2})?$/,//负数两位金额
        originFlowNo:/^[0-9]{22}[\-]{1}[0-9]{3}[\-]{1}[0-9]{2}$/
    },
    /*
    	检查是否匹配正则 参数 待检查的值 正则名
    	返回值 true||false
     */
    check: function(val, reg) {
        return this.commonReg[reg].test(val);
    },
    checkForm:function(selector){
        var dom = typeof(selector)=="string"?$(selector):selector
    },
    ajaxError:function(e){
        var error = e.message;
        if (!e.message) {
            switch (e.status) {
                case 200:
                    error = "200 No response"
                    break;
                case 404:
                    error = "404 Not Found"
                    break;
                case 500:
                    error = "500 Unexpected server error"
                    break;
                default:
                    error = e.status+" error,please try again."
                    break;
            }
        }
        layer.msg(error,{
            icon:2
        })
    }
}
window.onload = function(){
	common.init();
}