<div style="text-align: center;">
    <strong>Сведения о физическом лице</strong>
</div>
<table border="0.5" cellpadding="5">
    <tbody>
    <tr>
        <td style="width: 40%">Тип физического лица*</td>
        <td style="width: 20%">
            ФЛ
        </td>
        <td style="width: 20%">
            Телефон/фак:
        </td>
        <td style="width: 20%">
            {$phone}
        </td>
    </tr>
    <tr>
        <td style="width: 40%">
           Иная контактная информация 
           (E-mail и т.п.)
        </td>
        <td style="width: 60%">
            {$email}
        </td>
    </tr>
    <tr>
        <td style="width: 40%">
           ФИО
        </td>
        <td style="width: 60%">
            {$lastname|upper} {$firstname|upper} {$patronymic|upper}
        </td>
    </tr>
    <tr>
        <td style="width: 40%">
           ИНН
        </td>
        <td style="width: 60%">
            {$inn}
        </td>
    </tr>
    <tr>
        <td style="width: 40%">
           СНИЛС
        </td>
        <td style="width: 60%">
            {$snils}
        </td>
    </tr>
    <tr>
        <td style="width: 20%">
           Степень (уровень) риска клиента и обоснование отнесения Клиента к определенной степени (определенному уровню) риска клиента
        </td>
        <td style="width: 60%">
            Низкий
        </td>
    </tr>
    <tr>
        <td style="width: 40%">
           Сведения о принадлежности Клиента (регистрация, место жительства, место нахождения, наличие счета в банке) к государству (территории), которое (которая) не выполняет рекомендации Группы разработки финансовых мер борьбы с отмыванием денег (ФАТФ).
        </td>
        <td style="width: 60%">
           {$regaddress_full|escape}
        </td>
    </tr>
    <tr>
        <td rowspan="2" style="width: 15%">
           Сведения о принадлежности Клиента (регистрация, место жительства, место нахождения, наличие счета в банке) к государству (территории), которое (которая) не выполняет рекомендации Группы разработки финансовых мер борьбы с отмыванием денег (ФАТФ).
        </td>
        <td  style="width: 25%">
           Должность
        </td>
        <td style="width: 60%">
            {$profession}
        </td>
    </tr>
    <tr>
        <td  style="width: 25%">
           Наименование и адрес работодателя
        </td>
        <td style="width: 60%">
            {$workplace} {$workaddress}
        </td>
    </tr>
    <tr>
        <td  style="width: 40%">
           Сведения о связи Клиента с ИПДЛ, ДЛПМО, РПДЛ (указывается степень родства, статус (супруг или супруга))
        </td>
        <td style="width: 60%">
           Связь с ИПДЛ, ДЛПМО, РПДЛ отсутствует
        </td>
    </tr>
    <tr>
        <td  style="width: 40%">
           Дата начала отношений с Клиентом (дата заключения первого договора на проведение операции с денежными средствами или иным имуществом)
        </td>
        <td style="width: 60%">
           {$created|date}
        </td>
    </tr>
    <tr>
        <td  style="width: 40%">
           Дата прекращения отношений с Клиентом
        </td>
        <td style="width: 60%">
           {$return_date|date}
        </td>
    </tr>
    <tr>
        <td  style="width: 40%">
           Цели установления деловых отношений с Клиентом
        </td>
        <td style="width: 60%">
           Предоставление потребительского кредита (займа) без обеспечения
        </td>
    </tr>
    <tr>
        <td  style="width: 40%">
           Предполагаемый характер  деловых отношений с Клиентом
        </td>
        <td style="width: 60%">
           Исполнение договора потребительского кредита (займа)
        </td>
    </tr>
    <tr>
        <td  style="width: 40%">
           Цели финансово-хозяйственной деятельности (планируемые операции)
        </td>
        <td style="width: 60%">
            Возврат всей суммы займа и процентов за пользование им В соответствии с индивидуальными условиями
        </td>
    </tr>
    <tr>
        <td  style="width: 40%">
           Финансовое положение Клиента
        </td>
        <td style="width: 60%">
           Соответствует
        </td>
    </tr>
    <tr>
        <td  style="width: 40%">
           Деловая репутация Клиента
        </td>
        <td style="width: 60%">
           Соответствует
        </td>
    </tr>
    <tr>
        <td  style="width: 40%">
           Источники происхождения денежных средств и (или) иного имущества Клиента
        </td>
        <td style="width: 60%">
           Заработная плата
        </td>
    </tr>
    <tr>
        <td align="center" style="width: 100%">
           <strong>Адрес регистрации по месту жительства</strong>
        </td>
    </tr>
    <tr>
        <td  style="width: 40%">
           Страна места регистрации
        </td>
        <td style="width: 60%">
           РФ 
        </td>
    </tr>
    <tr>
        <td  style="width: 40%">
           Код субъекта Российской Федерации
        </td>
        <td style="width: 60%">
           {$regindex}
        </td>
    </tr>
    <tr>
        <td  style="width: 40%">
           Район
        </td>
        <td style="width: 60%">
           {$regregion}
        </td>
    </tr>
    <tr>
        <td  style="width: 40%">
           Населенный пункт
        </td>
        <td style="width: 60%">
           {$regcity}
        </td>
    </tr>
    <tr>
        <td  style="width: 40%">
           Улица
        </td>
        <td style="width: 60%">
           {$reglocality}
        </td>
    </tr>
    <tr>
        <td  style="width: 40%">
           Дом
        </td>
        <td style="width: 60%">
           {$reghousing}
        </td>
    </tr>
    <tr>
        <td  style="width: 40%">
           Корпус
        </td>
        <td style="width: 60%">
           {$regbuilding}
        </td>
    </tr>
    <tr>
        <td  style="width: 40%">
          Квартира
        </td>
        <td style="width: 60%">
           {$regroom}
        </td>
    </tr>
    <tr>
        <td  style="width: 100%">
           Адрес регистрации и пребывания совпадают (В случае совпадения отметить знаком Х)
        </td>
    </tr>
    <tr>
        <td align="center" style="width: 100%">
           <strong>Адрес места пребывания</strong>
        </td>
    </tr>
    <tr>
        <td  style="width: 40%">
          Страна места нахождения
        </td>
        <td style="width: 60%">
           РФ 
        </td>
    </tr>
    <tr>
        <td  style="width: 40%">
           Код субъекта Российской Федерации
        </td>
        <td style="width: 60%">
           {$faktregindex}
        </td>
    </tr>
    <tr>
        <td  style="width: 40%">
           Район
        </td>
        <td style="width: 60%">
           {$faktregion}
        </td>
    </tr>
    <tr>
        <td  style="width: 40%">
           Населенный пункт
        </td>
        <td style="width: 60%">
           {$faktcity}
        </td>
    </tr>
    <tr>
        <td  style="width: 40%">
           Улица
        </td>
        <td style="width: 60%">
           {$faktstreet}
        </td>
    </tr>
    <tr>
        <td  style="width: 40%">
           Дом
        </td>
        <td style="width: 60%">
           {$fakthousing}
        </td>
    </tr>
    <tr>
        <td  style="width: 40%">
           Корпус
        </td>
        <td style="width: 60%">
           {$faktbuilding}
        </td>
    </tr>
    <tr>
        <td  style="width: 40%">
          Квартира
        </td>
        <td style="width: 60%">
           {$faktroom}
        </td>
    </tr>
    <tr>
        <td align="center" style="width: 100%">
           <strong>Документ, удостоверяющий личность</strong>
        </td>
    </tr>
     <tr>
        <td  style="width: 50%">
          Вид документа
        </td>
        <td style="width: 50%">
           Паспорт
        </td>
    </tr>
    <tr>
        <td  style="width: 20%">
          Серия
        </td>
        <td  style="width: 20%">
            {$passport_serial|escape}
        </td>
        <td  style="width: 20%">
          
        </td>
        <td  style="width: 20%">
          Номер
        </td>
        <td  style="width: 20%">
          {$passport_number|escape}
        </td>
    </tr>
    <tr>
        <td  style="width: 40%">
          Дата выдачи
        </td>
        <td style="width: 60%">
           {$passport_date|date}
        </td>
    </tr>
    <tr>
        <td  style="width: 40%">
          Кем выдан
        </td>
        <td style="width: 60%">
           {$passport_issued|escape}
        </td>
    </tr>
    <tr>
        <td  style="width: 40%">
          Дата рождения
        </td>
        <td style="width: 60%">
           {$birth}
        </td>
    </tr>
    <tr>
        <td  style="width: 40%">
          Место рождения: страна
        </td>
        <td style="width: 60%">
           РФ
        </td>
    </tr>
    <tr>
        <td  style="width: 40%">
          Населенный пунк
        </td>
        <td style="width: 60%">
           {$birth_place}
        </td>
    </tr>
    <tr>
        <td  style="width: 40%">
          Гражданство
        </td>
        <td style="width: 60%">
           РФ
        </td>
    </tr>
    <tr>
        <td  style="width: 40%">
          Код подразделения, выдавшего документ
        </td>
        <td style="width: 60%">
           {$subdivision_code}
        </td>
    </tr>
    <tr>
        <td  style="width: 40%">
          Для миграционной карты: номер карты, дата начала срока пребывания и дата окончания срока пребывания в Российской Федерации
        </td>
        <td style="width: 60%">
           
        </td>
    </tr>
    <tr>
        <td  style="width: 40%">
          Данные документа, подтверждающего право иностранного гражданина или лица без гражданства на пребывание (проживание) в Российской Федерации: серия (если имеется) и номер документа, дата начала срока действия права пребывания (проживания), дата окончания срока действия права пребывания (проживания), в случае если наличие указанных данных предусмотрено законодательством РФ
        </td>
        <td style="width: 60%">
           
        </td>
    </tr>
    <tr>
        <td align="center" style="width: 100%">
           <strong>Сведения, подтверждающие наличие у лица полномочий представителя Клиента</strong>
        </td>
    </tr>
    <tr>
        <td  style="width: 40%">
          Наименование документа
        </td>
        <td style="width: 60%">
           
        </td>
    </tr>
    <tr>
        <td  style="width: 40%">
          Дата выдачи документ
        </td>
        <td style="width: 60%">
           
        </td>
    </tr>
    <tr>
        <td  style="width: 40%">
          Срок действия документ
        </td>
        <td style="width: 60%">
           
        </td>
    </tr>
    <tr>
        <td  style="width: 40%">
          Номер документа
        </td>
        <td style="width: 60%">
           
        </td>
    </tr>
    <tr>
        <td align="center" style="width: 100%">
           <strong>Сведения о наличии Бенефициарного владельца</strong>
        </td>
    </tr>
    <tr>
        <td  style="width: 40%">
          Меры, принятые по идентификации Бенефициарного владельца
        </td>
        <td style="width: 60%">
           
        </td>
    </tr>
    <tr>
        <td align="center" style="width: 100%">
           <strong>Сведения о Бенефициарном владельце</strong>
        </td>
    </tr>
    <tr>
        <td  style="width: 40%">
          ФИО Бенефициарного владельца<sup>1</sup>
        </td>
        <td style="width: 60%">
           
        </td>
    </tr>
    <tr>
        <td rowspan="2" style="width: 40%">
          ФИО Бенефициарного владельца<sup>1</sup>
        </td>
        <td style="width: 30%">
           <strong>Представленные Клиентом</strong>
        </td>
        <td style="width: 30%">
           <strong>Установленные МКК самостоятельно</strong>
        </td>
    </tr>
    <tr>
        <td style="width: 30%">
           
        </td>
        <td style="width: 30%">
           
        </td>
    </tr>
    <tr>
        <td  style="width: 40%">
          Обоснование принятого решения о признании физического лица Бенефициарным владельцем 
        </td>
        <td style="width: 60%">
           
        </td>
    </tr>
    </tbody>
</table>
<br>
<div>
    * ФЛ – Клиент - физическое лицо, ВП(ФЛ) – выгодоприобретатель – физическое лицо, ГИГ – гражданин иностранного государства, ИПДЛ – иностранное публичное должностное лицо (указать должность), С/БР ИПДЛ – супруги (близкие родственники) иностранных публичных должностных лиц (указать степень родства); ДЛПМО - должностное лицо публичных международных организаций, РПДЛ – должностное лицо Российской Федерации (указать должность); БВ – бенефициарный владелец; ПК – представитель Клиента.
    (При наличии у Клиента представителя, выгодоприобретателя, бенефициарного владельца – физических лиц – данная анкета заполняется на каждое лицо отдельно).
    При заполнении анкеты в отношении физических лиц, не являющихся непосредственно Клиентами в графе «Тип физического лица» указывается в отношении какого именно Клиента данные лица являются ВП (ФЛ), С/БР ИПДЛ, БВ, ПК.
    <br><br>
    <sup>1</sup> В случае, если Бенефициарным владельцем Клиента – физического лица является иное физическое лицо, то полные данные о Бенефициарном владельце – физическом лице заполняются в анкете (сведения о физическом лице) - Приложение №1(2) к настоящим ПВК по ПОД/ФТ/ФРОМУ.  
    <br><br>
    ФИО сотрудника, принявшего решение о приеме Клиента на обслуживание, должность, подпись:
    <br><br>
    Дата оформления анкеты:
    <br><br>
    ФИО сотрудника заполнившего (обновившего) анкету, должность, подпись:
    <br><br>
    Даты обновлений анкеты:
    <br><br>
    <div style="text-align: center;">
        <strong>Сведения о результатах каждой проверки наличия (отсутствия) в отношении Клиента информации о его причастности к экстремистской деятельности или терроризму, финансированию распространения оружия массового уничтожения
        </strong>
    </div>
</div>

<table border="0.5" cellpadding="5">
    <tbody>
    <tr>
        <td style="width: 20%">Дата проверки</td>
        <td style="width: 20%">Результаты проверки</td>
        <td style="width: 20%">Номер и дата Перечня организаций и физических лиц, в отношении которых имеются сведения об их причастности к экстремистской деятельности и терроризму</td>
        <td style="width: 20%">Номер и дата Перечня организаций и физических лиц, в отношении которых имеются сведения об их причастности к распространению оружия массового уничтожения</td>
        <td style="width: 20%">Номер и дата решения Комиссии о замораживании (блокировании) денежных средств или иного имущества</td>
    </tr>
    <tr>
        <td style="width: 20%">&nbsp;</td>
        <td style="width: 20%"></td>
        <td style="width: 20%"></td>
        <td style="width: 20%"></td>
        <td style="width: 20%"></td>
    </tr>    <tr>
        <td style="width: 20%">&nbsp;</td>
        <td style="width: 20%"></td>
        <td style="width: 20%"></td>
        <td style="width: 20%"></td>
        <td style="width: 20%"></td>
    </tr>
        <tr>
        <td style="width: 20%">&nbsp;</td>
        <td style="width: 20%"></td>
        <td style="width: 20%"></td>
        <td style="width: 20%"></td>
        <td style="width: 20%"></td>
    </tr>
    
    </tbody>
</table>
