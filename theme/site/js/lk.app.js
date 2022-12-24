;

function LkApp() {
    var app = this;

    app.order_updater;

    var _init = function () {

        if ($('.js-check-status').length > 0)
            _run_check_status();

    };

    $('.js-prolongation-form').submit(function (e) {

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
                        window.location.href = "https://barcredit.ru";
                    }, 40000);
                }
            }
        });
    };

    var _init_repeat_order = function () {
        $('.js-open-repeat-block').click(function (e) {
            e.preventDefault();

            $('.js-new-order-proposition').addClass('hide')
            $('.js-repeat-block').removeClass('hide').fadeIn();

        })

        $('.js-loan-repeat').click(function (e) {
            e.preventDefault();

            if ($(this).hasClass('loading'))
                return false;

            var agreement = $('.js-loan-repeat-form .js-loan-agreement').is(':checked')

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


    ;(function () {
        _init();

        _init_repeat_order();
        _init_agreement_list();
        _redirect_to_partner();
    })();
};

$(function () {
    if ($('.js-lk-app').length > 0)
        new LkApp();
})