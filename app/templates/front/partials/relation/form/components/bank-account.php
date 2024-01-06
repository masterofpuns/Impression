<div class='section well' data-section='bank-account'>
    <h5><?= t('BANK_ACCOUNT'); ?></h5>

    <?php
    if (!empty($bankAccount)) {
        ?>
        <input type='hidden' name='Relation[BankAccount][id]' value='<?= $bankAccount->id; ?>'>
        <?php
    }
    ?>

    <div class='form-group'>
        <div class='form-item'>
            <label class="form-label" for="form_relation-bank_account-iban"><?= t('IBAN'); ?></label>
            <input
                type='text'
                name='Relation[BankAccount][iban]'
                id="form_relation-bank_account-iban"
                class='form-control'
                value="<?= !empty($bankAccount) && !empty($bankAccount->iban) ? $bankAccount->iban : ''; ?>"
                >
        </div>
        <div class='form-item'>
            <label class="form-label" for='form_relation-bank_account-ascription'><?= t('ASCRIPTION'); ?></label>
            <input
                type='text'
                name='Relation[BankAccount][ascription]'
                id="form_relation-bank_account-ascription"
                class='form-control'
                value="<?= !empty($bankAccount) && !empty($bankAccount->ascription) ? $bankAccount->ascription : ''; ?>"
                >
        </div>
        <div class='form-item'>
            <label class="form-label" for='form_relation-bank_account-bic'><?= t('BIC'); ?></label>
            <input
                type='text'
                name='Relation[BankAccount][bic]'
                id="form_relation-bank_account-bic"
                class='form-control'
                value="<?= !empty($bankAccount) && !empty($bankAccount->bic) ? $bankAccount->bic : ''; ?>"
                >
        </div>
    </div>
</div><?php
