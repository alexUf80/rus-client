;function LoginApp()
{
    var app = this;
    
    app.DEBUG = 1;
    
    app.$phone;
    
    var _init = function(){
        
        app.$phone = $('.js-login-phone');
        app.$phone_error = $('.js-login-phone-error');
        
        app.$phone.mask("+7(999) 999-9999");

        
        $('.js-login-start').click(function(e){
            e.preventDefault();
            
            var $btn = $(this);
            
            if ($btn.hasClass('loaded'))
                return false;
            
            var _phone = app.$phone.val();
            
            if (_phone == '')
            {
                app.$phone.closest('.form-phone').addClass('-error');
                app.$phone_error.text('Укажите номер телефона').show();
                app.$phone.focus();
            }
            else
            {
                app.$phone.closest('.form-phone').removeClass('-error');
                app.$phone_error.text('').hide();

                $.ajax({
                    url: 'ajax/check_phone.php',
                    data: {
                        phone: _phone
                    },
                    beforeSend: function(){
                        $btn.addClass('loading');
                    },
                    success: function(resp){
                        if (!!app.DEBUG)
                            console.log(resp);

                        if (!!resp.user_removed)
                        {
                            $btn.removeClass('loading');
console.log('removed')
                            var _msg = 'Пользователь с телефоном '+_phone+' не найден.<br /><br />';
                            _msg += 'Проверьте правильность ввода номера телефона  <a href="/"></a> '
                            $('#error_modal .error-message').html(_msg);
                            $.fancybox.open({
                                src: '#error_modal'
                            })

                        }
                        else if (!!resp.have_pass)
                        {
                            $('#phone_block').hide();
                            $('#password_block').fadeIn();
                        }
                        else if (!!resp.user_exists)
                        {
                            // отправляем смс
                            new SmsApp(_phone, _success_callback);
                        }
                        else if (!!resp.incorrect)
                        {
                            $btn.removeClass('loading');
                            app.$phone.closest('.form-phone').addClass('-error');
                            app.$phone_error.text('Проверьте правильность номера').show();
                            app.$phone.focus();
                        }
                        else if (!!resp.not_found)
                        {                            
                            $btn.removeClass('loading');
                            var _msg = 'Пользователь с телефоном '+_phone+' на сайте не зарегистрирован.<br /><br />';
                            _msg += 'Проверьте правильность ввода номера телефона или <a href="/">заполните короткую анкету</a> для получения займа'
                            $('#error_modal .error-message').html(_msg);
                            $.fancybox.open({
                                src: '#error_modal'
                            })

                        }
                        else if (!!resp.error)
                        {
                            $btn.removeClass('loading');
                            $('#error_modal .error-message').html(resp.error);
                            $.fancybox.open({
                                src: '#error_modal'
                            });
                        }
                        else
                        {
                            $btn.removeClass('loading');
                            $('#error_modal .error-message').html('Нет ответа от сервера');
                            $.fancybox.open({
                                src: '#error_modal'
                            });
                        }
                    }
                });
            }
        });
        
    };
    
    var _success_callback = function(code){
console.info('_success_callback');
        $('.js-login-code').val(code);
        $('.js-login-form').submit();
    };
    
    var _init_login_password = function(){
        $('.js-login-password').click(function(e){
            e.preventDefault();
            
            if ($('#password').val() == '')
            {
                $('.form-password').addClass('-error');
                $('.js-login-password-error').fadeIn()
            }
            else
            {
                $('.form-password').removeClass('-error');
                $('.js-login-password-error').hide();
                $('.js-login-form').submit();
            }
            
            
        });
    };
    
    ;(function(){
        _init();
        _init_login_password();
        app.$phone.focus();
    })();
}

$(function(){
    if ($('.js-login-form').length > 0)
        new LoginApp();
});