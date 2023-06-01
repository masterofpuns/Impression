<div class="container main-content home text-center digital-registration-new">
    <div class="digital-registration-container">
        <?
            $app->renderPartial('registration/registration-steps', ['percentageComplete' => '10%', 'activeStep' => 'identification', 'fund' => $fund]);
        ?>
        <div class="digital-registration-formfields text-left">

            <h2>Graag onderstaande gegevens invullen</h2>
            <hr class="margin-t-small" />
            <br>
            
            <form data-hook="registration-form" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="Registration[Relation][type]" class="form-control" required value="organization">
                
                <p>Rechtspersoon</p>
                <hr class="margin-t-small" />
                <? // RELATIE GEGEVENS ?>
                <div class="row small-inner-margins">
                    <div class="form-group col-xs-12">
                        <label for="Registration[Relation][name]">Naam rechtspersoon:</label>
                        <input type="text" name="Registration[Relation][name]" class="form-control" required value="<?= isset($registration['Relation']['name']) ? $registration['Relation']['name'] : '' ?>">
                    </div>
                </div>
                <? // ADRES ?>
                <div class="row small-inner-margins">
                    <div class="form-group col-sm-6">
                        <label for="Registration[PostalAddress][street]">Vestigingsadres (geen postbus):</label>
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
                                <? foreach ($app->getCountries() as $country): if (empty($country->nationality)) { continue; } ?>
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
                <? // KVK ?>
                <div class="row small-inner-margins">
                    <div class="form-group col-xs-12">
                        <label for="Registration[Relation][cocNumber]">KvK nummer:</label>
                        <input type="text" name="Registration[Relation][cocNumber]" class="form-control" required value="<?= isset($registration['Relation']['cocNumber']) && isset($registration['Relation']['cocNumber']) ? $registration['Relation']['cocNumber'] : '' ?>">
                    </div>
                </div>
                <div class="row small-inner-margins">
                    <div class="form-group col-xs-12">
                        <label for="Registration[Relation][cocFile]">KvK uittreksel (niet ouder dan 6 maanden):</label>
	                    <?
	                    $app->renderPartial(
		                    'registration/components/file-upload',
		                    [
			                    'fileParams' => isset($registration['Relation']['cocFile']) ? $registration['Relation']['cocFile'] : null,
			                    'name' => 'Registration[Relation][cocFile]',
                                'required' => false
		                    ]
	                    );
	                    ?>
                    </div>
                </div>
                <div class="row small-inner-margins">
                    <div class="form-group col-xs-12">
                        <label for="">Wat zijn de activiteiten van de rechtspersoon?</label>
                        <input type="text" name="Registration[Relation][legalEntityActivities]" class="form-control" required value="<?= !empty($registration['Relation']['legalEntityActivities']) ? $registration['Relation']['legalEntityActivities'] : '' ?>">
                    </div>
                </div>
                <div class="row small-inner-margins">
                    <div class="form-group col-xs-12">
                        <label for="">Wat is de herkomst van middelen? <span class="info-tooltip" data-toggle="tooltip" title="Dit is een vereiste vanuit de Wet ter voorkoming van witwassen en financieren van terrorisme (Wwft)">i</span></label><br>
                        <em>(bijv. winst uit onderneming/ingebracht door aandeelhouders)</em>
                        <input type="text" name="Registration[Relation][originOfResources]" class="form-control" required value="<?= !empty($registration['Relation']['originOfResources']) ? $registration['Relation']['originOfResources'] : '' ?>">
                    </div>
                </div>
                <div class="row small-inner-margins">
                    <div class="form-group col-xs-12">
                        <label for="Relation[Relation][correspondenceType]">Correspondentiewijze <span class="info-tooltip" data-toggle="tooltip" title="Indien u ervoor kiest correspondentie digitaal te ontvangen,
krijgt u na de toewijzing van uw inschrijving inloggegevens voor het portaal Mijn-
IMMO. De keuze voor de correspondentiewijze is van toepassing op alle
participaties in alle fondsen die u met deze tenaamstelling bezit.">i</span> :</label>
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
                <? // BESTUURDERS ?>
	            <? if (empty($registration['ContactPerson']) || count($registration['ContactPerson']) < 2): ?>
                    <p>Bestuurder</p>
                    <? $app->renderPartial('registration/components/legalentity-contactperson', ['registration' => $registration, 'contactPersonIdx' => $contactPersonIdx]); ?>
                <? endif; ?>
                
                <div class="row small-inner-margins">
                    <div class="form-group col-xs-12">
	                    <? if (isset($registration['ContactPerson']) && count($registration['ContactPerson']) > 1): ?>
                        <label>Bestuurders</label>
                        <hr class="margin-t-small" />
                        <? else: ?>
                        <label>Indien er sprake is van gezamenlijke bevoegdheid:</label>
                        <? endif; ?>
                        
                        <? if (isset($registration['ContactPerson']) && count($registration['ContactPerson']) > 1): ?>
                            <? foreach ($registration['ContactPerson'] as $key => $contactPersonParams): ?>
                                <div class="digital-registration-extraperson">

                                    <span><?= $contactPersonParams['name']; ?></span>
                                    <button data-hook="registration-submit_form" data-edit="<?= $key ?>" data-action="<?= $extraManagerEditUrl ?>" class="clean-button hig-edit"></button>
                                    <button data-delete="<?= $key ?>" data-action="<?= $extraManagerDeleteUrl; ?>" class="clean-button">
                                        <div class="hig-trash"></div>
                                    </button>
                                </div>
                            <? endforeach; ?>
                        <? endif; ?>
                        <div>
                            <button data-hook="registration-submit_form" data-action="<?= $extraManagerNewUrl; ?>" class="btn btn-tertiary btn-icon">
                                <div class="hig-add"></div>
                                <span class="btn-text">Extra bestuurder toevoegen</span>
                            </button>
                        </div>

                        <span data-hook="registration-extra_person-error_msg"></span>
                    </div>
                </div>
            </form>
            <hr class="margin-t-small" />
            <? $app->renderPartial('registration/navigation-buttons', ['button' => true, 'prevUrl' => $prevUrl, 'nextUrl' => $nextUrl]); ?>
        </div>
    </div>
</div>