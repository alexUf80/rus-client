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
        <td align="center">1</td>
        <td align="center">2</td>
        <td align="center">3</td>
        <td align="center">4</td>
        <td align="center">5</td>
        <td align="center">6</td>
        <td align="center">7</td>
        <td align="center">1</td>
        <td align="center">2</td>
        <td align="center">3</td>
        <td align="center">4</td>
        <td align="center">5</td>
        <td align="center">6</td>
        <td align="center">1</td>
        <td align="center">2</td>
        <td align="center">3</td>
        <td align="center">4</td>
        <td align="center">5</td>
        <td align="center">6</td>
    </tr>
    <tr>
        <td align="center">{$date|date}</td>
        <td align="center">{$inssuance_delay}</td>
        <td align="center">{$contract->base_percent}</td>
        <td align="center">{$peni_sum}</td>
        <td align="center">{$pay_percents_summ}</td>
        <td align="center">{$percents_sum}</td>
        <td align="center">0</td>
        {*}{*}
        <td align="center">{$pay_body_summ+$pay_percents_summ+$pay_peni_summ}</td>
        <td align="center">{$pay_body_summ}</td>
        <td align="center">{$pay_percents_summ}</td>
        <td align="center">{$pay_peni_summ}</td>
        <td align="center">0</td>
        <td align="center">0</td>
        {*}{*}
        <td align="center">{$contract->loan_body_summ+$contract->loan_percents_summ+$contract->loan_peni_summ}</td>
        <td align="center">{$contract->loan_body_summ}</td>
        <td align="center">{$contract->loan_percents_summ}</td>
        <td align="center">{$contract->loan_peni_summ}</td>
        <td align="center">0</td>
        <td align="center">0</td>
    </tr>
</table>

<div>



