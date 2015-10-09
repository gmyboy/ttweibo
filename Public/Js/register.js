$(function() {
    //点击刷新验证码
    var verifyUrl = $('#verify-img').attr('src');
    $('#verify-img').click(function() {
        $(this).attr('src', verifyUrl + '/' + Math.random());
    });
    //jQuery Validate 表单验证
    /**
     * 添加验证方法
     * 以字母开头，5-17 字母、数字、下划线"_"
     */
    jQuery.validator.addMethod("userAccount", function(value, element) {
        var tel = /^[a-zA-Z][\w]{4,16}$/;
        return this.optional(element) || (tel.test(value));
    }, "以字母开头，5-17 字母、数字、下划线'_'");
    /**
     * 添加自动验证邮箱格式
     */
    jQuery.validator.addMethod("userEmail", function(value, element) {
        var tel = /^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/;
        return this.optional(element) || (tel.test(value));
    }, "填写正确格式的邮箱");

    //给表单添加validate 验证
    $('form[name=register]').validate({
        errorElement: 'span',
        success: function(label) {
            label.addClass('success');
        },
        rules: {
            account: {
                required: true,
                userAccount: true,
                remote: {
                    url: checkAccount,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        account: function() {
                            return $('#account').val();
                        }
                    }
                }
            },
            pwd: {
                required: true,
                userAccount: true
            },
            pwded: {
                required: true,
                equalTo: "#pwd"
            },
            email: {
                required: true,
                userEmail: true
            },
            uname: {
                required: true,
                rangelength: [2, 10],
                remote: {
                    url: checkUname,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        uname: function() {
                            return $('#uname').val();
                        }
                    }
                }
            },
            verify: {
                required: true,
                remote: {
                    url: checkVerify,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        verify: function() {
                            return $('#verify').val();
                        }
                    }
                }
            }
        },
        messages: {
            account: {
                required: '账号不能为空',
                remote: '账号已存在'
            },
            pwd: {
                required: '密码不能为空'
            },
            pwded: {
                required: '请确认密码',
                equalTo: '两次密码不一致'
            },
            email: {
                required: '安全邮箱不能为空'
            },
            uname: {
                required: '请填写您的昵称',
                rangelength: '昵称在2-10个字之间',
                remote: '昵称已存在'
            },
            verify: {
                required: ' ',
                remote: ' '
            }
        }
    });
});