<div class='section well' data-section='phonenumbers'>
    <h5><?= t('PHONE_NUMBERS'); ?></h5>

    <div data-hook="form-phone_number_container">
        <?php
        if (!empty($relation) && !empty($relation->id) && !empty($relation->getPhoneNumbers()))
        {
            $phoneNumberIdx = 0;
            foreach ($relation->getPhoneNumbers() as $phoneNumber)
            {
                $app->renderPartial(
                    'relation/form/components/phonenumber',
                    [
                        'phoneNumber' => $phoneNumber,
                        'phoneNumberIdx' => $phoneNumberIdx++,
                        'prefix' => $prefix
                    ]
                );
            }
        }
        ?>

    </div>

    <div class="input-group">
        <button type="button" class="btn btn-outline-secondary-600 btn-sm" data-hook="form-add_phone_number" data-prefix="<?= $prefix; ?>"><i class='bi bi-plus me-1'></i><?= t('ADD_PHONE_NUMBER'); ?></button>
    </div>
</div>