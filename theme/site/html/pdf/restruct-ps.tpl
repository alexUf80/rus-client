<div align="justify"><strong>Приложение 1 – График платежей погашения задолженности по договору займа <br>№1/0004785 от
        5 июня 2022 г.</strong></div>
<div>

</div>
<table border="1" cellpadding="5">
    <tr>
        <th><strong>Дата</strong></th>
        <th><strong>Сумма Платежа</strong></th>
    </tr>
        {foreach $schedules['payment_schedules'] as $schedule}
                <tr>
                    <td>{if $schedule['date'] != 'ИТОГО'}{$schedule['date']}{else}{$schedule['date']|date}{/if}</td>
                    <td>{$schedule['payment']}</td>
                </tr>
        {/foreach}
</table>
<div>

</div>
<table align="center">
    <tr>
        <td width="20%"></td>
        <td width="60%"><strong>РЕКВИЗИТЫ И ПОДПИСИ СТОРОН</strong></td>
        <td width="10%"></td>
    </tr>
</table>
<div>

</div>
<table>
    <tr>
        <td width="50%"><u>ЗАЙМОДАВЕЦ</u></td>
        <td width="50%"><u>ЗАЕМЩИК</u></td>
    </tr>
    <tr>
        <td width="50%"></td>
        <td width="50%"></td>
    </tr>
    <tr>
        <td width="50%">ООО МКК "БАРЕНЦ ФИНАНС"</td>
        <td width="50%">{$lastname|upper} {$firstname|upper} {$patronymic|upper}</td>
    </tr>
    <tr>
        <td width="50%">Юр. адрес: 163045, Архангельская обл., г. Архангельск,
            пр-д. К.С. Бадигина, д.19, оф. 107.
        </td>
        <td width="50%">Паспорт гражданина РФ: {$passport_serial}</td>
    </tr>
    <tr>
        <td width="50%">ИНН 9723120835</td>
        <td width="50%">Кем выдан: {$passport_issued|upper}
        </td>
    </tr>
    <tr>
        <td width="50%">КПП 290101001</td>
        <td width="50%">Дата выдачи: {$passport_date|date} код подр.: {$subdivision_code}</td>
    </tr>
    <tr>
        <td width="50%">ОГРН 1217700350812
        </td>
        <td width="50%">Дата рождения: {$birth|date}</td>
    </tr>
    <tr>
        <td width="50%">Банк: ПАО "ФК ОТКРЫТИЕ"</td>
        <td width="50%">Место рождения: {$birth_place}</td>
    </tr>
    <tr>
        <td width="50%">р\с 40701810607200000018</td>
        <td width="50%">Адрес регистрации: {$regaddress_full}
        </td>
    </tr>
    <tr>
        <td width="50%">к\с 30101810540300000795</td>
        <td width="50%">СНИЛС: {$snils}</td>
    </tr>
    <tr>
        <td width="50%">БИК 044030795</td>
        <td width="50%">Банковская карта: {$active_card}</td>
    </tr>
</table>
<div>

</div>
<table style="width: 50%">
    <tr>
        <td><strong>Кредитор</strong></td>
        <td>Кройтор В.В</td>
    </tr>
    <tr>
        <td><img src="{$config->root_dir}/theme/site/html/pdf/i/bfSigna.png"></td>
        <td><img src="{$config->root_dir}/theme/site/html/pdf/i/bfStamp.png"></td>
    </tr>
</table>
<table>
    <tr>
        <td></td>
        <td>
            <table border="1" cellpadding="4">
                <tr>
                    <td>Подписано Аналогом собственноручной подписи (АСП)<br>Идентификатор клиента: {$contract->user_id}
                        <br>Дата: {$contract->issuance_date|date}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>