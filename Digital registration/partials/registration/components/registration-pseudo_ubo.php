<tr>
    <td>
        <label class="custom-checkbox <?= isset($contactPersonParams['isPseudoUbo']) && $contactPersonParams['isPseudoUbo'] == '1' ? 'checked' : ''; ?> " data-checkbox="Registration[ContactPerson][<?= $contactPersonIdx; ?>][isPseudoUbo]" for="Registration[ContactPerson][<?= $contactPersonIdx; ?>][isPseudoUbo]">
            <span class="custom-checkbox-indicator"></span>
            <span class="custom-checkbox-text">
                &nbsp;
            </span>
        </label>
        <input data-hook="registration-toggle-is_pseudo_ubo" type="hidden" <?= isset($contactPersonParams['isPseudoUbo']) && $contactPersonParams['isPseudoUbo'] == '1' ? 'checked' : ''; ?> name="Registration[ContactPerson][<?= $contactPersonIdx; ?>][isPseudoUbo]" value="<?= isset($contactPersonParams['isPseudoUbo']) && $contactPersonParams['isPseudoUbo'] == '1' ? '1' : '0'; ?>">
        <strong>
            <?= isset($contactPersonParams['name']) ? $contactPersonParams['name'] : '' ?>
        </strong>
    </td>
</tr>

<tr class="<?= isset($contactPersonParams['isPseudoUbo']) && $contactPersonParams['isPseudoUbo'] == '1' ? '' : 'hide'; ?>" data-hook="show-pseudo_ubo_address">
    <td colspan="3" style="padding-bottom: 10px;">
        <div class="row small-inner-margins">
            <div class="form-group col-sm-6">
                <label for="Registration[ContactPersonPseudoUbo][<?= $contactPersonIdx; ?>][street]">Woonadres:</label>
                <input type="text" name="Registration[ContactPersonPseudoUbo][<?= $contactPersonIdx; ?>][street]" class="form-control" required value="<?= isset($contactPersonParams['street']) ? $contactPersonParams['street'] : '' ?>">
            </div>
            <div class="form-group col-sm-3">
                <label for="Registration[ContactPersonPseudoUbo][<?= $contactPersonIdx; ?>][number]">Huisnummer:</label>
                <input type="text" name="Registration[ContactPersonPseudoUbo][<?= $contactPersonIdx; ?>][number]" class="form-control" required value="<?= isset($contactPersonParams['number']) ? $contactPersonParams['number'] : '' ?>">
            </div>
            <div class="form-group col-sm-3">
                <label for="Registration[ContactPersonPseudoUbo][<?= $contactPersonIdx; ?>][numberSuffix]">Toevoeging:</label>
                <input data-hook="registration-contact_person-number_suffix" type="text" name="Registration[ContactPersonPseudoUbo][<?= $contactPersonIdx; ?>][numberSuffix]"class="form-control" value="<?= isset($contactPersonParams['numberSuffix']) ? $contactPersonParams['numberSuffix'] : '' ?>">
            </div>
        </div>
        <div class="row small-inner-margins">
            <div class="form-group col-sm-3">
                <label for="Registration[ContactPersonPseudoUbo][postalCode]">Postcode:</label>
                <input type="text" name="Registration[ContactPersonPseudoUbo][<?= $contactPersonIdx; ?>][postalCode]" class="form-control" required value="<?= isset($contactPersonParams['postalCode']) ? $contactPersonParams['postalCode'] : '' ?>">
            </div>
            <div class="form-group col-sm-5">
                <label for="Registration[ContactPersonPseudoUbo][<?= $contactPersonIdx; ?>][city]">Plaats:</label>
                <input type="text" name="Registration[ContactPersonPseudoUbo][<?= $contactPersonIdx; ?>][city]" class="form-control" required value="<?= isset($contactPersonParams['city']) ? $contactPersonParams['city'] : '' ?>">
            </div>
            <div class="form-group col-sm-4">
                <label for="Registration[ContactPersonPseudoUbo][<?= $contactPersonIdx; ?>][country]">Land:</label>
                <span class="dropdown-styling">
                    <select name="Registration[ContactPersonPseudoUbo][<?= $contactPersonIdx; ?>][country]" class="form-control" required>
                        <option value="" hidden>Selecteer...</option>
						<? foreach ($app->getCountries() as $country): if (empty($country->nationality)) { continue; } ?>
                        <option
                            value="<?=$country->name;?>"
							<?= isset($contactPersonParams['country']) && $contactPersonParams['country'] == $country->name ? 'selected' : ''; ?>
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
                    <select class="form-control" name="Registration[ContactPersonPseudoUbo][<?= $contactPersonIdx; ?>][residentOfUnitedStates]" required>
                        <option value="" hidden>Selecteer...</option>
                        <option value="0" <?= isset($contactPersonParams['residentOfUnitedStates']) && !is_null($contactPersonParams['residentOfUnitedStates']) && $contactPersonParams['residentOfUnitedStates'] == 0 ? 'selected' : ''; ?>>Nee</option>
                        <option value="1" <?= isset($contactPersonParams['residentOfUnitedStates']) && !is_null($contactPersonParams['residentOfUnitedStates']) && $contactPersonParams['residentOfUnitedStates'] == 1 ? 'selected' : ''; ?>>Ja</option>
                    </select>
                    <img class="hig-select" src="/app/templates/front/assets/images/icons/dropdown.svg">
                </span>
            </div>
        </div>
    </td>
</tr>