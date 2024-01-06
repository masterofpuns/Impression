<div class="block">
    <div class="flexer">
        <h5 class="mb-3"><?= t('CONTACT_DETAILS') ?></h5>
        <button type="button" class="btn btn-outline-secondary-700 border-0 blue-hover p-1"
                data-hook="view-relation-edit" data-section="contact"><i class="bi bi-pencil"></i></button>
    </div>
    <div class="data-flex-container">
        <strong><?= t('NAME') ?></strong>
        <?php
        $allContactPersons = $relation->getContactPersons();
        if (!empty($allContactPersons)) {
            foreach ($allContactPersons as $relationContactPersonKey => $relationContactPerson) {
                $contactPerson = new \app\perree\relation\MContactPerson($relationContactPerson->id);
                if (!empty($contactPerson->getFullNameInclInitialsAndTitle())) {
                    if (key($allContactPersons) != $relationContactPersonKey) {
                        echo ' ' . t('AND', [], false) . ' ';
                    }
                    echo $contactPerson->getFullNameInclInitialsAndTitle();
                }
            }
        } ?>
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