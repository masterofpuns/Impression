<div class="modal-header pb-0">
    <h5 class="modal-title"><span data-hook="modal-confirm-title"></span></h5>
    <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-circle"></i></button>
</div>
<div class="modal-body pb-0">
    <p class="mb-0" data-hook="modal-confirm-message"></p>
    <p class="mb-0" data-hook="modal-confirm-error"></p>
</div>
<div class="modal-footer pt-3  justify-content-center border-0">
    <button type="button" class="btn btn-sm btn-outline-secondary-600" data-bs-dismiss="modal"><?= t('CANCEL') ?></button>
    <button type="button" class="btn btn-sm btn-primary" data-hook="modal-confirm-confirm"><?= t('CONFIRM') ?></button>
</div>