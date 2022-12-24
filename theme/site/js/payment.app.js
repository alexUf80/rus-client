function PaymentApp()
{
    var app = this;
    
    app.payment_id;
    
    app.init = function(){
console.log('init')    
        
        $('.cancel_payment').click(function(){
            location.href = 'user';
        });
        
        $('#confirm_payment').click(function(e){
            app.confirm_payment(e);
        });

        $('#close_contract').click(function(e){

            $('#close_insurance').attr('checked', true);

            let amount = Number($(this).data('full-amount'));

            let contract_id = $('[name=contract_id]').val();

            let prolongation = $('[name=prolongation]').val();
            let code_sms = $('[name=code_sms]').val();

            if (amount > 0)
            {
                if ($('input[name=card_id]:checked').length > 0)
                {
                    var card_id = $('input[name=card_id]:checked').val();

                    app.other_payment(contract_id, amount, e, prolongation, code_sms, card_id);
                }
                else
                {
                    $('.payment-block-title').addClass('error');
                }
            }
            else
            {
                $('.payment-block').removeClass('loading').addClass('error');
                $('.payment-block-error p').html('Сумма должна быть больше нуля.');

                e.preventDefault();
                return false;
            }
        });
    };
    
    app.confirm_payment = function(e){
        
        var amount = $('[name=amount]').val();
        
        var contract_id = $('[name=contract_id]').val();
        
        var prolongation = $('[name=prolongation]').val();
        var code_sms = $('[name=code_sms]').val();
        
        if (amount > 0)
        {            
            if ($('[name=card_id]:checked').length > 0)
            {                
                var card_id = $('[name=card_id]:checked').val();
                
                app.other_payment(contract_id, amount, e, prolongation, code_sms, card_id);
            }
            else
            {
                $('.payment-block-title').addClass('error');
            }
        }
        else
        {
            $('.payment-block').removeClass('loading').addClass('error');
            $('.payment-block-error p').html('Сумма должна быть больше нуля.');
            
            e.preventDefault();
            return false;
        }
    };
    
    app.other_payment = function(contract_id, amount, e, prolongation, code_sms, card_id){
        console.info('app.other_payment');
        
        var $btn = $('#confirm_payment');
        
        $.ajax({
            url: 'ajax/best2pay.php',
            async: false,
            data: {
                action: 'get_payment_link',
                amount: amount,
                contract_id: contract_id,
                prolongation: prolongation, 
                code_sms: code_sms,
                card_id: card_id
            },
            success: function(resp){

                if (!!resp.error)
                {
                    $('.payment-block').removeClass('loading').addClass('error');
                    $('.payment-block-error p').html('Ошибка: '+resp.error);
                    e.preventDefault();
                    return false;                
                }
                else
                {
                    app.payment_id = resp.PaymentId;
                    app.check_state(app.payment_id);
                    document.cookie = "go_payment=1; path=/;";
                    $btn.attr('href', resp.link);
                    $('#close_contract').attr('href', resp.link);
                    
                    
                }

            }
        })
    };
    
    app.attach_card_payment = function(contract_id, card_id, amount, prolongation, code_sms){
        console.info('app.attach_card_payment');
        
        $.ajax({
            url: 'ajax/best2pay.php',
            data: {
                action: 'recurrent',
                card_id: card_id,
                contract_id: contract_id, 
                amount: amount,
                prolongation: prolongation, 
                code_sms: code_sms,
            },
            success: function(resp){
console.info(resp);
                if (!!resp.error)
                {
                    $('.payment-block-error').html('Ошибка: '+resp.error).removeClass('hide');
                    
                    return false;                
                }
                else
                {
                    $('.payment-block-error').addClass('hide');
                    location.href = 'account';
                    
                    
                }

            }
        });
    };
    
    app.check_state = function(payment_id){
        app.check_timeout = setTimeout(function(){
            $.ajax({
                url: 'ajax/payment.php',
                data: {
                    action: 'get_state',
                    payment_id: app.payment_id,
                },
                success: function(resp){
console.log(resp)
                    if (!!resp.error)
                    {
                        $('.payment-block').removeClass('loading').addClass('error');
                        $('.payment-block-error p').html('Ошибка: '+resp.error);

                    }
                    else
                    {
                        if (resp.Status == 'CONFIRMED')
                        {
                            $('.payment-block').removeClass('loading').addClass('success');
                            $('.payment-block-success p').html('Спасибо, оплата принята.');
                            
                        }
                        else if (resp.Status == 'REJECTED')
                        {
                            $('.payment-block').removeClass('loading').addClass('error');
                            $('.payment-block-error p').html('Не получилось оплатить<br />'+resp.Message);
                        }
                        else
                        {
                            app.check_state();
                            
                        }
                    }
                }
            })
        }, 5000);
    }
    
    ;(function(){
        app.init();
    })();
};
$(function(){
    new PaymentApp();
})

