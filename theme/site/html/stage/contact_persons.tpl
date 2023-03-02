{$meta_title='Контактные лица' scope=parent}

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
            <!--<div class="form_info_progress">
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
            </div>-->
          </div>
        </div>
        <div class="col-lg-7">
          <div class="main_form">
            <form action="" method="POST" class="regform js-form-app js-stage-personal-form">
              <div class="step_box step1">
                <div class="form_group -fs-18">
                  <div class="form_group-title -gil-m">Введите данные онтактного лица:</div>
                  <div class="form_row">
                    <label class="input_box ">
                      <input type="text" class="form-control js-input-cirylic js-input-required" name="fio" id="last_name" value="{$fio|escape}" />
                      <span class="input_name {if $lastname}-top{/if}">ФИО</span>
                    </label>
                    <label class="input_box ">
                      <!--<input type="text" class="form-control js-input-cirylic js-input-required" name="phone" id="last_name" value="{$lastname|escape}" />-->
                        <input type="text" oninput="phoneInput(this);" onblur="phoneOnblur(this);" 
                        class="form-control  js-input-required "
                        name="phonePersons" id="phonePersons" value="{$phone}"/>
                        <span style="margin-left: 15px;" class="input_name {if $lastname}-top{/if}">Номер телефона</span>
                    </label>
                  </div>
                  <div class="form_row" style="margin-top: 10px;">
                    <label class="input_box" style="width: 100%;">
                      <!--<input type="text" class="form-control js-input-cirylic js-input-required" name="firstname" id="first_name" value="{$firstname|escape}" />-->
                        <div style="display: flex;justify-content: space-between;">
                          <div><span style="margin-top: 6px;" class="">Кем приходится:</span></div>
                          <div>
                            <select class="form-control js-input-cirylic js-input-required" name="relation">
                                    <option value="none" selected="">Выберите из списка</option>
                                    <option value="мать/отец">мать/отец</option>
                                    <option value="муж/жена">муж/жена</option>
                                    <option value="сын/дочь">сын/дочь</option>
                                    <option value="коллега">коллега</option>
                                    <option value="друг/сосед">друг/сосед</option>
                                    <option value="иной родственник">иной родственник</option>
                            </select>
                          </div>
                        </div>
                        
                      <!--<span class="input_name {if $firstname}-top{/if}">Кем приходится</span>-->
                    </label>
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
  <script type="text/javascript">
    let work_phone = document.querySelector('#phonePersons');
    let value = work_phone.value;

    if (value[0] != '7') {
        work_phone.value = '7';
    } 
 </script>
</main>
