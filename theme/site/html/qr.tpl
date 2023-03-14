{capture name='page_scripts'}


{/capture}

{capture name='page_styles'}

{/capture}

<main class="main">
    <div class="section ">
        <div class="wrapper">
            <div class="section_row row">


                <div class="col-lg-12">
                    {* <h1>{$page->name}</h1> *}
                    <section class="breadcrumbs">
                        <a href="" class="breadcrumbs__link breadcrumbs__link_home">Главная</a>
                        <span class="breadcrumbs__separator"> / </span>
                        <a class="breadcrumbs__link breadcrumbs__link_active">{$page->name|escape}</a>
                    </section>
                    <div>
                        123
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>
