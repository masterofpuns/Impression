<div class="container-fluid relation-detail-container ps-4 pe-4 mt-4 ">
    <?php if ($relation->type == 'CONTACT_PERSON') { ?>
        <a href="<?=$relation->parent->getUrlView()?>" class="btn btn-outline-secondary-600 btn-sm mb-4"><i class="bi bi-arrow-left-circle ms-1" aria-hidden="true"> </i><?= t('BACK_TO_PARENT_RELATION')?></a>
    <?php } ?>
    <h5 class="page-header">
        <?php if($relation->archived == 1){ ?>
            <span class="archived-status"><?=t('ARCHIVED')?> - </span>
        <?php } ?>

        <?php if ($relation->type == 'CONTACT_PERSON') { ?>
            <?= t('CONTACT_PERSON_OF').' '.t($relation->parent->type, [], false).' '.t($relation->parent->nameSortable) ?>
        <?php } else { ?>
            <?= t($relation->nameSortable) ?> <span class="relation-type">(<?= t($relation->type) ?>)</span>
        <?php } ?>
    </h5>
    <?php if(!empty($category)){ ?>
        <span class="subtitle mb-4"><?= t($category->name) ?? ""; ?></span>
    <?php } ?>

    <div class="mt-5 row bg-secondary-200 pt-4 pe-3 ps-3 mas-s">
        <div class="d-flex justify-content-end">
            <div class="col-5 d-flex justify-content-end gap-3">
                <a href="<?=$relation->getUrlEdit()?>" class="btn btn-outline-secondary-600 btn-sm mb-4"><i class="bi bi-pencil ms-1" aria-hidden="true"> </i><?= t('RELATION_EDIT')?></a>
                <?php if($relation->archived != 1){ ?>
                    <button class="btn btn-outline-secondary-600 btn-sm mb-4" data-hook="modal-confirm-archive" data-bs-toggle="modal"
                            data-bs-target="#modal-confirm"  data-cssId="modal-confirm"
                            data-confirm-href="<?=$relation->getUrlArchiveAjax()?>"
                            data-confirm-title="<?= t('RELATION_ARCHIVE')?>"
                            data-confirm-message='Weet je zeker dat je de relatie "<?=$relation->nameSortable?>" wilt archiveren?'>
                        <i class="bi bi-archive ms-1" aria-hidden="true"> </i><?= t('RELATION_ARCHIVE')?>
                    </button>
                <?php } ?>
            </div>
        </div>

        <?php
        switch ($relation->type) {
            case 'INDIVIDUAL';
                echo '<div class="col-md-12 col-lg-6 col-xl-5 mb-4">';
                $app->renderPartial('relation/view/contactdetails-individual', [
                    'typeData' => $typeData,
                    'relation' => $relation
                ]);
                echo '</div>';
                echo '<div class="col-md-12 col-lg-6 col-xl-4 mb-4">';
                $app->renderPartial('relation/view/generaldata', [
                    'typeData' => $typeData,
                    'relation' => $relation
                ]);
                echo '</div>';
                echo '<div class="col-md-12 col-lg-6 col-xl-3 mb-4">';
                $app->renderPartial('relation/view/identificationdata', [
                    'typeData' => $typeData,
                    'relation' => $relation
                ]);
                echo '</div>';
                echo '<div class="col-md-12 col-lg-6 mb-4">';
                $app->renderPartial('relation/view/contactpersons', [
                    'table' => $tableContactPerson,
                    'datatable' => $datatableContactPerson,
                    'relation' => $relation
                ]);
                echo '</div>';
                break;
            case 'ORGANIZATION';
                echo '<div class="col-md-12 col-lg-6 mb-4">';
                $app->renderPartial('relation/view/contactdetails-organization', [
                    'typeData' => $typeData,
                    'relation' => $relation
                ]);
                echo '</div>';
                echo '<div class="col-md-12 col-lg-6 mb-4">';
                $app->renderPartial('relation/view/generaldata', [
                    'typeData' => $typeData,
                    'relation' => $relation
                ]);
                echo '</div>';
                echo '<div class="col-md-12 col-lg-6 mb-4">';
                $app->renderPartial('relation/view/contactpersons', [
                    'table' => $tableContactPerson,
                    'datatable' => $datatableContactPerson,
                    'relation' => $relation
                ]);
                echo '</div>';
                break;
            case 'COLLECTIVE';
                echo '<div class="col-md-12 col-lg-6 mb-4">';
                $app->renderPartial('relation/view/contactdetails-collective', [
                    'typeData' => $typeData,
                    'relation' => $relation
                ]);
                echo '</div>';
                echo '<div class="col-md-12 col-lg-6 mb-4">';
                $app->renderPartial('relation/view/generaldata', [
                    'typeData' => $typeData,
                    'relation' => $relation
                ]);
                echo '</div>';
                echo '<div class="col-md-12 col-lg-6 mb-4">';
                $app->renderPartial('relation/view/contactpersons', [
                    'table' => $tableContactPerson,
                    'datatable' => $datatableContactPerson,
                    'relation' => $relation
                ]);
                echo '</div>';
                break;
            case 'ADVISOR';
                echo '<div class="col-md-12 col-lg-6 mb-4">';
                $app->renderPartial('relation/view/contactdetails-advisor', [
                    'typeData' => $typeData,
                    'relation' => $relation
                ]);
                echo '</div>';
                echo '<div class="col-md-12 col-lg-6 mb-4">';
                $app->renderPartial('relation/view/identificationdata', [
                    'typeData' => $typeData,
                    'relation' => $relation
                ]);
                echo '</div>';
                break;
            case 'CONTACT_PERSON';
                echo '<div class="col-md-12 col-lg-6 mb-4">';
                $app->renderPartial('relation/view/contactdetails-contactperson', [
                    'typeData' => $typeData,
                    'relation' => $relation
                ]);
                echo '</div>';
                echo '<div class="col-md-12 col-lg-6 mb-4">';
                $app->renderPartial('relation/view/identificationdata', [
                    'typeData' => $typeData,
                    'relation' => $relation
                ]);
                echo '</div>';
                break;
        }
        ?>

        <div class="col-md-12 col-lg-6 mb-4">
            <?php
            $app->renderPartial('document/table-document', [
                'table' => $tableDocument,
                'datatable' => $datatableDocument,
                'actions' => !empty($documentActions) ? $documentActions : null
            ]); ?>
        </div>
        <div class="col-md-12 col-lg-6 mb-4">
            <?php
            $app->renderPartial(
                'relation/view/notes',
                [
                    'notes' => $notes,
                    'add_note_url' => $add_note_url,
                    'delete_note_url' => $delete_note_url,
                    'relation' => $relation
                ]
            ); ?>
        </div>
    </div>
</div>

<?php
$app->renderPartial('modal/modal-relation-note-add-edit');
$app->renderPartial('modal/modal-relation-note-delete');
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
