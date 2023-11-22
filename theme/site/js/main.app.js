;

function MainApp() {
    var app = this; 

    app.DEBUG = 1;

    app.$phone;

    var _init = function () {

        app.$phone = $('.js-loan-phone');
        app.$phone_error = $('.js-loan-phone-error');

        app.$phone.mask("+7(999) 999-9999");

        $('.js-loan-start').click(function (e) {
            e.preventDefault();

            var $btn = $(this);

            var _phone = app.$phone.val();

            var _agreement = $('.js-loan-agreement').is(':checked');

            var _error = 0;
            if (_phone == '') {
                app.$phone.closest('.form-phone').addClass('-error');
                app.$phone_error.text('Укажите номер телефона').show();
                app.$phone.focus();

                _error = 1;
            }
            else {
                app.$phone.closest('.form-phone').removeClass('-error');
                app.$phone_error.text('').hide();
            }

            $('.js-need-check').each(function () {
                if (!$(this).is(':checked')) {

                    if($(this).attr('id') != 'service_reason' || $(this).attr('id') != 'service_insurance')
                        _error = 1;

                    $(this).closest('.check').addClass('-error');
                }
                else {
                    $(this).closest('.check').removeClass('-error');
                }
            });


            if (!_agreement) {
                $('.js-loan-agreement-block').addClass('-error');

                _error = 1;
            }
            else {
                $('.js-loan-agreement-block').removeClass('-error');
            }

            if (!_error) {

                $.ajax({
                    url: 'ajax/check_phone.php',
                    data: {
                        phone: _phone
                    },
                    beforeSend: function () {
                        $btn.addClass('loading');
                    },
                    success: function (resp) {
                        if (!!app.DEBUG)
                            console.log(resp);

                        if (!!resp.user_exists) {
                            $btn.removeClass('loading');
                            $('.js-error-phone-number').text(_phone);
                            $.fancybox.open({
                                src: '#user_exists_modal'
                            });
                        }
                        else if (!!resp.incorrect) {
                            $btn.removeClass('loading');
                            app.$phone.closest('.form-phone').addClass('-error');
                            app.$phone_error.text('Проверьте правильность номера').show();
                            app.$phone.focus();
                        }
                        else if (!!resp.not_found) {

                            $('[name="service_insurance"]').val();
                            $('[name="service_reason"]').val();
                            setTimeout(function () {
                                // отправляем смс
                                new SmsApp(_phone, _success_callback);
                            }, 50);
                        }
                        else if (!!resp.error) {
                            $btn.removeClass('loading');
                            $('#error_modal .error-message').html(resp.error);
                            $.fancybox.open({
                                src: '#error_modal'
                            });
                        }
                        else {
                            console.error(resp);
                        }
                    }
                });
            }
        });

    };

    var _success_callback = function (code) {
        //alert('проверка3');
        console.info('_success_callback');
        $('.js-loan-code').val(code);
        $('.js-loan-start-form').submit();
    };


    var _init_agreement_list = function () {
        $('.js-toggle-agreement-list').click(function (e) {
            e.preventDefault();

            $('#agreement_list').slideToggle()
        })
    }

    var checkAgreementList = function () {

        $('#check_agreement').on('click', function () {
            if ($(this).is(':checked')) {
                $('input[type="checkbox"]').each(function () {
                    var is_checked = $(this).prop('checked');
                    // if($(this).attr('id') == 'service_reason' || $(this).attr('id') == 'service_sms' || $(this).attr('id') == 'service_insurance' ){
                    //     $(this).prop('checked', is_checked);
                    // }
                    // else{
                        $(this).prop('checked', true);
                        $(this).val(1);
                    // }
                });
                $('.check .custom-checkbox').each(function () {
                    $(this).val(1);
                });
            } else {
                $('input[type="checkbox"]').each(function () {
                    $(this).prop('checked', false);
                    $(this).val(0);
                });
                $('.check .custom-checkbox').each(function () {
                    $(this).val(0);
                });
            }

        });

        $('.js-need-check').on('click', function () {
            var checked_count = 0;
            var total_cou = 0;
            $('.js-need-check').each(function () {
                total_cou++;
                if ($(this).prop('checked') == true) {
                    checked_count++;
                }
            });
            if (total_cou == checked_count) {
                $('#check_agreement').prop('checked', true);
            }
            else{
                $('#check_agreement').prop('checked', false);
            }
        });
    };

    var _init_toggle_services = function () {
            $('.js-loan-phone').blur(function () {

                console.log($(this).val()) //'79171018924', '79179400617'

            });

            $('#service_reason').change(function () {
                if ($(this).is(':checked'))
                    $('[name="service_reason"]').val(1);
                else
                    $('[name="service_reason"]').val(0);
            });
            $('#service_insurance').change(function () {
                if ($(this).is(':checked'))
                    $('[name="service_insurance"]').val(1);
                else
                    $('[name="service_insurance"]').val(0);
            });
            $('#service_sms').change(function () {
                if ($(this).is(':checked'))
                    $('[name="service_sms"]').val(1);
                else
                    $('[name="service_sms"]').val(0);
            });
        }

    ;(function () {
        _init();
        _init_agreement_list();
        _init_toggle_services();
        checkAgreementList();
    })();
};

$(function () {
    if ($('.js-loan-start-form').length > 0){
        new MainApp();
    }
        
});
