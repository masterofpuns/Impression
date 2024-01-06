<div class='col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg bg-secondary-200'>
    <div class='d-flex flex-column align-items-center align-items-sm-start pt-4 text-white sidebar'>
        <ul class='nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start' id='menu'>
            <?
            // Navigation menu
            if (isset($app->menus['nav'])) {
                $app->renderPartial(
                    'navbar/nav',
                    array(
                        'menu' => $app->menus['nav'],
                    )
                );
            }
            ?>
        </ul>
        <hr>
    </div>
</div>
