<div class="container main-content home text-center digital-registration-new">
    <div class="digital-registration-container">
        <?
            $app->renderPartial('registration/registration-steps', ['percentageComplete' => '10%', 'activeStep' => 'identification', 'fund' => $fund]);
        ?>
        <div class="digital-registration-formfields text-left">

            <h2>Graag onderstaande gegevens invullen</h2>
            <hr class="margin-t-small" />
            <form data-hook="registration-form" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="Registration[Relation][type]" class="form-control" required value="<?= isset($registration['ContactPerson']) && count($registration['ContactPerson']) > 1 ? 'collective' : 'contactPerson'; ?>">
                
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
                    <div class="form-group col-sm-6">
                        <label for="Registration[PostalAddress][street]">Straat:</label>
                        <input type="text" name="Registration[PostalAddress][street]" class="form-control" required value="<?= isset($registration['PostalAddress']['street']) ? $registration['PostalAddress']['street'] : '' ?>">
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="Registration[PostalAddress][number]">Huisnummer:</label>
                        <input type="text" name="Registration[PostalAddress][number]" class="form-control" required value="<?= isset($registration['PostalAddress']['number']) ? $registration['PostalAddress']['number'] : '' ?>">
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="Registration[PostalAddress][numberSuffix]">Toevoeging:</label>
                        <input type="text" name="Registration[PostalAddress][numberSuffix]"class="form-control" value="<?= isset($registration['PostalAddress']['numberSuffix']) ? $registration['PostalAddress']['numberSuffix'] : '' ?>">
                    </div>
                </div>
                <div class="row small-inner-margins">
                    <div class="form-group col-sm-3">
                        <label for="Registration[PostalAddress][postalCode]">Postcode:</label>
                        <input type="text" name="Registration[PostalAddress][postalCode]" class="form-control" required value="<?= isset($registration['PostalAddress']['postalCode']) ? $registration['PostalAddress']['postalCode'] : '' ?>">
                    </div>
                    <div class="form-group col-sm-5">
                        <label for="Registration[PostalAddress][city]">Plaats:</label>
                        <input type="text" name="Registration[PostalAddress][city]" class="form-control" required value="<?= isset($registration['PostalAddress']['city']) ? $registration['PostalAddress']['city'] : '' ?>">
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="Registration[PostalAddress][country]">Land:</label>
                        <span class="dropdown-styling">
                            <select name="Registration[PostalAddress][country]" class="form-control" required>
                                <option value="" hidden>Selecteer...</option>
                                <? foreach ($app->getCountries() as  $country): if (empty($country->nationality)) { continue; } ?>
                                <option
                                    value="<?=$country->name;?>"
	                                <?= isset($registration['PostalAddress']['country']) && $registration['PostalAddress']['country'] == $country->name ? 'selected' : ''; ?>
                                    >
                                    <?=$country->name;?>
                                </option>
                                <? endforeach; ?>
                            </select>
                            <img class="hig-select" src="/app/templates/front/assets/images/icons/dropdown.svg">
                        </span>
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
                <div class="row small-inner-margins">
                    <div class="form-group col-xs-12">
                        <label for="Relation[Relation][correspondenceType]">Correspondentiewijze
                            <span class="info-tooltip" data-toggle="tooltip" title="Indien u ervoor kiest correspondentie digitaal te ontvangen,
                        krijgt u na de toewijzing van uw inschrijving inloggegevens voor het portaal Mijn-
                        IMMO. De keuze voor de correspondentiewijze is van toepassing op alle
                        participaties in alle fondsen die u met deze tenaamstelling bezit.">i</span>
                         :</label>
                        <span class="dropdown-styling">
                            <select class="form-control" name="Registration[Relation][correspondenceType]" required>
                                <option value="" hidden>Selecteer...</option>
                                <option <?= isset($registration['Relation']['correspondenceType']) && $registration['Relation']['correspondenceType'] == '1' ? 'selected' : '' ?> value="1">Per post</option>
                                <option <?= isset($registration['Relation']['correspondenceType']) && $registration['Relation']['correspondenceType'] == '2' ? 'selected' : '' ?> value="2">Digitaal</option>
                            </select>
                            <img class="hig-select" src="/app/templates/front/assets/images/icons/dropdown.svg">
                        </span>
                    </div>
                    <div class="form-group xs-hide col-sm-6"></div>
                </div>
                
                <? $app->renderPartial('registration/additional-questions', ["registration" => $registration, "fund" => $fund, "contactPersonIdx" => $contactPersonIdx]); ?>
                
                <div class="row small-inner-margins">
                    <div class="form-group col-xs-12">
                        <label>Indien u met meerdere personen wilt inschrijven:</label>
                        <? if (isset($registration['ContactPerson']) && count($registration['ContactPerson']) > 1): ?>
                            <? foreach ($registration['ContactPerson'] as $key => $contactPersonParams): if ($key == 0) { continue; } ?>
                                <div class="digital-registration-extraperson">

                                    <span><?= $contactPersonParams['name']; ?></span>
                                    <button data-hook="registration-submit_form" data-edit="<?= $key ?>" data-action="<?= $extraPersonEditUrl ?>" class="clean-button hig-edit"></button>
                                    <button data-delete="<?= $key ?>" data-action="<?= $extraPersonDeleteUrl; ?>" class="clean-button">
                                        <div class="hig-trash"></div>
                                    </button>
                                </div>
                            <? endforeach; ?>
                        <? endif; ?>
                        <div>
                            <button data-hook="registration-submit_form" data-action="<?= $extraPersonNewUrl ?>" class="btn btn-tertiary btn-icon">
                                <div class="hig-add"></div>
                                <span class="btn-text">Extra persoon toevoegen</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            <hr class="margin-t-small" />
            <? $app->renderPartial('registration/navigation-buttons', ['button' => true, 'prevUrl' => $prevUrl, 'nextUrl' => $nextUrl]); ?>
        </div>
    </div>
</div>