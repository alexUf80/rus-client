{$meta_title="Информация о работе" scope=parent}

{capture name='page_scripts'}
    <script src="theme/site/libs/autocomplete/jquery.autocomplete-min.js"></script>
    <script src="theme/site/js/dadata.app.js"></script>
    <script src="theme/site/js/form.app.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script>
        $(".ss").keypress(function (event) {
            event = event || window.event;
            if (event.charCode && event.charCode != 0 && event.charCode != 46 && (event.charCode < 48 || event.charCode > 57))
                return false;
        });
    </script>
{/capture}

{capture name='page_styles'}
    <link rel="stylesheet" href="theme/site/libs/autocomplete/styles.css"/>
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
                            <p>На срок: <span
                                        class="days">{$user->first_loan_period} {$user->first_loan_period|plural:'день':'дней':'дня'}</span>
                            </p>
                        </div>
                        <div class="form_info_progress">
                            <div class="form_info_progress_text -step1">До получения займа осталось: <span
                                        class="step -green">4 этапа</span></div>
                            <div class="form_info_progress_text -step2">До получения займа осталось: <span
                                        class="step -green">3 этапа</span></div>
                            <div class="form_info_progress_text -step3">До получения займа осталось: <span
                                        class="step -green">2 этапа</span></div>
                            <div class="form_info_progress_text -step4 active">До получения займа осталось: <span
                                        class="step -green">1 этап</span></div>
                            <div class="form_info_progress_text -step5"><span class="step -green">Последний этап</span>
                            </div>
                            <div class="form_info_progress_control -step4"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="main_form">
                        <!--js-form-app js-stage-work-form-->
                        <script type="text/javascript">
                       
                         function validateСhange2() {
                                let check = 0;

                                let work_phone = document.querySelector('#work_phone');
                                let average_pay = document.querySelector('#average_pay');
                                let amount_pay = document.querySelector('#amount_pay');
                                
                                let paren = work_phone.parentElement;
                                let paren_average_pay = average_pay.parentElement;
                                let paren_amount_pay = amount_pay.parentElement;

                                work_phone = work_phone.value;
                                average_pay = average_pay.value;
                                amount_pay = amount_pay.value;
                                if (work_phone.length < 11) {
                                    setTimeout(() =>{
                                        paren.classList.remove("-ok");
                                        paren.classList.add("-error");
                                     }, 100);
                                    check = 1;
                                }
                                if (average_pay.length == 0) {
                                    setTimeout(() =>{
                                        paren_average_pay.classList.remove("-ok");
                                        paren_average_pay.classList.add("-error");
                                     }, 100);
                                    check = 1;
                                }
                                if (amount_pay.length == 0) {
                                    setTimeout(() =>{
                                        paren_amount_pay.classList.remove("-ok");
                                        paren_amount_pay.classList.add("-error");
                                     }, 100);
                                    check = 1;
                                }
                                if (check) return false;
                                return true;
                            }
                        </script>
                        <form action="" method="POST" class="regform js-form-app js-stage-work-form" onsubmit="return validateСhange2()">

                            <input type="hidden" name="juicescore_session_id" id="juicescore_session_id" value=""/>

                            <div class="step_box step4">
                                <div class="form_group -fs-18 js-dadata-work">
                                    <input type="hidden" name="workaddress" class="js-dadata-company-address"
                                           value="{$workaddress|escape}"/>
                                    <div class="form_group-title -gil-m">Информация о работе</div>
                                    <div class="form_row">
                                        <label class="input_box -fullwidth">
                                            <input type="text" style="padding-right: 80px;" class="form-control js-input-required js-dadata-company"
                                                   name="workplace"  maxlength="500" id="company" value="{$workplace|escape}"/>
                                            <span class="input_name {if $workplace}-top{/if}">Компания</span>
                                        </label>
                                    </div>
                                    <div class="form_row">
                                        <label class="input_box">
                                            <input maxlength="500" type="text" class="form-control js-input-required" name="profession"
                                                   id="post" value="{$profession|escape}"/>
                                            <span class="input_name {if $profession}-top{/if}">Должность</span>
                                        </label>
                                        <label class="input_box">
                                            <!--<input type="text"
                                                   class="form-control js-mask-phone js-input-required js-dadata-phone"
                                                   name="workphone" id="work_phone" value="{$workphone}"/>-->
                                                   <input type="number" oninput="phoneInput(this);" onblur="phoneOnblur(this);" 
                                                   class="form-control  js-input-required "
                                                   name="workphone" id="work_phone" value="{$workphone}" placeholder="7(___)___-__-__"/>
                                                    
                                            <!--<span style="margin-top: -20px;" class="input_name {if $workphone}-top{/if}">Рабочий телефон</span>-->
                                        </label>    
                                    </div>
                                    <div class="form_row">
                                        <label class="input_box">
                                            <input type="number" class="ss form-control js-input-digits js-input-required"
                                                   name="income" id="income" value="{$income}"/>
                                            <span class="input_name {if $income}-top{/if}">Ежемесячный доход</span>
                                        </label>
                                        <label class="input_box">
                                            <input type="number" class="form-control js-input-digits js-input-required"
                                                   name="expenses" id="expenses" value="{$expenses}"/>
                                            <span class="input_name {if $expenses}-top{/if}">Ежемесячные расходы</span>
                                        </label>
                                    </div>
                                    <div class="form_row">
                                        <label class="input_box">
                                            <input onclick="bigInput(this);" type="number" class="form-control js-input-digits" id="average_pay" name="average_pay"
                                                   id="average_pay" value="{$average_pay}"/>
                                            <span class="input_name {if $average_pay}-top{/if}">Среднемесячный платеж по кредитам и займам</span>
                                        </label>
                                        <label class="input_box">
                                            <input onclick="bigInput(this);" type="number" class="form-control js-input-digits" id="amount_pay" name="amount_pay"
                                                   id="amount_pay" value="{$amount_pay}"/>
                                            <span class="input_name {if $amount_pay}-top{/if}">Сумма просроченных кредитов и займов</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="step_box_btn">
                                    <a href="/stage/work?step=prev"
                                       class="btn btn_back btn-link -green -gil-m">Назад</a>
                                    <button type="submit" id="next_stage" class="btn btn_next btn-secondary">Далее
                                    </button>
                                </div>

                            </div>


                        </form>
                    </div>
                </div>
            </div>


        </div>
    </div>
</main>

<script type="text/javascript">
    var juicyLabConfig = {
        completeButton: "#next_stage"
    };
</script>
<script type="text/javascript">
    var s = document.createElement('script');
    s.type = 'text/javascript';
    s.async = true;
    s.src = "https://score.juicyscore.net/static/js.js";
    var x = document.getElementsByTagName('head')[0];
    x.appendChild(s);
</script>
<noscript><img style="display:none;" src="https://score.juicyscore.net/savedata/?isJs=0"/></noscript>
<script>
    window.addEventListener('sessionready', function (e) {
        console.log('sessionready', e.detail.sessionId)
        $('#juicescore_session_id').val(e.detail.sessionId)
    })

</script>
<script type="text/javascript">
    let work_phone = document.querySelector('#work_phone');
    let value = work_phone.value;

    if (value[0] != '7') {
        work_phone.value = '7';
    } 
 </script>
