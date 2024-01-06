<div class='section well' data-section='visiting-address'>
    <h5><?= t('VISITING_ADDRESS'); ?></h5>

    <input type='hidden' name='Relation[VisitingAddress][type]' value='VISITING'>
    <?php
    if (!empty($visitingAddress)) {
        ?>
        <input type='hidden' name='Relation[VisitingAddress][id]' value='<?= $visitingAddress->id; ?>'>
        <?php
    }
    ?>

    <div class='form-group'>
        <div class='row'>
            <div class='form-item col-12'>
                <label for='form_relation-visiting_address-street' class="form-label"><?= t('STREET'); ?><span data-hook="required">*</span></label>
                <input
                    type='text'
                    name='Relation[VisitingAddress][street]'
                    id="form_relation-visiting_address-street"
                    class='form-control'
                    value="<?= !empty($visitingAddress) && !empty($visitingAddress->street) ? $visitingAddress->street : ''; ?>"
                    >
            </div>
        </div>
        <div class="row">
            <div class="form-item col-6">
                <label for="form_relation-visiting_address-number" class='form-label'><?= t('HOUSE_NUMBER'); ?><span data-hook="required">*</span></label>
                <input
                    type="text"
                    name="Relation[VisitingAddress][number]"
                    id="form_relation-visiting_address-number"
                    class='form-control'
                    value="<?= !empty($visitingAddress) && !empty($visitingAddress->number) ? $visitingAddress->number : ''; ?>"
                    >
            </div>
            <div class='form-item col-6'>
                <label for='form_relation-visiting_address-number_suffix' class='form-label'><?= t('HOUSE_NUMBER_SUFFIX'); ?></label>
                <input
                    type="text"
                    name="Relation[VisitingAddress][numberSuffix]"
                    id="form_relation-visiting_address-number_suffix"
                    class='form-control'
                    value="<?= !empty($visitingAddress) && !empty($visitingAddress->numberSuffix) ? $visitingAddress->numberSuffix : ''; ?>"
                    >
            </div>
        </div>
        <div class='row'>
            <div class='form-item col-sm-4'>
                <label for='form_relation-visiting_address-postal_code' class="form-label"><?= t('POSTAL_CODE'); ?><span data-hook="required">*</span></label>
                <input
                    type='text'
                    name='Relation[VisitingAddress][postalCode]'
                    id="form_relation-visiting_address-postal_code"
                    class='form-control'
                    value="<?= !empty($visitingAddress) && !empty($visitingAddress->postalCode) ? $visitingAddress->postalCode : ''; ?>"
                    >
            </div>
            <div class="form-item col-sm-8">
                <label for="form_relation-visiting_address-city" class='form-label'><?= t('CITY'); ?><span data-hook="required">*</span></label>
                <input
                    type="text"
                    name="Relation[VisitingAddress][city]"
                    id="form_relation-visiting_address-city"
                    class='form-control'
                    value="<?= !empty($visitingAddress) && !empty($visitingAddress->city) ? $visitingAddress->city : ''; ?>"
                    >
            </div>
        </div>
        <div class='row'>
            <div class='form-item col-12'>
                <label for='form_relation-visiting_address-country' class='form-label'><?= t('COUNTRY'); ?><span data-hook="required">*</span></label>
                <select name="Relation[VisitingAddress][countryId]" id="form_relation-visiting_address-country" class="form-select">
                    <option value="" hidden>Selecteer</option>
                    <?php
                    if (!empty($app->countries))
                    {
                        foreach ($app->countries as $country)
                        {
                            ?>
                            <option
                                value="<?= $country->id; ?>"
                                <?= !empty($visitingAddress) && !empty($visitingAddress->countryId) && $visitingAddress->countryId == $country->id ? 'selected' : ''; ?>
                                >
                                <?= $country->name; ?>
                            </option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </div>
            <span data-hook="required"><p class="m-0"> * Verplicht</p></span>
        </div>
    </div>
</div>