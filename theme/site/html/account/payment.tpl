{$meta_title='Подтверждение платежа' scope=parent}

{capture name='page_scripts'}
    <script src="theme/site/js/payment.app.js?v=1.22"></script>
{/capture}

{capture name='page_styles'}

{/capture}

<main class="main js-lk-app">
    <div class="section_lk_navbar">
        <div class="container">
            <nav class="navbar lk_menu">
                <ul class="nav lk_menu_nav -gil-m">
                    <li class="nav-item">
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
                </ul>
            </nav>
        </div>
    </div>
    <div class="content_wrap">
        <div class="container">

            {if $error_time}
                <h2 class="text-danger text-center">
                    Уважаемый клиент, <br/>ведутся технические работы, <br/>повторите попытку через 10 минут
                </h2>
            {else}
                <h1>Подтверждение платежа</h1>
                <div class="new_order_box " data-status="{$order->status}" data-order="{$order->order_id}">

                    <input type="hidden" name="amount" value="{$amount}"/>
                    <input type="hidden" name="contract_id" value="{$contract_id}"/>
                    <input type="hidden" name="code_sms" value="{$code_sms}"/>

                    <div class="hide payment-block-error alert alert-danger">

                    </div>

                    <div class="row">

                        {if $error}
                            <div class="col-12">
                                <div class="alert alert-danger">
                                    {$error}
                                </div>
                            </div>
                        {/if}

                        <div class="col-12">
                            <div class="-fs-32 -gil-b -green text-center pb-3">
                                Сумма платежа {$amount} руб.
                            </div>
                        </div>
                        <div class="col-md-8 pt-4">

                            <ul class="payment-card-list row">
                                {foreach $cards as $card}
                                    <li class="col-12 col-md-4 col-sm-6 ">
                                        <input type="radio" name="card_id" id="card_{$card->id}" value="{$card->id}"
                                               {if $card@first}checked{/if} />
                                        <label for="card_{$card->id}">
                                            <strong>{$card->pan}</strong>
                                            <span>{$card->expdate}</span>
                                        </label>
                                    </li>
                                {/foreach}
                                <li class="col-12 col-md-4 col-sm-6 ">
                                    <input type="radio" id="card_other" name="card_id" value="other"
                                           {if !$cards}checked=""{/if} />
                                    <label for="card_other">
                                        <strong>Другая карта</strong>
                                        <span>&nbsp;</span>
                                    </label>
                                </li>
                            </ul>

                        </div>
                        <div class="col-md-4">
                            {if $amount == $full_amount}
                                <div>
                                    <div class="pt-4 text-center">
                                        <a href="#" id="close_contract" data-full-amount="{$full_amount}"
                                           class="btn btn-primary btn-block">
                                            {if $contract->status == 11}
                                                Оплатить займ
                                            {else}
                                                Погасить займ
                                            {/if}
                                        </a>
                                    </div>
                                </div>
                                {else}
                                <input type="hidden" name="prolongation" value="{$prolongation}">
                                <div class="pt-4 text-center">
                                    <a href="#" id="confirm_payment" class="btn btn-primary btn-block">Оплатить</a>
                                </div>
                            {/if}
                        </div>
                    </div>
                </div>
            {/if}


        </div>
    </div>
</main>
