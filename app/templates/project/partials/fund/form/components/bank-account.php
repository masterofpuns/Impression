<div class='section well' data-section='bank-account'>
    <h5>Bankrekening</h5>

    <?php
    if (!empty($bankAccount)) {
        ?>
        <input type='hidden' name='Fund[BankAccount][id]' value='<?= $bankAccount->id; ?>'>
        <?php
    }
    ?>

    <div class='form-group'>
        <div class='form-item mb-3'>
            <label class="form-label" for="form-bank_account-iban"><?= t('IBAN'); ?></label>
            <input
                type='text'
                name='Fund[BankAccount][iban]'
                id="form-bank_account-iban"
                class='form-control'
                value="<?= !empty($bankAccount) && !empty($bankAccount->iban) ? $bankAccount->iban : ''; ?>"
                >
        </div>
        <div class='form-item mb-3'>
            <label class="form-label" for='form-bank_account-ascription'><?= t('ASCRIPTION'); ?></label>
            <input
                type='text'
                name='Fund[BankAccount][ascription]'
                id="form-bank_account-ascription"
                class='form-control'
                value="<?= !empty($bankAccount) && !empty($bankAccount->ascription) ? $bankAccount->ascription : ''; ?>"
                >
        </div>
        <div class='form-item mb-3'>
            <label class="form-label" for='form-bank_account-bic'><?= t('BIC'); ?></label>
            <input
                type='text'
                name='Fund[BankAccount][bic]'
                id="form-bank_account-bic"
                class='form-control'
                value="<?= !empty($bankAccount) && !empty($bankAccount->bic) ? $bankAccount->bic : ''; ?>"
                >
        </div>
    </div>
</div><?php
