<div class="container-fluid ps-4 pe-3 mt-4">
    <div class="d-flex justify-content-start flex-row  mb-4">
        <div class="col d-flex flex-row gap-5 align-items-center">
            <h4 class="page-name text-text-color"><?= t($app->doc->heading); ?></h4>
        </div>
    </div>

    <?php $app->renderPartial('fund/overview/navbar-tabs'); ?>
    <div class="tab-background p-4">
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