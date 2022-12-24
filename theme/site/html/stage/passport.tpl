{$meta_title='Паспортные данные' scope=parent}

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
              <div class="form_info_progress_text -step1">До получения займа осталось: <span
                  class="step -green">4 этапа</span></div>
              <div class="form_info_progress_text -step2 active">До получения займа осталось: <span class="step -green">3
                  этапа</span></div>
              <div class="form_info_progress_text -step3">До получения займа осталось: <span class="step -green">2
                  этапа</span></div>
              <div class="form_info_progress_text -step4">До получения займа осталось: <span class="step -green">1
                  этап</span></div>
              <div class="form_info_progress_text -step5"><span class="step -green">Последний этап</span></div>
              <div class="form_info_progress_control -step2"></div>
            </div>
          </div>
        </div>
        <div class="col-lg-7">
          <div class="main_form">
            <form action="" method="POST" class="regform js-form-app js-stage-passport-form">
              
              {if in_array('passport_found', (array)$errors)}
              
              <div class="alert alert-warning">
                Пользователь с такими паспортными данными уже зарегистрирован.<br />
                Войдите через <a href="lk/login">форму входа</a><br />
              </div>
              
              {else}
              
              <div class="step_box step2">
                <div class="form_group -fs-18">
                  <div class="form_group-title -gil-m">Паспортные данные:</div>
                  <div class="form_row">
                    <label class="input_box">
                      <input type="text" class="form-control js-mask-passport js-input-required" name="passport_serial" id="series" value="{$passport_serial}" />
                      <span class="input_name {if $passport_serial}-top{/if}">Серия и номер</span>
                    </label>
                    <label class="input_box ">
                      <input type="text" class="form-control js-mask-date js-input-required" name="passport_date" id="issue" value="{$passport_date}" />
                      <span class="input_name {if $passport_date}-top{/if}">Дата выдачи</span>
                    </label>
                  </div>
                  <div class="form_row">
                    <label class="input_box">
                      <input type="text" class="form-control js-input-cirylic js-cirylic-plus js-input-required" name="passport_issued" id="issued_by" value="{$passport_issued}" />
                      <span class="input_name {if $passport_issued}-top{/if}">Кем выдан</span>
                    </label>
                    <label class="input_box">
                      <input type="text" class="form-control js-mask-subdivision js-input-required" name="subdivision_code" id="department_code" value="{$subdivision_code}" />
                      <span class="input_name {if $subdivision_code}-top{/if}">Код подразделения</span>
                    </label>
                  </div>
                </div>
                <div class="form_group -fs-18">
                  <div class="form_group-title -gil-m">СНИЛС</div>
                  <div class="form_row">
                    <label class="input_box -fullwidth">
                      <input type="text" class="form-control js-mask-snils" name="snils" id="snils" value="{$snils}" />
                      <span class="input_name {if $snils}-top{/if}">СНИЛС</span>
                    </label>
                  </div>
                </div>
                <div class="step_box_btn">
                  <a href="/stage/passport?step=prev" class="btn btn_back btn-link -green -gil-m">Назад</a>
                  <button type="submit" class="btn btn_next btn-secondary">Далее</button>
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
