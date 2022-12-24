{$meta_title="История займов" scope=parent}

{capture name='page_scripts'}


{/capture}

{capture name='page_styles'}
    
{/capture}


<main class="main">
  <div class="section_lk_navbar">
    <div class="container">
      <nav class="navbar lk_menu">
        <ul class="nav lk_menu_nav -gil-m">
          <li class="nav-item">
            <a class="nav-link" href="account">Общая информация</a>
          </li>
          <li class="nav-item active">
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
      <h1>Личный кабинет</h1>

      <!-- история займов -->
      {if $history_items}
      <div class="person_info_box">
        <div class="person_info">
          <div class="person_info_title -fs-26 -gil-m">История займов:</div>
          <div class="person_info_wrap -fs-18">
            <div class="order_item_list">
              
              <div class="order_item_row header_row row">
                <div class="col-sm-4">
                  <div class="order_item_col">Номер договора</div>
                </div>
                <div class="col-sm-4">
                  <div class="order_item_col">Дата</div>
                </div>
                <div class="col-sm-4">
                  <div class="order_item_col">Остаток</div>
                </div>
              </div>
              
              {foreach $history_items as $item}
              <div class="order_item_row row -gil-m">
                <div class="col-sm-4">
                  <div class="order_item_col">{$item->number}</div>
                </div>
                <div class="col-sm-4">
                  <div class="order_item_col">{$item->date|date}</div>
                </div>
                <div class="col-sm-4">
                  <div class="order_item_col">{$item->summ} руб.</div>
                </div>
              </div>
              {/foreach}
              {*}
              <div class="order_item_row row -gil-m">
                <div class="col-sm-3">
                  <div class="order_item_col">№5312-351241</div>
                </div>
                <div class="col-sm-3">
                  <div class="order_item_col">7 000 руб.</div>
                </div>
                <div class="col-sm-3">
                  <div class="order_item_col">до 12.05.2020г.</div>
                </div>
                <div class="col-sm-3">
                  <div class="order_item_col -green">одобрено</div>
                </div>
              </div>
              <div class="order_item_row row -gil-m">
                <div class="col-sm-3">
                  <div class="order_item_col">№1242-371246</div>
                </div>
                <div class="col-sm-3">
                  <div class="order_item_col">32 000 руб.</div>
                </div>
                <div class="col-sm-3">
                  <div class="order_item_col">до 27.02.2019г.</div>
                </div>
                <div class="col-sm-3">
                  <div class="order_item_col -red">отказано</div>
                </div>
              </div>
              <div class="order_item_row row -gil-m">
                <div class="col-sm-3">
                  <div class="order_item_col">№5312-351241</div>
                </div>
                <div class="col-sm-3">
                  <div class="order_item_col">7 000 руб.</div>
                </div>
                <div class="col-sm-3">
                  <div class="order_item_col">до 12.05.2020г.</div>
                </div>
                <div class="col-sm-3">
                  <div class="order_item_col -green">одобрено</div>
                </div>
              </div>
              {*}
            </div>
          </div>
        </div>
      </div>
      {/if}
      
      
      {if $orders}
      <div class="person_info_box">
        <div class="person_info">
          <div class="person_info_title -fs-26 -gil-m">История займов:</div>
          <div class="person_info_wrap -fs-18">
            <div class="order_item_list">
              
              <div class="order_item_row header_row row">
                <div class="col-sm-3">
                  <div class="order_item_col">Номер договора</div>
                </div>
                <div class="col-sm-3">
                  <div class="order_item_col">Сумма</div>
                </div>
                <div class="col-sm-3">
                  <div class="order_item_col">Срок</div>
                </div>
                <div class="col-sm-3">
                  <div class="order_item_col">Статус</div>
                </div>
              </div>
              
              {foreach $orders as $o}
              <div class="order_item_row row -gil-m">
                <div class="col-sm-3">
                  <div class="order_item_col">{$o->contract->number}</div>
                </div>
                <div class="col-sm-3">
                  <div class="order_item_col">{$o->amount} руб.</div>
                </div>
                <div class="col-sm-3">
                  <div class="order_item_col">{$o->period} {$o->period|plural:'день':'дней':'дня'}</div>
                </div>
                <div class="col-sm-3">
                  <div class="order_item_col status_{$o->status}">{$statuses[$o->status]}</div>
                </div>
              </div>
              {/foreach}
              {*}
              <div class="order_item_row row -gil-m">
                <div class="col-sm-3">
                  <div class="order_item_col">№5312-351241</div>
                </div>
                <div class="col-sm-3">
                  <div class="order_item_col">7 000 руб.</div>
                </div>
                <div class="col-sm-3">
                  <div class="order_item_col">до 12.05.2020г.</div>
                </div>
                <div class="col-sm-3">
                  <div class="order_item_col -green">одобрено</div>
                </div>
              </div>
              <div class="order_item_row row -gil-m">
                <div class="col-sm-3">
                  <div class="order_item_col">№1242-371246</div>
                </div>
                <div class="col-sm-3">
                  <div class="order_item_col">32 000 руб.</div>
                </div>
                <div class="col-sm-3">
                  <div class="order_item_col">до 27.02.2019г.</div>
                </div>
                <div class="col-sm-3">
                  <div class="order_item_col -red">отказано</div>
                </div>
              </div>
              <div class="order_item_row row -gil-m">
                <div class="col-sm-3">
                  <div class="order_item_col">№5312-351241</div>
                </div>
                <div class="col-sm-3">
                  <div class="order_item_col">7 000 руб.</div>
                </div>
                <div class="col-sm-3">
                  <div class="order_item_col">до 12.05.2020г.</div>
                </div>
                <div class="col-sm-3">
                  <div class="order_item_col -green">одобрено</div>
                </div>
              </div>
              {*}
            </div>
          </div>
        </div>
      </div>
      {/if}
      <!-- /история займов -->
      
    </div>
  </div>
</main>
