<?php
use app\h;
?>
<div class="container-fluid fund-detail-container ps-4 pe-4 mt-4 ">
    <?php
    $app->renderPartial('fund/view/header', ['fund' => $fund]);
    $app->renderPartial('fund/view/navbar-tabs', ['fund' => $fund]);
    ?>

    <div class="mt-5 row bg-secondary-200 pt-4 pe-3 ps-3 mas-s">
        <div class="col-md-12 col-lg-6 col-xl-6 mb-6">
            <div class="block">
                <div class="flexer">
                    <h5 class="mb-3"><?= t('VERTAALSTRING') ?></h5>
                </div>
            </div>
        </div>

        <div class="tab-background pt-4 pb-4">
            <div class='container-content p-3 table-responsive' data-hook='dataTable_container'>
                <?php
                // load table
                $app->renderPartial(
                    'table/table',
                    array(
                        'table' => $table,
                        'datatable' => $datatable
                    )
                );
                ?>
            </div>
        </div>
    </div>
</div>