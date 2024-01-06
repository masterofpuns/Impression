<div class='section well' data-section='advisor'>
    <h5>Contactgegevens</h5>

    <div class='form-group'>
        <div class='row'>
            <div class='form-item col-12'>
                <label for='form_relation-individual-salutation' class='form-label'><?= t('SALUTATION'); ?></label>
                <select name="Relation[Advisor][salutationId]" id="form_relation-individual-salutation"
                        class="form-select">
                    <option value="" hidden>Selecteer</option>
                    <?php
                    if (!empty($app->salutations)) {
                        foreach ($app->salutations as $salutation) {
                            ?>
                            <option
                                value="<?= $salutation->id; ?>"
                                <?= !empty($relation) && !empty($relation->typeObject) && !empty($relation->typeObject->salutationId) && $relation->typeObject->salutationId == $salutation->id ? 'selected' : ''; ?>
                            >
                                <?= $salutation->name; ?>
                            </option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="form-item col-12">
                <label for="form_relation-individual-title_before" class="form-label"><?= t('TITLE_BEFORE'); ?></label>
                <input
                    type="text"
                    name="Relation[Advisor][titleBefore]"
                    id="form_relation-individual-title_before"
                    class='form-control'
                    value="<?= !empty($relation) && !empty($relation->typeObject) && !empty($relation->typeObject->titleBefore) ? $relation->typeObject->titleBefore : ''; ?>"
                >
            </div>
        </div>
        <div class="row">
            <div class="form-item col-12">
                <label for="form_relation-individual-initials" class='form-label'><?= t('INITIALS'); ?>*</label>
                <input
                    type="text"
                    name="Relation[Advisor][initials]"
                    id="form_relation-individual-initials"
                    class='form-control'
                    value="<?= !empty($relation) && !empty($relation->typeObject) && !empty($relation->typeObject->initials) ? $relation->typeObject->initials : ''; ?>"
                >
            </div>
        </div>
        <div class="row">
            <div class="form-item col-12">
                <label for="form_relation-individual-last_name_prefix" class='form-label'><?= t(
                        'LAST_NAME_PREFIX'
                    ); ?></label>
                <input
                    type="text"
                    name="Relation[Advisor][lastNamePrefix]"
                    id="form_relation-individual-last_name_prefix"
                    class='form-control'
                    value="<?= !empty($relation) && !empty($relation->typeObject) && !empty($relation->typeObject->lastNamePrefix) ? $relation->typeObject->lastNamePrefix : ''; ?>"
                >
            </div>
        </div>
        <div class="row">
            <div class="form-item col-12">
                <label for="form_relation-individual-last_name" class='form-label'><?= t('LAST_NAME'); ?>*</label>
                <input
                    type="text"
                    name="Relation[Advisor][lastName]"
                    id="form_relation-individual-last_name"
                    class='form-control'
                    value="<?= !empty($relation) && !empty($relation->typeObject) && !empty($relation->typeObject->lastName) ? $relation->typeObject->lastName : ''; ?>"
                >
            </div>
        </div>
        <div class="row">
            <div class="form-item col-12">
                <label for="form_relation-individual-title_after" class='form-label'><?= t('TITLE_AFTER'); ?></label>
                <input
                    type="text"
                    name="Relation[Advisor][titleAfter]"
                    id="form_relation-individual-title_after"
                    class='form-control'
                    value="<?= !empty($relation) && !empty($relation->typeObject) && !empty($relation->typeObject->titleAfter) ? $relation->typeObject->titleAfter : ''; ?>"
                >
            </div>
        </div>
        <div class="row">
            <div class='form-item col-12'>
                <label for='form_relation-first_name' class="form-label"><?= t('FIRST_NAME'); ?></label>
                <input
                    type="text"
                    name="Relation[Advisor][firstName]"
                    id="form_relation-individual-first_name"
                    class="form-control"
                    value="<?= !empty($relation) && !empty($relation->typeObject) && !empty($relation->typeObject->firstName) ? $relation->typeObject->firstName : ''; ?>"
                >
            </div>
        </div>
    </div>
</div>