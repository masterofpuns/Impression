<div class='section well' data-section="organization">
    <h5>Contactgegevens</h5>

    <div class='form-group'>
        <div class='form-item'>
            <label for='form_relation-organization-first_name' class='form-label'><?= t('COMPANY_NAME'); ?>*</label>
            <input
                type="text"
                name="Relation[Organization][name]"
                id="form_relation-organization-name"
                class="form-control"
                value="<?= !empty($relation) && !empty($relation->typeObject) && !empty($relation->typeObject->name) ? $relation->typeObject->name : ''; ?>"
                >
        </div>
        <div class='form-item'>
            <label for='form_relation-organization-website' class='form-label'><?= t('WEBSITE'); ?>*</label>
            <input
                type="text"
                name="Relation[Organization][website]"
                id="form_relation-organization-website"
                class="form-control"
                value="<?= !empty($relation) && !empty($relation->typeObject) && !empty($relation->typeObject->website) ? $relation->typeObject->website : ''; ?>"
                >
        </div>
        <p class="m-0">* Verplicht</p>
    </div>
</div>