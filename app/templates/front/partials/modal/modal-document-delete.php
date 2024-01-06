<div class='modal' id='modal-document-delete' tabindex='-1'>
    <div class='modal-dialog modal-dialog-centered'>
        <div class='modal-content p-3 rounded'>
            <div class='modal-header border-0 pb-0'>
                <h5 class='modal-title'><?= t('DELETE_DOCUMENT'); ?></h5>
                <button type='button' class='btn' data-bs-dismiss='modal' aria-label='Close'><i class='bi bi-x-circle'></i></button>
            </div>
            <div class='modal-body pb-0'>
                <span data-hook="modal-document-delete_document_message"></span>
            </div>
            <div class="modal-footer pt-3 pb-4 justify-content-center border-0">
                <button type="button" class="btn btn-outline-secondary-600 btn-cancel icon-btn icon-abort px-14" data-bs-dismiss="modal"><?= t('CANCEL') ?></button>
                <button type="button" class="btn btn-success px-14" data-hook="modal-document-delete-confirm" data-document-id><?= t('CONFIRM'); ?></button>
            </div>
        </div>
    </div>
</div>