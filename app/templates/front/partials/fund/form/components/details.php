<div class='section well' data-section='details'>
    <h5>Inhoudelijke gegevens</h5>
    <div class='form-group'>
        <div class="row">
            <div class='form-group'>
                <label class="form-label"><?= t('TYPE'); ?>*</label>
                <div class='form-item mb-0'>
                    <label for='form-details-type-real_estate' class="form-label mb-3">
                        <input
                            type='radio'
                            name='Fund[type]'
                            id='form-details-type-real_estate'
                            value="REAL_ESTATE"
                            class="form-check-input"
                            <?= !empty($fund) && !empty($fund->type) && $fund->type == 'REAL_ESTATE' ? 'checked' : ''; ?>
                        > <?= t('REAL_ESTATE' ); ?>
                    </label>
                </div>
                <div class='form-item mb-0'>
                    <label for='form-details-type-prolongation' class="form-label mb-3">
                        <input
                            type='radio'
                            name='Fund[type]'
                            id='form-details-type-prolongation'
                            value="PROLONGATION"
                            class="form-check-input"
                            <?= !empty($fund) && !empty($fund->type) && $fund->type == 'PROLONGATION' ? 'checked' : ''; ?>
                        > <?= t('PROLONGATION' ); ?>
                    </label>
                </div>
            </div>

            <div class='form-item col-12 mb-3'>
                <label for='form-details-bond_amount' class="form-label"><?= t('BOND_AMOUNT'); ?></label>
                <input
                    type="number"
                    min="0"
                    name="Fund[bondAmount]"
                    id="form-details-bond_amount"
                    class="form-control"
                    value="<?= !empty($fund) && !empty($fund->bondAmount) ? $fund->bondAmount : ''; ?>"
                >
            </div>

            <div class='form-item col-12 mb-3'>
                <label for='form-details-bond_volume' class="form-label"><?= t('BOND_VOLUME'); ?></label>
                <div class="input-group">
                    <div class="input-group-text"><i class="bi bi-currency-euro"></i></div>
                    <input
                        type="number"
                        min="0"
                        name="Fund[bondVolume]"
                        id="form-details-bond_volume"
                        class="form-control"
                        value="<?= !empty($fund) && !empty($fund->bondVolume) ? $fund->bondVolume : ''; ?>"
                        >
                </div>
            </div>

            <div class='form-item col-12 mb-3'>
                <label for='form-details-bond_value' class="form-label"><?= t('BOND_VALUE'); ?></label>
                <div class="input-group">
                    <div class="input-group-text"><i class="bi bi-currency-euro"></i></div>
                    <input
                        type="number"
                        min="0"
                        name="Fund[bondValue]"
                        id="form-details-bond_value"
                        class="form-control"
                        value="<?= !empty($fund) && !empty($fund->bondValue) ? $fund->bondValue : ''; ?>"
                    >
                </div>
            </div>

            <div class='form-item col-12 mb-3'>
                <label for='form-details-interest_rate' class="form-label"><?= t('INTEREST_RATE_PROSPECTUS'); ?></label>
                <div class="input-group">
                    <input
                        type="number"
                        min="0"
                        step=".01"
                        name="Fund[interestRate]"
                        id="form-details-interest_rate"
                        class="form-control"
                        value="<?= !empty($fund) && !empty($fund->interestRate) ? $fund->interestRate : ''; ?>"
                        >
                    <div class="input-group-text"><i class="bi bi-percent"></i></div>
                </div>
            </div>

            <div class='form-item col-12 mb-3'>
                <label for='form-details-days_fiscal_year' class="form-label"><?= t('DAYS_FISCAL_YEAR'); ?></label>
                <input
                    type="number"
                    min="0"
                    name="Fund[daysFiscalYear]"
                    id="form-details-days_fiscal_year"
                    class="form-control"
                    value="<?= !empty($fund) && !empty($fund->daysFiscalYear) ? $fund->daysFiscalYear : ''; ?>"
                    >
            </div>

            <div class='form-item col-12 mb-3'>
                <label for='form-details-fund_start_date' class="form-label"><?= t('FUND_START_DATE'); ?></label>
                <input
                    type="text"
                    name="Fund[fundStartDate]"
                    id="form-details-fund_start_date"
                    class="form-control datepicker"
                    value="<?= !empty($fund) && !empty($fund->fundStartDate) ? $fund->fundStartDate : ''; ?>"
                >
            </div>

            <div class='form-item col-12 mb-3'>
                <label for='form-details-fund_end_date' class="form-label"><?= t('FUND_END_DATE'); ?></label>
                <input
                    type="text"
                    name="Fund[fundEndDate]"
                    id="form-details-fund_end_date"
                    class="form-control datepicker"
                    value="<?= !empty($fund) && !empty($fund->fundEndDate) ? $fund->fundEndDate : ''; ?>"
                >
            </div>

            <div class='form-item col-12 mb-3'>
                <label for='form-details-chamber_of_commerce_number' class="form-label"><?= t('CHAMBER_OF_COMMERCE_NUMBER'); ?></label>
                <input
                    type="text"
                    name="Fund[chamberOfCommerceNumber]"
                    id="form-details-chamber_of_commerce_number"
                    class="form-control"
                    value="<?= !empty($fund) && !empty($fund->chamberOfCommerceNumber) ? $fund->chamberOfCommerceNumber : ''; ?>"
                >
            </div>
        </div>
    </div>
</div>