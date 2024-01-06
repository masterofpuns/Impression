<?php
$postalAddressEqualsVisitingAddress = !empty($relation) && !empty($relation->typeObject) && $relation->typeObject->postalAddressEqualsVisitingAddress ? true : false;
?>
<div class='section well' data-section='postal-address'>
    <h5><?= t('POSTAL_ADDRESS'); ?></h5>

    <input type='hidden' name='Relation[PostalAddress][type]' value='POSTAL'>
    <?php
    if (!empty($postalAddress)) {
        ?>
        <input type='hidden' name='Relation[PostalAddress][id]' value='<?= $postalAddress->id; ?>'>
        <?php
    }
    ?>

    <div class='form-group'>
        <div class='form-check'>
            <label class="form-check-label" for='form_relation-postal_address-postal_address_equals_visiting_address'>
                <input
                    type='checkbox'
                    name='Relation[PostalAddress][postalAddressEqualsVisitingAddress]'
                    id='form_relation-postal_address-postal_address_equals_visiting_address'
                    data-hook="form_relation-postal_address-postal_address_equals_visiting_address"
                    class="form-check-input"
                    <?= $postalAddressEqualsVisitingAddress ? 'checked' : ''; ?>
                    >
                <?= t('POSTAL_ADDRESS_EQUALS_VISITING_ADDRESS'); ?>
            </label>
        </div>
    </div>

    <div class='form-group hide' data-hook="form-postal_address_container">
        <div class='row'>
            <div class='form-item col-12'>
                <label for='form_relation-postal_address-street' class='form-label'><?= t('STREET'); ?></label>
                <input
                    type='text'
                    name='Relation[PostalAddress][street]'
                    id="form_relation-postal_address-street"
                    class='form-control'
                    value="<?= !$postalAddressEqualsVisitingAddress && !empty($postalAddress) && !empty($postalAddress->street) ? $postalAddress->street : ''; ?>"
                    >
            </div>
        </div>
        <div class="row">
            <div class="form-item col-6">
                <label for="form_relation-postal_address-number" class='form-label'><?= t('HOUSE_NUMBER'); ?></label>
                <input
                    type="text"
                    name="Relation[PostalAddress][number]"
                    id="form_relation-postal_address-number"
                    class='form-control'
                    value="<?= !$postalAddressEqualsVisitingAddress && !empty($postalAddress) && !empty($postalAddress->number) ? $postalAddress->number : ''; ?>"
                    >
            </div>
            <div class='form-item col-6'>
                <label for='form_relation-postal_address-number_suffix' class='form-label'><?= t('HOUSE_NUMBER_SUFFIX'); ?></label>
                <input
                    type="text"
                    name="Relation[PostalAddress][numberSuffix]"
                    id="form_relation-postal_address-number_suffix"
                    class='form-control'
                    value="<?= !$postalAddressEqualsVisitingAddress && !empty($postalAddress) && !empty($postalAddress->numberSuffix) ? $postalAddress->numberSuffix : ''; ?>"
                    >
            </div>
        </div>
        <div class='row'>
            <div class='form-item col-sm-4'>
                <label for='form_relation-postal_address-postal_code' class="form-label"><?= t('POSTAL_CODE'); ?></label>
                <input
                    type='text'
                    name='Relation[PostalAddress][postalCode]'
                    id="form_relation-postal_address-postal_code"
                    class='form-control'
                    value="<?= !$postalAddressEqualsVisitingAddress && !empty($postalAddress) && !empty($postalAddress->postalCode) ? $postalAddress->postalCode : ''; ?>"
                    >
            </div>
            <div class="form-item col-sm-8">
                <label for="form_relation-postal_address-city" class='form-label'><?= t('CITY'); ?></label>
                <input
                    type="text"
                    name="Relation[PostalAddress][city]"
                    id="form_relation-postal_address-city"
                    class='form-control'
                    value="<?= !$postalAddressEqualsVisitingAddress && !empty($postalAddress) && !empty($postalAddress->city) ? $postalAddress->city : ''; ?>"
                    >
            </div>
        </div>
        <div class='row'>
            <div class='form-item col-sm-12'>
                <label for='form_relation-postal_address-country' class='form-label'><?= t('COUNTRY'); ?></label>
                <select name="Relation[PostalAddress][countryId]" id="form_relation-postal_address-country"
                        class="form-select">
                    <option value="" hidden>Selecteer</option>
                    <?php
                    if (!empty($app->countries))
                    {
                        foreach ($app->countries as $country)
                        {
                            ?>
                            <option
                                value="<?= $country->id; ?>"
                                <?= !$postalAddressEqualsVisitingAddress && !empty($postalAddress) && $postalAddress->countryId == $country->id ? 'selected' : ''; ?>
                                >
                                <?= $country->name; ?>
                            </option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
</div>