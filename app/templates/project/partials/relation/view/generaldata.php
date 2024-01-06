<div class="block">
    <h5><?= t('GENERAL_DATA') ?></h5>
    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-6">
            <div class="data-flex-container">
                <strong><?= t('RELATIONNUMBER') ?></strong>
                <?= $typeData->id ?>
            </div>
            <div class="data-flex-container">
                <strong><?= t('EMISSION_COSTS') ?></strong>
                <?= $typeData->emissionPercentage ? $typeData->emissionPercentage.'%' : '<span class="text-secondary-600">'.t('NO_EMISSION_COSTS_PROVIDED').'</span>'; ?>
            </div>
            <div class="data-flex-container">
                <strong><?= t('ADVISOR') ?></strong>
                <?php
                if (!empty($relation->getAdvisors())) {
                    $advisors = $relation->getAdvisors();
                    foreach ($advisors as $advisor) { ?>
                        <?= $advisor->typeObject->getFullName() ?>
                        <?php
                    }
                } else { ?>
                    <?= '<span class="text-secondary-600">'.t('NO_ADVISOR_PROVIDED').'</span>' ?>
                    <?php
                } ?>
            </div>
            <div class="data-flex-container">
                <strong><?= t('APPROACH_VIA') ?></strong>
                <?= t($typeData->correspondenceType) . ' (' . t($typeData->language) . ')' ?>
            </div>
            <div class="data-flex-container">
                <strong><?= t('TYPE_TAX') ?></strong>
                <?= $typeData->taxType ? t($typeData->taxType) : '<span class="text-secondary-600">'.t('NO_TYPE_TAX_PROVIDED').'</span>'; ?>
            </div>
        </div>
        <div class="col-md-12 col-lg-12 col-xl-6">
            <div class="data-flex-container">
                <strong><?= t('BANKING_ACCOUNTS') ?></strong>
                <?php
                if (!empty($relation->getBankAccounts())) {
                    foreach ($relation->getBankAccounts() as $bankAccount) {
                        $app->renderPartial('elements/bankingAccount', [
                            'bankingAccount' => $bankAccount,
                        ]);
                    }
                } else {
                    echo '<span class="text-secondary-600">'.t('NO_BANKING_ACCOUNT_PROVIDED').'</span>';
                } ?>
            </div>
            <?php
            if ($relation->type == 'ORGANIZATION') { ?>
                <div class="data-flex-container">
                    <strong><?= t('CHAMBER_OF_COMMERCE_FILE') ?></strong>
                    <?php
                    if (!empty($typeData->getChamberOfCommerceFile())) { ?>
                        <div class="d-flex justify-content-start">
                        <a href="<?=$typeData->getChamberOfCommerceFile()->relativeLocation?>" download="<?=preg_replace("/\.[^.]*$|\s+/", "", $typeData->getChamberOfCommerceFile()->filename)?>"><i class="bi p-1 bi-file-earmark-arrow-down text-secondary-700 blue-hover"></i></a>
                        <a href="<?=$typeData->getChamberOfCommerceFile()->relativeLocation?>" target="_blank"><i class="bi p-1 bi-eye text-secondary-700 blue-hover"></i></a>
                        </div>
                        <?php
                    } else { ?>
                        <?= '<span class="text-secondary-600">'.t('NO_CHAMBER_OF_COMMERCE_FILE_PROVIDED').'</span>' ?>
                        <?php
                    } ?>
                </div>
                <div class="data-flex-container">
                    <strong><?= t('CHAMBER_OF_COMMERCE_NUMBER') ?></strong>
                    <?= $typeData->chamberOfCommerceNumber ?: '<span class="text-secondary-600">'.t('NO_CHAMBER_OF_COMMERCE_NUMBER_PROVIDED').'</span>'; ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>