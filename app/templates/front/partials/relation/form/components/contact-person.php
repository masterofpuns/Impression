<div class='form-group mb-2' data-hook="form-contact_person<?= isset($placeholder) && $placeholder ? '-placeholder' : ''; ?>" data-contact_person_index="">
    <?php
    if (!empty($contactPersonRelation) && !empty($contactPersonRelation->id))
    {
        ?>
        <input type="hidden" name="ContactPersons[<?= isset($contactPersonIdx) ? $contactPersonIdx : 'CP_IDX'; ?>][id]" value="<?= $contactPersonRelation->id; ?>">
        <?php
    }
    ?>

    <div class='well'>
        <?php
        if (empty($contactPersonRelation->id))
        {
            ?>
            <button type="button" class="btn btn-outline-danger" data-hook='form-contact_person-delete'><i class='bi bi-trash'></i></button>
            <?php
        }
        ?>
        <div class='hide' data-form_group='hidden-participant'>
            <input
                type='hidden'
                name="ContactPersons[<?= isset($contactPersonIdx) ? $contactPersonIdx : 'CP_IDX'; ?>][isParticipant]"
                id='form_relation-contact_person-hidden_participant'
                value="<?= !empty($contactPersonRelation) ? $contactPersonRelation->typeObject->isParticipant : ""; ?>"
                <?= !empty($relation) && $relation->type !== 'COLLECTIVE' ? 'disabled' : ''; ?>
            >
        </div>
        <div class='row hide' data-form_group='participant'>
            <div class='form-item col-12 '>
                <strong><?= t('IS_PARTICIPANT'); ?></strong>
                <div class='form-group'>
                    <div class='form-check'>
                        <label class='form-check-label' for="form_relation-contact_person-is_participant-yes">
                            <input
                                class='form-check-input'
                                type='radio'
                                name="ContactPersons[<?= isset($contactPersonIdx) ? $contactPersonIdx : 'CP_IDX'; ?>][isParticipant]"
                                id="form_relation-contact_person-is_participant-yes"
                                value="1"
                                <?= !empty($contactPersonRelation) && $contactPersonRelation->typeObject->isParticipant ? 'checked' : ''; ?>
                            >
                            <?= t('YES'); ?>
                        </label>
                    </div>
                    <div class='form-check'>
                        <label class='form-check-label' for='form_relation-contact_person-is_participant-no'>
                            <input
                                class='form-check-input'
                                type='radio'
                                name="ContactPersons[<?= isset($contactPersonIdx) ? $contactPersonIdx : 'CP_IDX'; ?>][isParticipant]"
                                id='form_relation-contact_person-is_participant-no'
                                value='0'
                                <?= !empty($contactPersonRelation) && !$contactPersonRelation->typeObject->isParticipant ? 'checked' : ''; ?>
                            >
                            <?= t('NO'); ?>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class='row hide' data-form_group="proxy">
            <div class='form-item col-12'>
                <strong><?= t('IS_PROXY'); ?></strong>
                <div class='form-group'>
                    <div class='form-check'>
                        <label class='form-check-label' for="form_relation-contact_person-is_proxy-yes">
                            <input
                                class='form-check-input'
                                type='radio'
                                name="ContactPersons[<?= isset($contactPersonIdx) ? $contactPersonIdx : 'CP_IDX'; ?>][isProxy]"
                                id="form_relation-contact_person-is_proxy-yes"
                                value="1"
                                <?= !empty($contactPersonRelation) && $contactPersonRelation->typeObject->isProxy ? 'checked' : ''; ?>
                                >
                                <?= t('YES'); ?>
                        </label>
                    </div>
                    <div class='form-check'>
                        <label class='form-check-label' for='form_relation-contact_person-is_proxy-no'>
                            <input
                                class='form-check-input'
                                type='radio'
                                name="ContactPersons[<?= isset($contactPersonIdx) ? $contactPersonIdx : 'CP_IDX'; ?>][isProxy]"
                                id='form_relation-contact_person-is_proxy-no'
                                value='0'
                                <?= !empty($contactPersonRelation) && !$contactPersonRelation->typeObject->isProxy ? 'checked' : ''; ?>
                            >
                            <?= t('NO'); ?>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class='hide' data-form_group='hidden-manager'>
            <input
                type='hidden'
                name="ContactPersons[<?= isset($contactPersonIdx) ? $contactPersonIdx : 'CP_IDX'; ?>][isManager]"
                id='form_relation-contact_person-hidden_manager'
                value="<?= !empty($contactPersonRelation) ? $contactPersonRelation->typeObject->isManager : 1; ?>"
                <?= !empty($relation) && $relation->type !== 'ORGANIZATION' ? 'disabled' : ''; ?>
                >
        </div>
        <div class='row hide' data-form_group='manager'>
            <div class='form-item col-12 '>
                <strong><?= t('IS_MANAGER'); ?>*</strong>
                <div class='form-group'>
                    <div class='form-check'>
                        <label class='form-check-label' for="form_relation-contact_person-is_manager-yes">
                            <input
                                class='form-check-input'
                                type='radio'
                                name="ContactPersons[<?= isset($contactPersonIdx) ? $contactPersonIdx : 'CP_IDX'; ?>][isManager]"
                                id="form_relation-contact_person-is_manager-yes"
                                data-hook="form_relation-contact_person-is_manager"
                                value="1"
                                <?= !empty($contactPersonRelation) && $contactPersonRelation->typeObject->isManager ? 'checked' : ''; ?>
                            >
                            <?= t('YES'); ?>
                        </label>
                    </div>
                    <div class='form-check'>
                        <label class='form-check-label' for='form_relation-contact_person-is_manager-no'>
                            <input
                                class='form-check-input'
                                type='radio'
                                name="ContactPersons[<?= isset($contactPersonIdx) ? $contactPersonIdx : 'CP_IDX'; ?>][isManager]"
                                id='form_relation-contact_person-is_manager-no'
                                data-hook="form_relation-contact_person-is_manager"
                                value='0'
                                <?= !empty($contactPersonRelation) && !$contactPersonRelation->typeObject->isManager ? 'checked' : ''; ?>
                            >
                            <?= t('NO'); ?>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class='hide' data-form_group='hidden-correspondence'>
            <input
                type='hidden'
                name="ContactPersons[<?= isset($contactPersonIdx) ? $contactPersonIdx : 'CP_IDX'; ?>][receivesCorrespondence]"
                id='form_relation-contact_person-hidden_correspondence'
                value="<?= !empty($contactPersonRelation) ? $contactPersonRelation->typeObject->receivesCorrespondence : 1; ?>"
                >
        </div>
        <div class='row' data-form_group="correspondence">
            <div class='form-item col-12'>
                <strong><?= t('RECEIVES_CORRESPONDENCE'); ?></strong>
                <div class='form-group'>
                    <div class='form-check'>
                        <label class='form-check-label' for="form_relation-contact_person-receives_correspondence-yes">
                            <input
                                class='form-check-input'
                                type='radio'
                                name="ContactPersons[<?= isset($contactPersonIdx) ? $contactPersonIdx : 'CP_IDX'; ?>][receivesCorrespondence]"
                                id="form_relation-contact_person-receives_correspondence-yes"
                                value="1"
                                <?= !empty($contactPersonRelation) && $contactPersonRelation->typeObject->receivesCorrespondence ? 'checked' : ''; ?>
                            >
                            <?= t('YES'); ?>
                        </label>
                    </div>
                    <div class='form-check'>
                        <label class='form-check-label' for='form_relation-contact_person-receives_correspondence-no'>
                            <input
                                class='form-check-input'
                                type='radio'
                                name="ContactPersons[<?= isset($contactPersonIdx) ? $contactPersonIdx : 'CP_IDX'; ?>][receivesCorrespondence]"
                                id='form_relation-contact_person-receives_correspondence-no'
                                value='0'
                                <?= !empty($contactPersonRelation) && !$contactPersonRelation->typeObject->receivesCorrespondence ? 'checked' : ''; ?>
                            >
                            <?= t('NO'); ?>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class='row'>
            <div class='form-item col-12'>
                <label for='form_relation-contact_person-salutation' class='form-label'><?= t('SALUTATION'); ?>*</label>
                <select
                    name="ContactPersons[<?= isset($contactPersonIdx) ? $contactPersonIdx : 'CP_IDX'; ?>][salutationId]"
                    id="form_relation-contact_person-salutation"
                    class="form-select">
                    <option value="" hidden>Selecteer</option>
                    <?php
                    if (!empty($app->salutations)) {
                        foreach ($app->salutations as $salutation) {
                            ?>
                            <option
                                value="<?= $salutation->id; ?>"
                                <?= !empty($contactPersonRelation) && !empty($contactPersonRelation->typeObject) && !empty($contactPersonRelation->typeObject->salutationId) && $contactPersonRelation->typeObject->salutationId == $salutation->id ? 'selected' : ''; ?>
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
                <label for="form_relation-contact_person-title_before" class="form-label"><?= t('TITLE_BEFORE'); ?></label>
                <input
                    type="text"
                    name="ContactPersons[<?= isset($contactPersonIdx) ? $contactPersonIdx : 'CP_IDX'; ?>][titleBefore]"
                    id="form_relation-contact_person-title_before"
                    class='form-control'
                    value="<?= !empty($contactPersonRelation) && !empty($contactPersonRelation->typeObject) && !empty($contactPersonRelation->typeObject->titleBefore) ? $contactPersonRelation->typeObject->titleBefore : ''; ?>"
                >
            </div>
        </div>
        <div class="row">
            <div class="form-item col-12">
                <label for="form_relation-contact_person-initials" class='form-label'><?= t('INITIALS'); ?>*</label>
                <input
                    type="text"
                    name="ContactPersons[<?= isset($contactPersonIdx) ? $contactPersonIdx : 'CP_IDX'; ?>][initials]"
                    id="form_relation-contact_person-initials"
                    class='form-control'
                    value="<?= !empty($contactPersonRelation) && !empty($contactPersonRelation->typeObject) && !empty($contactPersonRelation->typeObject->initials) ? $contactPersonRelation->typeObject->initials : ''; ?>"
                >
            </div>
        </div>
        <div class="row">
            <div class="form-item col-12">
                <label for="form_relation-contact_person-last_name_prefix" class='form-label'><?= t(
                        'LAST_NAME_PREFIX'
                    ); ?></label>
                <input
                    type="text"
                    name="ContactPersons[<?= isset($contactPersonIdx) ? $contactPersonIdx : 'CP_IDX'; ?>][lastNamePrefix]"
                    id="form_relation-contact_person-last_name_prefix"
                    class='form-control'
                    value="<?= !empty($contactPersonRelation) && !empty($contactPersonRelation->typeObject) && !empty($contactPersonRelation->typeObject->lastNamePrefix) ? $contactPersonRelation->typeObject->lastNamePrefix : ''; ?>"
                >
            </div>
        </div>
        <div class="row">
            <div class="form-item col-12">
                <label for="form_relation-contact_person-last_name" class='form-label'><?= t('LAST_NAME'); ?>*</label>
                <input
                    type="text"
                    name="ContactPersons[<?= isset($contactPersonIdx) ? $contactPersonIdx : 'CP_IDX'; ?>][lastName]"
                    id="form_relation-contact_person-last_name"
                    class='form-control'
                    value="<?= !empty($contactPersonRelation) && !empty($contactPersonRelation->typeObject) && !empty($contactPersonRelation->typeObject->lastName) ? $contactPersonRelation->typeObject->lastName : ''; ?>"
                >
            </div>
        </div>
        <div class="row">
            <div class="form-item col-12">
                <label for="form_relation-contact_person-title_after" class='form-label'><?= t(
                        'TITLE_AFTER'
                    ); ?></label>
                <input
                    type="text"
                    name="ContactPersons[<?= isset($contactPersonIdx) ? $contactPersonIdx : 'CP_IDX'; ?>][titleAfter]"
                    id="form_relation-contact_person-title_after"
                    class='form-control'
                    value="<?= !empty($contactPersonRelation) && !empty($contactPersonRelation->typeObject) && !empty($contactPersonRelation->typeObject->titleAfter) ? $contactPersonRelation->typeObject->titleAfter : ''; ?>"
                >
            </div>
        </div>
        <div class="row">
            <div class='form-item col-12'>
                <label for='form_relation-first_name' class="form-label"><?= t('FIRST_NAMES'); ?></label>
                <input
                    type="text"
                    name="ContactPersons[<?= isset($contactPersonIdx) ? $contactPersonIdx : 'CP_IDX'; ?>][firstName]"
                    id="form_relation-contact_person-first_name"
                    class="form-control"
                    value="<?= !empty($contactPersonRelation) && !empty($contactPersonRelation->typeObject) && !empty($contactPersonRelation->typeObject->lastName) ? $contactPersonRelation->typeObject->firstName : ''; ?>"
                >
            </div>
        </div>
        <p class="m-0"> * Verplicht</p>
    </div>

    <?php
    $app->renderPartial(
        'relation/form/components/phonenumbers',
        [
            'relation' => (!empty($contactPersonRelation) ? $contactPersonRelation : null),
            'prefix' => 'ContactPersons[' .  (isset($contactPersonIdx) ? $contactPersonIdx : 'CP_IDX') . ']'
        ]
    );
    $app->renderPartial(
        'relation/form/components/email-addresses',
        [
            'relation' => (!empty($contactPersonRelation) ? $contactPersonRelation : null),
            'prefix' => 'ContactPersons[' .  (isset($contactPersonIdx) ? $contactPersonIdx : 'CP_IDX') . ']'
        ]
    );
    ?>

    <div class='well'>
        <h5><?= t('IDENTIFICATION_DATA'); ?></h5>

        <div class='form-group'>
            <div class='form-item'>
                <label for="form_relation-identification-file" class="form-label"><?= t('ID_FILE'); ?></label>
                <input type='file'
                       name='ContactPersons[<?= isset($contactPersonIdx) ? $contactPersonIdx : 'CP_IDX'; ?>][idFile]'
                       id="form_relation-identification-file" class='form-control'>
            </div>
            <div class='form-item'>
                <label for='form_relation-identification-type' class='form-label'><?= t('ID_TYPE'); ?></label>
                <select name='ContactPersons[<?= isset($contactPersonIdx) ? $contactPersonIdx : 'CP_IDX'; ?>][idType]'
                        id='form_relation-identification-type' class='form-select'>
                    <option value='' hidden>Selecteer</option>
                    <option <?= !empty($contactPersonRelation) && !empty($contactPersonRelation->typeObject) && !empty($contactPersonRelation->typeObject->idType) && $contactPersonRelation->typeObject->idType == 'PASSPORT' ? 'selected' : ''; ?>
                        value='PASSPORT'><?= t('PASSPORT'); ?></option>
                    <option <?= !empty($contactPersonRelation) && !empty($contactPersonRelation->typeObject) && !empty($contactPersonRelation->typeObject->idType) && $contactPersonRelation->typeObject->idType == 'IDENTITY_CARD' ? 'selected' : ''; ?>
                        value='IDENTITY_CARD'><?= t('IDENTITY_CARD'); ?></option>
                    <option <?= !empty($contactPersonRelation) && !empty($contactPersonRelation->typeObject) && !empty($contactPersonRelation->typeObject->idType) && $contactPersonRelation->typeObject->idType == 'DRIVERS_LICENSE' ? 'selected' : ''; ?>
                        value='DRIVERS_LICENSE'><?= t('DRIVERS_LICENSE'); ?></option>
                </select>
            </div>
            <div class='form-item'>
                <label for='form_relation-identification-number' class='form-label'><?= t('ID_NUMBER'); ?></label>
                <input
                    type='text'
                    name='ContactPersons[<?= isset($contactPersonIdx) ? $contactPersonIdx : 'CP_IDX'; ?>][idNumber]'
                    id="form_relation-identification-number"
                    class='form-control'
                    value="<?= !empty($contactPersonRelation) && !empty($contactPersonRelation->typeObject) && !empty($contactPersonRelation->typeObject->idNumber) ? $contactPersonRelation->typeObject->idNumber : ''; ?>"
                >
            </div>
            <div class='form-item'>
                <label for='form_relation-identification-date_expiration' class='form-label'><?= t(
                        'ID_DATE_EXPIRATION'
                    ); ?></label>
                <input
                    type='text'
                    name='ContactPersons[<?= isset($contactPersonIdx) ? $contactPersonIdx : 'CP_IDX'; ?>][idDateExpiration]'
                    id="form_relation-identification-date_expiration"
                    class='form-control datepicker'
                    value="<?= !empty($contactPersonRelation) && !empty($contactPersonRelation->typeObject) && !empty($contactPersonRelation->typeObject->idDateExpiration) ? $contactPersonRelation->typeObject->idDateExpiration : ''; ?>"
                >
            </div>
            <div class='form-item'>
                <label for='form_relation-identification-nationality' class='form-label'><?= t(
                        'NATIONALITY'
                    ); ?></label>
                <input
                    type='text'
                    name='ContactPersons[<?= isset($contactPersonIdx) ? $contactPersonIdx : 'CP_IDX'; ?>][nationality]'
                    id="form_relation-identification-nationality"
                    class='form-control'
                    value="<?= !empty($contactPersonRelation) && !empty($contactPersonRelation->typeObject) && !empty($contactPersonRelation->typeObject->nationality) ? $contactPersonRelation->typeObject->nationality : ''; ?>"
                >
            </div>
            <div class='form-item'>
                <label for='form_relation-identification-place_of_birth' class='form-label'><?= t(
                        'PLACE_OF_BIRTH'
                    ); ?></label>
                <input
                    type='text'
                    name='ContactPersons[<?= isset($contactPersonIdx) ? $contactPersonIdx : 'CP_IDX'; ?>][birthPlace]'
                    id="form_relation-identification-place_of_birth"
                    class='form-control'
                    value="<?= !empty($contactPersonRelation) && !empty($contactPersonRelation->typeObject) && !empty($contactPersonRelation->typeObject->birthPlace) ? $contactPersonRelation->typeObject->birthPlace : ''; ?>"
                >
            </div>
            <div class='form-item'>
                <label for='form_relation-identification-birthdate' class='form-label'><?= t('BIRTH_DATE'); ?></label>
                <input
                    type='text'
                    name='ContactPersons[<?= isset($contactPersonIdx) ? $contactPersonIdx : 'CP_IDX'; ?>][birthDate]'
                    id="form_relation-identification-birthdate"
                    class='form-control datepicker'
                    value="<?= !empty($contactPersonRelation) && !empty($contactPersonRelation->typeObject) && !empty($contactPersonRelation->typeObject->birthDate) ? $contactPersonRelation->typeObject->birthDate : ''; ?>"
                >
            </div>
        </div>
    </div>
</div>