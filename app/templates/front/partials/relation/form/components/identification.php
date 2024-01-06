<div class='section well' data-section='identification'>
    <h5><?= t('IDENTIFICATION_DATA'); ?></h5>

    <div class='form-group'>
        <div class='form-item'>
            <label for="form_relation-identification-file" class="form-label"><?= t('ID_FILE'); ?></label>
            <input type='file' name='Relation[idFile]' id="form_relation-identification-file" class='form-control'>
        </div>
        <div class='form-item'>
            <label for='form_relation-identification-type' class='form-label'><?= t('ID_TYPE'); ?></label>
            <select name='Relation[Identification][idType]' id='form_relation-identification-type' class='form-select'>
                <option value='' hidden>Selecteer</option>
                <option <?= !empty($relation) && !empty($relation->typeObject) && !empty($relation->typeObject->idType) && $relation->typeObject->idType == 'PASSPORT' ? 'selected' : ''; ?> value='PASSPORT'><?= t('PASSPORT'); ?></option>
                <option <?= !empty($relation) && !empty($relation->typeObject) && !empty($relation->typeObject->idType) && $relation->typeObject->idType == 'IDENTITY_CARD' ? 'selected' : ''; ?> value='IDENTITY_CARD'><?= t('IDENTITY_CARD'); ?></option>
                <option <?= !empty($relation) && !empty($relation->typeObject) && !empty($relation->typeObject->idType) && $relation->typeObject->idType == 'DRIVERS_LICENSE' ? 'selected' : ''; ?> value='DRIVERS_LICENSE'><?= t('DRIVERS_LICENSE'); ?></option>
            </select>
        </div>
        <div class='form-item'>
            <label for='form_relation-identification-number' class='form-label'><?= t('ID_NUMBER'); ?></label>
            <input
                type='text'
                name='Relation[Identification][idNumber]'
                id="form_relation-identification-number"
                class='form-control'
                value="<?= !empty($relation) && !empty($relation->typeObject) && !empty($relation->typeObject->idNumber) ? $relation->typeObject->idNumber : ''; ?>"
                >
        </div>
        <div class='form-item'>
            <label for='form_relation-identification-date_expiration' class='form-label'><?= t('ID_DATE_EXPIRATION'); ?></label>
            <input
                type='text'
                name='Relation[Identification][idDateExpiration]'
                id="form_relation-identification-date_expiration"
                class='form-control datepicker'
                value="<?= !empty($relation) && !empty($relation->typeObject) && !empty($relation->typeObject->idDateExpiration) ? $relation->typeObject->idDateExpiration : ''; ?>"
                >
        </div>
        <div class='form-item'>
            <label for='form_relation-identification-nationality' class='form-label'><?= t('NATIONALITY'); ?></label>
            <input
                type='text'
                name='Relation[Identification][nationality]'
                id="form_relation-identification-nationality"
                class='form-control'
                value="<?= !empty($relation) && !empty($relation->typeObject) && !empty($relation->typeObject->nationality) ? $relation->typeObject->nationality : ''; ?>"
                >
        </div>
        <div class='form-item'>
            <label for='form_relation-identification-place_of_birth' class='form-label'><?= t('PLACE_OF_BIRTH'); ?></label>
            <input
                type='text'
                name='Relation[Identification][birthPlace]'
                id="form_relation-identification-place_of_birth"
                class='form-control'
                value="<?= !empty($relation) && !empty($relation->typeObject) && !empty($relation->typeObject->birthPlace) ? $relation->typeObject->birthPlace : ''; ?>"
                >
        </div>
        <div class='form-item'>
            <label for='form_relation-identification-birthdate' class='form-label'><?= t('BIRTH_DATE'); ?></label>
            <input
                type='text'
                name='Relation[Identification][birthDate]'
                id="form_relation-identification-birthdate"
                class='form-control datepicker'
                placeholder="dd-mm-jjjj"
                value="<?= !empty($relation) && !empty($relation->typeObject) && !empty($relation->typeObject->birthDate) ? $relation->typeObject->birthDate : ''; ?>"
                >
        </div>
    </div>
</div>