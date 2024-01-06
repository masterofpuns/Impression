<div class="block">
    <div class="flexer">
        <h5 class="mb-3"><?= t('CONTACT_DETAILS') ?></h5>
        <button type="button" class="btn btn-outline-secondary-700 border-0 blue-hover p-1"
                data-hook="view-relation-edit" data-section="contact"><i class="bi bi-pencil"></i></button>
    </div>
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
                <strong><?= t('VISITATION_ADDRESS') ?></strong>
                <?php
                if (!empty($relation->getVisitingAddress())) {
                    $app->renderPartial('elements/address', [
                        'address' => $relation->getVisitingAddress(),
                    ]);
                } else { ?>
                    <?= '<span class="text-secondary-600">'.t('NO_VISITATION_ADDRESS_PROVIDED').'</span>' ?>
                    <?php
                } ?>
            </div>
            <div class="data-flex-container">
                <strong><?= t('POSTAL_ADDRESS') ?></strong>
                <?php
                if (!$typeData->postalAddressEqualsVisitingAddress && !empty($relation->getPostalAddress())) {
                    $app->renderPartial('elements/address', [
                        'address' => $relation->getPostalAddress(),
                    ]);
                } elseif ($typeData->postalAddressEqualsVisitingAddress && !empty($relation->getVisitingAddress())) {
                    $app->renderPartial('elements/address', [
                        'address' => $relation->getVisitingAddress(),
                    ]);
                } else { ?>
                    <?= '<span class="text-secondary-600">'.t('NO_POSTAL_ADDRESS_PROVIDED').'</span>' ?>
                    <?php
                } ?>
            </div>
        </div>
        <div class="col-md-12 col-lg-12 col-xl-6">
            <div class="data-flex-container" >
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
        </div>
    </div>

</div>