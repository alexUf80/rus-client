;function CalcApp($form)
{
    var app = this;

    app.DEBUG = 0;

    app.$form;
    app.$range_summ;
    app.$range_period;
    app.$info_summ;
    app.$info_period;
    app.$total_summ;
    app.$total_period;

    app.loan_percent;

    app.min_summ;
    app.max_summ;
    app.min_period;
    app.max_period;
    app.current_summ;
    app.current_period;

    app.summ_postfix = " ₽";
    app.period_postfix = " дней";

    var _init = function(){

        app.$form = $form;

        app.$input_summ = app.$form.find('.js-input-summ');
        app.$input_period = app.$form.find('.js-input-period');
        app.$info_summ = app.$form.find('.js-info-summ');
        app.$info_period = app.$form.find('.js-info-period');
        app.$total_summ = app.$form.find('.js-total-summ');
        app.$total_period = app.$form.find('.js-total-period');

        app.loan_percent = parseFloat(app.$form.attr('data-percent'));

        app.min_summ = parseInt(app.$input_summ.attr('min'));
        app.max_summ = parseInt(app.$input_summ.attr('max'));
        app.current_summ = parseInt(app.$input_summ.val());
        app.min_period = parseInt(app.$input_period.attr('min'));
        app.max_period = parseInt(app.$input_period.attr('max'));
        app.current_period = parseInt(app.$input_period.val());

    };

    var _init_promo_code = function(){
        $('.js-toggle-promo-code').click(function(e){
            e.preventDefault();

            $('#promo_code').slideToggle()
        })

        const _promo_code = document.cookie
            .split('; ')
            .find((row) => row.startsWith('promo_code='))
            ?.split('=')[1];

        if (_promo_code) {
            //document.cookie = 'promo_code='+_promo_code+'; path=/; expires=Tue, 19 Jan 2021 03:14:07 GMT';
        
            $.ajax({
                url: 'ajax/CheckPromoCodes.php',
                data: {
                    code: _promo_code
                },
                beforeSend: function(){
                },
                success: function(resp){
                    
                    console.log(resp);
        
                    if (!!resp.false)
                    {
                        $('.js-success-promo').hide();
                        $('.js-error-promo').show();
                    }
                    else if (!!resp.checked)
                    {
                        $('#promo_code').show()
                        $('.js-error-promo').hide();
                        $('.js-success-promo').show();
                        $('#promo_input').hide();
                        $('#check_promo_code').hide();
                        app.loan_percent = resp.percent;
                        _calculate();
                        document.cookie = 'promo_code='+_promo_code+'; path=/';
                    }
                }
            });
        }

        $('#check_promo_code').click(function(e){
            e.preventDefault();
    
            var _code = $('#promoCode').val();
    
            $.ajax({
                url: 'ajax/CheckPromoCodes.php',
                data: {
                    code: _code
                },
                beforeSend: function(){
                },
                success: function(resp){
                    
                    console.log(resp);
        
                    if (!!resp.false)
                    {
                        $('.js-success-promo').hide();
                        $('.js-error-promo').show();
                    }
                    else if (!!resp.checked)
                    {
                        $('.js-error-promo').hide();
                        $('.js-success-promo').show();
                        $('#promo_input').hide();
                        $('#check_promo_code').hide();
                        app.loan_percent = resp.percent;
                        _calculate();
                        document.cookie = 'promo_code='+_code+'; path=/';
                    }
                }
            });
        })
    }

    var _init_ranges = function(){
        app.$input_summ.ionRangeSlider({
            skin: "round",
            type: "single",
            grid: true,
            min: app.min_summ,
            max: app.max_summ,
            from: app.current_summ,
            step: 1,
            grid_num:9,
//            grid_snap: true,
            onStart: function(data) {
                app.$input_summ.val(data.from);
                // app.$info_summ.text(data.from + app.summ_postfix);
                app.$info_summ.text(new Intl.NumberFormat("ru").format(data.from));
           
                app.current_summ = data.from;
                _calculate();
            },
            onChange: function(data) {
                app.$input_summ.val(data.from);
                // app.$info_summ.text(data.from + app.summ_postfix);
                app.$info_summ.text(new Intl.NumberFormat("ru").format(data.from));
                app.current_summ = data.from;
                _calculate();
            },
            postfix: app.summ_postfix,
        });

        app.$input_period.ionRangeSlider({
            skin: "round",
            type: "single",
            grid: true,
            min: app.min_period,
            max: app.max_period,
            from: app.current_period,
            step: 2,
            grid_num: 3,
            // grid_snap: true,
            onStart: function(data) {
                app.$input_period.val(data.from);
                // app.$info_period.text(data.from + app.period_postfix);
                app.$info_period.text( data.from);
                app.current_period = data.from;
                _calculate();
            },
            onChange: function(data) {
                app.$input_period.val(data.from);
                // app.$info_period.text(data.from + app.period_postfix);
                app.$info_period.text(data.from);
                app.current_period = data.from;
                _calculate();
            },
            postfix: app.period_postfix,
        });

    };

    var _set_cookie = function()
    {
        document.cookie = 'loan_summ='+app.current_summ+'; path=/';
        document.cookie = 'loan_period='+app.current_period+'; path=/';
    };

    var _get_string_date = function(_date)
    {
        var _string_date = '';

        var c_date = _date.getDate() > 9 ? _date.getDate() : '0' + _date.getDate();
        _string_date += c_date + '.';

        var c_month = _date.getMonth() > 8 ? _date.getMonth() + 1 : '0' + (_date.getMonth() + 1);
        _string_date += c_month + '.';

        _string_date += _date.getFullYear();

        return _string_date;
    };

    var _calculate = function(){

        var _total_summ = app.current_summ + (app.current_summ / 100 * app.current_period * app.loan_percent);
        app.$total_summ.text(new Intl.NumberFormat("ru").format(_total_summ));

        var _now = new Date();
        _now.setDate(_now.getDate() + app.current_period);
        var _string_total_date = _get_string_date(_now);
        app.$total_period.text(_string_total_date);

        console.log(app.loan_percent);

        if (!!app.DEBUG)
        {
            console.info('_total_summ', _total_summ);
            console.info('date', _string_total_date);
        }
        _set_cookie();
    };

    ;(function(){
    _init();
    _init_promo_code();
    _init_ranges();
})();
};

$(function(){
    if ($('.js-calc').length > 0)
        new CalcApp($('.js-calc'));
});