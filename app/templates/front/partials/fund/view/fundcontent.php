<div class="block">
    <div class="flexer">
        <h5 class="mb-3"><?= t('FUND_CONTENT') ?></h5>
        <button type='button' class='btn btn-outline-secondary-700 border-0 blue-hover p-1'
                data-hook='view-fund-edit' data-section='contact'><i class='bi bi-pencil'></i></button>
    </div>
    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-4">
            <div class="data-flex-container">
                <strong><?= t('FIRST_PAYMENT') ?></strong>
                <?= $fund->firstPayment ?>
            </div>
            <div class="data-flex-container">
                <strong><?= t('TYPE') ?></strong>
                <?= $fund->type ? t($fund->type) : '<span class="text-secondary-600">'.t('NO_TYPE_PROVIDED').'</span>'; ?>
            </div>
            <div class="data-flex-container">
                <strong><?= t('BOND_AMOUNT') ?></strong>
                <?= $fund->bondAmount ?: '<span class="text-secondary-600">'.t('NO_BOND_AMOUNT_PROVIDED').'</span>'; ?>
            </div>
            <div class="data-flex-container">
                <strong><?= t('BOND_VOLUME') ?></strong>
                <?= $fund->bondVolume ?: '<span class="text-secondary-600">'.t('NO_BOND_VOLUME_PROVIDED').'</span>'; ?>
            </div>
        </div>

        <div class="col-md-12 col-lg-12 col-xl-4">
            <div class="data-flex-container">
                <strong><?= t('BOND_VALUE') ?></strong>
                <?= $fund->bondValue ?>
            </div>
            <div class="data-flex-container">
                <strong><?= t('INTEREST_RATE_PROSPECTUS') ?></strong>
                <?= $fund->interestRate ? $fund->interestRate.'%' : '<span class="text-secondary-600">'.t('NO_INTEREST_RATE_PROVIDED').'</span>'; ?>
            </div>
            <div class="data-flex-container">
                <strong><?= t('DAYS_FISCAL_YEAR') ?></strong>
                <?= $fund->daysFiscalYear ?: '<span class="text-secondary-600">'.t('NO_DAYS_FISCAL_YEAR_PROVIDED').'</span>'; ?>
            </div>
            <div class="data-flex-container">
                <strong><?= t('FUND_TERM') ?></strong>
                <?= $fund->getFundTerm() ?: '<span class="text-secondary-600">'.t('NO_FUND_TERM_PROVIDED').'</span>'; ?>
            </div>
        </div>


        <div class="col-md-12 col-lg-12 col-xl-4">
            <div class="data-flex-container">
                <strong><?= t('BANKING_ACCOUNT') ?></strong>
                <?php
                if (!empty($fund->getBankAccount())) {
                    $app->renderPartial('elements/bankingAccount', [
                        'bankingAccount' => $fund->getBankAccount(),
                    ]);
                } else {
                    echo '<span class="text-secondary-600">'.t('NO_BANKING_ACCOUNT_PROVIDED').'</span>';
                } ?>
            </div>
            <div class="data-flex-container">
                <strong><?= t('CHAMBER_OF_COMMERCE_NUMBER') ?></strong>
                <?= $fund->chamberOfCommerceNumber ?: '<span class="text-secondary-600">'.t('NO_CHAMBER_OF_COMMERCE_NUMBER_PROVIDED').'</span>'; ?>
            </div>
        </div>

    </div>
</div>