<div class="block contact-persons-block" data-hook="dataTable_container">
    <div class='utilities-table-container flex-wrap align-items-center d-flex justify-content-between flex-row'>
        <div>
            <h5 ><?=t('CONTACT_PERSONS')?></h5>
        </div>
        <div class="d-flex justify-content-between flex-wrap gap-3">
            <div class="dropdown d-flex align-items-center">
                <button type="button"
                        class="btn text-center  custom-dropdown ms-2 btn-secondary-100 d-flex align-items-center filter-dropdown dropdown-toggle"
                        role="button" id="custom_table_control-filter_show_status" data-bs-toggle="dropdown"
                        aria-expanded="false">
                    <span class="floating-label">Toon</span>
                    <span class="text-size-filter" data-hook="custom_table_control-filter_show_status-active"><?= t(
                            'CURRENT_CONTACTPERSONS'
                        ) ?></span>
                </button>
                <ul class="dropdown-menu filter-dropdown-tables" aria-labelledby="custom_table_control-filter_show"
                    data-hook="custom_table_control-filter_show_status-select">
                    <li>
                        <button class="dropdown-item first-item" type="button" data-value="all"><?= t(
                                'SELECT_ALL'
                            ) ?></button>
                    </li>
                    <li>
                        <button class="dropdown-item" type="button" data-value="active"><?= t(
                                'CURRENT_CONTACTPERSONS'
                            ) ?></button>
                    </li>
                    <li>
                        <button class="dropdown-item" type="button" data-value="archived"><?= t(
                                'ARCHIVED_CONTACTPERSONS'
                            ) ?></button>
                    </li>
                </ul>

                <input type="hidden" name="showStatusFilter" value="active">
            </div>
            <div class="d-flex align-items-center">
                <a href="<?= $app->getDocByName('relation-contactperson-add')->getUrl([$relation->id]); ?>"
                   class='btn btn-sm btn-outline-success text-nowrap'>
                    <i class='bi bi-plus'></i> <?= t('ADD_CONTACT_PERSON'); ?>
                </a>
            </div>
        </div>
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