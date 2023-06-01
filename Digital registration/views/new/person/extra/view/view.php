<div class="container main-content home text-center digital-registration-new">
    <div class="digital-registration-container">
        <?
            $app->renderPartial('registration/registration-steps', ['percentageComplete' => '10%', 'activeStep' => 'identification', 'fund' => $fund]);
        ?>
        <div class="digital-registration-formfields text-left">

            <h2>Extra persoon toevoegen</h2>
            <hr class="margin-t-small" />
            <form data-hook="registration-form" method="POST" enctype="multipart/form-data">
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
                    <div class="form-group col-sm-9"></div>
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
                    <div class="form-group col-sm-12">
                        <label data-hook="registration-contact_person-extra-address-checkbox" class="custom-checkbox" for="Registration[ContactPerson][<?= $contactPersonIdx; ?>][addressEqualsMainRelationAddress]">
                            <span class="custom-checkbox-indicator"></span>
                            <span class="custom-checkbox-text">
                                <?
                                $label = "Adres gelijk aan hoofdrelatie";
                                if (isset($registration['ContactPerson'][0])) {
                                    $label = 'Adres gelijk aan '.strtolower($registration['ContactPerson'][0]['salutation']);
                                    $label .= ' ';
                                    $label .= $registration['ContactPerson'][0]['initials'];
                                    if (!empty($registration['ContactPerson'][0]['lastNamePrefix'])) {
	                                    $label .= ' ';
	                                    $label .= $registration['ContactPerson'][0]['lastNamePrefix'];
                                    }
	                                $label .= ' ';
                                    $label .= $registration['ContactPerson'][0]['lastName'];
                                }
                                
                                ?>
                                <?= $label; ?>
                            </span>
                        </label>
                        <input <?= !empty($registration['ContactPerson'][$contactPersonIdx]) && isset($registration['ContactPerson'][$contactPersonIdx]['addressEqualsMainRelationAddress']) && !$registration['ContactPerson'][$contactPersonIdx]['addressEqualsMainRelationAddress'] ? '' : 'checked' ?> data-hook="registration-contact_person-extra-address-hidden" type="checkbox" name="Registration[ContactPerson][<?= $contactPersonIdx; ?>][addressEqualsMainRelationAddress]" value="<?= isset($registration['ContactPerson'][$contactPersonIdx]['addressEqualsMainRelationAddress']) ? $registration['ContactPerson'][$contactPersonIdx]['addressEqualsMainRelationAddress'] : 1 ?>">
                    </div>
                </div>
                
                <div data-hook="registration-contact_person-extra-address-container" class="<?= isset($registration['ContactPerson'][$contactPersonIdx]['addressEqualsMainRelationAddress']) && !($registration['ContactPerson'][$contactPersonIdx]['addressEqualsMainRelationAddress']) ? '' : 'hide' ?>">
                    <div class="row small-inner-margins">
                        <div class="form-group col-sm-6">
                            <label for="Registration[ContactPerson][<?= $contactPersonIdx; ?>][street]">Straat:</label>
                            <input type="text" name="Registration[ContactPerson][<?= $contactPersonIdx; ?>][street]" class="form-control" required value="<?= isset($registration['ContactPerson'][$contactPersonIdx]['street']) ? $registration['ContactPerson'][$contactPersonIdx]['street'] : '' ?>">
                        </div>
                        <div class="form-group col-sm-3">
                            <label for="Registration[ContactPerson][<?= $contactPersonIdx; ?>][number]">Huisnummer:</label>
                            <input type="text" name="Registration[ContactPerson][<?= $contactPersonIdx; ?>][number]" class="form-control" required value="<?= isset($registration['ContactPerson'][$contactPersonIdx]['number']) ? $registration['ContactPerson'][$contactPersonIdx]['number'] : '' ?>">
                        </div>
                        <div class="form-group col-sm-3">
                            <label for="Registration[ContactPerson][<?= $contactPersonIdx; ?>][numberSuffix]">Toevoeging:</label>
                            <input data-hook="registration-contact_person-number_suffix" type="text" name="Registration[ContactPerson][<?= $contactPersonIdx; ?>][numberSuffix]"class="form-control" value="<?= isset($registration['ContactPerson'][$contactPersonIdx]['numberSuffix']) ? $registration['ContactPerson'][$contactPersonIdx]['numberSuffix'] : '' ?>">
                        </div>
                    </div>
                    <div class="row small-inner-margins">
                        <div class="form-group col-sm-3">
                            <label for="Registration[ContactPerson][<?= $contactPersonIdx; ?>][postalCode]">Postcode:</label>
                            <input type="text" name="Registration[ContactPerson][<?= $contactPersonIdx; ?>][postalCode]" class="form-control" required value="<?= isset($registration['ContactPerson'][$contactPersonIdx]['postalCode']) ? $registration['ContactPerson'][$contactPersonIdx]['postalCode'] : '' ?>">
                        </div>
                        <div class="form-group col-sm-5">
                            <label for="Registration[ContactPerson][<?= $contactPersonIdx; ?>][city]">Plaats:</label>
                            <input type="text" name="Registration[ContactPerson][<?= $contactPersonIdx; ?>][city]" class="form-control" required value="<?= isset($registration['ContactPerson'][$contactPersonIdx]['city']) ? $registration['ContactPerson'][$contactPersonIdx]['city'] : '' ?>">
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="Registration[ContactPerson][<?= $contactPersonIdx; ?>][country]">Land:</label>
                            <span class="dropdown-styling">
                                <select name="Registration[ContactPerson][<?= $contactPersonIdx; ?>][country]" class="form-control" required>
                                    <option value="" hidden>Selecteer...</option>
                                    <? foreach ($app->getCountries() as  $country): if (empty($country->nationality)) { continue; } ?>
                                    <option
                                        value="<?=$country->name;?>"
                                        <?= isset($registration['ContactPerson'][$contactPersonIdx]['country']) && $registration['ContactPerson'][$contactPersonIdx]['country'] == $country->name ? 'selected' : ''; ?>
                                    >
                                        <?=$country->name;?>
                                    </option>
                                    <? endforeach; ?>
                                </select>
                                <img class="hig-select" src="/app/templates/front/assets/images/icons/dropdown.svg">
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="row small-inner-margins phone-numbers">
                    <div class="col-xs-12" data-hook="phonenumber-item">
                        <div class="row">
					        <? if (!isset($registration['ContactPerson'][$contactPersonIdx]['phoneNumbers'])): ?>
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
					        <? else: ?>
					        <? foreach ($registration['ContactPerson'][$contactPersonIdx]['phoneNumbers'] as $key => $phoneNumber): ?>
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
                            <div class="form-group col-xs-12 col-sm-8">
                                <label for="Registration[ContactPerson][<?= $contactPersonIdx ?>][phoneNumbers][<?=$key?>][number]">Nummer:</label>
                                <input type="text" name="Registration[ContactPerson][<?= $contactPersonIdx ?>][phoneNumbers][<?=$key?>][number]" class="form-control" required value="<?=$phoneNumber['number'];?>">
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
	            <? $app->renderPartial('registration/additional-questions', ["registration" => $registration, "fund" => $fund, "contactPersonIdx" => $contactPersonIdx]); ?>
            </form>
            <hr class="margin-t-small" />
            <? $app->renderPartial('registration/navigation-buttons', ['prevUrl' => $prevUrl, 'nextUrl' => $nextUrl]); ?>
        </div>
    </div>
</div>