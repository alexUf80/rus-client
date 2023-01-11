{$meta_title='Вход в личный кабинет' scope=parent}

{capture name='page_scripts'}

<script src="theme/site/js/login.app.js?v=1.02"></script>

{/capture}

{capture name='page_styles'}
<style>
  body {
    margin: 0;
    font-family: Roboto, Arial, sans-serif;
  }

  .heading {
    display: block;
    color: #fff;
    text-transform: uppercase;
    font-weight: 700;
  }

  .heading.big {
    font-size: 85px;
  }

  .heading.medium {
    font-size: 50px;
  }

  .heading.small {
    display: block;
    font-size: 40px;
    text-align: left;
    margin-top: 60px;
  }

  .block {
    /* height: 800px; */
    text-align: center;
    padding: 94px 140px;
    position: relative;
    overflow: hidden;
    background: url("https://thumb.tildacdn.com/tild6266-3732-4437-b230-663837393362/-/format/webp/handsome-man-in-snow.png") 50% 50% no-repeat,
      #353536;
    background-size: cover;
    box-sizing: border-box;
  }

  .block__inner {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1200px;
    margin: 0 auto;
  }

  .block__background {
    height: 100%;
    position: absolute;
    left: 0;
    top: 0;
    transform: translateX(-6%);
  }

  .calculator-outer {
    display: inline-block;
    position: relative;
  }

  .calculator {
    display: inline-block;
    max-width: 433px;
    width: 100%;
    background-color: #fff;
    border-radius: 5px;
    text-align: left;
    padding: 50px;
    box-sizing: border-box;
    position: relative;
  }

  .calculator-background {
    content: "";
    display: block;
    width: 100%;
    height: 100%;
    background-color: #bd9457;
    position: absolute;
    left: 7px;
    top: 7px;
    border-radius: 5px;
  }

  .calculator__form-group {
    display: block;
    margin-bottom: 2.5rem;
  }

  .calculator__form-label {
    display: block;
    margin-bottom: .5rem;
    font-size: 1.2rem;
    font-weight: 600;
  }

  .calculator__input {
    width: 100%;
    -webkit-appearance: none;
  }

  .calculator__input::-webkit-slider-thumb {
    width: 19px;
    height: 19px;
    border-radius: 50%;
    background-color: #a60d0d;
    cursor: pointer;
    border: 1px solid #fff;
    box-shadow: 0 0 5px rgba(0, 0, 0, .5);

    -webkit-appearance: none;
    margin-top: -8px;
  }

  .calculator__input::-moz-range-thumb {
    width: 19px;
    height: 19px;
    border-radius: 50%;
    background-color: #a60d0d;
    cursor: pointer;
    border: 1px solid #fff;
    box-shadow: 0 0 5px rgba(0, 0, 0, .5);
  }

  .calculator__input::-moz-range-track {
    width: 100%;
    height: 3px;
    background-color: rgba(0, 0, 0, .2);
  }

  .calculator__input-container {
    position: relative;
    padding-top: 37px;
  }

  .calculator__output {
    display: inline-block;
    padding: .4rem;
    border-radius: 4px;
    box-shadow: 0 0 2px rgba(0, 0, 0, .5);
    background: #fff;
    margin-bottom: .2rem;
    position: absolute;
    left: 50%;
    top: 0;
    transform: translateX(-45%);
    white-space: nowrap;
  }

  .calculator__input-ranges {
    margin-top: .4rem;
    display: flex;
    justify-content: space-between;
  }

  .calculator__result {
    margin-bottom: 2rem;
  }

  .calculator__result-row {
    display: flex;
    justify-content: space-between;
    font-size: 1.2rem;
    font-weight: 600;
  }

  .calculator__result-row+.calculator__result-row {
    margin-top: 1rem;
  }

  .calculator__button {
    color: #fff;
    background-color: #a60d0d;
    border: none;
    border-radius: 5px;
    font-weight: 700;
    font-size: 17px;
    width: 320px;
    height: 60px;
    margin-left: auto;
    margin-right: auto;
    padding: 0 15px;
    display: block;
    cursor: pointer;
  }

  @media (max-width: 1400px) {
    .block {
      padding: 94px 50px;
    }
  }

  @media (max-width: 991px) {
    .block {
      background: none;
      padding: 0;
      height: unset;
    }

    .calculator-background {
      display: none;
    }

    .calculator-outer {
      width: 100%;
    }

    .calculator {
      display: block;
      max-width: 600px;
      width: 100%;
      margin: 0 auto;
    }

    .block__text-outer {
      width: 100%;
      background: url("https://thumb.tildacdn.com/tild6266-3732-4437-b230-663837393362/-/format/webp/handsome-man-in-snow.png") 200px 50% no-repeat,
        #353536;
      background-size: cover;
      box-sizing: border-box;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      padding: 50px 5%;
    }

    .block__inner {
      flex-direction: column;
    }

    .heading.big {
      font-size: 64px;
    }

    .heading.medium {
      font-size: 38px;
    }

    .heading.small {
      font-size: 28px;
      margin-top: 20px;
    }
  }

  @media (max-width: 768px) {
    .block__text-outer {
      background-position: 50px 50%;
    }
  }

  @media (max-width: 576px) {
    .heading {
      margin: 0 auto;
    }

    .heading.big {
      font-size: 52px;
    }

    .heading.medium {
      font-size: 31px;
    }

    .heading.small {
      font-size: 24px;
    }

    .block__text-outer {
      background-position: 50% 50%;
      padding: 250px 0 50px;
    }

    .calculator__button {
      max-width: unset;
      width: 100%;
    }

    .calculator * {
      font-size: 16px !important;
    }
  }
</style>
{/capture}



<main class="main">
  <div id="rec427745335" class="r t-rec" style=" " data-animationappear="off" data-record-type="131"><!-- T123 -->
    <div class="t123">
      <div class="t-container_100 ">
        <div class="t-width t-width_100 ">

          <section class="block">
            <div class="calculator-outer">
              <div class="calculator-background"></div>
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
                    <button id="calculator-button" class="calculator__button js-login-start" type="button">Войти</button>
                  </div>
                </div>

                <div id="password_block" style="display:none">
                  <div class="form-group form-password">
                    <span class="password_info -fs-14">Пароль</span>
                    <input type="password" name="password" id="password" class="form-control -fs-18 -gil-m js-login-password-input" value="" />
                    <div class="error_text js-login-password-error" style="display:none">Введите пароль</div>
                  </div>
                  <div class="form-group form-btn">
                    <button id="calculator-button" class="calculator__button js-login-password" type="button">Войти</button>
                  </div>
                  <div class="form-group form-btn">
                    <a href="reset_password" class="reset_password">Забыли пароль?</a>
                  </div>
                </div>

              </form>
            </div>


          </section>
        </div>
      </div>

    </div>
  </div>
</main>
