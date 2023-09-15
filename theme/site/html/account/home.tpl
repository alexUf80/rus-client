{$meta_title='Личный кабинет' scope=parent}

{capture name='page_scripts'}
    <script src="theme/site/js/calc.app.js?v=1.09"></script>
    <script src="theme/site/js/lk.app.js?v=1.10"></script>
    <script src="theme/site/js/contract_accept.app.js?v=1.09"></script>
    <script>
        $(function () {

            let clickClose = 0;

            $('.closeContract').on('click', function (e) {
                e.preventDefault();

                if(clickClose > 0)
                    return 1;

                clickClose += 1;

                let form = $(this).closest('form').serialize();

                $.ajax({
                    url: 'ajax/best2pay.php',
                    data: form,
                    success: function (resp) {
                    }
                });
            });
        });
    </script>
{/capture}

{capture name='page_styles'}

{/capture}

<main class="main js-lk-app">
    <div class="section_lk_navbar">
        <div class="container">
            <nav class="navbar lk_menu">
                <ul class="nav lk_menu_nav -gil-m">
                    <li class="nav-item active">
                        <a class="nav-link" href="account">Общая информация</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="account/history">История займов</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="account/cards">Банковские карты</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="account/data">Личные данные</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="account/docs">Документы</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="lk/logout">Выйти</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    <div class="content_wrap">
        <div class="container">
            <div style="display: flex">
                <h1>Личный кабинет</h1>
                <!-- общая информация -->
                {if !empty($warning_card)}
                    <div style="margin: 15px 50px; color: #ff0009">
                        {$warning_card}
                    </div>
                {/if}
            </div>

            {if $user_balance1c}
                <div class="new_order_box " data-status="{$order->status}" data-order="{$order->order_id}">
                    <div class="row">
                        <div class="col-12">
                            <div class="-fs-32 -gil-b -green text-center pb-3">
                                У Вас есть активный займ от {$user_balance1c->loan_date|date}г.
                            </div>
                            {if $order->contract->status == 4}
                                <div class="-fs-32 -gil-b -red text-center pb-3">
                                    Ваш займ просрочен!
                                </div>
                            {/if}
                        </div>
                        <div class="col-md-6 pt-4">
                            <dl class="row pb-2 border-bottom">
                                <dd class="col-6 text-left">Номер договора</dd>
                                <dt class="col-6 text-right">{$user_balance1c->number|escape}</dt>
                            </dl>
                            <dl class="row pb-2 border-bottom">
                                <dd class="col-6 text-left">Основной долг</dd>
                                <dt class="col-6 text-right">{($user_balance1c->loan_body_summ)|convert} руб.</dt>
                            </dl>
                            <dl class="row pb-2 border-bottom">
                                <dd class="col-6 text-left">Проценты</dd>
                                <dt class="col-6 text-right">{($user_balance1c->loan_percents_summ)|convert} руб.</dt>
                            </dl>
                            {if $user_balance1c->loan_peni_summ > 0}
                                <dl class="row pb-2 border-bottom">
                                    <dd class="col-6 text-left">Пени</dd>
                                    <dt class="col-6 text-right">{($user_balance1c->loan_peni_summ)|convert} руб.</dt>
                                </dl>
                            {/if}
                            {*}
                            <dl class="row pb-2">
                              <dd class="col-6 text-left">Дата возврата</dd>
                              <dt class="col-6 text-right">{$order->contract->return_date|date} </dt>
                            </dl>
                            <dl class="row pb-2 border-bottom">
                              <dd class="col-6 text-left">Сумма на дату возврата</dd>
                              <dt class="col-6 text-right">{$order->contract->return_amount|convert} руб.</dt>
                            </dl>
                            {*}
                        </div>
                        <div class="col-md-6">

                            <div class="pt-4 pr-5 pl-5 text-center">

                            </div>
                            <div class="pt-4 text-center">
                                <form action="account/pay" method="POST" class="border rounded">
                                    <input type="hidden" name="user_balance_id" value="{$user_balance1c->id}"/>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="row">
                                                <div class="col-12">
                                                    <input type="text" class="form-control text-right" name="amount"
                                                           value="{$user_balance1c->loan_body_summ + $user_balance1c->loan_percents_summ + $user_balance1c->loan_peni_summ}"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 pt-1">
                                            <button type="submit" class="btn btn-primary btn-block">Погасить</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            {*
                            {if $prolongation_amount && $show_prolongation}
                                <div class="pt-4 text-center">
                                    <form action="account/pay" method="POST" class="border rounded">
                                        <input type="hidden" name="contract_id" value="{$order->contract->id}"/>
                                        <input type="hidden" name="prolongation" value="1"/>
                                        <input type="hidden" name="code" value=""/>
                                        <h3 class="mb-0">Пролонгация</h3>
                                        <span class="text-muted">до: {$order->prolongation_date}</span>
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <input type="text" readonly=""
                                                               style="background-color: #fbfbfb;"
                                                               class="form-control text-right" name="amount"
                                                               value="{1 * $prolongation_amount}"
                                                               min="{$prolongation_amount}"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6 pt-1">
                                                <button type="submit" class="btn btn-primary btn-block">Продлить</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            {/if}
                            *}
                        </div>
                    </div>
                </div>
            {elseif $order->contract->sud}
                <div class="new_order_box " data-status="{$order->status}" data-order="{$order->order_id}">
                    <div class="row">
                        <div class="col-12">
                            <div class="-fs-32 -gil-b -red text-center pb-3">
                                У Вас есть активный займ от {$order->contract->create_date|date}г.
                                <br/>
                                Ваш договор передан в суд
                            </div>
                        </div>
                        <div class="col-md-6 pt-4">
                        </div>
                        <div class="col-md-6">

                            <div class="pt-4 pr-5 pl-5 text-center">

                            </div>


                        </div>
                    </div>
                </div>
            {elseif $order}

                {* Заявка на рассмотрении *}
                {if $order->status < 2}
                    <div class="new_order_box js-check-status" data-status="{$order->status}"
                         data-order="{$order->order_id}">
                        <div class="row">
                            <div class="col-12">
                                <div class="-fs-32 -gil-b -green text-center pb-3">Ваша заявка рассматривается</div>
                            </div>
                            <div class="col-md-6 pt-4">
                                <dl class="row pb-2 border-bottom">
                                    <dd class="col-6 text-left">Номер заявки</dd>
                                    <dt class="col-6 text-right">{$order->order_id}</dt>
                                </dl>
                                <dl class="row pb-2 border-bottom">
                                    <dd class="col-6 text-left">Дата</dd>
                                    <dt class="col-6 text-right">{$order->date|date}</dt>
                                </dl>
                                <dl class="row pb-2 border-bottom">
                                    <dd class="col-6 text-left">Сумма</dd>
                                    <dt class="col-6 text-right">{$order->amount|convert} руб.</dt>
                                </dl>
                                <dl class="row pb-2">
                                    <dd class="col-6 text-left">Срок</dd>
                                    <dt class="col-6 text-right">{$order->period} {$order->period|plural:'день':'дней':'дня'}</dt>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <div class="time_preloader"></div>
                                {*}
                                <div class="pt-4 pr-5 pl-5 text-center">Ожидайте, в ближайшее время поступит автоматический звонок</div>
                                {*}
                            </div>
                        </div>
                    </div>
                {/if}

                {* заявка одобрена *}
                {if $order->status == 2}
                    <div class="new_order_box  js-check-status" data-status="{$order->status}"
                         data-order="{$order->order_id}">
                        <div class="row">
                            <div class="col-12">
                                <div class="-fs-32 -gil-b -green text-center pb-3">
                                    {if $order->contract->status == 0}
                                        Вам одобрен кредит {$order->amount|convert} руб. на {$order->period} {$order->period|plural:'день':'дней':'дня'}
                                    {else}
                                        Договор подписан. Через несколько минут мы отправим деньги на карту.
                                    {/if}
                                </div>
                            </div>
                            <div class="col-md-6 pt-4">
                                <dl class="row pb-2 border-bottom">
                                    <dd class="col-6 text-left">Номер заявки</dd>
                                    <dt class="col-6 text-right">{$order->order_id}</dt>
                                </dl>
                                <dl class="row pb-2 border-bottom">
                                    <dd class="col-6 text-left">Одобренная сумма</dd>
                                    <dt class="col-6 text-right">{$order->amount|convert} руб.</dt>
                                </dl>
                                <dl class="row pb-2 border-bottom">
                                    <dd class="col-6 text-left">Сумма к возврату</dd>
                                    <dt class="col-6 text-right">{$order->return_amount|convert} руб.</dt>
                                </dl>
                                <dl class="row pb-2">
                                    <dd class="col-6 text-left">Дата возврата</dd>
                                    <dt class="col-6 text-right">{$order->return_period|date} </dt>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                {if $order->contract->status == 0}
                                    <div class="order_accept_icon"></div>
                                    {*}
                                    <div class="pt-4 pr-5 pl-5 text-center">
                                        Перейдите к подписанию договора
                                    </div>
                                    <div class="pt-4 text-center">
                                        <a href="accept/{$order->contract_id}" class="btn btn-primary">Подписать договор</a>
                                    </div>
                                    {*}
                                    <form id="accept_credit_form" data-phone="{$user->phone_mobile}">
                                        <input type="hidden" name="contract_id" value="{$order->contract_id}"/>
                                        <input type="hidden" name="phone" value="{$user->phone_mobile}"/>

                                        <div class="form-group">
                                            <div class="form_row">
                                                <div class="check mb-0 js-loan-agreement-block">
                                                    <input type="checkbox" class="custom-checkbox js-loan-agreement"
                                                           id="check_agreement" name="agreement" value="1"/>
                                                    <label for="check_agreement" class="check_box -gil-m">
                                                        <span>Я ознакомлен и согласен со <a href="#agreement_list"
                                                                                            class="green-link js-toggle-agreement-list">следующим</a></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        {include file='agreement_list.tpl'}

                                        <div class="form-group">
                                            <div class="form-phone">
                                                <label class="phone_info -fs-14" for="accept_code">Код активации из
                                                    СМС</label>
                                                <input type="number" oninput="handleChange(this);" placeholder=""
                                                       name="accept_code" id="accept_code"
                                                       class="js-accept-code form-control" value=""/>
                                                <a class="js-repeat-accept-code" href="javascript:void(0);">отправить
                                                    еще раз <span class="js-accept-timer"></span></a>

                                                <div class="js-accept-code-error -red"></div>
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <a href="javascript:void(0);" class="btn btn-primary js-accept-contract">Подписать
                                                договор</a>
                                        </div>
                                        <div class="hide">
                                        </div>
                                    </form>
                                {else}
                                    <div class="time_preloader"></div>
                                    <div class="pt-4 pr-5 pl-5 text-center">Ожидайте перевода займа на карту.</div>
                                {/if}
                            </div>
                        </div>
                    </div>
                {/if}

                {* договор подписан *}
                {if $order->status == 4}
                    <div class="new_order_box js-check-status" data-status="{$order->status}"
                         data-order="{$order->order_id}">
                        <div class="row">
                            <div class="col-12">
                                <div class="-fs-32 -gil-b -green text-center pb-3">
                                    Договор подписан. Через несколько минут мы отправим деньги на карту.
                                </div>
                            </div>
                            <div class="col-md-6 pt-4">
                                <dl class="row pb-2 border-bottom">
                                    <dd class="col-6 text-left">Номер договора</dd>
                                    <dt class="col-6 text-right">{$order->contract->id}</dt>
                                </dl>
                                <dl class="row pb-2 border-bottom">
                                    <dd class="col-6 text-left">Одобренная сумма</dd>
                                    <dt class="col-6 text-right">{$order->amount|convert} руб.</dt>
                                </dl>
                                <dl class="row pb-2 border-bottom">
                                    <dd class="col-6 text-left">Сумма к возврату</dd>
                                    <dt class="col-6 text-right">{$order->return_amount|convert} руб.</dt>
                                </dl>
                                <dl class="row pb-2">
                                    <dd class="col-6 text-left">Дата возврата</dd>
                                    <dt class="col-6 text-right">{$order->return_period|date} </dt>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <div class="time_preloader"></div>
                                <div class="pt-4 pr-5 pl-5 text-center">Ожидайте перевода займа на карту.</div>
                            </div>
                        </div>
                    </div>
                {/if}

                {* кредитный доктор *}
                {*}
                {*}
                {if $order->status == 3 || $order->status == 8}
                    {if !$cards}
                        <div class="-fs-24 -gil-b -red text-center pb-3">
                            Для получения займа необходимо привязать карту
                            <div class="pt-3">
                                <a href="/account/cards" class="btn btn-primary">Привязать карту</a>
                            </div>
                        </div>
                    
                    {else}
                        <div class="new_order_box js-new-order-doctor" data-status="3">
                            <div class="row">
                                <div class="col-lg-2 col-md-0">
                                </div>
                                {if $loan_doctor_steps_count > $user->loan_doctor}
                                    <div class="col-lg-8 col-md-12">
                                        <div class="-fs-32 -gil-b text-center pb-3" style="color:#bd9457">Вам согласован займ по специальному тарифу</div>
                                    </div>
                                {/if}
                                <div class="col-lg-2 col-md-0">
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-lg-2  col-md-1 col-sm-0">
                                </div>
                                <div class="col-lg-4  col-md-5 col-sm-6">
                                    <div class="col-12 p-0 pt-1">
                                        {if $loan_doctor_steps_count <= $user->loan_doctor}
                                            <button type="button" class="btn btn-primary w-100 h-100 p-2" style="background-color: #B7B7B7; cursor: auto">
                                                Все шаги <br> кредитного доктора пройдены
                                            </button>
                                        {else}
                                            <button type="button" class="btn btn-success js-open-loan-doctor-block w-100 h-100 p-2">
                                                {if $user->loan_doctor == 0}
                                                    Получить займ <br> по специальному тарифу
                                                {else}
                                                    Кредитный доктор <br> шаг {$user->loan_doctor + 1}
                                                {/if}
                                            </button>
                                        {/if}
                                    </div>
                                </div>
                                <div class="col-lg-4  col-md-5 col-sm-6">
                                        <div class="col-12 p-0 pt-1  w-100 h-100">
                                        {if $reject_block}
                                            <button type="button" class="btn btn-primary w-100 h-100 p-2" style="background-color: #B7B7B7; cursor: auto">
                                                Подать заявку<br>
                                                Будет доступно {$reject_block|date}
                                            </button>
                                        {else}
                                            <button type="button" class="btn btn-primary js-open-repeat-block w-100 h-100 p-2" style="background-color: #1948C0">
                                                Подать заявку
                                            </button>
                                        {/if}
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-1 col-sm-0">
                                </div>
                            </div>

                            <div class="row mt-5">
                                <div class="col-12 text-center ">
                                    <a href="https://luchshie-zaymi.ru" target="_blank" class="blue-link">
                                        Получите займ у наших партнёров
                                        <br/>
                                        <span class="sc-htoDjs fOeadG"></span>
                                        <span class="sc-iwsKbI jQhHGA">Лучшие предложения для вас</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="new_order_box hide js-order-doctor">
                            <div class="-fs-32 -gil-b" style="color:#bd9457">
                                Специальное предложение<br> – Улучшение кредитной истории
                            </div>
                            <p class="doctor-p pt-3">При принятии решения об одобрении займа, наш сервис анализирует множество параметров. Поэтому мы одобряем займы клиентам не только с хорошей кредитной историей, но и, в некоторых случаях, с пустой или проблемной. Для клиентов с пустой кредитной историей мы даем возможность ее создать, а для клиентов с проблемной кредитной историей - шанс ее улучшить.</p>
                            <div class="steps mt-5">
                                <div class="steps-step">
                                    <div class="steps-img">
                                        <img src="theme/site/new/doctor/step1.svg" alt="">
                                    </div>
                                    <div class="steps-text">
                                        Оформление услуги
                                    </div>
                                </div>
                                <div class="steps-arrow">
                                    <img src="theme/site/new/doctor/steps-arrow.svg" alt="">
                                </div>
                                <div class="steps-step">
                                    <div class="steps-img">
                                        <img src="theme/site/new/doctor/step2.svg" alt="">
                                    </div>
                                    <div class="steps-text">
                                        Получение займа
                                    </div>
                                </div>
                                <div class="steps-arrow">
                                    <img src="theme/site/new/doctor/steps-arrow.svg" alt="">
                                </div>
                                <div class="steps-step">
                                    <div class="steps-img">
                                        <img src="theme/site/new/doctor/step3.svg" alt="">
                                    </div>
                                    <div class="steps-text">
                                        Возвращение займа без просрочки
                                    </div>
                                </div>
                                <div class="steps-arrow">
                                    <img src="theme/site/new/doctor/steps-arrow.svg" alt="">
                                </div>
                                <div class="steps-step">
                                    <div class="steps-img">
                                        <img src="theme/site/new/doctor/step4.svg" alt="">
                                    </div>
                                    <div class="steps-text">
                                        Повышение кредитного рейтинга - открытие новой ступени
                                    </div>
                                </div>
                            </div>
                            <div class="degress">
                                <div class="-fs-32 -gil-b degress-title" style="color:#bd9457">
                                    Ступени
                                </div>
                                <div class="degress-items">
                                    <div class="degress-item">
                                        <div class="degress-number" style="color: #FF5F5F;">
                                            01
                                        </div>
                                        <div class="degress-color" style="background: #FF5F5F; margin-left: 24px;">
                                        </div>
                                        <div class="degress-text">
                                            <div class="degress-big-text">
                                                Стоимость услуги = 2000 руб.
                                            </div>
                                            <div class="degress-small-text">
                                                Выдача займа = 1000 руб.<br>
                                                Беспроцентный срок погашения = 14 дней
                                            </div>
                                        </div>
                                    </div>
                                    <div class="degress-item">
                                        <div class="degress-number degress-number-second" style="color: #CDCDCD;">
                                            02
                                        </div>
                                        <div class="degress-color degress-color-second" style="background: #CDCDCD; margin-left: 54px;">
                                        </div>
                                        <div class="degress-text">
                                            <div class="degress-big-text">
                                                Стоимость услуги = 3000 руб.
                                            </div>
                                            <div class="degress-small-text">
                                                Выдача займа = 2000 руб.<br>
                                                Беспроцентный срок погашения = 14 дней
                                            </div>
                                        </div>
                                    </div>
                                    <div class="degress-items-hide hide">
                                        <div class="degress-item">
                                            <div class="degress-number" style="color: #FFA25F;">
                                                03
                                            </div>
                                            <div class="degress-color" style="background: #FFA25F; margin-left: 84px;">
                                            </div>
                                            <div class="degress-text">
                                                <div class="degress-big-text">
                                                    Стоимость услуги = 4000 руб.
                                                </div>
                                                <div class="degress-small-text">
                                                    Выдача займа = 3000 руб.<br>
                                                    Беспроцентный срок погашения = 14 дней
                                                </div>
                                            </div>
                                        </div>
                                        <div class="degress-item">
                                            <div class="degress-number" style="color: #FFDC5F">
                                                04
                                            </div>
                                            <div class="degress-color" style="background: #FFDC5F; margin-left: 114px;">
                                            </div>
                                            <div class="degress-text">
                                                <div class="degress-big-text">
                                                    Стоимость услуги = 5000 руб.
                                                </div>
                                                <div class="degress-small-text">
                                                    Выдача займа = 4000 руб.<br>
                                                    Беспроцентный срок погашения = 14 дней
                                                </div>
                                            </div>
                                        </div>
                                        <div class="degress-item">
                                            <div class="degress-number" style="color: #F2FF5F">
                                                05
                                            </div>
                                            <div class="degress-color" style="background: #F2FF5F; margin-left: 144px;">
                                            </div>
                                            <div class="degress-text">
                                                <div class="degress-big-text">
                                                    Стоимость услуги = 6000 руб.
                                                </div>
                                                <div class="degress-small-text">
                                                    Выдача займа = 5000 руб.<br>
                                                    Беспроцентный срок погашения = 14 дней
                                                </div>
                                            </div>
                                        </div>
                                        <div class="degress-item">
                                            <div class="degress-number" style="color: #D5FF5F">
                                                06
                                            </div>
                                            <div class="degress-color" style="background: #D5FF5F; margin-left: 174px;">
                                            </div>
                                            <div class="degress-text">
                                                <div class="degress-big-text">
                                                    Стоимость услуги = 7000 руб.
                                                </div>
                                                <div class="degress-small-text">
                                                    Выдача займа = 6000 руб.<br>
                                                    Беспроцентный срок погашения = 14 дней
                                                </div>
                                            </div>
                                        </div>
                                        <div class="degress-item">
                                            <div class="degress-number" style="color: #D5FF5F">
                                                07
                                            </div>
                                            <div class="degress-color" style="background: #D5FF5F; margin-left: 204px;">
                                            </div>
                                            <div class="degress-text">
                                                <div class="degress-big-text">
                                                    Стоимость услуги = 8000 руб.
                                                </div>
                                                <div class="degress-small-text">
                                                    Выдача займа = 7000 руб.<br>
                                                    Беспроцентный срок погашения = 14 дней
                                                </div>
                                            </div>
                                        </div>
                                        <div class="degress-item">
                                            <div class="degress-number" style="color: #B8FF5F">
                                                08
                                            </div>
                                            <div class="degress-color" style="background: #B8FF5F; margin-left: 234px;">
                                            </div>
                                            <div class="degress-text">
                                                <div class="degress-big-text">
                                                    Стоимость услуги = 9000 руб.
                                                </div>
                                                <div class="degress-small-text">
                                                    Выдача займа = 8000 руб.<br>
                                                    Беспроцентный срок погашения = 14 дней
                                                </div>
                                            </div>
                                        </div>
                                        <div class="degress-item">
                                            <div class="degress-number" style="color: #88FF5F">
                                                09
                                            </div>
                                            <div class="degress-color" style="background: #88FF5F; margin-left: 264px;">
                                            </div>
                                            <div class="degress-text">
                                                <div class="degress-big-text">
                                                    Стоимость услуги = 10 000 руб.
                                                </div>
                                                <div class="degress-small-text">
                                                    Выдача займа = 9000 руб.<br>
                                                    Беспроцентный срок погашения = 14 дней
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-success js-loan_doctor_degress p-2 pl-4 pr-4 mt-4" style="background: #19C034;">
                                    Показать все ступени
                                </button>
                            </div>
                            <div class="degress-about">
                                <div class="-fs-32 -gil-b degress-about-title" style="color:#bd9457">
                                    Подробнее о предложении
                                </div>

                                <div class="degress-about-items">
                                    <div class="degress-about-item">
                                        <div class="degress-about-big-text">
                                            Как это работает
                                            <span class="degress-h"></span>
                                            <span class="degress-v"></span>
                                        </div>
                                        <div class="degress-about-text hide">
                                            Информация обо всех полученных и возвращенных займах передается в Бюро Кредитных Историй, поэтому когда вы возвращаете займ в срок, вы формируете положительную кредитную историю и повышаете свой кредитный рейтинг. Сформировав положительную кредитную историю и высокий кредитный рейтинг, вы повышаете шансы на одобрение банковских кредитов и иных займов в будущем.
                                        </div>
                                    </div>
                                    <div class="degress-about-item">
                                        <div class="degress-about-big-text">
                                            Что требуется от меня
                                            <span class="degress-h"></span>
                                            <span class="degress-v"></span>
                                        </div>
                                        <div class="degress-about-text hide">
                                            Требуется
                                        </div>
                                    </div>
                                    <div class="degress-about-item">
                                        <div class="degress-about-big-text">
                                            Возможно ли гарантировать улучшение
                                            <span class="degress-h"></span>
                                            <span class="degress-v"></span>
                                        </div>
                                        <div class="degress-about-text hide">
                                            Возможно
                                        </div>
                                    </div>
                                    <div class="degress-about-item">
                                        <div class="degress-about-big-text">
                                            Главные признаки «хорошего» заемщика
                                            <span class="degress-h"></span>
                                            <span class="degress-v"></span>
                                        </div>
                                        <div class="degress-about-text hide">
                                            Признаки
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {if $loan_doctor_steps_count <= $user->loan_doctor}
                                <div class="-fs-32 -gil-b degress-about-title" style="color:#bd9457">
                                    Все шаги пройдены
                                </div>
                            {else}
                                <div class="-fs-32 -gil-b degress-about-title" style="color:#bd9457">
                                    Кредитный доктор шаг {$user->loan_doctor + 1}
                                </div>

                                <form class="calculator js-loan-doctor-form js-calc mt-4" method="POST" data-percent="{$loan_percent}">

                                    <input type="hidden" name="local_time" class="js-local-time" value=""/>
                                    <input type="hidden" name="juicescore_session_id" id="juicescore_session_id" value=""/>
                                    <input type="hidden" name="loan_doctor_step" id="loan_doctor_step" value="{$user->loan_doctor + 1}"/>
                                    <input type="hidden" name="phone" id="phone" value="{$user->phone_mobile}"/>
                                    <input type="hidden" name="sms" class="js-loan-code" value=""/>

                                    <div class="row">
                                        <div class="col-md-7">
                                            <div class="form-group form-group-one">
                                                <div class="form_row">
                                                    <label class="form-group-title -fs-18 -gil-m" for="amount-one">
                                                        Сумма займа:
                                                    </label>
                                                    <span class="range_res -fs-26 -gil-b js-info-summ" id="demo"></span>
                                                </div>
                                                <div class="amount" style="display: none">
                                                    <input type="range" name="amount" min="{$loan_doctor_steps[$user->loan_doctor+1]}" max="{$loan_doctor_steps[$user->loan_doctor+1]}" 
                                                        value="{$loan_doctor_steps[$user->loan_doctor+1]}" class="slider js-input-summ" id="amount-one">
                                                </div>
                                            </div>
                                            <div class="form-group form-group-two">
                                                <div class="form_row">
                                                    <label class="form-group-title -fs-18 -gil-m" for="amount-two">
                                                        Срок займа:
                                                    </label>
                                                    <span class="range_res -fs-26 -gil-b js-info-period" id="demo2"></span>
                                                </div>
                                                <div class="amount" style="display: none">
                                                    <input type="range" name="period" min="14" max="14"
                                                        value="14" class="slider js-input-period" id="amount-two">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group form-group-res br-10">
                                                <div class="form_row">
                                                    <div class="res_title -fs-18 -gil-m">Итого к возврату:</div>
                                                    <div class="res_info_sum -fs-20 -gil-b"><span class="js-total-summ"></span> ₽
                                                    </div>
                                                </div>
                                                <div class="form_row">
                                                    <div class="res_title -fs-18 -gil-m">Срок до:</div>
                                                    <div class="res_info_data  -fs-20 -gil-b"><span
                                                                class="js-total-period">12.12.2020</span>
                                                        г.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <select name="card_id" class="form-control" style="padding: 0!important;">
                                                    {foreach $cards as $card}
                                                        <option value="{$card->id}">{$card->pan} {$card->expdate}</option>
                                                    {/foreach}
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <div class="form_row">
                                                    <div class="check mb-0 js-loan-agreement-block">
                                                        <input type="checkbox" class="custom-checkbox js-loan-agreement"
                                                            id="check_agreement" name="agreement" value="1"/>
                                                        <label for="check_agreement" class="check_box -gil-m">
                                                            <span>Я ознакомлен и согласен со <a href="#agreement_list"
                                                                                                class="green-link js-toggle-agreement-list"
                                                                                                data-fancybox>следующим</a></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            {include file='agreement_list.tpl'}
                                            <div class="form-group form-btn">
                                                <a href="#" class="btn btn-secondary -fs-20 -fullwidth js-loan-repeat">Получить
                                                    займ</a>
                                                {*}
                                                <span class="bottom_text -fs-14 -center">нажимая на кнопку, вы соглашаетесь с
                                                <a href="#agreement_list" data-fancybox>договором оферты</a>
                                                </span>
                                                {*}
                                            </div>

                                        </div>
                                    </div>
                                </form>

                            {/if}

                        </div>
                    {/if}

                    



                {/if}
                {*}
                {*}

                {* отказ *}
                {*}
                {if ($order->status == 3 || $order->status == 8)}
                    <div class="new_order_box " data-status="3" data-order="{$order->order_id}">
                        <div class="row">
                            <div class="col-12">
                                <div class="-fs-32 -gil-b -red text-center pb-3">Вам отказано в кредите</div>
                            </div>
                            <div class="col-md-6 pt-4">
                                <dl class="row pb-2 border-bottom">
                                    <dd class="col-6 text-left">Номер заявки</dd>
                                    <dt class="col-6 text-right">{$order->order_id}</dt>
                                </dl>
                                <dl class="row pb-2 border-bottom">
                                    <dd class="col-6 text-left">Дата</dd>
                                    <dt class="col-6 text-right">{$order->date|date}</dt>
                                </dl>
                                <dl class="row pb-2 border-bottom">
                                    <dd class="col-6 text-left">Сумма</dd>
                                    <dt class="col-6 text-right">{$order->amount|convert} руб.</dt>
                                </dl>
                                <dl class="row pb-2">
                                    <dd class="col-6 text-left">Срок</dd>
                                    <dt class="col-6 text-right">{$order->period} {$order->period|plural:'день':'дней':'дня'}</dt>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <div class="order_reject_icon"></div>
                                <div class="pt-4 pr-5 pl-5 text-center">
                                    <small>{$order->reject_reason}</small>
                                    <div>
                                        <a href="https://luchshie-zaymi.ru" target="_blank" class="blue-link">
                                            Получите займ у наших партнёров
                                            <br/>
                                            <span class="sc-htoDjs fOeadG"></span>
                                            <span class="sc-iwsKbI jQhHGA">Лучшие предложения для вас</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            {if $reject_block}
                                <div class="col-12">
                                    <div class="-fs-24 -gil-b -red text-center pb-3">
                                        <br/>
                                        По заявке принято отрицательное решение, вы можете подать новую заявку
                                        с {$reject_block|date}
                                    </div>
                                </div>
                            {/if}
                        </div>
                    </div>
                {/if}
                {*}

                {* Займ выдан *}
                {if $order->status == 5}
                    <div class="new_order_box " data-status="{$order->status}" data-order="{$order->order_id}">
                        <div class="row">
                            <div class="col-12">
                                <div class="-fs-32 -gil-b -green text-center pb-3">
                                    У Вас есть активный займ от {$order->contract->create_date|date}г.
                                </div>
                                {if $order->contract->status == 4}
                                    <div class="-fs-32 -gil-b -red text-center pb-3">
                                        Ваш займ просрочен!
                                    </div>
                                {/if}
                            </div>
                            <div class="col-md-6 pt-4">
                                <dl class="row pb-2 border-bottom">
                                    <dd class="col-6 text-left">Номер договора</dd>
                                    <dt class="col-6 text-right">
                                        {$order->contract->number}
                                    </dt>
                                </dl>
                                <dl class="row pb-2 border-bottom">
                                    <dd class="col-6 text-left">Основной долг</dd>
                                    <dt class="col-6 text-right">{($order->contract->loan_body_summ*1)} руб.</dt>
                                </dl>
                                <dl class="row pb-2 border-bottom">
                                    <dd class="col-6 text-left">Проценты</dd>
                                    <dt class="col-6 text-right">{($order->contract->loan_percents_summ + $order->contract->loan_charge_summ)*1}
                                        руб.
                                    </dt>
                                </dl>
                                {if $order->contract->loan_peni_summ > 0}
                                    <dl class="row pb-2 border-bottom">
                                        <dd class="col-6 text-left">Пени</dd>
                                        <dt class="col-6 text-right">{($order->contract->loan_peni_summ*1)} руб.</dt>
                                    </dl>
                                {/if}
                                {if $order->contract->status == 11}
                                    <dl class="row pb-2">
                                        <dd class="col-6 text-left">Дата платежа</dd>
                                        <dt class="col-6 text-right">{$order->contract->next_pay|date} </dt>
                                    </dl>
                                {else}
                                    <dl class="row pb-2">
                                        <dd class="col-6 text-left">Дата возврата</dd>
                                        <dt class="col-6 text-right">{$order->contract->return_date|date} </dt>
                                    </dl>
                                {/if}
                                {*}
                                <dl class="row pb-2 border-bottom">
                                  <dd class="col-6 text-left">Сумма на дату возврата</dd>
                                  <dt class="col-6 text-right">{$order->contract->return_amount|convert} руб.</dt>
                                </dl>
                                {*}
                            </div>
                            <div class="col-md-6">

                                <div class="pt-4 pr-5 pl-5 text-center">

                                </div>
                                <div class="pt-2 text-center">
                                    <form action="account/pay" method="POST" class="border rounded p-2">
                                        <input type="hidden" name="contract_id" value="{$order->contract->id}"/>
                                        <input type="hidden" name="action" value="recurrent_close"/>
                                        <input type="hidden"
                                               name="card_id" {foreach $cards as $card} {if $card->base_card == 1}value="{$card->id}"{/if}{/foreach}/>
                                        <div class="row">
                                            <div class="col-md-5 col-12">
                                                <input type="number" max="999999" style="width: 100%; padding: 5px;"
                                                       class="form-control text-right" name="amount"
                                                       value="{$order->contract->loan_body_summ + $order->contract->loan_percents_summ + $order->contract->loan_charge_summ + $order->contract->loan_peni_summ}"
                                                        {if $order->contract->status == 11}
                                                readonly
                                                        {/if}/>
                                            </div>
                                            <div class="col-md-7 pt-1">
                                                {if $order->contract->status == 11}
                                                    <button type="submit" class="btn btn-primary btn-block">Продлить
                                                    </button>
                                                {else}
                                                    <button type="submit" class="btn btn-primary btn-block">Погасить
                                                    </button>
                                                {/if}
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                {if $prolongation_amount && $order->contract->type == 'base' && $show_prolongation}
                                    <div class="pt-4 text-center">
                                        <form action="account/pay" method="POST" data-user="{$user->id}"
                                              data-contract="{$order->contract->id}"
                                              class="border rounded js-prolongation-form p-2">
                                            <input type="hidden" name="contract_id" value="{$order->contract->id}"/>
                                            <input type="hidden" name="prolongation" value="1"/>
                                            <input type="hidden" name="code" value=""/>
                                            <input type="hidden" name="phone" value="{$user->phone_mobile}"/>
                                            <h3 class="mb-0">Пролонгация 2</h3>
                                            <span class="text-muted">до: {$order->prolongation_date}</span>
                                            <div class="row">
                                                <div class="col-md-5 col-12">
                                                    <input type="text" readonly="" style="background-color: #fbfbfb; ; padding: 5px;"
                                                           class="form-control text-right" name="amount"
                                                           value="{$prolongation_amount}"
                                                           min="{$prolongation_amount}"/>
                                                </div>
                                                <div class="col-md-7 col-12 pt-1">
                                                    <button type="submit" class="btn btn-primary btn-block">
                                                        Продлить
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                {/if}
                            </div>
                            <div class="col-md-6"></div>

                        </div>
                    </div>
                {/if}
            {/if}


            {*}
            <div class="person_info_box">
              <div class="person_info">
                <div class="person_info_title -fs-26 -gil-m">Займ от {$order->date|date}г.</div>
                <div class="person_info_wrap -fs-18">
                  <div class="order_info_row row">
                    <div class="col-sm-4 col-md-3">
                      <div class="order_item_info">
                        <div class="order_info_title">Номер заявки</div>
                        <div class="order_info_text -gil-m">№{$order->contract->id}</div>
                      </div>
                    </div>
                    <div class="col-sm-4 col-md-3">
                      <div class="order_item_info">
                        <div class="order_info_title">Сумма</div>
                        <div class="order_info_text -gil-m">{$order->contract->amount} руб.</div>
                      </div>
                    </div>
                    <div class="col-sm-4 col-md-3">
                      <div class="order_item_info">
                        <div class="order_info_title">Срок</div>
                        <div class="order_info_text -gil-m">{$order->contract->period}</div>
                      </div>
                    </div>
                    <div class="col-sm-4 col-md-3">
                      <div class="order_item_info">
                        <div class="order_info_title">Статус</div>
                        <div class="order_info_text -gil-m status_{$order->status}">
                          {$statuses[$order->status]}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            {*}

            {if (!$reject_block && !$user_balance1c && (!$order || $order->status > 5 || $order->status == 3))}
                <div class="new_order_box js-new-order-proposition" data-status="{$order->status}"
                     data-order="{$order->order_id}">
                    <div class="row">
                        {if $error}
                            <div class="col-12">
                                <div class="alert alert-danger">
                                    {$error|escape}
                                </div>
                            </div>
                        {/if}
                        <div class="col-12">
                            <div class="-fs-32 -gil-b text-center pb-3">
                                {if $order->status == 6}
                                    <span class="-red">При переводе возникла ошибка</span>
                                {else}
                                    <span class="-green">У Вас нет открытых займов </span>
                                {/if}
                            </div>
                            {if !$cards}
                                <div class="-fs-24 -gil-b -red text-center pb-3">
                                    Для получения займа необходимо привязать карту
                                    <div class="pt-3">
                                        <a href="/account/cards" class="btn btn-primary">Привязать карту</a>
                                    </div>
                                </div>
                            {/if}
                        </div>
                        {if $cards}
                            <div class="col-md-12">
                                <div class="pt-4 text-center">
                                    <form action="account/pay" method="POST">
                                        <input type="hidden" name="contract_id" value="{$order->contract->id}"/>
                                        <div class="row">
                                            <div class="col-12 pt-1">
                                                {if $need_fields}
                                                    <a href="account/anketa" class="btn btn-primary">Получить новый</a>
                                                {else}
                                                    <button type="button" class="btn btn-primary js-open-repeat-block">
                                                        Получить новый
                                                    </button>
                                                {/if}
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        {/if}
                    </div>
                </div>
                <div class="new_order_box hide js-repeat-block">
                    <form class="calculator js-loan-repeat-form js-calc" method="POST" data-percent="{$loan_percent}">

                        <input type="hidden" name="local_time" class="js-local-time" value=""/>
                        <input type="hidden" name="juicescore_session_id" id="juicescore_session_id" value=""/>

                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group form-group-one">
                                    <div class="form_row">
                                        <label class="form-group-title -fs-18 -gil-m" for="amount-one">
                                            Выберите сумму:
                                        </label>
                                        <span class="range_res -fs-26 -gil-b js-info-summ" id="demo"></span>
                                    </div>
                                    <div class="amount">
                                        <input type="range" name="amount" min="{$min_summ}" max="{$max_summ}"
                                               value="{$current_summ}" class="slider js-input-summ" id="amount-one">
                                    </div>
                                </div>
                                <div class="form-group form-group-two">
                                    <div class="form_row">
                                        <label class="form-group-title -fs-18 -gil-m" for="amount-two">
                                            Выберите срок:
                                        </label>
                                        <span class="range_res -fs-26 -gil-b js-info-period" id="demo2"></span>
                                    </div>
                                    <div class="amount">
                                        <input type="range" name="period" min="{$min_period}" max="{$max_period}"
                                               value="{$current_period}" class="slider js-input-period" id="amount-two">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group form-group-res br-10">
                                    <div class="form_row">
                                        <div class="res_title -fs-18 -gil-m">Итого к возврату:</div>
                                        <div class="res_info_sum -fs-20 -gil-b"><span class="js-total-summ"></span> ₽
                                        </div>
                                    </div>
                                    <div class="form_row">
                                        <div class="res_title -fs-18 -gil-m">Срок до:</div>
                                        <div class="res_info_data  -fs-20 -gil-b"><span
                                                    class="js-total-period">12.12.2020</span>
                                            г.
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <select name="card_id" class="form-control" style="padding: 0!important;">
                                        {foreach $cards as $card}
                                            <option value="{$card->id}">{$card->pan} {$card->expdate}</option>
                                        {/foreach}
                                    </select>
                                </div>
                                <div class="form-group">
                                    <div class="form_row">
                                        <div class="check mb-0 check_box justify-content-center">
                                            <span><a href="#promo_code" class="js-toggle-promo-code"
                                                     style="color: #4A2982">У меня есть промокод</a></span>
                                        </div>
                                    </div>
                                </div>
                                <div id="promo_code" style="display:none" class="pr-3 pl-3">
                                    <div style="color: #4A2982; display:none" class="text-center js-success-promo">
                                        <p>Промокод активирован
                                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="8"
                                                 viewBox="0 0 10 8" fill="none">
                                                <path d="M9.88442 1.8301L4.15429 7.55898C4.00072 7.71194 3.75208 7.71194 3.59912 7.55898L0.114476 4.05205C-0.0384842 3.89847 -0.0384842 3.6489 0.114476 3.49625L0.947087 2.66426C1.10067 2.51099 1.3493 2.51099 1.50226 2.66426L3.87965 5.05744L8.4957 0.441075C8.64866 0.288115 8.8973 0.288115 9.0515 0.441075L9.88411 1.2743C10.0386 1.42757 10.0386 1.67714 9.88442 1.8301Z"
                                                      fill="#33CC66"></path>
                                            </svg>
                                        </p>
                                    </div>
                                    <div class="text-center text-danger js-error-promo" style="display:none">
                                        <p>Промокод не применен</p>
                                    </div>
                                    <div id="promo_input" class="form-group form-phone">
                                        <input id="promoCode" type="text" class="form-control -fs-18 -gil-m">
                                        <span class="phone_info -fs-14">Промокод</span>
                                    </div>
                                    <div class="form-group form-btn">
                                        <a id="check_promo_code" href="javascript:void(0);"
                                           class="btn btn-secondary  -fs-20 -fullwidth  js-promo-code-ckeck ">Применить</a>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form_row">
                                        <div class="check mb-0 js-loan-agreement-block">
                                            <input type="checkbox" class="custom-checkbox js-loan-agreement"
                                                   id="check_agreement" name="agreement" value="1"/>
                                            <label for="check_agreement" class="check_box -gil-m">
                                                <span>Я ознакомлен и согласен со <a href="#agreement_list"
                                                                                    class="green-link js-toggle-agreement-list"
                                                                                    data-fancybox>следующим</a></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                {include file='agreement_list.tpl'}
                                <div class="form-group form-btn">
                                    <a href="#" class="btn btn-secondary -fs-20 -fullwidth js-loan-repeat">Получить
                                        займ</a>
                                    {*}
                                    <span class="bottom_text -fs-14 -center">нажимая на кнопку, вы соглашаетесь с
                                    <a href="#agreement_list" data-fancybox>договором оферты</a>
                                    </span>
                                    {*}
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
            {/if}


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


        </div>
    </div>
</main>
