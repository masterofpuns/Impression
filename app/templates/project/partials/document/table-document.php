<div class='block document-block' data-hook='dataTable_container'>
    <div class='flex-wrap align-items-center d-flex justify-content-between flex-row'>
        <h5><?= t('DOCUMENTS') ?></h5>

        <?php
        if (!empty($actions)) {
            $app->renderPartial(
                'button/button-group',
                [
                    'align' => 'right',
                    'buttons' => $actions,
                    'showDescription' => true
                ],
            );
        }
        ?>

    </div>
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