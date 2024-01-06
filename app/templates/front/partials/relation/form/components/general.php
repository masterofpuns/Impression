<div class='section well' data-section='general'>
    <h5><?= t('GENERAL_DATA'); ?></h5>

    <div class='form-group'>
        <div class='form-item'>
            <label for="form_relation-general-emission_cost" class="form-label"><?= t('EMISSION_COST'); ?></label>
            <div class='input-group mb-3'>
                <input
                    type='text'
                    name='Relation[General][emissionPercentage]'
                    id='form_relation-general-emission_cost'
                    class='form-control'
                    value="<?= !empty($relation) && !empty($relation->typeObject) && !empty($relation->typeObject->emissionPercentage) ? $relation->typeObject->emissionPercentage : ''; ?>"
                    >
                <span class='input-group-text'>%</span>
            </div>
        </div>
        <div class='form-item'>
            <label for='form_relation-general-advisor' class='form-label'><?= t('ADVISOR'); ?></label>
            <select name='Relation[General][advisorRelationId]' id='form_relation-general-advisor' class='form-select'>
                <option value='' hidden>Selecteer</option>
                <?php foreach ($app->getAdvisors() as $advisorRelation) { ?>
                    <option <?= !empty($relation) && !empty($relation->id) && !empty($relation->getAdvisor($advisorRelation->id)) ? 'selected' : ''; ?> value='<?= $advisorRelation->id; ?>'><?= $advisorRelation->typeObject->getName(); ?></option>
                <?php } ?>
            </select>
        </div>
        <div class='form-group'>
            <label class="form-label"><?= t('CORRESPONDENCE_TYPE'); ?>*</label>
            <div class='form-item mb-0'>
                <label for='form_relation-general-correspondence_type-email' class="form-label">
                    <input
                        type='radio'
                        name='Relation[General][correspondenceType]'
                        id='form_relation-general-correspondence_type-email'
                        value="DIGITAL"
                        class="form-check-input"
                            <?= !empty($relation) && !empty($relation->typeObject) && !empty($relation->typeObject->correspondenceType) && $relation->typeObject->correspondenceType == 'DIGITAL' ? 'checked' : ''; ?>
                        > <?= t('EMAIL' ); ?>
                </label>
            </div>
            <div class='form-item mb-0'>
                <label for='form_relation-general-correspondence_type-mail' class='form-label'>
                    <input
                        type='radio'
                        name='Relation[General][correspondenceType]'
                        id='form_relation-general-correspondence_type-mail'
                        value="MAIL"
                        class="form-check-input"
                            <?= !empty($relation) && !empty($relation->typeObject) && !empty($relation->typeObject->correspondenceType) && $relation->typeObject->correspondenceType == 'MAIL' ? 'checked' : ''; ?>
                        > <?= t('MAIL'); ?>
                </label>
            </div>
        </div>
        <div class='form-group'>
            <label class='form-label'><?= t('LANGUAGE'); ?>*</label>
            <div class='form-item mb-0'>
                <label for='form_relation-general-language-nl' class="form-label">
                    <input
                        type='radio'
                        name='Relation[General][language]'
                        id='form_relation-general-language-nl'
                        value="NL"
                        class="form-check-input"
                            <?= !empty($relation) && !empty($relation->typeObject) && !empty($relation->typeObject->language) && $relation->typeObject->language == 'NL' ? 'checked' : ''; ?>
                        > <?= t('DUTCH'); ?>
                </label>
            </div>
            <div class='form-item'>
                <label for='form_relation-general-language-en' class='form-label'>
                    <input
                        type='radio'
                        name='Relation[General][language]'
                        id='form_relation-general-language-en'
                        value='EN'
                        class="form-check-input"
                            <?= !empty($relation) && !empty($relation->typeObject) && !empty($relation->typeObject->language) && $relation->typeObject->language == 'EN' ? 'checked' : ''; ?>
                        > <?= t('ENGLISH'); ?>
                </label>
            </div>
        </div>
        <div class='form-group'>
            <label class='form-label'><?= t('TAX_TYPE'); ?></label>
            <div class='form-item mb-0'>
                <label for='form_relation-general-tax_type-income' class="form-label">
                    <input
                        type='radio'
                        name='Relation[General][taxType]'
                        id='form_relation-general-tax_type-income'
                        value="INCOME_TAX"
                        class="form-check-input"
                            <?= !empty($relation) && !empty($relation->typeObject) && !empty($relation->typeObject->taxType) && $relation->typeObject->taxType == 'INCOME_TAX' ? 'checked' : ''; ?>
                        > <?= t('INCOME_TAX'); ?>
                </label>
            </div>
            <div class='form-item mb-0'>
                <label for='form_relation-general-tax_type-company' class='form-label'>
                    <input
                        type='radio'
                        name='Relation[General][taxType]'
                        id='form_relation-general-tax_type-company'
                        value='COMPANY_TAX'
                        class="form-check-input"
                            <?= !empty($relation) && !empty($relation->typeObject) && !empty($relation->typeObject->taxType) && $relation->typeObject->taxType == 'COMPANY_TAX' ? 'checked' : ''; ?>
                        > <?= t('COMPANY_TAX'); ?>
                </label>
            </div>
        </div>
    </div>
</div>