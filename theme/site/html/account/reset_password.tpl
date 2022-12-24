{$meta_title='Сброс пароля' scope=parent}

{capture name='page_scripts'}
<script>
    $(function(){
        $('#phone').mask("+7(999) 999-9999");
    });
</script>
{/capture}

{capture name='page_styles'}
    
{/capture}

<main class="main">
  <div class="section section_itop">
    <div class="container">
      <div class="section_row row">
        
        <div class="col-lg-5 offset-lg-3">
          <div class="itop_calc">
            <form class="calculator js-reset-password-form" method="POST">
                <div class="form-group form-phone">
                    <div class="form-group-title -fs-22 -gil-m text-center" for="amount-one">
                        Сброс пароля
                    </div>
                </div>
                
                {if $success}
                <div class="p-3">
                    <div class="alert alert-success">{$success}</div>
                    <a href="/lk/login">Перейти на страницу входа в личный кабинет</a>
                </div>
                
                {else}
                
                <div id="phone_block">
                    <div class="form-group form-phone">
                        <span class="phone_info -fs-14">Ваш номер телефона</span>
                        <input type="text" name="phone" id="phone" class="form-control -fs-18 -gil-m js-mask-phone" value="" />
                        <div class="error_text js-reset-password-error" style="display:none">Укажите номер телефона</div>
                    </div>
                    <div class="form-group form-btn">
                        <button type="submit" class="btn btn-secondary -fs-20 -fullwidth ">Отправить</button>
                    </div>
                </div>
                {/if}

            </form>
          </div>
        </div>
      </div>

    </div>
  </div>
</main>
