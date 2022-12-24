;function StageAddressApp()
{
    var app = this;
    
    var _init = function(){
        
        $('#clone_address').change(function(){
            if ($(this).is(':checked'))
            {
                $('[name=Regregion]').removeClass('js-input-required');
                $('[name=Regcity]').removeClass('js-input-required');
                $('[name=Regstreet]').removeClass('js-input-required');
                $('[name=Reghousing]').removeClass('js-input-required');
                
                $('.js-regaddress-block').slideUp();
                
                $('.js-regaddress-block').find('.-error').removeClass('-error');
                $('.js-regaddress-block').find('.error_text').remove();
            }
            else
            {
                $('[name=Regregion]').addClass('js-input-required');
                $('[name=Regcity]').addClass('js-input-required');
                $('[name=Regstreet]').addClass('js-input-required');
                $('[name=Reghousing]').addClass('js-input-required');                

                $('.js-regaddress-block').slideDown();
            }
        })
    }
    
    ;(function(){
        _init();
    })();
};

$(function(){
    if ($('.js-stage-address-form').length > 0)
        new StageAddressApp();
});