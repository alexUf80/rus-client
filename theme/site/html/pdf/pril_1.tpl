<div>

</div>
<table align="center">
    <tr>
        <td width="100%" align="right"><strong>Приложение № 1</strong></td>
    </tr>
    <tr>
        <td width="100%" align="center"><strong>Расчет</strong></td>
    </tr>
    <tr>
        <td width="100%" align="center"><strong>начислений и поступивших платежей по договору
                № {$contract->number} от {$contract->inssuance_date|date}</strong></td>
    </tr>
    <tr><br>
    </tr>
    <tr>
        <td width="50%" align="left"><strong>Заемщик (Ф.И.О.):
                {$lastname|upper} {$firstname|upper} {$patronymic|upper}</strong></td>
        <td width="50%" align="right"><strong>Дата составления:
        {$date|date}</strong></td>
    </tr>
    <tr><br>
    </tr>
    <tr>
        <td width="50%" align="left"><strong>Первоначальная сумма займа: 
                {$amount|upper} руб.</strong></td>
        <td width="50%" align="right"><strong>Оплачено всего:
        {$pay_body_summ+$pay_percents_summ+$pay_peni_summ} руб.</strong></td>
    </tr>
    <tr>
        <td width="50%" align="left"><strong>Дата выдачи займа: 
                {$contract->inssuance_date|date}</strong></td>
        <td width="50%" align="right"><strong>Из них
        </strong> в погашение процентов {$pay_percents_summ} руб.</td>
    </tr>
    <tr>
        <td width="50%" align="left"><strong>Срок займа (в днях):  
                {$contract->period}</strong></td>
        <td width="50%" align="right">в погашение основного долга {$pay_body_summ} руб.</td>
    </tr>
    <tr>
        <td width="50%" align="left"><strong>Процентная ставка (% в день) 
                {$contract->base_percent}</strong></td>
        <td width="50%" align="right">в погашение пени {$pay_peni_summ} руб.</td>
    </tr>
    <tr>
        <td width="50%" align="left"><strong>Процентная ставка (% годовых)  
                {$contract->base_percent * 365}</strong></td>
        <td width="50%" align="right">в погашение штрафов 0 руб.</td>
    </tr>
    <tr>
        <td width="100%" align="right">в погашение иных платежей 0 руб.</td>
    </tr>
    <tr>
        <td><br></td>
    </tr>
    <tr>
        <td width="50%" align="left"><strong>Дата погашения займа: 
                {$contract->return_date|date}</strong></td>
        <td width="50%" align="right"><strong>Общая сумма задолженности: {$contract->loan_body_summ+$contract->loan_percents_summ+$contract->loan_peni_summ} руб.</strong></td>
    </tr>
    <tr>
        <td width="100%" align="right"><strong>В том числе</strong> по основному долгу {$contract->loan_body_summ} руб.</td>
    </tr>
    <tr>
        <td width="100%" align="right">по процентам {$contract->loan_percents_summ} руб.</td>
    </tr>
    <tr>
        <td width="100%" align="right">по пени {$contract->loan_peni_summ} руб.</td>
    </tr>
    <tr>
        <td width="100%" align="right">по штрафам 0 руб.</td>
    </tr>
    <tr>
        <td width="100%" align="right">по иным платежам 0 руб.</td>
    </tr>
    <tr>
        <br>
    </tr>
</table>

<br><br>


<table border="1">
    <tr>
        <td width="33.33%" colspan="7" align="center">Начислено</td>
        <td width="33.33%" colspan="6" align="center">Оплачено</td>
        <td width="33.33%" colspan="6" align="center">Остаток задолженности</td>
    </tr>
    <tr>
        <td align="center">Дата расчета</td>
        <td align="center">Количество дней с даты получения займа</td>
        <td align="center">Процентная ставка в день, %</td>
        <td align="center">Сумма начисленных процентов в день, руб.</td>
        <td align="center">Сумма процентов накопительным итогом, руб.</td>
        <td align="center">Сумма штрафов в день, руб.</td>
        <td align="center">Сумма иных платежей в день, руб. (с указанием назначения платежа)</td>
        <td align="center">Всего</td>
        <td align="center">В счет основного долга</td>
        <td align="center">В счет процентов</td>
        <td align="center">В счет пени</td>
        <td align="center">В счет штрафов</td>
        <td align="center">В счет иных платежей (с указанием назначения платежа)</td>
        <td align="center">Всего</td>
        <td align="center">Основной долг</td>
        <td align="center">Проценты</td>
        <td align="center">Пени</td>
        <td align="center">Штрафы</td>
        <td align="center">Иные платежи (с указанием назначения платежа)</td>
    </tr>
    {foreach $operations_by_date as $val}
        <tr>
            <td align="center" style="font-size:8">{$val['date']|date_format:"%d.%m.%y"}</td>
            <td align="center" style="font-size:8">{$val['days_from_create_date']}</td>
            <td align="center" style="font-size:8">{$val['percent_per_day']|number_format:2:".":""}</td>
            <td align="center" style="font-size:8">
                {if $val['sum_percents_per_day']}
                    {$val['sum_percents_per_day']|number_format:2:".":""}
                {/if}
            </td>
            <td align="center" style="font-size:8">
                {if $val['sum_percents_all_time']}
                    {$val['sum_percents_all_time']|number_format:2:".":""}
                {/if}
            </td>
            <td align="center" style="font-size:8">
                {if $val['sum_peni_per_day']}
                    {$val['sum_peni_per_day']|number_format:2:".":""}
                {/if}
            </td>
            <td align="center" style="font-size:8">
                {if $val['sum_other_payments_per_day']}
                    {$val['sum_other_payments_per_day']|number_format:2:".":""}
                {/if}
            </td>
            {*}{*}
            <td align="center" style="font-size:8">
                {if $val['sum_pay_all']}
                    {$val['sum_pay_all']|number_format:2:".":""}
                {/if}
            </td>
            <td align="center" style="font-size:8">
                {if $val['sum_pay_od']}
                    {$val['sum_pay_od']|number_format:2:".":""}
                {/if}
            </td>
            <td align="center" style="font-size:8">
                {if $val['sum_pay_percents']}
                    {$val['sum_pay_percents']|number_format:2:".":""}
                {/if}
            </td>
            <td align="center" style="font-size:8">
                {if $val['sum_pay_peni']}
                    {$val['sum_pay_peni']|number_format:2:".":""}
                {/if}
            </td>
            <td align="center" style="font-size:8">
                {if $val['sum_pay_penalty']}
                    {$val['sum_pay_penalty']|number_format:2:".":""}
                {/if}
            </td>
            <td align="center" style="font-size:8">
                {if $val['sum_pay_other']}
                    {$val['sum_pay_other']|number_format:2:".":""}
                {/if}
            </td>
            {*}{*}
            <td align="center" style="font-size:8">
                {if $val['sum_debt_all']}
                    {$val['sum_debt_all']|number_format:2:".":""}
                {/if}
            </td>
            <td align="center" style="font-size:8">
                {if $val['sum_debt_od']}
                    {$val['sum_debt_od']|number_format:2:".":""}
                {/if}
            </td>
            <td align="center" style="font-size:8">
                {if $val['sum_debt_percents']}
                    {$val['sum_debt_percents']|number_format:2:".":""}
                {/if}
            </td>
            <td align="center" style="font-size:8">
                {if $val['sum_debt_peni']}
                    {$val['sum_debt_peni']|number_format:2:".":""}
                {/if}
            </td>
            <td align="center" style="font-size:8">
                {if $val['sum_debt_penalty']}
                    {$val['sum_debt_penalty']|number_format:2:".":""}
                {/if}
            </td>
            <td align="center" style="font-size:8">
                {if $val['sum_debt_other']}
                    {$val['sum_debt_other']|number_format:2:".":""}
                {/if}
            </td>
        </tr>
    {/foreach}
</table>

<div>



