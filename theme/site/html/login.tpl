{$meta_title='Вход в личный кабинет' scope=parent}

{capture name='page_scripts'}

  <script src="theme/site/js/login.app.js?v=1.02"></script>

{/capture}

{capture name='page_styles'}
    
{/capture}

<main class="main">
  <div class="section section_itop">
    <div class="container">
      <div class="section_row row">
        
        <div class="col-lg-5 offset-lg-3">
          <div class="itop_calc">
            <form class="calculator js-login-form" method="POST">
                <div class="form-group form-phone">
                    <div class="form-group-title -fs-22 -gil-m text-center" for="amount-one">
                        Вход в личный кабинет
                    </div>
                </div>
                
                <div id="phone_block">
                    <div class="form-group form-phone">
                        <span class="phone_info -fs-14">Ваш номер телефона</span>
                        <input type="text" name="phone" id="phone" class="form-control -fs-18 -gil-m js-login-phone js-mask-phone" value="" />
                        <input type="hidden" name="code" id="" class="js-mask-sms js-login-code" value="" />
                        <div class="error_text js-login-phone-error" style="display:none">Укажите номер телефона</div>
                    </div>
                    <div class="form-group form-btn">
                        <a href="#" class="btn btn-secondary -fs-20 -fullwidth js-login-start">Войти</a>
                    </div>
                </div>

                <div id="password_block" style="display:none">
                    <div class="form-group form-password">
                        <span class="password_info -fs-14">Пароль</span>
                        <input type="password" name="password" id="password" class="form-control -fs-18 -gil-m js-login-password-input" value="" />
                        <div class="error_text js-login-password-error" style="display:none">Введите пароль</div>
                    </div>
                    <div class="form-group form-btn">
                        <a href="#" class="btn btn-secondary -fs-20 -fullwidth js-login-password">Войти</a>
                    </div>
                    <div class="form-group form-btn">
                        <a href="reset_password">Забыли пароль?</a>
                    </div>
                </div>

            </form>
          </div>
        </div>
      </div>

    </div>
  </div>
</main>
