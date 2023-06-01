<div class="container main-content home text-center digital-registration-new">
    <div class="digital-registration-container">
		<? $app->renderPartial('registration/registration-steps', ['percentageComplete' => '10%', 'activeStep' => 'identification', 'fund' => $fund]); ?>
        <div class="digital-registration-formfields text-left registration-ubo">

            <div>
                <h2>UBO toevoegen</h2>
                <hr class="margin-t-small margin-b-big">
                <div data-target="login">
                    <div class="main-info-container row">
                        <div class="col-xs-12 flex">
                            <button class="clean-button more-info" data-hook="open" data-section="login">
                                <img class="hig-plus" src="/app/templates/front/assets/images/icons/more.svg">
                                <img class="hig-min" src="/app/templates/front/assets/images/icons/less.svg">
                            </button>
                            <div class="info-wrapper" data-hook="open-button">
                                <p class="main-info">Wat is een UBO?</p>
                            </div>
                        </div>
                    </div>
                    <div class="more-info-container">
                        <div class="row">
                            <div class="more-info-target ubo-info col-xs-12">
                                <? $partial = $app->getCmsPartial('registration-information-ubo'); ?>
                                <?= !empty($partial) ? $partial->content : ""; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="margin-t-big margin-b-big">
            </div>

            <h2>De volgende natuurlijke personen hebben een uiteindelijk belang van meer dan 25%</h2>
            <hr class="margin-t-small" />
            <br>
            <form data-hook="registration-form" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="form-group col-sm-12">
                        <label for="">Is een van de bestuurders van <?= isset($registration['Relation']['name']) ? $registration['Relation']['name'] : '' ?> UBO?</label>
						<div data-hook="custom-errormsg">
	                        <span class="dropdown-styling fake-col-sm3">
	                            <select class="form-control" name="dummyHasManagerAsUbo" data-hook="isubo">
	                                <option value="" hidden></option>
	                                <option value="1" <?= isset($hasManagerAsUbo) && $hasManagerAsUbo ? 'selected' : ''; ?>>Ja</option>
	                                <option value="0" <?= isset($hasManagerAsUbo) && !$hasManagerAsUbo ? 'selected' : ''; ?>>Nee</option>
	                            </select>
	                            <img class="hig-select" src="/app/templates/front/assets/images/icons/dropdown.svg">
	                        </span>
                            <div class="customErrorContainer"></div>
                        </div>
                    </div>
                </div>
                <div class="<?= isset($hasManagerAsUbo) && $hasManagerAsUbo ? '' : 'hide'; ?>" data-showubo="1">
                    <div class="row">
                        <div class="col-xs-12">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Bestuurder(s):</th>
                                        <th>(Indirect) Belang:</th>
                                        <th>Zeggenschap:</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <? if (!empty($registration['ContactPerson'])): ?>
                                        <? foreach ($registration['ContactPerson'] as $contactPersonIdx => $contactPersonParams): ?>
                                        <? \app\m::app()->renderPartial('registration/components/registration-ubo', ['contactPersonIdx' => $contactPersonIdx, 'contactPersonParams' => $contactPersonParams]); ?>
                                        <? endforeach; ?>
                                    <? endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-12">
                        <label for="">Zijn er andere uiteindelijk belanghebbende(n) met een belang van meer dan 25%?</label>
                        <div data-hook="custom-errormsg">
	                        <span class="dropdown-styling fake-col-sm3">
	                            <select class="form-control" name="dummyHasUbo" data-hook="interest">
	                                <option value="" hidden></option>
	                                <option value="1" <?= isset($hasAdditionalUbos) && $hasAdditionalUbos ? 'selected' : ''; ?>>Ja</option>
	                                <option value="0" <?= isset($hasAdditionalUbos) && !$hasAdditionalUbos ? 'selected' : ''; ?>>Nee</option>
	                            </select>
	
	                            <img class="hig-select" src="/app/templates/front/assets/images/icons/dropdown.svg">
	                        </span>
                            <div class="customErrorContainer"></div>
                        </div>
                    </div>
                    <div class="form-group col-sm-9 hidden-xs"></div>
                </div>
                <div>
                    <div class="row <?= isset($hasAdditionalUbos) && $hasAdditionalUbos ? '' : 'hide'; ?>" data-interest="1">
                        <div class="col-xs-12">
		                    <? if (!empty($registration['Ubo'])): ?>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Overige UBO('s):</th>
                                        <th>(Indirect) Belang:</th>
                                        <th>Zeggenschap:</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
				                    <? foreach ($registration['Ubo'] as $uboIdx => $uboParams): ?>
                                    <tr data-hook="tr-ubo">
                                        <td>
						                    <?= isset($uboParams['name']) ? $uboParams['name'] : '' ?>
                                        </td>
                                        <td>
                                            <input required min="0" max="100" step=".01" type="number" name="Registration[Ubo][<?= $uboIdx; ?>][interestRate]" class="form-group smaller text-center" value="<?= isset($uboParams['interestRate']) ? $uboParams['interestRate'] : '' ?>"> %
                                        </td>
                                        <td>
                                            <input required min="0" max="100" step=".01" type="number" name="Registration[Ubo][<?= $uboIdx; ?>][ownershipRate]" class="form-group smaller text-center" value="<?= isset($uboParams['ownershipRate']) ? $uboParams['ownershipRate'] : '' ?>"> %
                                        </td>
                                        <td>
                                            <? if ($uboParams['edit']): ?>
                                            <button data-edit="<?= $uboIdx ?>" data-action="<?= $extraUboEditUrl ?>" class="clean-button hig-edit"></button>
                                            <? endif; ?>
	                                        <? if ($uboParams['delete']): ?>
                                            <button data-delete-ubo="<?= $uboIdx ?>" data-action="<?= $extraUboDeleteUrl; ?>" class="clean-button">
                                                <div class="hig-trash"></div>
                                            </button>
                                            <? endif; ?>
                                            <span data-hook="registration-extra_ubo-error_msg"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <div class="row small-inner-margins">
                                                <div class="form-group col-sm-6">
                                                    <label for="Registration[Ubo][<?= $uboIdx; ?>][street]">Woonadres:</label>
                                                    <input type="text" name="Registration[Ubo][<?= $uboIdx; ?>][street]" class="form-control" required value="<?= isset($uboParams['street']) ? $uboParams['street'] : '' ?>">
                                                </div>
                                                <div class="form-group col-sm-3">
                                                    <label for="Registration[Ubo][<?= $uboIdx; ?>][number]">Huisnummer:</label>
                                                    <input type="text" name="Registration[Ubo][<?= $uboIdx; ?>][number]" class="form-control" required value="<?= isset($uboParams['number']) ? $uboParams['number'] : '' ?>">
                                                </div>
                                                <div class="form-group col-sm-3">
                                                    <label for="Registration[Ubo][<?= $uboIdx; ?>][numberSuffix]">Toevoeging:</label>
                                                    <input data-hook="registration-contact_person-number_suffix" type="text" name="Registration[Ubo][<?= $uboIdx; ?>][numberSuffix]"class="form-control" value="<?= isset($uboParams['numberSuffix']) ? $uboParams['numberSuffix'] : '' ?>">
                                                </div>
                                            </div>
                                            <div class="row small-inner-margins">
                                                <div class="form-group col-sm-3">
                                                    <label for="Registration[ContactPerson][postalCode]">Postcode:</label>
                                                    <input type="text" name="Registration[Ubo][<?= $uboIdx; ?>][postalCode]" class="form-control" required value="<?= isset($uboParams['postalCode']) ? $uboParams['postalCode'] : '' ?>">
                                                </div>
                                                <div class="form-group col-sm-5">
                                                    <label for="Registration[Ubo][<?= $uboIdx; ?>][city]">Plaats:</label>
                                                    <input type="text" name="Registration[Ubo][<?= $uboIdx; ?>][city]" class="form-control" required value="<?= isset($uboParams['city']) ? $uboParams['city'] : '' ?>">
                                                </div>
                                                <div class="form-group col-sm-4">
                                                    <label for="Registration[Ubo][<?= $uboIdx; ?>][country]">Land:</label>
                                                    <span class="dropdown-styling">
                                                        <select name="Registration[Ubo][<?= $uboIdx; ?>][country]" class="form-control" required>
                                                            <option value="" hidden>Selecteer...</option>
                                                            <? foreach ($app->getCountries() as $country): if (empty($country->nationality)) { continue; } ?>
                                                            <option
                                                                value="<?=$country->name;?>"
                                                                <?= isset($uboParams['country']) && $uboParams['country'] == $country->name ? 'selected' : ''; ?>
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
                                                <div class="form-group col-sm-12">
                                                    <label for="">Bent u Amerikaans staatsburger of woonachtig in de Verenigde staten?</label>
                                                    <span class="dropdown-styling">
                                                        <select class="form-control" name="Registration[Ubo][<?= $uboIdx; ?>][residentOfUnitedStates]" required>
                                                            <option value="" hidden>Selecteer...</option>
                                                            <option value="0" <?= isset($uboParams['residentOfUnitedStates']) && $uboParams['residentOfUnitedStates'] == '0' ? 'selected' : ''; ?>>Nee</option>
                                                            <option value="1" <?= isset($uboParams['residentOfUnitedStates']) && $uboParams['residentOfUnitedStates'] == '1' ? 'selected' : ''; ?>>Ja</option>
                                                        </select>
                                                        <img class="hig-select" src="/app/templates/front/assets/images/icons/dropdown.svg">
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>                                    
				                    <? endforeach; ?>
                                </tbody>
                            </table>
		                    <? endif; ?>
                        </div>
                        <div class="col-sm-12">
                            <button data-hook="registration-submit_form" data-button-type="add-ubo" data-action="<?= $extraUboNewUrl; ?>" class="btn btn-tertiary btn-icon">
                                <div class="hig-add"></div>
                                <span class="btn-text">UBO toevoegen</span>
                            </button>
                        </div>
                    </div>
                    <div class="row <?= (!isset($hasAdditionalUbos) || (isset($hasAdditionalUbos) && !$hasAdditionalUbos)) && (!isset($hasManagerAsUbo) || (isset($hasManagerAsUbo) && !$hasManagerAsUbo)) ? '' : 'hide'; ?>" data-hook="pseudo">
                        <div class="col-sm-12" data-hook="custom-errormsg">
                            Geef hieronder aan wie de pseudo-UBO is <span class="info-tooltip" data-toggle="tooltip" title="Een pseudo-UBO is de aangewezen persoon behorend tot het hoger leidinggevend personeel die wordt genoteerd als UBO, in de omstandigheid dat er geen personen als UBO kunnen worden aangewezen. Onder 'hoger leidinggevend personeel' wordt het statutair bestuur van de deelnemende organisatie verstaan.">i</span>
                            <input type="hidden" name="dummyPseudoUbo" />
                            <div class="customErrorContainer"></div>
                        </div>
                        <div class="col-sm-12">
                            <table class="table">
                                <tbody>
                                    <? if (!empty($registration['ContactPerson'])): ?>
                                    <? foreach ($registration['ContactPerson'] as $contactPersonIdx => $contactPersonParams): ?>
                                    <? \app\m::app()->renderPartial('registration/components/registration-pseudo_ubo', ['contactPersonIdx' => $contactPersonIdx, 'contactPersonParams' => $contactPersonParams]); ?>
                                    <? endforeach; ?>
                                    <? endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
            <hr class="margin-t-small" />
			<? $app->renderPartial('registration/navigation-buttons', ['prevUrl' => $prevUrl, 'button' => true, 'nextUrl' => $nextUrl]); ?>
        </div>
    </div>
</div>