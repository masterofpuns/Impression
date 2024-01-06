<div class="section well" data-section='email-addresses'>
    <h5><?= t("EMAIL_ADDRESSES"); ?></h5>

    <div data-hook='form-email_address_container'>
        <?php
        if (!empty($relation) && !empty($relation->id) && !empty($relation->getEmailAddresses()))
        {
            $emailAddressIdx = 0;
            foreach ($relation->getEmailAddresses() as $emailAddress)
            {
                $app->renderPartial(
                    'relation/form/components/email-address',
                    [
                        'emailAddress' => $emailAddress,
                        'emailAddressIdx' => $emailAddressIdx++,
                        'prefix' => $prefix
                    ]
                );
            }
        }
        ?>
    </div>

    <div class="input-group">
        <button type="button" class="btn btn-outline-secondary-600 btn-sm" data-hook="form-add_email_address" data-prefix="<?= $prefix; ?>"><i class='bi bi-plus me-1'></i><?= t('ADD_EMAIL_ADDRESS'); ?></button>
    </div>
</div>