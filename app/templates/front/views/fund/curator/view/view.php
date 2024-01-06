<?php
use app\h;
?>
<div class="container-fluid fund-detail-container ps-4 pe-4 mt-4 ">
    <h5 class="page-header">
        <?php if($curator->archived == 1){ ?>
            <span class="archived-status"><?=t('ARCHIVED')?> - </span>
        <?php } ?>

        <?= t($curator->name) ?>
        <button type='button' class='btn btn-outline-secondary-700 border-0 blue-hover p-1'
                data-hook='view-curator-edit' data-section='contact'><i class='bi bi-pencil'></i></button>

    </h5>

    <div class="mt-5 row bg-secondary-200 pt-4 pe-3 ps-3 mas-s">
        <div class="d-flex justify-content-end">
            <div class="col-5 d-flex justify-content-end gap-3">
                <?php if($curator->archived != 1){ ?>
                    <button class="btn btn-outline-secondary-600 btn-sm mb-4" data-hook="modal-confirm-archive" data-bs-toggle="modal"
                            data-bs-target="#modal-confirm"  data-cssId="modal-confirm"
                            data-confirm-href="<?=$curator->getUrlArchiveAjax()?>"
                            data-confirm-title="<?= t('CURATOR_ARCHIVE')?>"
                            data-confirm-message='Weet je zeker dat je de beheerder "<?=$curator->name?>" wilt archiveren?'>
                        <i class="bi bi-archive ms-1" aria-hidden="true"> </i><?= t('CURATOR_ARCHIVE')?>
                    </button>
                <?php } ?>
            </div>
        </div>

        <div class="col-md-12 col-lg-12 col-xl-12 mb-4">
            <?php
            $app->renderPartial('fund/view/table-fund', [
                'table' => $tableFund,
                'datatable' => $datatableFund,
                'actions' => !empty($fundActions) ? $fundActions : null
            ]); ?>
        </div>
    </div>
</div>

<?php
$app->renderPartial(
    'modal/container-large',
    [
        'cssId' => 'modal-confirm',
        'contentPartial' => 'modal/confirm',
        'contentVariables' => []
    ]
);
?>