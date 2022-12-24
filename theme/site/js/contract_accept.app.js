function ContractAcceptApp() {
    var app = this;

    app.$form;
    app.phone;

    app.sms_timer;

    var _init = function () {

        app.$form = $('#accept_credit_form');

        app.phone = app.$form.data('phone');
    };

    var _init_accept_button = function () {
        $('.js-accept-contract').click(function (e) {
            e.preventDefault();

            var agreement = app.$form.find('.js-loan-agreement').is(':checked');

            if (!agreement) {
                app.$form.find('.js-loan-agreement-block').addClass('-error');
            }
            else {
                app.$form.find('.js-loan-agreement-block').removeClass('-error');

                var _code = app.$form.find('[name=accept_code]').val();
                var contract_id = app.$form.find('[name=contract_id]').val();

                // check sms
                $.ajax({
                    url: 'ajax/sms_code.php',
                    data: {
                        contract_id: contract_id,
                        code: _code,
                        action: 'check_accept_sms',
                    },
                    beforeSend: function () {

                    },
                    success: function (resp) {
                        if (!!resp.error) {
                            app.$form.find('.js-accept-code-error').html(resp.error).closest('.form-phone').addClass('-error');
                        }
                        if (!!resp.success) {

                            app.$form.find('.js-accept-code-error').html('').closest('.form-phone').removeClass('-error');

                            $.ajax({
                                url: 'accept/' + contract_id,
                                type: 'POST',
                                data: app.$form.serialize(),
                                beforeSend: function () {

                                },
                                success: function (resp) {
                                    location.href = 'account';
                                }
                            });
                        }
                    }
                });
            }
        });
    };

    var _add_insurance_sum = function () {
        $('#service_insurance').click(function () {
            let link = $('.pep').attr('href');

            if ($(this).is(':checked'))
                link = link + '?insurance=1';
            else
                link = link.replace('?insurance=1', '');

            $('.pep').attr('href', link);
        });
    };

    var _send_accept_code = function () {

        var _contract_id = app.$form.find('[name=contract_id]').val();

        let phone = app.$form.find('[name=phone]').val();

        $.ajax({
            url: 'ajax/sms_code.php',
            data: {
                action: 'send_accept_code',
                contract_id: _contract_id,
                phone: phone
            },
            success: function (resp) {
                if (!!resp.error) {
                    if (resp.error == 'sms_time')
                        _set_timer(resp.time_left);
                    else
                        console.log(resp);
                }
                else {
                    _set_timer(resp.time_left);
                    app.sms_sent = 1;

                    if (!!resp.developer_code)
                        $('.js-accept-code').val(resp.developer_code).change();
                }
            }
        });

    };

    var _set_timer = function (_seconds) {
        clearInterval(app.sms_timer);

        app.sms_timer = setInterval(function () {
            _seconds--;
            if (_seconds > 0) {
                $('.js-accept-timer').text(_seconds + 'сек');
                $('.js-repeat-accept-code').addClass('disable');
            }
            else {
                $('.js-accept-timer').text('');
                $('.js-repeat-accept-code').removeClass('disable');
                clearInterval(app.sms_timer);
            }
        }, 1000);

    };

    var _init_repeat_sms = function () {
        $('.js-repeat-accept-code').click(function (e) {
            e.preventDefault();
            if (!$(this).hasClass('disable'))
                _send_accept_code();
        });
    };

    ;(function () {
        _init();
        _init_accept_button();
        _init_repeat_sms();
        _add_insurance_sum();
    })();
}

$(function () {
    if ($('#accept_credit_form').length > 0)
        new ContractAcceptApp();
})

function ContractAcceptAppOLD() {
    var app = this;

    app.$form;
    app.phone;

    var _init = function () {

        app.$form = $('#accept_credit_form');

        app.phone = app.$form.data('phone');
    };

    var _init_accept_button = function () {
        $('.js-accept-contract').click(function (e) {
            e.preventDefault();

            var agreement = app.$form.find('.js-loan-agreement').is(':checked')

            if (!agreement) {
                app.$form.find('.js-loan-agreement-block').addClass('-error');
            }
            else {

                app.$form.find('.js-loan-agreement-block').removeClass('-error');

                new SmsApp(app.phone, _success_callback, {
                    button_name: 'Подписать договор',
                    modal: false
                });
            }
        });
    };

    var _success_callback = function (code) {

        app.$form.find('[name=accept_code]').val(code);
        var contract_id = app.$form.find('[name=contract_id]').val();

        $.ajax({
            url: 'accept/' + contract_id,
            type: 'POST',
            data: app.$form.serialize(),
            beforeSend: function () {

            },
            success: function (resp) {
                location.href = 'account';
            }
        });

    };

    ;(function () {
        _init();
        _init_accept_button();
    })();
}