<?php
use app\h;
?>
<div class="container-fluid fund-detail-container ps-4 pe-4 mt-4 ">
    <?php
    $app->renderPartial('fund/view/header', ['fund' => $fund]);
    $app->renderPartial('fund/view/navbar-tabs', ['fund' => $fund]);
    ?>

    <div class="mt-5 row bg-secondary-200 pt-4 pe-3 ps-3 mas-s">
        <div class="d-flex justify-content-end">
            <div class="col-5 d-flex justify-content-end gap-3">
                <?php if($fund->archived != 1){ ?>
                    <button class="btn btn-outline-secondary-600 btn-sm mb-4" data-hook="modal-confirm-archive" data-bs-toggle="modal"
                            data-bs-target="#modal-confirm"  data-cssId="modal-confirm"
                            data-confirm-href="<?=$fund->getUrlArchiveAjax()?>"
                            data-confirm-title="<?= t('FUND_ARCHIVE')?>"
                            data-confirm-message='Weet je zeker dat je het fonds "<?=$fund->name?>" wilt archiveren?'>
                        <i class="bi bi-archive ms-1" aria-hidden="true"> </i><?= t('FUND_ARCHIVE')?>
                    </button>
                <?php } ?>
            </div>
        </div>

        <div class="col-md-12 col-lg-4 col-xl-3 mb-4">
            <?php // Fondsgegevens
            $app->renderPartial('fund/view/funddetails', [
                'fund' => $fund
            ]);
            ?>
        </div>

        <div class="col-md-12 col-lg-8 col-xl-9 mb-4">
            <?php // Fonds inhoudelijk
            $app->renderPartial('fund/view/fundcontent', [
                'fund' => $fund
            ]);
            ?>
        </div>

        <div class="col-md-12 col-lg-4 col-xl-3 mb-4">
            <?php //Aflossing
            $app->renderPartial('fund/view/repayment', [
                'fund' => $fund
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
