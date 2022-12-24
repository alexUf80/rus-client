{$meta_title='Личный кабинет' scope=parent}

{capture name='page_scripts'}
    <script src="theme/site/js/calc.app.js"></script>
    <script src="theme/site/js/lk.app.js"></script>
    <script src="theme/site/js/contract_accept.app.js"></script>
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
                        <a class="nav-link text-danger" href="lk/logout">Выйти</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    <div class="content_wrap">
        <div class="container">
            <h1>Личный кабинет</h1>
            <!-- общая информация -->


            {if $order && $order->status == 5}
                <div class="new_order_box " data-status="{$order->status}" data-order="{$order->order_id}">
                    <div class="row">
                        <div class="col-12">
                            {*}
                              <div class="-fs-32 -gil-b -red text-center pb-3">
                                  Ваш договор передан в стороннюю организацию
                              </div>
                            {*}
                        </div>
                        <div class="col-md-6 pt-4">
                            <dl class="row pb-2 border-bottom">
                                <dd class="col-6 text-left">Номер договора</dd>
                                <dt class="col-6 text-right">{*$order->contract->number*}Договор</dt>
                            </dl>
                            <dl class="row pb-2 border-bottom">
                                <dd class="col-6 text-left">Основной долг</dd>
                                <dt class="col-6 text-right">{($order->contract->loan_body_summ)*1} руб.</dt>
                            </dl>
                            <dl class="row pb-2 border-bottom">
                                <dd class="col-6 text-left">Проценты</dd>
                                <dt class="col-6 text-right">{($order->contract->loan_percents_summ + $order->contract->loan_charge_summ)*1}
                                    руб.
                                </dt>
                            </dl>
                            <dl class="row pb-2 border-bottom">
                                <dd class="col-6 text-left">Пени</dd>
                                <dt class="col-6 text-right">{($order->contract->loan_peni_summ)*1} руб.</dt>
                            </dl>
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

                                {if in_array($order->contract->number, ['НП21-210389'])}
                                    <div class="pb-3">
                                        Право требования задолженности по договору {$order->contract->number}
                                        уступлено "ООО Юридическая компания №1"
                                        <br/>
                                        тел. 8-800-222-076-69
                                    </div>
                                {/if}

                                <form action="account/pay" method="POST" class="border rounded">
                                    <input type="hidden" name="contract_id" value="{$order->contract->id}"/>
                                    <div class="row">
                                        <div class="col-12 col-md-4">
                                            <div class="row">
                                                <div class="col-12">
                                                    <input type="text" class="form-control text-right" name="amount"
                                                           value="{$order->contract->loan_body_summ + $order->contract->loan_percents_summ + $order->contract->loan_charge_summ + $order->contract->loan_peni_summ}"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 pt-1">
                                            {if $order->contract->type == 'onec'}
                                                <a href="https://nalichnoe.com/login/"
                                                   class="btn btn-primary btn-block">Перейти&nbsp;к&nbsp;оплате</a>
                                            {else}
                                                <button type="submit" class="btn btn-primary btn-block">Оплатить
                                                </button>
                                            {/if}
                                        </div>
                                    </div>
                                </form>
                            </div>

                            {if $prolongation_amount && $show_prolongation}
                                <div class="pt-4 text-center">
                                    <form action="account/pay" method="POST" class="border rounded">
                                        <input type="hidden" name="contract_id" value="{$order->contract->id}"/>
                                        <input type="hidden" name="prolongation" value="1"/>
                                        <h3 {if $order->prolongation_date} class="mb-0" {/if}>Пролонгация</h3>
                                        {if $order->prolongation_date}
                                            <span class="text-muted">до: {$order->prolongation_date}</span>
                                        {/if}
                                        <div class="row">
                                            <div class="col-12 col-md-4">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <input type="text" class="form-control text-right" readonly=""
                                                               style="background-color: #fbfbfb;" name="amount"
                                                               value="{1 * $prolongation_amount}"
                                                               min="{$prolongation_amount}"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6 pt-1">
                                                {if $order->contract->type == 'onec'}
                                                    <a href="https://nalichnoe.com/login/"
                                                       class="btn btn-primary btn-block">Перейти&nbsp;к&nbsp;оплате</a>
                                                {else}
                                                    <button type="submit" class="btn btn-primary btn-block">Оплатить
                                                    </button>
                                                {/if}
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            {/if}

                        </div>
                    </div>
                </div>
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


        </div>
    </div>
</main>
