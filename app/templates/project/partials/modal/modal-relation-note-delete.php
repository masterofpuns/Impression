<div class="modal" id="modal-note-delete" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-3 rounded">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title" data-hook="modal-note-title"><?=t('NOTE_DELETE')?></h5>
                <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-circle"></i></button>
            </div>
            <div class="modal-body pb-0">
                <p data-hook="modal-relation-note-message"></p>
                <p data-hook="modal-confirm-error"></p>
            </div>
            <div class="modal-footer pt-3 justify-content-center border-0">
                <button type="button" class="btn btn-sm btn-outline-secondary-600 btn-cancel icon-btn icon-abort px-14" data-bs-dismiss="modal"><?=t('CANCEL')?></button>
                <button type="button" class="btn btn-sm btn-danger px-14" data-hook="modal-relation-note-delete-confirm"><?=t('DELETE')?></button>
            </div>
        </div>
    </div>
</div>