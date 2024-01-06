<div class="modal" id="modal-registration_add_edit">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-3 rounded">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title" data-hook="modal-registration-title" data-translation-add="<?=t('REGISTRATION_ADD')?>" data-translation-edit="<?=t('REGISTRATION_EDIT')?>"><?=t('REGISTRATION_ADD')?></h5>
                <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-circle"></i></button>
            </div>
            <div class="modal-body pb-0">
                <form enctype="multipart/form-data" data-hook="form-registration_add_edit">
                    <?php // FUND ?>
                    <div class='form-item mb-2'>
                        <label for='form-registration_add_edit-fund_name' class='form-label'><?= t('FUND'); ?></label>
                        <span class="form-control-static" data-hook="form-registration_add_edit-fund_name"></span>
                        <input
                            type='hidden'
                            name='Registration[fundId]'
                            id="form-registration_add_edit-fund_id"
                            class='form-control'
                            data-hook="form-registration_add_edit-fund_id"
                        >
                    </div>

                    <?php // RELATION ?>
                    <div class='form-item mb-2'>
                        <label for='form-registration_add_edit-relation_name' class='form-label'><?= t('RELATION'); ?></label>
                        <input
                            type='text'
                            name='Registration[relationName]'
                            id="form-registration_add_edit-relation_name"
                            class='form-control'
                            data-hook="form-registration_add_edit-relation_name"
                        >
                        <input
                            type='hidden'
                            name='Registration[relationId]'
                            id="form-registration_add_edit-relation_id"
                            class='form-control'
                            data-hook="form-registration_add_edit-relation_id"
                        >
                        <ul class="list-group" data-hook="form-registration_add_edit-autocomplete_container"></ul>
                    </div>

                    <?php // BOND AMOUNT ?>
                    <div class='form-item mb-2'>
                        <label for='form-registration_add_edit-bond_amount' class='form-label'><?= t('BOND_AMOUNT'); ?></label>
                        <div class="input-group">
                            <span class="input-group-text">-</span>
                            <input
                                type='text'
                                name='Registration[bondAmount]'
                                id="form-registration_add_edit-bond_amount"
                                class='form-control'
                                data-hook="form-registration_add_edit-bond_amount"
                                >
                            <span class="input-group-text">+</span>
                        </div>
                    </div>

                    <?php // INTEREST RATE ?>
                    <div class='form-item mb-2'>
                        <label for='form-registration_add_edit-interest_rate' class='form-label'><?= t('INTEREST_RATE_PROSPECTUS'); ?></label>
                        <div class="input-group">
                            <input
                                type='number'
                                min="1"
                                name='Registration[interestRate]'
                                id="form-registration_add_edit-interest_rate"
                                class='form-control'
                                data-hook="form-registration_add_edit-interest_rate"
                                >
                            <span class="input-group-text">%</span>
                        </div>
                    </div>

                    <?php // BANK ACCOUNT ?>
                    <div class='form-item mb-2'>
                        <label for='form-registration_add_edit-bank_account' class='form-label'><?= t('BANK_ACCOUNT'); ?></label>
                        <select
                            name='Registration[bankAccountId]'
                            id="form-registration_add_edit-bank_account"
                            class='form-control'
                            data-hook="form-registration_add_edit-bank_account"
                            >
                            <option hidden>Selecteer...</option>
                        </select>

                        <span data-hook="form-registration_add_edit-add_bank_account_container"></span>
                    </div>

                    <?php // ADVISOR ?>
                    <div class='form-item mb-2'>
                        <label for='form-registration_add_edit-advisor_id' class='form-label'><?= t('ADVISOR'); ?></label>
                        <select
                            name='Registration[advisorId]'
                            id="form-registration_add_edit-advisor_id"
                            class='form-control'
                            data-hook="form-registration_add_edit-advisor_id"
                            >
                            <option hidden>Selecteer...</option>
                        </select>
                    </div>

                    <?php // REGISTRATION DOCUMENT ?>
                    <div class='form-item'>
                        <label for='form-registration_add_edit-registration_form' class='form-label'><?= t('REGISTRATION_FORM'); ?></label>
                        <input type='file' name='Registration[file][registrationForm]' id="form-registration_add_edit-document" class='form-control'>
                        <input type="hidden" name="Registration[registrationFormMediaId]" data-hook="form-registration_add_edit-registration_document_id" value="">
                    </div>

                    <?php // COMMENTS ?>
                    <div class='form-item mb-2'>
                        <label for='form-registration_add_edit-comments' class='form-label'><?= t('COMMENTS'); ?></label>
                        <textarea
                            name='Registration[comments]'
                            id="form-registration_add_edit-comments"
                            class='form-control'
                            data-hook="form-registration_add_edit-comments"
                            >
                        </textarea>
                    </div>

                </form>
            </div>
            <div class="modal-footer pt-3  justify-content-center border-0">
                <button type="button" class="btn btn-outline-secondary-600 btn-cancel icon-btn icon-abort px-14" data-bs-dismiss="modal"><?=t('CANCEL')?></button>
                <button type="button" class="btn btn-success px-14" data-hook="modal-registration-form-registration_add_edit-confirm"><?=t('CONFIRM')?></button>
            </div>
        </div>
    </div>
</div>