;

function LkApp() {
    var app = this;

    app.order_updater;
    app.code;
    app.phone;

    var _init = function () {

        if ($('.js-check-status').length > 0)
            _run_check_status();

    };

    $('.js-prolongation-form').submit(function (e) {
        // alert('ворк');

        if ($(this).find('[name=code]').val() == '') {
            e.preventDefault();

            var _phone = $(this).find('[name=phone]').val();
            var user_id = $(this).attr('data-user');
            var contract_id = $(this).attr('data-contract');
            let amount = $(this).find('input[name="amount"]').val();

            new SmsApp(_phone, _prolongation_success_callback, {
                button_name: 'Пролонгация договора',
                user_id: user_id,
                contract_id: contract_id,
                amount: amount,
                modal: false,
                checkbox: true
            });
        }
    });

    var _prolongation_success_callback = function (code) {
        $('.js-prolongation-form [name=code]').val(code);

        $.ajax({
            url: 'ajax/ProlongationSum.php',
            data: {
                action: 'check_prolongation_sum'
            },
            dataType: 'JSON',
            success: function (resp) {
                if ($('#service_polise').is(':checked')) {
                    let sum = $('.js-prolongation-form').find('input[name="amount"]').val();
                    sum = Number(sum) + Number(resp['sum']);
                    $('.js-prolongation-form').find('input[name="amount"]').removeAttr('value');
                    $('.js-prolongation-form').find('input[name="amount"]').val(sum);
                    $('.js-prolongation-form').find('input[name="amount"]').attr('min', sum);

                }

                $('.js-prolongation-form').submit();
            }
        });
    }

    var _run_check_status = function () {

        var _order_id = $('.js-check-status').data('order');
        var _status = $('.js-check-status').data('status');

        app.order_updater = setInterval(function () {
            $.ajax({
                url: 'ajax/check_order.php',
                data: {
                    order_id: _order_id,
                    status: _status
                },
                success: function (resp) {
                    if (!!resp.error) {
                        clearInterval(app.order_updater);
                        console.error(resp.error);
                    }
                    else {
                        if (!!resp.reload)
                            location.reload();
                    }
                }
            })
        }, 10000);
    };

    var _redirect_to_partner = function () {

        let orderId = $('.new_order_box').attr('data-order');

        $.ajax({
            url: 'ajax/CheckStatus.php',
            method: 'POST',
            data: {
                orderId: orderId
            },
            success: function (status) {
                if(status == 3 || status == 8)
                {
                    setTimeout(function () {
                        window.location.href = "https://best-zaym.ru/";
                    }, 40000);
                }
            }
        });
    };

    var _init_loan_doctor_degress = function () {
        $('.js-loan_doctor_degress').click(function (e) {
            e.preventDefault();

            $('.degress-items-hide').removeClass('hide').fadeIn();
            $('.js-loan_doctor_degress').addClass('hide');
            $('.degress-number-second').css('color', '#FF8F5F');
            $('.degress-color-second').css('background', '#FF8F5F');
        });


        $('.degress-about-big-text').click(function (e) {
            $('.degress-about-text').addClass('hide');
            $('.degress-h').css('transform', 'rotate(0deg)');
            $('.degress-v').css('transform', 'rotate(90deg)');
            $(this).parent().children('.degress-about-text').removeClass('hide');
            $(this).children('.degress-h').css('transform', 'rotate(-45deg)');
            $(this).children('.degress-v').css('transform', 'rotate(45deg)');
        });
    };

    var _init_repeat_order = function () {
        $('.js-open-repeat-block').click(function (e) {
            e.preventDefault();

            $('.js-new-order-proposition').addClass('hide')
            $('.js-new-order-doctor').addClass('hide')
            $('.js-repeat-block').removeClass('hide').fadeIn();

        })

        $('.js-loan-repeat-form .js-loan-repeat').click(function (e) {
            e.preventDefault();

            if ($(this).hasClass('loading'))
                return false;

            var agreement = $('.js-loan-repeat-form .js-loan-agreement').is(':checked')

            $('.js-need-check').each(function () {
                if (!$(this).is(':checked')) {

                    if($(this).attr('id') != 'service_reason' || $(this).attr('id') != 'service_insurance')
                        _error = 1;
                        agreement = false;

                    $(this).closest('.check').addClass('-error');
                }
                else {
                    $(this).closest('.check').removeClass('-error');
                }
            });

            if (!agreement) {
                $('.js-loan-repeat-form .js-loan-agreement-block').addClass('-error');
            }
            else {
                $('.js-loan-repeat-form .js-loan-agreement-block').removeClass('-error');

                var date = new Date();
                var local_time = parseInt(date.getTime() / 1000);
                $('.js-loan-repeat-form .js-local-time').val(local_time);

                $(this).addClass('loading');

                $('[name="service_insurance"]').val();
                $('[name="service_reason"]').val();
                $('[name="service_sms"]').val();
                $('.js-loan-repeat-form').submit();
            }
        });
    };

    var _init_agreement_list = function () {
        $('.js-toggle-agreement-list').click(function (e) {
            e.preventDefault();

            $('#agreement_list').slideToggle()
        })
    }

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

    var _init_loan_doctor = function () {
        $('.js-open-loan-doctor-block').click(function (e) {
            e.preventDefault();

            $('.js-new-order-doctor').addClass('hide')
            $('.js-order-doctor').removeClass('hide').fadeIn();

        })

        $('.js-loan-doctor-form .js-loan-repeat').click(function (e) {
            e.preventDefault();

            if ($(this).hasClass('loading'))
                return false;

            var agreement = $('.js-loan-doctor-form .js-loan-agreement').is(':checked')

            $('.js-need-check').each(function () {
                if (!$(this).is(':checked')) {

                    if($(this).attr('id') != 'service_reason' || $(this).attr('id') != 'service_insurance')
                        _error = 1;
                        agreement = false;

                    $(this).closest('.check').addClass('-error');
                }
                else {
                    $(this).closest('.check').removeClass('-error');
                }
            });

            if (!agreement) {
                $('.js-loan-doctor-form .js-loan-agreement-block').addClass('-error');
            }
            else {
                $('.js-loan-doctor-form .js-loan-agreement-block').removeClass('-error');

                app.phone = $('#phone').val();

                _create_modal(app.phone);
                // return;

                $.ajax({
                    url: 'ajax/sms_code.php',
                    data: {
                        action: 'send',
                        phone: app.phone,
                        via_call: 0,
                        // registration: app.options.registration
                    },
                    success: function(resp){
                        if (!!resp.error)
                        {
                            // if (resp.error == 'sms_time')
                            //     _set_timer(resp.time_left);
                            // else
                                console.log(resp);
                        }
                        else
                        {
                            console.log('done');
                            // _set_timer(resp.time_left);
                            // app.sms_sent = 1;
        
                            // if (!!via_call && !!resp.aero_call_id)
                            // {
                            //     setTimeout(function(){
                            //         $.ajax({
                            //             url: 'ajax/sms_code.php',
                            //             data: {
                            //                 action: 'check_aero_status',
                            //                 phone: app.phone,
                            //                 aero_call_id: resp.aero_call_id,
                            //                 via_call: via_call,
                            //             },
                            //         })
                            //     }, 60000);
                            // }
        
                            // if (!!resp.developer_code)
                            //     $('.js-sms-code').val(resp.developer_code).change();
                        }
                    }
                });

                // var date = new Date();
                // var local_time = parseInt(date.getTime() / 1000);
                // $('.js-loan-doctor-form .js-local-time').val(local_time);

                // $(this).addClass('loading');

                // $('[name="service_insurance"]').val();
                // $('[name="service_reason"]').val();
                // $('[name="service_sms"]').val();
                // $('.js-loan-doctor-form').submit();
            }
        });
    };

    var _create_modal = function(phone){

        app.$modal = $('#sms_code_modal');
        app.$modal.html('');

        var _tpl = '<div>На Ваш номер телефона <div><strong>'+phone+'</strong></div> <div id="wasSent">было отправлено сообщение с кодом подтверждения</div></div>';
        _tpl += '<div class="form-group form-phone js-sms-code-wrap">';
        _tpl += '<span class="phone_info -fs-14" id="enterCode">Введите код из СМС</span>';
        _tpl += '<input type="number" min=0 max=9999  oninput="handleChange(this);" name="" autocomplete="one-time-code" class="form-control -fs-18 -gil-m js-mask-sms js-sms-code" value="">';
        _tpl += '<div class="error_text js-sms-error" style="display:none">Код не совпадает</div>';
        // _tpl += '<a href="javascript:void(0);" class="js-sms-repeat sms-repeat"><div id="sendAgain">Получить код по СМС</div> <span class="js-sms-timer"></span></a>';
        _tpl += '</div>';
        _tpl += '<a href="#" class="btn btn-secondary -fs-20 -fullwidth js-sms-confirm">Продолжить</a>';

        app.$modal.html(_tpl);

        $.fancybox.open({
            src: '#sms_code_modal',
            modal: false
        });

        _init_confirm_sms();
    }

    var _init_confirm_sms = function(){
        $('.js-sms-confirm').click(function(e){
            e.preventDefault();
            _check_sms();
        });
    };
    
    var _check_sms = function(){
        app.code = $('.js-sms-code').val();
        var _data = {
            action: 'check',
            phone: app.phone,
            code: app.code
        };
        $.ajax({
            url: 'ajax/sms_code.php',
            data: _data,
            beforeSend: function(){
            },
            success: function(resp){
                if (resp.success)
                {
                    //alert('проверка2' + app.code);
                    $('.js-sms-error').html('').hide();
                    _success_callback(app.code);
                    $('.js-sms-code-wrap').removeClass('-error').addClass('-ok');
                }
                else
                {
                    // alert('проверка');
                    // код не совпадает
                    $('.js-sms-error').html('Код не совпадает').show();
                    $('.js-sms-code-wrap').removeClass('-ok').addClass('-error')
                }
            }

        });

    };

    var _success_callback = function (code) {
        //alert('проверка3');
        console.info('_success_callback');
        $('.js-loan-code').val(code);
        $('.js-loan-doctor-form').submit();
    };

    ;(function () {
        _init();
        _init_toggle_services();
        _init_repeat_order();
        _init_agreement_list();
        _redirect_to_partner();
        _init_loan_doctor();
        _init_loan_doctor_degress();
    })();
};

$(function () {
    if ($('.js-lk-app').length > 0)
        new LkApp();
})