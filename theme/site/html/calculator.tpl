<div id="rec427745335" class="r t-rec" style=" " data-animationappear="off" data-record-type="131"><!-- T123 -->
  <div class="t123">
    <div class="t-container_100 ">
      <div class="t-width t-width_100 ">
        <meta charset="UTF-8">
        <title>Calculator</title>

        <style id="range-styles">
          #sum::-webkit-slider-runnable-track {
            width: 100%;
            height: 3px;
            background: linear-gradient(to right, #a60d0d 0%, #a60d0d 50%, rgba(0, 0, 0, .2) 50%, rgba(0, 0, 0, .2) 100%);
          }

          #date::-webkit-slider-runnable-track {
            width: 100%;
            height: 3px;
            background: linear-gradient(to right, #a60d0d 0%, #a60d0d 50%, rgba(0, 0, 0, .2) 50%, rgba(0, 0, 0, .2) 100%);
          }
        </style>


        <section id="form" class="block">
          <div class="block__inner">
            <div class="block__text-outer">
              <strong class="heading big">Срочные</strong>
              <strong class="heading medium">займы онлайн</strong>
              <strong class="heading big">на карту</strong>

              <span class="heading small">Просто, прозрачно <br> и выгодно</span>
            </div>
            <div class="calculator-outer">
              <div class="calculator-background"></div>
              <form class="calculator js-loan-start-form js-calc" method="POST" data-percent="{$loan_percent}">
                <label class="calculator__form-group">
                  <span class="calculator__form-label">Выберите сумму в рублях:</span>
                  <div class="calculator__input-container">
                    <output id="sum-output" class="calculator__output" style="left: 76.9231%;">12 000</output>
                    <input name="amount" id="sum" data-range-slider="" class="calculator__input" min="{$min_summ}" max="{$max_summ}" value="{$current_summ}" step="500" type="range">
                    <div class="calculator__input-ranges">
                      <small>2 000</small>
                      <small>15 000</small>
                    </div>
                  </div>
                </label>
                <label class="calculator__form-group">
                  <span class="calculator__form-label">Выберите количество дней:</span>
                  <div class="calculator__input-container">
                    <output id="date-output" class="calculator__output" style="left: 100%;">{$current_period}</output>
                    <input name="period" id="date" data-range-slider="" class="calculator__input" min="{$min_period}" max="{$max_period}" value="{$current_period}" type="range">
                    <div class="calculator__input-ranges">
                      <small>{$min_period}</small>
                      <small>{$max_period}</small>
                    </div>
                  </div>
                </label>
                <div class="calculator__result">
                  <span class="calculator__result-row">
                    <span>Сумма займа: </span>
                    <span>
                      <span id="result-sum">12 000</span>
                      рублей
                    </span>
                  </span>
                  <span class="calculator__result-row">
                    <span>Срок займа до: </span>
                    <span id="result-date">12.02.2023</span>
                  </span>
                  <span class="calculator__result-row">
                    <span>Сумма к возврату: </span>
                    <span>
                      <span id="result-return-sum">15 960</span>
                      рублей
                    </span>
                  </span>
                </div>
                <div class="form-group form-phone ">
                  <span class="phone_info -fs-14">Ваш номер телефона</span>
                  <input type="text" name="phone" id="phone" class="form-control -fs-18 -gil-m js-mask-phone js-loan-phone" value="">
                  <input type="hidden" name="code" id="" class="js-mask-sms js-loan-code" value="">
                  <div class="error_text js-loan-phone-error" style="display:none">Укажите номер телефона</div>
                </div>
                <div class="form-group">
                  <div class="form_row">
                    <div class="check mb-0 js-loan-agreement-block">
                      <input type="checkbox" class="custom-checkbox js-loan-agreement" id="check_agreement" name="agreement" value="1" />
                      <label for="check_agreement" class="check_box -gil-m">
                        <span>Я ознакомлен со <a href="#agreement_list" class="green-link js-toggle-agreement-list">следующим</a></span>
                      </label>
                    </div>
                  </div>
                </div>

                {include file='agreement_list.tpl'}

                <button id="calculator-button" class="calculator__button js-loan-start" type="button">Получить заём</button>

                <div class="form-group">
                  <div class="form_row">
                    <div class="check mb-0 check_box justify-content-center">
                      <span><a href="#promo_code" class="green-link js-toggle-promo-code">У меня есть промокод</a></span>
                    </div>
                  </div>
                </div>

                <div id="promo_code" style="display:none">
                  <div id="promo_input" class="form-group form-phone">
                    <span class="phone_info -fs-14">Промокод</span>
                    <input id="promoCode" type="text" class="form-control -fs-18 -gil-m">
                  </div>

                  <div style="display:none; color: RGB(189, 148, 87)" class="text-center mb-3 js-success-promo">
                    <p>Промокод активирован <svg xmlns="http://www.w3.org/2000/svg" width="10" height="8" viewBox="0 0 10 8" fill="none">
                        <path d="M9.88442 1.8301L4.15429 7.55898C4.00072 7.71194 3.75208 7.71194 3.59912 7.55898L0.114476 4.05205C-0.0384842 3.89847 -0.0384842 3.6489 0.114476 3.49625L0.947087 2.66426C1.10067 2.51099 1.3493 2.51099 1.50226 2.66426L3.87965 5.05744L8.4957 0.441075C8.64866 0.288115 8.8973 0.288115 9.0515 0.441075L9.88411 1.2743C10.0386 1.42757 10.0386 1.67714 9.88442 1.8301Z" fill="#33CC66"></path>
                      </svg>
                    </p>
                  </div>

                  <div class="text-center text-danger mb-3 js-error-promo" style="display:none">
                    <p>Промокод не применен</p>
                  </div>

                  <div class="form-group form-btn">
                    <button id="check_promo_code" href="javascript:void(0);" class="calculator__button  js-promo-code-ckeck">Применить</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </section>

        <style>
          body {
            margin: 0;
            font-family: Roboto, Arial, sans-serif;
          }

          .heading {
            display: block;
            color: #fff;
            text-transform: uppercase;
            font-weight: 700;
          }

          .heading.big {
            font-size: 85px;
          }

          .heading.medium {
            font-size: 50px;
          }

          .heading.small {
            display: block;
            font-size: 40px;
            text-align: left;
            margin-top: 60px;
          }

          .block {
            text-align: right;
            padding: 94px 140px;
            position: relative;
            overflow: hidden;
            background: url("https://thumb.tildacdn.com/tild6266-3732-4437-b230-663837393362/-/format/webp/handsome-man-in-snow.png") 50% 50% no-repeat,
              #353536;
            background-size: cover;
            box-sizing: border-box;
          }

          .block__inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
          }

          .block__background {
            height: 100%;
            position: absolute;
            left: 0;
            top: 0;
            transform: translateX(-6%);
          }

          .calculator-outer {
            display: inline-block;
            position: relative;
          }

          .calculator {
            display: inline-block;
            max-width: 420px;
            width: 100%;
            background-color: #fff;
            border-radius: 5px;
            text-align: left;
            padding: 50px;
            box-sizing: border-box;
            position: relative;
          }

          .calculator-background {
            content: "";
            display: block;
            width: 100%;
            height: 100%;
            background-color: #bd9457;
            position: absolute;
            left: 7px;
            top: 7px;
            border-radius: 5px;
          }

          .calculator__form-group {
            display: block;
            margin-bottom: 2.5rem;
          }

          .calculator__form-label {
            display: block;
            margin-bottom: .5rem;
            font-size: 1.2rem;
            font-weight: 600;
          }

          .calculator__input {
            width: 100%;
            -webkit-appearance: none;
          }

          .calculator__input::-webkit-slider-thumb {
            width: 19px;
            height: 19px;
            border-radius: 50%;
            background-color: #a60d0d;
            cursor: pointer;
            border: 1px solid #fff;
            box-shadow: 0 0 5px rgba(0, 0, 0, .5);

            -webkit-appearance: none;
            margin-top: -8px;
          }

          .calculator__input::-moz-range-thumb {
            width: 19px;
            height: 19px;
            border-radius: 50%;
            background-color: #a60d0d;
            cursor: pointer;
            border: 1px solid #fff;
            box-shadow: 0 0 5px rgba(0, 0, 0, .5);
          }

          .calculator__input::-moz-range-track {
            width: 100%;
            height: 3px;
            background-color: rgba(0, 0, 0, .2);
          }

          .calculator__input-container {
            position: relative;
            padding-top: 37px;
          }

          .calculator__output {
            display: inline-block;
            padding: .4rem;
            border-radius: 4px;
            box-shadow: 0 0 2px rgba(0, 0, 0, .5);
            background: #fff;
            margin-bottom: .2rem;
            position: absolute;
            left: 50%;
            top: 0;
            transform: translateX(-45%);
            white-space: nowrap;
          }

          .calculator__input-ranges {
            margin-top: .4rem;
            display: flex;
            justify-content: space-between;
          }

          .calculator__result {
            margin-bottom: 2rem;
          }

          .calculator__result-row {
            display: flex;
            justify-content: space-between;
            font-size: 1.2rem;
            font-weight: 600;
          }

          .calculator__result-row+.calculator__result-row {
            margin-top: 1rem;
          }

          .calculator__button {
            color: #fff;
            background-color: #a60d0d;
            border: none;
            border-radius: 5px;
            font-weight: 700;
            font-size: 17px;
            width: 320px;
            height: 60px;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 1rem;
            padding: 0 15px;
            display: block;
            cursor: pointer;
          }

          @media (max-width: 1400px) {
            .block {
              padding: 94px 50px;
            }
          }

          @media (max-width: 991px) {
            .block {
              background: none;
              padding: 0;
              height: unset;
            }

            .calculator-background {
              display: none;
            }

            .calculator-outer {
              width: 100%;
            }

            .calculator {
              display: block;
              max-width: 600px;
              width: 100%;
              margin: 0 auto;
            }

            .block__text-outer {
              width: 100%;
              background: url("https://thumb.tildacdn.com/tild6266-3732-4437-b230-663837393362/-/format/webp/handsome-man-in-snow.png") 200px 50% no-repeat,
                #353536;
              background-size: cover;
              box-sizing: border-box;
              display: flex;
              flex-direction: column;
              align-items: flex-start;
              padding: 50px 5%;
            }

            .block__inner {
              flex-direction: column;
            }

            .heading.big {
              font-size: 64px;
            }

            .heading.medium {
              font-size: 38px;
            }

            .heading.small {
              font-size: 28px;
              margin-top: 20px;
            }
          }

          @media (max-width: 768px) {
            .block__text-outer {
              background-position: 50px 50%;
            }
          }

          @media (max-width: 576px) {
            .heading {
              margin: 0 auto;
            }

            .heading.big {
              font-size: 52px;
            }

            .heading.medium {
              font-size: 31px;
            }

            .heading.small {
              font-size: 24px;
            }

            .block__text-outer {
              background-position: 50% 50%;
              padding: 250px 0 50px;
            }

            .calculator__button {
              max-width: unset;
              width: 100%;
            }

            .calculator * {
              font-size: 16px !important;
            }
          }
        </style>

        <script>
          (function() {
            let rangeStyleSheet = null;
            let sumRangeCssStyles = null;
            let dateRangeCssStyles = null;

            const sumSlider = document.getElementById('sum');
            const dateSlider = document.getElementById('date');

            const sumOutput = document.getElementById('sum-output');
            const dateOutput = document.getElementById('date-output');

            const resultSumEl = document.getElementById('result-sum');
            const resultReturnSumEl = document.getElementById('result-return-sum');
            const resultDateEl = document.getElementById('result-date');

            const calculatorButton = document.getElementById('calculator-button');

            for (let i = 0; i < document.styleSheets.length; ++i) {
              if (document.styleSheets[i].ownerNode.id === 'range-styles') {
                rangeStyleSheet = document.styleSheets[i];
                for (let j = 0; j < rangeStyleSheet.cssRules.length; ++j) {
                  if (/#sum/gi.test(rangeStyleSheet.cssRules[j].selectorText)) {
                    sumRangeCssStyles = rangeStyleSheet.cssRules[j];
                  }
                  if (/#date/gi.test(rangeStyleSheet.cssRules[j].selectorText)) {
                    dateRangeCssStyles = rangeStyleSheet.cssRules[j];
                  }
                }
                break;
              }
            }

            init();

            window.addEventListener('input', function(event) {
              const element = event.target;
              if (!element.hasAttribute('data-range-slider') || !sumRangeCssStyles) {
                return;
              }
              debugger;
              const currentValue = +element.value;

              switch (element.id) {
                case 'sum': {
                  updateSumSliderView();
                  if (currentValue <= 10000 && dateSlider.value < 16) {
                    dateSlider.value = 16;
                    updateDateSliderView()
                  }
                  break;
                }
                case 'date': {
                  updateDateSliderView();
                  if (currentValue <= 15 && sumSlider.value < 10500) {
                    sumSlider.value = 10500;
                    updateSumSliderView();
                  }
                  break;
                }
              }

              updateResult();
            });


            function calculateReturnSum(sum, days) {
              const percent = 0.01;
              return sum + sum * days * percent;
            }

            function updateDateSliderView() {
              const min = +dateSlider.min;
              const max = +dateSlider.max;
              const currentValue = +dateSlider.value;
              const percent = (currentValue - min) / (max - min) * 100;
              dateRangeCssStyles.style.background = "linear-gradient(to right, #a60d0d 0%, #a60d0d " + percent + "%, rgba(0, 0, 0, .2) " + percent + "%, rgba(0, 0, 0, .2) 100%)";
              dateOutput.style.left = percent + "%";
              dateOutput.textContent = currentValue.toLocaleString().replace(/,/gi, ' ');
            }

            function updateSumSliderView() {
              const min = +sumSlider.min;
              const max = +sumSlider.max;
              const currentValue = +sumSlider.value;
              const percent = (currentValue - min) / (max - min) * 100;

              sumRangeCssStyles.style.background = "linear-gradient(to right, #a60d0d 0%, #a60d0d " + percent + "%, rgba(0, 0, 0, .2) " + percent + "%, rgba(0, 0, 0, .2) 100%)";
              sumOutput.style.left = percent + "%";

              const sum = currentValue.toLocaleString().replace(/,/gi, ' ');
              sumOutput.textContent = sum;
              resultSumEl.textContent = sum;
            }

            function updateResult() {
              const currentDate = new Date();
              const resultDate = new Date(currentDate.setDate(+dateSlider.value + currentDate.getDate()));

              resultReturnSumEl.textContent = calculateReturnSum(+sumSlider.value, +dateSlider.value).toLocaleString().replace(/,/gi, ' ');
              resultDateEl.textContent = resultDate.toLocaleDateString('ru-RU');
            }

            function init() {
              dateSlider.value = 33;
              updateDateSliderView();
              sumSlider.value = 12000;
              updateSumSliderView();
              updateResult();
            }
          })()
        </script>



      </div>
    </div>
  </div>
</div>
