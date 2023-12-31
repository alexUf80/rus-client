{$meta_title='Оплата задолженности' scope=parent}
{$wrapper='cession/index.tpl' scope=parent}

{capture name='page_scripts'}
<script>
    function C2oApp()
    {
        var app = this;
        
        app.init_open_payform = function(){
            $(document).on('click', '.js-open-payform', function(e){
                e.preventDefault();
            
                $(this).hide();
                $('.js-open-proloform').hide();
                $('h3.text-center.-red').hide();
                $('.pay-text').text('Оплатить займ');
                $('.pay-sum').val({$total_summ});
                $('[name="prolongation"]').val(0);
                
                $('.js-payform-wrapper').fadeIn();
            })
            
            $(document).on('click', '.js-open-proloform', function(e){
                e.preventDefault();
            
                $(this).hide();
                $('.js-open-payform').hide();
                $('h3.text-center.-red').hide();
                $('.pay-text').text('Сделать пролонгацию');
                $('.pay-sum').val({$prolongation_amount});
                $('[name="prolongation"]').val(1);
                
                $('.js-payform-wrapper').fadeIn();
            })
            
        };
        
        app.init_start_payform = function(){
            $(document).on('click', '.js-start-payform', function(e){
                e.preventDefault();
            
                var $form = $(this).closest('form');
                
                if ($form.hasClass('loading'))
                    return false;
                
                $.ajax({
                    url: 'ajax/best2pay.php',
                    data: $form.serialize(),
                    beforeSend: function(){
                        $form.addClass('loading');
                    },
                    success: function(resp){
                        if (resp.link)
                            location.href = resp.link;
                        else
                            alert(resp.error);
                    }
                });
            })
            
        };
        
        ;(function(){
            app.init_open_payform();
            app.init_start_payform();
        })();
    }
    
    $(function(){
        new C2oApp();
    })
</script>
{/capture}

{capture name='page_styles'}
    
{/capture}

<main class="main">
  <div class="section ">
    <div class="container">
      
      
      <h1 class="text-center">
        Уважаемый клиент.
      </h1>
      
      
        {$total_summ = $contract->loan_body_summ+$contract->loan_percents_summ+$contract->loan_peni_summ+$contract->loan_charge_summ}
        
        {if $total_summ > 0}
            <h3 class="text-center -red">
                По № договора {$contract->number}<br />задолженность составляет {$total_summ} руб
            </h3>
          <div class="text-center">
            <a href="{url}/pay" class="btn btn-primary js-open-payform">Погасить задолженность</a>
            {if $prolongation_amount && $contract->type == 'base' && $show_prolongation}
                <br><br>
                <h3 class="text-center -red">
                    Можно сделать пролонгацию займа.<br /> Сумма пролонгации {$prolongation_amount}
                </h3>
                <a href="{url}/pay" class="btn btn-primary js-open-proloform">Сделать пролонгацию</a>
            {/if}
            <div style="display:none" class="js-payform-wrapper">
                <div class="row">
                    <div class="col-lg-4 offset-lg-4">
                        <div class="itop_calc">
                            <form class="js-payform" method="POST">
                                
                                <input type="hidden" name="contract_id" value="{$contract->id}" />
                                <input type="hidden" name="action" value="get_payment_link" />
                                
                                <input type="hidden" name="prolongation" value="0" />
                                <input type="hidden" name="code_sms" value="" />

                                <div class="form-group form-phone">
                                    <div class="pay-text form-group-title -fs-22 -gil-m text-center" for="amount-one">
                                        Оплатить займ
                                    </div>
                                </div>
                                
                                <div id="">
                                    <div class="form-group form-phone">
                                        <span class="phone_info -fs-14">Сумма</span>
                                        <input type="text" name="amount" id="amount" class="pay-sum form-control -fs-18 -gil-m" value="{$total_summ}">
                                    </div>
                                    <div class="form-group form-btn">
                                        <a href="#" class="btn btn-secondary -fs-20 -fullwidth js-start-payform">Оплатить</a>
                                    </div>
                                </div>
                
                            </form>
                        </div>
                    </div>
                </div>

            </div>
          </div>
        {else}
            <h3 class="text-center">
                По № договора{$contract->number}<br />у вас нет задолженности! 
            </h3>
        {/if}        
      
    </div>
  </div>
</main>