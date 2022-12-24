;function FormApp()
{
    var app = this;
    
    app.$form;
    
    app.patronymic_verify = 0;
    
    var _init = function(){
        
        app.$form = $('.js-form-app');
    };
        
    var _init_submit = function(){
        app.$form.submit(function(e){
            
            app.check_fields();
            
            
            if (app.$form.find('.-error').length > 0)
            {
                e.preventDefault();
        
                $('html').animate({ 
            	    scrollTop: app.$form.find('.-error').first().offset().top - 50 
                }, 500);
            }
        });
    };
    
    var _init_cirylic = function(){

        $('.js-input-cirylic').keyup(function(){
            var _current = $(this).val();
    
            if ($(this).hasClass('js-cirylic-plus'))
                var _replace = this.value.replace(/[^а-яё0-9\.\, ]/ig,'');
            else
                var _replace = this.value.replace(/[^а-яё ]/ig,'');
            
            if (_replace != '')
                $(this).val(_replace[0].toUpperCase() + _replace.slice(1));
            else
                $(this).val(_replace);
        });
        
        app.$form.find('input').blur(function(){
            _check_input($(this));
        });
    }
    
    
    
    app.check_fields = function(){
        
        app.$form.find('input').each(function(){
            _check_input($(this));
        });
        
    };
    
    var _check_input = function($input){
        
        // радио кнопки
        if ($input.attr('type') == 'radio')
        {
            var $radio_block = $input.closest('.js-radio-required');
            var _msg = $radio_block.data('msg') || 'Выберите значение';
            if ($radio_block.find('[type=radio]:checked').length < 1)
                $radio_block.addClass('-error').append('<div class="error_text">'+_msg+'</div>');
            else
                $radio_block.removeClass('-error').find('.error_text').remove();

            return;
        }
        
        // кирилица
        if ($input.hasClass('js-input-cirylic'))
        {
            var _current = $input.val().trim();
    
            if ($input.hasClass('js-cirylic-plus'))
                var _replace = $input.val().replace(/[^а-яё0-9\.\, ]/ig,'');
            else
                var _replace = $input.val().replace(/[^а-яё ]/ig,'');
            
            if (_current != _replace)
            {
                $input.closest('label').removeClass('-ok').addClass('-error').find('.error_text').remove();
                $input.closest('label').append('<div class="error_text">Допускается ввод только русских букв</div>');
            }
            else
            {
                $input.closest('label').removeClass('-error').addClass('-ok').find('.error_text').remove();
            }            
        }
        
        // обязательные поля
        if ($input.hasClass('js-input-required'))
        {
            var $input_box = $input.closest('.input_box')
            var _msg = $input_box.data('msg') || 'Заполните поле';
            if ($input.val().trim() == '')
                $input_box.addClass('-error').removeClass('-ok').find('.error_text').remove(), $input_box.append('<div class="error_text">'+_msg+'</div>');
            else
                $input_box.removeClass('-error').addClass('-ok').find('.error_text').remove();            
        }
        
        // отчество
        if ($input.attr('name') == 'patronymic')
        {
            if (!app.patronymic_verify)
            {
                var _split = $input.val().split(' ');

                var $input_box = $input.closest('.input_box')
                var _msg = $input_box.data('msg') || 'Проверьте правильность отчества';
                if (_split.length > 1)
                    $input_box.addClass('-error').removeClass('-ok').find('.error_text').remove(), $input_box.append('<div class="error_text">'+_msg+'</div>');
                else
                    $input_box.removeClass('-error').addClass('-ok').find('.error_text').remove();            
                
                app.patronymic_verify = 1;
            }
        }
        
    };
    
    var _init_masks = function(){
        if ($(".js-mask-phone").length > 0)
            $(".js-mask-phone").mask("+7(999) 999-9999");
        if ($(".js-mask-date").length > 0)
            $(".js-mask-date").mask('99.99.9999');
        if ($(".js-mask-sms").length > 0)
            $('.js-mask-sms').mask('999999');
        if ($(".js-mask-passport").length > 0)
            $(".js-mask-passport").mask('9999-999999');
        if ($(".js-mask-subdivision").length > 0)
            $(".js-mask-subdivision").mask('999-999');
        if ($(".js-mask-snils").length > 0)
            $('.js-mask-snils').mask("999-999-999-99");

    }
    
    ;(function(){
        _init();
        _init_submit();
        _init_cirylic();
        _init_masks();
    })();
};

$(function(){
    if ($('.js-form-app').length > 0)
        new FormApp();
});