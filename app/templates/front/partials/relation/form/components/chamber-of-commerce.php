<div class='section well' data-section="chamber-of-commerce">
    <h5>Contactgegevens</h5>

    <div class='form-group'>
        <div class='form-item'>
            <label for='form_relation-chamber_of_commerce-file' class='form-label'><?= t('CHAMBER_OF_COMMERCE_FILE'); ?></label>
            <input type='file' name='Relation[chamberOfCommerceFile]' id="form_relation-chamber_of_commerce-file" class='form-control'>
        </div>
        <div class='form-item'>
            <label for='form_relation-chamber_of_commerce-number' class='form-label'><?= t('CHAMBER_OF_COMMERCE_NUMBER'); ?></label>
            <input
                type='text'
                name='Relation[ChamberOfCommerce][chamberOfCommerceNumber]'
                id="form_relation-chamber_of_commerce-number"
                class='form-control'
                value="<?= !empty($relation) && !empty($relation->typeObject) && !empty($relation->typeObject->chamberOfCommerceNumber) ? $relation->typeObject->chamberOfCommerceNumber : ''; ?>"
            >
        </div>
    </div>
</div>