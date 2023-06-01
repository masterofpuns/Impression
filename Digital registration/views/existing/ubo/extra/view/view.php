<div class="container main-content home text-center digital-registration-new">
    <div class="digital-registration-container">
        <?
            $app->renderPartial('registration/registration-steps', ['percentageComplete' => '10%', 'activeStep' => 'identification', 'fund' => $fund]);
        ?>
        <div class="digital-registration-formfields text-left">

            <h2>UBO toevoegen</h2>
            <hr class="margin-t-small" />
            <form data-hook="registration-form" method="POST" enctype="multipart/form-data">
                <div class="row small-inner-margins">
                    <div class="form-group col-sm-3">
                        <label for="Registration[Ubo][<?= $uboIdx; ?>][salutation]">Aanhef:</label>
                        <span class="dropdown-styling">
                            <select class="form-control" name="Registration[Ubo][<?= $uboIdx; ?>][salutation]" required>
                                <option value="" hidden>Selecteer...</option>
                                <option <?= isset($registration['Ubo'][$uboIdx]) && $registration['Ubo'][$uboIdx]['salutation'] == 'De heer' ? 'selected' : '' ?> value="De heer">De heer</option>
                                <option <?= isset($registration['Ubo'][$uboIdx]) && $registration['Ubo'][$uboIdx]['salutation'] == 'Mevrouw' ? 'selected' : '' ?> value="Mevrouw">Mevrouw</option>
                            </select>
                            <img class="hig-select" src="/app/templates/front/assets/images/icons/dropdown.svg">
                        </span>
                    </div>
                    <div class="form-group col-sm-9"></div>
                </div>
                <div class="row small-inner-margins">
                    <div class="form-group col-sm-3">
                        <label for="Registration[Ubo][<?= $uboIdx; ?>][initials]">Voorletters:</label>
                        <input type="text" name="Registration[Ubo][<?= $uboIdx; ?>][initials]" class="form-control" required value="<?= isset($registration['Ubo'][$uboIdx]['initials']) ? $registration['Ubo'][$uboIdx]['initials'] : '' ?>">
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="Registration[Ubo][<?= $uboIdx; ?>][lastNamePrefix]">Tussenvoegsel:</label>
                        <input type="text" name="Registration[Ubo][<?= $uboIdx; ?>][lastNamePrefix]" class="form-control" value="<?= isset($registration['Ubo'][$uboIdx]['lastNamePrefix']) ? $registration['Ubo'][$uboIdx]['lastNamePrefix'] : '' ?>">
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="Registration[Ubo][<?= $uboIdx; ?>][lastName]">Achternaam:</label>
                        <input type="text" name="Registration[Ubo][<?= $uboIdx; ?>][lastName]"class="form-control" required value="<?= isset($registration['Ubo'][$uboIdx]['lastName']) ? $registration['Ubo'][$uboIdx]['lastName'] : '' ?>">
                    </div>
                </div>
                
                
                <div class="row small-inner-margins">
                    <div class="form-group col-sm-6">
                        <label for="Registration[Ubo][<?= $uboIdx; ?>][street]">Straat:</label>
                        <input type="text" name="Registration[Ubo][<?= $uboIdx; ?>][street]" class="form-control" required value="<?= isset($registration['Ubo'][$uboIdx]['street']) ? $registration['Ubo'][$uboIdx]['street'] : '' ?>">
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="Registration[Ubo][<?= $uboIdx; ?>][number]">Huisnummer:</label>
                        <input type="text" name="Registration[Ubo][<?= $uboIdx; ?>][number]" class="form-control" required value="<?= isset($registration['Ubo'][$uboIdx]['number']) ? $registration['Ubo'][$uboIdx]['number'] : '' ?>">
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="Registration[Ubo][<?= $uboIdx; ?>][numberSuffix]">Toevoeging:</label>
                        <input type="text" name="Registration[Ubo][<?= $uboIdx; ?>][numberSuffix]"class="form-control" value="<?= isset($registration['Ubo'][$uboIdx]['numberSuffix']) ? $registration['Ubo'][$uboIdx]['numberSuffix'] : '' ?>">
                    </div>
                </div>
                <div class="row small-inner-margins">
                    <div class="form-group col-sm-3">
                        <label for="Registration[Ubo][postalCode]">Postcode:</label>
                        <input type="text" name="Registration[Ubo][<?= $uboIdx; ?>][postalCode]" class="form-control" required value="<?= isset($registration['Ubo'][$uboIdx]['postalCode']) ? $registration['Ubo'][$uboIdx]['postalCode'] : '' ?>">
                    </div>
                    <div class="form-group col-sm-5">
                        <label for="Registration[Ubo][<?= $uboIdx; ?>][city]">Plaats:</label>
                        <input type="text" name="Registration[Ubo][<?= $uboIdx; ?>][city]" class="form-control" required value="<?= isset($registration['Ubo'][$uboIdx]['city']) ? $registration['Ubo'][$uboIdx]['city'] : '' ?>">
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="Registration[Ubo][<?= $uboIdx; ?>][country]">Land:</label>
                        <span class="dropdown-styling">
                            <select name="Registration[Ubo][<?= $uboIdx; ?>][country]" class="form-control" required>
                                <option value="" hidden>Selecteer...</option>
					            <? foreach ($app->getCountries() as $country): if (empty($country->nationality)) { continue; } ?>
                                <option
                                    value="<?=$country->name;?>"
						            <?= isset($registration['Ubo'][$uboIdx]['country']) && $registration['Ubo'][$uboIdx]['country'] == $country->name ? 'selected' : ''; ?>
                                >
						            <?=$country->name;?>
                                </option>
					            <? endforeach; ?>
                            </select>
                            <img class="hig-select" src="/app/templates/front/assets/images/icons/dropdown.svg">
                        </span>
                    </div>
                </div>

                <div class="row small-inner-margins">
                    <div class="form-group col-sm-3">
                        <label for="">PEP:</label> <span class="info-tooltip" data-toggle="tooltip" title="Een natuurlijk persoon die een prominente publieke functie bekleedt of bekleed heeft, alsmede directe familieleden en personen met wie zij een nauwe zakelijke relatie hebben">i</span>
                        <span class="dropdown-styling">
                            <select class="form-control" name="Registration[Ubo][<?= $uboIdx; ?>][isPep]" required>
                                <option value="" hidden>Selecteer...</option>
                                <option value="0" <?= isset($registration['Ubo'][$uboIdx]['isPep']) && $registration['Ubo'][$uboIdx]['isPep'] == 0 ? 'selected' : ''; ?>>Nee</option>
                                <option value="1" <?= isset($registration['Ubo'][$uboIdx]['isPep']) && $registration['Ubo'][$uboIdx]['isPep'] == 1 ? 'selected' : ''; ?>>Ja</option>
                            </select>
                            <img class="hig-select" src="/app/templates/front/assets/images/icons/dropdown.svg">
                        </span>
                    </div>
                    <div class="form-group col-sm-9 hidden-xs"></div>
                </div>

                <div class="row small-inner-margins">
                    <div class="form-group col-sm-12">
                        <label for="">Bent u Amerikaans staatsburger of woonachtig in de Verenigde staten?</label>
                        <span class="dropdown-styling fake-col-sm3">
                            <select class="form-control" name="Registration[Ubo][<?= $uboIdx; ?>][residentOfUnitedStates]" required>
                                <option value="" hidden>Selecteer...</option>
                                <option value="0" <?= isset($registration['Ubo'][$uboIdx]['residentOfUnitedStates']) && $registration['Ubo'][$uboIdx]['residentOfUnitedStates'] == 0 ? 'selected' : ''; ?>>Nee</option>
                                <option value="1" <?= isset($registration['Ubo'][$uboIdx]['residentOfUnitedStates']) && $registration['Ubo'][$uboIdx]['residentOfUnitedStates'] == 1 ? 'selected' : ''; ?>>Ja</option>
                            </select>
                            <img class="hig-select" src="/app/templates/front/assets/images/icons/dropdown.svg">
                        </span>
                    </div>
                </div>

                <div class="row small-inner-margins">
                    <div class="form-group col-xs-12">
                        <label for="Registration[Ubo][<?= $uboIdx; ?>][nationality]">Nationaliteit:</label>
                        <span class="dropdown-styling">
                            <select name="Registration[Ubo][<?= $uboIdx; ?>][nationality]" class="form-control" required data-hook="registration-nationality">
                                <option value="" hidden>Selecteer...</option>
					            <? foreach ($app->getCountries("nationality") as $country): if (empty($country->nationality)) { continue; } ?>
                                <option
                                    data-region="<?= $country->region; ?>"
                                    value="<?=$country->nationality;?>"
						            <?= isset($registration['Ubo'][$uboIdx]['nationality']) && $registration['Ubo'][$uboIdx]['nationality'] == $country->nationality ? 'selected' : ''; ?>
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
                        <label for="Registration[Ubo][<?= $uboIdx; ?>][idType]">Geldig legitimatiebewijs:</label>
                    </div>
                    <div class="form-group col-xs-12 col-sm-4 first-child">
                        <span class="dropdown-styling">
                            <select name="Registration[Ubo][<?= $uboIdx; ?>][idType]" class="form-control" required data-hook="registration-id_type">
                                <option value="" hidden>Selecteer...</option>
                                <option <?= isset($registration['Ubo'][$uboIdx]['idType']) && $registration['Ubo'][$uboIdx]['idType'] == 'passport' ? 'selected' : '' ?> value="passport" data-availability="world">Paspoort</option>
                                <option <?= isset($registration['Ubo'][$uboIdx]['idType']) && $registration['Ubo'][$uboIdx]['idType'] == 'identityCard' ? 'selected' : '' ?> value="identityCard" data-availability="europe">Identiteitsbewijs</option>
                                <option <?= isset($registration['Ubo'][$uboIdx]['idType']) && $registration['Ubo'][$uboIdx]['idType'] == 'driversLicense' ? 'selected' : '' ?> value="driversLicense" data-availability="netherlands">Rijbewijs</option>
                            </select>
                            <img class="hig-select" src="/app/templates/front/assets/images/icons/dropdown.svg">
                        </span>
                    </div>
                    <div class="form-group col-xs-12 col-sm-8">
			            <?
			            $app->renderPartial(
				            'registration/components/file-upload',
				            [
					            'fileParams' => isset($registration['Ubo'][$uboIdx]['idFile']) ? $registration['Ubo'][$uboIdx]['idFile'] : null,
					            'name' => 'Registration[Ubo]['.$uboIdx.'][idFile]'
				            ]
			            );
			            ?>
                    </div>
                </div>
                <div class="row small-inner-margins registration-interest">
                    <div class="form-group  col-xs-12 col-sm-6">
                        <label for="Registration[Ubo][<?= $uboIdx; ?>][interestRate]">(Indirect) Belang:</label>
                        <input required min="0" max="100" step=".01" type="number" name="Registration[Ubo][<?= $uboIdx; ?>][interestRate]" class="form-control smaller text-center fake-col-sm3" value="<?= isset($registration['Ubo'][$uboIdx]['interestRate']) ? $registration['Ubo'][$uboIdx]['interestRate'] : '' ?>"> %
                    </div>
                    <div class="form-group  col-xs-12 col-sm-6">
                        <label for="Registration[Ubo][<?= $uboIdx; ?>][ownershipRate]">Zeggenschap:</label>
                        <input required min="0" max="100" step=".01" type="number" name="Registration[Ubo][<?= $uboIdx; ?>][ownershipRate]" class="form-control smaller text-center fake-col-sm3" value="<?= isset($registration['Ubo'][$uboIdx]['ownershipRate']) ? $registration['Ubo'][$uboIdx]['ownershipRate'] : '' ?>"> %
                    </div>
                </div>
            </form>
            <hr class="margin-t-small" />
            <? $app->renderPartial('registration/navigation-buttons', ['prevUrl' => $prevUrl, 'nextUrl' => $nextUrl]); ?>
        </div>
    </div>
</div>