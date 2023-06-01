<tr class="<?= isset($contactPersonParams['isUbo']) && $contactPersonParams['isUbo'] ? '' : 'bottom'; ?>">
    <td>
        <label class="custom-checkbox <?= isset($contactPersonParams['isUbo']) && $contactPersonParams['isUbo'] ? 'checked' : ''; ?>" data-checkbox="Registration[ContactPerson][<?= $contactPersonIdx; ?>][isUbo]" for="Registration[ContactPerson][<?= $contactPersonIdx; ?>][isUbo]">
            <span class="custom-checkbox-indicator"></span>
            <span class="custom-checkbox-text">
                &nbsp;
            </span>
        </label>
        <input data-hook="registration-toggle-is_ubo" <?= isset($contactPersonParams['isUbo']) && $contactPersonParams['isUbo'] ? 'checked' : ''; ?> type="hidden" name="Registration[ContactPerson][<?= $contactPersonIdx; ?>][isUbo]" value="<?= isset($contactPersonParams['isUbo']) ? $contactPersonParams['isUbo'] : 0; ?>">
			<?= isset($contactPersonParams['name']) ? $contactPersonParams['name'] : '' ?>
    </td>
    <td>
        <input data-hook="registration-contact_person-interest_rate" <?= isset($contactPersonParams['isUbo']) && $contactPersonParams['isUbo'] ? 'required' : ''; ?> min="0" max="100" step=".01" type="number" name="Registration[ContactPerson][<?= $contactPersonIdx; ?>][interestRate]" class="form-group smaller text-center" value="<?= isset($contactPersonParams['interestRate']) ? $contactPersonParams['interestRate'] : '' ?>"> %
    </td>
    <td>
        <input data-hook="registration-contact_person-ownership_rate" <?= isset($contactPersonParams['isUbo']) && $contactPersonParams['isUbo'] ? 'required' : ''; ?> min="0" max="100" step=".01" type="number" name="Registration[ContactPerson][<?= $contactPersonIdx; ?>][ownershipRate]" class="form-group smaller text-center" value="<?= isset($contactPersonParams['ownershipRate']) ? $contactPersonParams['ownershipRate'] : '' ?>"> %
    </td>
</tr>
<?
    $cpIsUbo = isset($contactPersonParams['isUbo']) && $contactPersonParams['isUbo'];
?>
<tr class="<?= $cpIsUbo ? 'bottom' : 'hide'; ?>" data-hook="show-ubo_address">
    <td colspan="3" style="padding-bottom: 10px;">
        <div class="row small-inner-margins">
            <div class="form-group col-sm-6">
                <label for="Registration[ContactPerson][<?= $contactPersonIdx; ?>][street]">Woonadres:</label>
                <input type="text" name="Registration[ContactPerson][<?= $contactPersonIdx; ?>][street]" class="form-control" <?= $cpIsUbo ? 'required' : ''; ?> value="<?= isset($contactPersonParams['street']) ? $contactPersonParams['street'] : '' ?>">
            </div>
            <div class="form-group col-sm-3">
                <label for="Registration[ContactPerson][<?= $contactPersonIdx; ?>][number]">Huisnummer:</label>
                <input type="text" name="Registration[ContactPerson][<?= $contactPersonIdx; ?>][number]" class="form-control" <?= $cpIsUbo ? 'required' : ''; ?> value="<?= isset($contactPersonParams['number']) ? $contactPersonParams['number'] : '' ?>">
            </div>
            <div class="form-group col-sm-3">
                <label for="Registration[ContactPerson][<?= $contactPersonIdx; ?>][numberSuffix]">Toevoeging:</label>
                <input data-hook="registration-contact_person-number_suffix" type="text" name="Registration[ContactPerson][<?= $contactPersonIdx; ?>][numberSuffix]"class="form-control" value="<?= isset($contactPersonParams['numberSuffix']) ? $contactPersonParams['numberSuffix'] : '' ?>">
            </div>
        </div>
        <div class="row small-inner-margins">
            <div class="form-group col-sm-3">
                <label for="Registration[ContactPerson][postalCode]">Postcode:</label>
                <input type="text" name="Registration[ContactPerson][<?= $contactPersonIdx; ?>][postalCode]" class="form-control" <?= $cpIsUbo ? 'required' : ''; ?> value="<?= isset($contactPersonParams['postalCode']) ? $contactPersonParams['postalCode'] : '' ?>">
            </div>
            <div class="form-group col-sm-5">
                <label for="Registration[ContactPerson][<?= $contactPersonIdx; ?>][city]">Plaats:</label>
                <input type="text" name="Registration[ContactPerson][<?= $contactPersonIdx; ?>][city]" class="form-control" <?= $cpIsUbo ? 'required' : ''; ?> value="<?= isset($contactPersonParams['city']) ? $contactPersonParams['city'] : '' ?>">
            </div>
            <div class="form-group col-sm-4">
                <label for="Registration[ContactPerson][<?= $contactPersonIdx; ?>][country]">Land:</label>
                <span class="dropdown-styling">
                    <select name="Registration[ContactPerson][<?= $contactPersonIdx; ?>][country]" class="form-control" <?= $cpIsUbo ? 'required' : ''; ?>>
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
                    <select class="form-control" name="Registration[ContactPerson][<?= $contactPersonIdx; ?>][residentOfUnitedStates]">
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