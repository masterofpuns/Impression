<div class="modal " id="modal-document-add-edit" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-3 rounded">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title" data-hook="modal-document-title" data-translation-add="<?=t('DOCUMENT_ADD')?>" data-translation-edit="<?=t('DOCUMENT_EDIT')?>"></h5>
                <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-circle"></i></button>
            </div>
            <div class="modal-body pb-0">
                <form enctype="multipart/form-data" data-hook="modal-document-form_document_upload_document">
                    <div class='form-item mb-2'>
                        <label for='modal-document-form_document-name' class='form-label'><?= t('DOCUMENT_NAME'); ?></label>
                        <input
                            type='text'
                            name='Document[name]'
                            id="modal-document-form_document-name"
                            class='form-control'
                            data-hook="modal-document-form_document-name"
                        >
                    </div>
                    <div class='form-item'>
                        <label for='modal-document-form_document-file' class='form-label' data-hook="modal-document-label-upload" data-translation-add="<?= t('UPLOAD_DOCUMENT'); ?>" data-translation-edit="<?= t('UPLOAD_NEW_DOCUMENT'); ?>"></label>
                        <input type='file' name='Document[file]' id="modal-document-form_document-file" class='form-control'>
                    </div>
                    <input type="hidden" name="Document[id]" data-hook="modal-document-form_document-id" value="">
                </form>
            </div>
            <div class="modal-footer pt-3  justify-content-center border-0">
                <button type="button" class="btn btn-outline-secondary-600 btn-cancel icon-btn icon-abort px-14" data-bs-dismiss="modal"><?=t('CANCEL')?></button>
                <button type="button" class="btn btn-success px-14" data-hook="modal-document-add-edit-confirm" data-translation-add="<?=t('ADD')?>" data-translation-edit="<?=t('EDIT')?>"></button>
            </div>
        </div>
    </div>
</div>