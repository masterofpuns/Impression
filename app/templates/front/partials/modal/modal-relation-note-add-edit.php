<div class="modal " id="modal-note-add-edit" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-3 rounded">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title" data-hook="modal-note-title" data-translation-add="<?=t('NOTE_ADD')?>" data-translation-edit="<?=t('NOTE_EDIT')?>"></h5>
                <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-circle"></i></button>
            </div>
            <div class="modal-body pb-0">
                <div class='form-item'>
<!--                    <label for='form_relation-note-description'>--><?php //= t('DESCRIPTION'); ?><!--</label>-->
                    <textarea type='text' name='Relation[Note][description]' id="form_relation-note-description" class='form-control'></textarea>
                </div>
            </div>
            <div class="modal-footer pt-3  justify-content-center border-0">
                <button type="button" class="btn btn-sm btn-outline-secondary-600 btn-cancel icon-btn icon-abort px-14" data-bs-dismiss="modal"><?=t('CANCEL')?></button>
                <button type="button" class="btn btn-sm btn-success px-14" data-hook="modal-relation-note-add-edit-confirm" data-translation-add="<?=t('ADD')?>" data-translation-edit="<?=t('EDIT')?>"></button>
            </div>
        </div>
    </div>
</div>