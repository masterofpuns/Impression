<div class="block">
    <h5><?= t('CONTACT_DETAILS') ?></h5>
    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-6">
            <div class="data-flex-container">
                <strong><?= t('NAME') ?></strong>
                <?= $typeData->getFullNameInclInitialsAndTitle() ?>
            </div>
            <div class="data-flex-container">
                <strong><?= t('FIRST_NAMES') ?></strong>
                <?= $typeData->firstName ?: '<span class="text-secondary-600">'.t('NO_FIRSTNAMES_PROVIDED').'</span>'; ?>
            </div>
            <div class="data-flex-container">
                <strong><?= t('MANAGER') ?></strong>
                <?php
                if (!empty($typeData->isManager) && $typeData->isManager == 1) {
                    echo t('YES');
                } else {
                    echo t('NO');
                } ?>
            </div>
        </div>
        <div class="col-md-12 col-lg-12 col-xl-6">
            <div class="data-flex-container">
                <strong><?= t('PHONE_NUMBER') ?></strong>
                <?php
                $app->renderPartial('elements/phoneNumbers', [
                    'phoneNumbers' => $relation->getPhoneNumbers(),
                ]);
                ?>
            </div>
            <div class="data-flex-container">
                <strong><?= t('EMAIL_ADDRESS') ?></strong>
                <?php
                $app->renderPartial('elements/emailAddresses', [
                    'emailAddresses' => $relation->getEmailAddresses(),
                ]);
                ?>
            </div>
            <div class="data-flex-container">
                <strong><?= t('RECEIVES_CORRESPONDENCE') ?></strong>
                <?php
                if (!empty($typeData->receivesCorrespondence) && $typeData->receivesCorrespondence == 1) {
                    echo t('YES');
                } else {
                    echo t('NO');
                } ?>
            </div>
        </div>
    </div>
</div>