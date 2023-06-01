<hr class="margin-t-small" />
<div class="row small-inner-margins">
    <div class="form-group col-sm-3">
        <label for="Registration[ContactPerson][<?= $contactPersonIdx; ?>][salutation]">Aanhef:</label>
        <span class="dropdown-styling">
            <select class="form-control" name="Registration[ContactPerson][<?= $contactPersonIdx; ?>][salutation]" required>
                <option value="" hidden>Selecteer...</option>
                <option <?= isset($registration['ContactPerson'][$contactPersonIdx]) && $registration['ContactPerson'][$contactPersonIdx]['salutation'] == 'De heer' ? 'selected' : '' ?> value="De heer">De heer</option>
                <option <?= isset($registration['ContactPerson'][$contactPersonIdx]) && $registration['ContactPerson'][$contactPersonIdx]['salutation'] == 'Mevrouw' ? 'selected' : '' ?> value="Mevrouw">Mevrouw</option>
            </select>
            <img class="hig-select" src="/app/templates/front/assets/images/icons/dropdown.svg">
        </span>
    </div>
    <div class="form-group col-sm-9 hidden-xs"></div>
</div>
<div class="row small-inner-margins">
    <div class="form-group col-sm-3">
        <label for="Registration[ContactPerson][<?= $contactPersonIdx; ?>][initials]">Voorletters:</label>
        <input type="text" name="Registration[ContactPerson][<?= $contactPersonIdx; ?>][initials]" class="form-control" required value="<?= isset($registration['ContactPerson'][$contactPersonIdx]['initials']) ? $registration['ContactPerson'][$contactPersonIdx]['initials'] : '' ?>">
    </div>
    <div class="form-group col-sm-3">
        <label for="Registration[ContactPerson][<?= $contactPersonIdx; ?>][lastNamePrefix]">Tussenvoegsel:</label>
        <input type="text" name="Registration[ContactPerson][<?= $contactPersonIdx; ?>][lastNamePrefix]" class="form-control" value="<?= isset($registration['ContactPerson'][$contactPersonIdx]['lastNamePrefix']) ? $registration['ContactPerson'][$contactPersonIdx]['lastNamePrefix'] : '' ?>">
    </div>
    <div class="form-group col-sm-6">
        <label for="Registration[ContactPerson][<?= $contactPersonIdx; ?>][lastName]">Achternaam:</label>
        <input type="text" name="Registration[ContactPerson][<?= $contactPersonIdx; ?>][lastName]"class="form-control" required value="<?= isset($registration['ContactPerson'][$contactPersonIdx]['lastName']) ? $registration['ContactPerson'][$contactPersonIdx]['lastName'] : '' ?>">
    </div>
</div>
<div class="row small-inner-margins">
    <div class="form-group col-sm-3">
        <label for="">PEP:</label> <span class="info-tooltip" data-toggle="tooltip" title="Een natuurlijk persoon die een prominente publieke functie bekleedt of bekleed heeft, alsmede directe familieleden en personen met wie zij een nauwe zakelijke relatie hebben">i</span>
        <span class="dropdown-styling">
            <select class="form-control" name="Registration[ContactPerson][<?= $contactPersonIdx; ?>][isPep]" required>
                <option value="">Selecteer...</option>
                <option value="0" <?= isset($registration['ContactPerson'][$contactPersonIdx]['isPep']) && $registration['ContactPerson'][$contactPersonIdx]['isPep'] == 0 ? 'selected' : ''; ?>>Nee</option>
                <option value="1" <?= isset($registration['ContactPerson'][$contactPersonIdx]['isPep']) && $registration['ContactPerson'][$contactPersonIdx]['isPep'] == 1 ? 'selected' : ''; ?>>Ja</option>
            </select>
            <img class="hig-select" src="/app/templates/front/assets/images/icons/dropdown.svg">
        </span>
    </div>
    <div class="form-group col-sm-9 hidden-xs"></div>
</div>
<div class="row small-inner-margins">
    <div class="form-group col-sm-12">
        <label for="">Heeft u nog andere dienstbetrekkingen?</label>
        <span class="dropdown-styling fake-col-sm3">
            <select class="form-control" data-toggle="Registration[ContactPerson][<?= $contactPersonIdx; ?>][otherEmployment]" required data-hook="toggle-employment">
                <option value="" hidden>Selecteer...</option>
                <option value="0" <?= isset($registration['ContactPerson'][$contactPersonIdx]['otherEmployment']) && !is_null($registration['ContactPerson'][$contactPersonIdx]['otherEmployment']) && $registration['ContactPerson'][$contactPersonIdx]['otherEmployment'] == '' ? 'selected' : ''; ?>>Nee</option>
                <option value="1" <?= isset($registration['ContactPerson'][$contactPersonIdx]['otherEmployment']) && !is_null($registration['ContactPerson'][$contactPersonIdx]['otherEmployment']) && $registration['ContactPerson'][$contactPersonIdx]['otherEmployment'] != '' ? 'selected' : ''; ?>>Ja</option>
            </select>            
            <img class="hig-select" src="/app/templates/front/assets/images/icons/dropdown.svg">
        </span>
    </div>
</div>
<div class="row small-inner-margins <?= !empty($registration['ContactPerson'][$contactPersonIdx]['otherEmployment']) ? '' : 'hide'; ?>" data-hook="show-employment">
    <div class="form-group col-xs-12">
        <label for="">namelijk:</label>
        <input type="text" name="Registration[ContactPerson][<?= $contactPersonIdx; ?>][otherEmployment]" class="form-control" value="<?= isset($registration['ContactPerson'][$contactPersonIdx]['otherEmployment']) ? $registration['ContactPerson'][$contactPersonIdx]['otherEmployment'] : '' ?>" data-hook="input-employment">
    </div>
</div>
<? // TELEFOONNUMMERS ?>
<div class="row small-inner-margins phone-numbers">
    <div class="col-xs-12" data-hook="phonenumber-item">
        <div class="row">
            <? if (!isset($registration['ContactPerson'][$contactPersonIdx]['phoneNumbers'])): ?>
            <div class="col-xs-12" data-hook="phonenumber-item">
                <div class="row">
                    <div class="form-group col-xs-12 col-sm-4">
                        <label for="Registration[ContactPerson][<?= $contactPersonIdx ?>][phoneNumbers][0][type]">Telefoon:</label>
                        <span class="dropdown-styling">
                            <select name="Registration[ContactPerson][<?= $contactPersonIdx ?>][phoneNumbers][0][type]" class="form-control" required>
                                <option value="">Selecteer...</option>
                                <?
                                foreach ($app->getPhoneNumberTypes() as $phoneNumberType) {
                                    if($phoneNumberType == 'fax') { continue; }
                                    ?>
                                    <option value="<?=$phoneNumberType?>"><?=t(strtoupper($phoneNumberType))?></option>
                                    <?
                                }
                                ?>
                            </select>
                            <img class="hig-select" src="/app/templates/front/assets/images/icons/dropdown.svg">
                        </span>
                    </div>
                    <div class="form-group col-xs-12 col-sm-8">
                        <label for="Registration[ContactPerson][<?= $contactPersonIdx ?>][phoneNumbers][0][number]">Nummer:</label>
                        <input type="text" name="Registration[ContactPerson][<?= $contactPersonIdx ?>][phoneNumbers][0][number]" class="form-control" required>
                    </div>
                </div>
            </div>
            <? else: ?>
                <? foreach ($registration['ContactPerson'][$contactPersonIdx]['phoneNumbers'] as $key => $phoneNumber): ?>
                <div class="col-xs-12" data-hook="phonenumber-item">
                    <div class="row">
                        <div class="form-group col-xs-12 col-sm-4">
                            <label for="Registration[ContactPerson][<?= $contactPersonIdx ?>][phoneNumbers][<?=$key?>][type]">Telefoon:</label>
                            <span class="dropdown-styling">
                                <select name="Registration[ContactPerson][<?= $contactPersonIdx ?>][phoneNumbers][<?=$key?>][type]" class="form-control" required>
                                    <option value="">Selecteer...</option>
                                    <? foreach ($app->getPhoneNumberTypes() as $phoneNumberType): if($phoneNumberType == 'fax') { continue; } ?>
                                    <option <?= $phoneNumber['type'] == $phoneNumberType ? 'selected' : '' ?> value="<?=$phoneNumberType?>"><?=t(strtoupper($phoneNumberType))?></option>
                                    <? endforeach; ?>
                                </select>
                                <img class="hig-select" src="/app/templates/front/assets/images/icons/dropdown.svg">
                            </span>
                        </div>
                        <div class="form-group col-xs-10 col-sm-7">
                            <label for="Registration[ContactPerson][<?= $contactPersonIdx ?>][phoneNumbers][<?=$key?>][number]">Nummer:</label>
                            <input type="text" name="Registration[ContactPerson][<?= $contactPersonIdx ?>][phoneNumbers][<?=$key?>][number]" class="form-control" required value="<?=$phoneNumber['number'];?>">
                        </div>
                        <div class="col-xs-2 col-sm-1">
                            <? if (!empty($key)): ?>
                            <button class="clean-button" data-hook="remove-phonenumber">
                                <div class="hig-trash"></div>
                            </button>
                            <? endif; ?>
                        </div>
                    </div>
                </div>
                <? endforeach; ?>
            <? endif; ?>
        </div>
    </div>
    <div class="form-group col-xs-12">
        <button data-hook="add-phonenumber" class="btn btn-tertiary btn-icon">
            <div class="hig-add"></div>
            <span class="btn-text">Telefoonnummer toevoegen</span>
        </button>
    </div>
</div>
<? $app->renderPartial('registration/components/registration-input-email_address', ['registration' => $registration, 'contactPersonIdx' => $contactPersonIdx]); ?>
<div class="row small-inner-margins">
    <div class="form-group col-xs-12">
        <label for="Registration[ContactPerson][<?= $contactPersonIdx; ?>][nationality]">Nationaliteit:</label>
        <span class="dropdown-styling">
            <select name="Registration[ContactPerson][<?= $contactPersonIdx; ?>][nationality]" class="form-control" required data-hook="registration-nationality">
                <option value="" hidden>Selecteer...</option>
                <? foreach ($app->getCountries("nationality") as $country): if (empty($country->nationality)) { continue; } ?>
                <option
                    data-region="<?= $country->region; ?>"
                    value="<?=$country->nationality;?>"
                    <?= isset($registration['ContactPerson'][$contactPersonIdx]['nationality']) && $registration['ContactPerson'][$contactPersonIdx]['nationality'] == $country->nationality ? 'selected' : ''; ?>
                    >
                    <?=$country->nationality;?>
                </option>
                <? endforeach; ?>
            </select>
            <img class="hig-select" src="/app/templates/front/assets/images/icons/dropdown.svg">
        </span>
    </div>
</div>
<div class="row small-inner-margins">
    <div class="form-group col-xs-12 margin-b-0">
        <label for="Registration[ContactPerson][<?= $contactPersonIdx; ?>][idType]">Geldig legitimatiebewijs:</label>
    </div>
    <div class="form-group col-xs-12 col-sm-4 first-child">
        <span class="dropdown-styling">
            <select name="Registration[ContactPerson][<?= $contactPersonIdx; ?>][idType]" class="form-control" required data-hook="registration-id_type">
                <option value="" hidden>Selecteer...</option>
                <option <?= isset($registration['ContactPerson'][$contactPersonIdx]['idType']) && $registration['ContactPerson'][$contactPersonIdx]['idType'] == 'passport' ? 'selected' : '' ?> value="passport" data-availability="world">Paspoort</option>
                <option <?= isset($registration['ContactPerson'][$contactPersonIdx]['idType']) && $registration['ContactPerson'][$contactPersonIdx]['idType'] == 'identityCard' ? 'selected' : '' ?> value="identityCard" data-availability="europe">Identiteitsbewijs</option>
                <option <?= isset($registration['ContactPerson'][$contactPersonIdx]['idType']) && $registration['ContactPerson'][$contactPersonIdx]['idType'] == 'driversLicense' ? 'selected' : '' ?> value="driversLicense" data-availability="netherlands">Rijbewijs</option>
            </select>
            <img class="hig-select" src="/app/templates/front/assets/images/icons/dropdown.svg">
        </span>
    </div>
    <div class="form-group col-xs-12 col-sm-8">
        <?
        $app->renderPartial(
            'registration/components/file-upload',
            [
                'fileParams' => isset($registration['ContactPerson'][$contactPersonIdx]['idFile']) ? $registration['ContactPerson'][$contactPersonIdx]['idFile'] : null,
                'name' => 'Registration[ContactPerson]['.$contactPersonIdx.'][idFile]'
            ]
        );
        ?>
    </div>
</div>