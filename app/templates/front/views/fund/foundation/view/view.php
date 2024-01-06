<?php
use app\h;
?>
<div class="container-fluid fund-detail-container ps-4 pe-4 mt-4 ">
    <h5 class="page-header">
        <?php if($foundation->archived == 1){ ?>
            <span class="archived-status"><?=t('ARCHIVED')?> - </span>
        <?php } ?>

        <?= t($foundation->name) ?>
    </h5>

    <div class="mt-5 row bg-secondary-200 pt-4 pe-3 ps-3 mas-s">
        <div class="d-flex justify-content-end">
            <div class="col-5 d-flex justify-content-end gap-3">
                <?php if($foundation->archived != 1){ ?>
                    <button class="btn btn-outline-secondary-600 btn-sm mb-4" data-hook="modal-confirm-archive" data-bs-toggle="modal"
                            data-bs-target="#modal-confirm"  data-cssId="modal-confirm"
                            data-confirm-href="<?=$foundation->getUrlArchiveAjax()?>"
                            data-confirm-title="<?= t('FOUNDATION_ARCHIVE')?>"
                            data-confirm-message='Weet je zeker dat je de stichting "<?=$foundation->name?>" wilt archiveren?'>
                        <i class="bi bi-archive ms-1" aria-hidden="true"> </i><?= t('FOUNDATION_ARCHIVE')?>
                    </button>
                <?php } ?>
            </div>
        </div>

        <div class="col-md-12 col-lg-4 col-xl-3 mb-4">
            <?php // Fondsgegevens
            $app->renderPartial('fund/view/foundationdetails', [
                'foundation' => $foundation
            ]);
            ?>
        </div>

        <div class="col-md-12 col-lg-8 col-xl-9 mb-4">
            <?php
            $app->renderPartial('document/table-document', [
                'table' => $tableDocument,
                'datatable' => $datatableDocument,
                'actions' => !empty($documentActions) ? $documentActions : null
            ]); ?>
        </div>
    </div>
</div>

<?php
$app->renderPartial('modal/modal-document-add-edit');
$app->renderPartial('modal/modal-document-delete');
$app->renderPartial(
    'modal/container-large',
    [
        'cssId' => 'modal-confirm',
        'contentPartial' => 'modal/confirm',
        'contentVariables' => []
    ]
);
?>
