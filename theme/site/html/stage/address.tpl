{$meta_title="Адреса регистрации и проживания" scope=parent}

{capture name='page_scripts'}
  <script src="theme/site/libs/autocomplete/jquery.autocomplete-min.js"></script>
  <script src="theme/site/js/form.app.js"></script>
  <script src="theme/site/js/stage_address.app.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/suggestions-jquery@21.12.0/dist/js/jquery.suggestions.min.js"></script>
    <!--<script src="theme/site/js/dadata.app.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>-->
  <script>
      $(function () {
          let token_dadata = "25c845f063f9f3161487619f630663b2d1e4dcd7";

          $('.Regadress').suggestions({
              token: token_dadata,
              type: "ADDRESS",
              minChars: 3,
              /* Вызывается, когда пользователь выбирает одну из подсказок */
              onSelect: function (suggestion) {
                  $(this).val(suggestion.value);
                  $('.Registration').val(JSON.stringify(suggestion));
              }
          });

          $('.Faktaddress').suggestions({
              token: token_dadata,
              type: "ADDRESS",
              minChars: 3,
              /* Вызывается, когда пользователь выбирает одну из подсказок */
              onSelect: function (suggestion) {
                  $(this).val(suggestion.value);
                  $('.Fakt_adress').val(JSON.stringify(suggestion));
              }
          });
      });
  </script>
{/capture}

{capture name='page_styles'}
  <link rel="stylesheet" href="theme/site/libs/autocomplete/styles.css"/>
  <link href="https://cdn.jsdelivr.net/npm/suggestions-jquery@21.12.0/dist/css/suggestions.min.css" rel="stylesheet"/>
{/capture}
<script type="text/javascript">
  function validateСhange() {
    let regadressfull = document.querySelector('#Regadressfull');
    let value = regadressfull.value;
    let paren = regadressfull.parentElement;

    value = value.replace(/\s+/g, '');

    validateField (value, paren, regadressfull);


    let cloneAddress = document.querySelector('#clone_address');

    let faktadressfull = document.querySelector('#actual_address_toggle');
    value = faktadressfull.value;
    paren = faktadressfull.parentElement;

    value = value.replace(/\s+/g, '');

    if(cloneAddress.checked == false){  
      validateField (value, paren, faktadressfull);
    } 
    else{
      paren.classList.remove("-error");
      paren.classList.add("-ok");
    }
  }

  function validateField (value, paren, adressfull) {
    if ( value.length == 0 ) {
      adressfull.value = '';
      paren.classList.add("-error");
      return false;
    }
    paren.classList.remove("-error");
    paren.classList.add("-ok");
    return true;
  }

  function regadressfullOnblur (input) {

      let regadressfull = input;

      let value = regadressfull.value;
      let paren = regadressfull.parentElement;

      value = value.replace(/\s+/g, '');

      checkField (value, paren);
    }

  function faktadressfullOnblur (input) {
      let faktadressfull = input;

      let value = faktadressfull.value;
      let paren = faktadressfull.parentElement;

      value = value.replace(/\s+/g, '');

      checkField (value, paren);
    }

  function checkField (value, paren) {
    if ( value.length == 0 ) {
        setTimeout(() =>{
            if(paren.classList.contains("-ok"))
            paren.classList.remove("-ok");
            paren.classList.add("-error");
          }, 10);
      } else {
         setTimeout(() =>{
            if(paren.classList.contains("-error"))
            paren.classList.remove("-error");
            paren.classList.add("-ok");
          }, 10);
      }
  }
</script>

<main class="main">
  <div class="section section_form">
    <div class="container">
      <div class="section_form_row row">
        <div class="col-lg-5">
          <div class="main_form_info">
            <div class="form_info_title -fs-28">
              <span class="-black">Пройдите короткую регистрацию</span>
              <span class="-orange">чтобы получить займ</span>
            </div>
            <div class="form_info_subtitle">
              <p>Вы выбрали сумму: <span class="sum">{$user->first_loan_amount} рублей</span></p>
              <p>На срок: <span
                        class="days">{$user->first_loan_period} {$user->first_loan_period|plural:'день':'дней':'дня'}</span>
              </p>
            </div>
            <div class="form_info_progress">
              <div class="form_info_progress_text -step1">До получения займа осталось: <span
                        class="step -green">4 этапа</span></div>
              <div class="form_info_progress_text -step2">До получения займа осталось: <span
                        class="step -green">3
                  этапа</span></div>
              <div class="form_info_progress_text -step3 active">До получения займа осталось: <span
                        class="step -green">2
                  этапа</span></div>
              <div class="form_info_progress_text -step4">До получения займа осталось: <span
                        class="step -green">1
                  этап</span></div>
              <div class="form_info_progress_text -step5"><span class="step -green">Последний этап</span>
              </div>
              <div class="form_info_progress_control -step3"></div>
            </div>
          </div>
        </div>
        <div class="col-lg-7">
          <div class="main_form">
            <form action="" method="POST" class="regform js-form-app js-stage-address-form" onsubmit="return validateСhange()">

              <div class="step_box step3">
                <div class="form_group -fs-18 js-dadata-address">
                  <div class="form_group-title -gil-m">Адрес регистрации</div>
                  <label class="input_box">
                    <input class="form-control casing-upper-mask Regadress" name="Regadressfull" onblur="regadressfullOnblur(this);"
                            style="width: 500px;padding-right: 70px;" maxlength="500" type="text" id="Regadressfull"
                            {if !empty($Regaddressfull)}value="{$Regaddressfull->adressfull}"{/if}/>
                    <span class="input_name -top">Адрес регистрации</span>
                    <input style="display: none" class="Registration" name="Regadress"/>
                  </label>
                </div>
                <div class="form_group -fs-18 js-dadata-address js-dadata-okato">
                  <div class="form_group-title -gil-m">Адрес места жительства</div>
                  <div class="form_row">
                    <div class="check">
                      <input type="checkbox" class="custom-checkbox" id="clone_address"
                             name="clone_address" value="1" {if $equal_addresses}checked{/if} />
                      <label for="clone_address" class="check_box check_box_black -gil-m">
                        Совпадает с адресом места жительства
                      </label>
                    </div>
                  </div>
                  <div class="js-regaddress-block">
                    <label class="input_box">
                      <input class="form-control casing-upper-mask Faktaddress" onblur="faktadressfullOnblur(this);"
                           id="actual_address_toggle" maxlength="500" name="Faktaddressfull"
                           style="width: 500px;"
                           name="Faktadressfull"
                           {if !empty($Faktaddressfull)}value="{$Faktaddressfull->adressfull}"{/if}
                           type="text"/>
                      <span class="input_name -top">Адрес места жительства</span>
                    </label>
                    <input style="display: none" class="Fakt_adress" name="Fakt_adress"/>
                  </div>
                </div>
                <div class="step_box_btn">
                  <a href="stage/address?step=prev"
                     class="btn btn_back btn-link -green -gil-m">Назад</a>
                  <button type="submit" class="btn btn_next btn-secondary">Далее</button>
                </div>
              </div>

            </form>
          </div>
        </div>
      </div>

    </div>
  </div>
</main>
