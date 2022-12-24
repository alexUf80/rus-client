{$meta_title='Персональные данные' scope=parent}

{capture name='page_scripts'}

  <script src="theme/site/js/form.app.js"></script>

{/capture}

{capture name='page_styles'}
    
{/capture}

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
              <p>На срок: <span class="days">{$user->first_loan_period} {$user->first_loan_period|plural:'день':'дней':'дня'}</span></p>
            </div>
            <div class="form_info_progress">
              <div class="form_info_progress_text -step1 active">До получения займа осталось: <span
                  class="step -green">4 этапа</span></div>
              <div class="form_info_progress_text -step2">До получения займа осталось: <span class="step -green">3
                  этапа</span></div>
              <div class="form_info_progress_text -step3">До получения займа осталось: <span class="step -green">2
                  этапа</span></div>
              <div class="form_info_progress_text -step4">До получения займа осталось: <span class="step -green">1
                  этап</span></div>
              <div class="form_info_progress_text -step5"><span class="step -green">Последний этап</span></div>
              <div class="form_info_progress_control -step"></div>
            </div>
          </div>
        </div>
        <div class="col-lg-7">
          <div class="main_form">
            <form action="" method="POST" class="regform js-form-app js-stage-personal-form">
              <div class="step_box step1">
                <div class="form_group -fs-18">
                  <div class="form_group-title -gil-m">ФИО как в паспорте:</div>
                  <div class="form_row">
                    <label class="input_box ">
                      <input type="text" class="form-control js-input-cirylic js-input-required" name="lastname" id="last_name" value="{$lastname|escape}" />
                      <span class="input_name {if $lastname}-top{/if}">Фамилия</span>
                    </label>
                    <label class="input_box">
                      <input type="text" class="form-control js-input-cirylic js-input-required" name="firstname" id="first_name" value="{$firstname|escape}" />
                      <span class="input_name {if $firstname}-top{/if}">Имя</span>
                    </label>
                  </div>
                  <div class="form_row">
                    <label class="input_box ">
                      <input type="text" class="form-control js-input-cirylic js-input-required" name="patronymic" id="patronym" value="{$patronymic|escape}" />
                      <span class="input_name {if $patronymic}-top{/if}">Отчество</span>
                    </label>
                    <label class="input_box ">
                      <input type="email" class="form-control js-input-required" name="email" id="email" value="{$email|escape}" />
                      <span class="input_name {if $email}-top{/if}">Емайл</span>
<!--div class="error_text">Не верный формат</div-->
                    </label>
                  </div>
                </div>
                <div class="form_group -fs-18 js-radio-required" data-msg="Укажите Ваш пол">
                  <div class="form_group-title -gil-m">Укажите ваш пол:</div>
                  <div class="form_row justify-content-start ">
                    <div class="form_radio_btn">
                      <input id="radio-1" type="radio" name="gender" value="male" {if $gender=='male'}checked{/if} />
                      <label for="radio-1">Мужской</label>
                    </div>
                    <div class="form_radio_btn">
                      <input id="radio-2" type="radio" name="gender" value="female" {if $gender=='female'}checked{/if} />
                      <label for="radio-2">Женский</label>
                    </div>
                  </div>
                </div>
                <div class="form_group -fs-18">
                  <div class="form_group-title -gil-m">Дата и место рождения:</div>
                  <div class="form_row">
                    <label class="input_box {if in_array('bad_birth', (array)$errors)}-error{/if}">
                      <input type="text" class="form-control js-mask-date js-input-required" name="birth" id="birth_date" value="{$birth}" />
                      <span class="input_name {if $birth}-top{/if}">Дата рождения</span>
                      {if in_array('bad_birth', $errors)}
                      <div class="error_text">Кредиты выдаются лицам старше 16 лет</div>
                      {/if}
                    </label>
                    <label class="input_box">
                      <input type="text" class="form-control js-input-required js-input-cirylic js-cirylic-plus" name="birth_place" id="birthplace" value="{$birth_place}" />
                      <span class="input_name {if $birth_place}-top{/if}">Место рождения</span>
                    </label>
                  </div>
                </div>
                <div class="form_group -fs-18">
                  <div class="form_group-title -gil-m">Социальные сети</div>
                  <div class="form_row">
                    <label class="input_box -fullwidth">
                      <input type="text" class="form-control" name="social" id="social" value="{$social}" />
                      <span class="input_name {if $social}-top{/if}">Ссылки на соц. сети</span>
                    </label>
                    <span class="-fs-14 -green ">Добавьте ссылку на аккаунт в социальных сетях и получите +15% к одобрению займа</span>
                  </div>
                </div>
                
                <div class="step_box_btn">
                  <a href="" class="btn btn_back btn-link -green -gil-m"></a>
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
