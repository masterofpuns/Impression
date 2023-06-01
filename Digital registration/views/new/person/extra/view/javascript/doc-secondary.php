//INCLUDES
<? $app->renderPartial('registration/javascript/registration-general_functions', ['contactPersonIdx' => $contactPersonIdx, 'slug' => $slug]); ?>
var rulesFileUpload = {
    'Registration[ContactPerson][<?= $contactPersonIdx; ?>][idFile]': {
        required: function(element) {
            var container = $(element).closest('.registration-file_upload-container');
            var hasContent = container.find('[data-hook="registration-upload-file_delete-wrapper"]');
            console.log(hasContent);
            return hasContent.length < 1;
        }
    }
};
$.extend(rulesForm, rulesFileUpload);
validator.settings.rules = rulesForm;

// VARS
var idTypeOptions = $('[data-hook="registration-id_type"] option');
var idTypeValueContactPerson = '<?= isset($registration['ContactPerson'][$contactPersonIdx]['idType']) ? $registration['ContactPerson'][$contactPersonIdx]['idType'] : ''; ?>';

// FUNCTIONS
function addPhoneNumber(e) {
    e.preventDefault();
    var phonenumberCount = $('[data-hook="phonenumber-item"]').length
    var rowHtml = `
        <div class="col-xs-12" data-hook="phonenumber-item">
            <div class="row">
                <div class="form-group col-xs-12 col-sm-4">
                    <label for="Registration[ContactPerson][<?= $contactPersonIdx; ?>][phoneNumbers][${phonenumberCount}][type]">Telefoon:</label>
                    <span class="dropdown-styling">
                        <select name="Registration[ContactPerson][<?= $contactPersonIdx; ?>][phoneNumbers][${phonenumberCount}][type]" class="form-control" required>
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
                <div class="form-group col-xs-12 col-sm-7">
                    <label for="Registration[ContactPerson][<?= $contactPersonIdx; ?>][phoneNumbers][${phonenumberCount}][number]">Nummer:</label>
                    <input type="text" name="Registration[ContactPerson][<?= $contactPersonIdx; ?>][phoneNumbers][${phonenumberCount}][number]" class="form-control" required>
                </div>
                <div class="col-sm-1">
                    <button class="clean-button" data-hook="remove-phonenumber">
                        <div class="hig-trash"></div>
                    </button>
                </div>
            </div>
      </div>
    `;
    var lastPhonenumber = $('[data-hook="phonenumber-item"]').last();
    $(rowHtml).insertAfter(lastPhonenumber);
}
function removePhoneNumber(e) {
    e.preventDefault();
    $(this).closest('[data-hook="phonenumber-item"]').remove();
}
function uploadFile(e) {
    e.preventDefault();
    console.log($('input[type="file"').length);
    $('input[type="file"').click();
}
function changeNationality(e) {
    e.preventDefault();

    var region = $(this).find('option:selected').data('region');
    var idTypeSelect = $('[data-hook="registration-id_type"]');
    
    idTypeSelect.val('').empty().append(idTypeOptions[0]);
    switch (region) {
        case 'netherlands':
            idTypeSelect.append(idTypeOptions);
            break;
        case 'europe':
            $.each(idTypeOptions, function(idx, option) {
                if ($(option).data('availability') == 'netherlands') { return; }
                idTypeSelect.append(option);
            });
            break;
        case 'world':
            $.each(idTypeOptions, function(idx, option) {
                if ($(option).data('availability') == 'netherlands' || $(option).data('availability') == 'europe') { return; }
                idTypeSelect.append(option);
            });
            break;
    }

    if (idTypeValueContactPerson != '') {
        idTypeSelect.find('option[value=' + idTypeValueContactPerson + ']').prop('selected', true);
    }
}
function toggleAddressEqualsMainRelationAddress(e) {
    var checked = $(this).hasClass('checked');
    handleAddressContainer(checked)
}
function handleAddressContainer(checked) {
    var addressContainer = $('[data-hook="registration-contact_person-extra-address-container"]');
    switch (checked) {
        case true:
            $(this).closest('.form-group').find('[data-hook="registration-contact_person-extra-address-hidden"]').val(1);
            addressContainer.find('input, select').not('[data-hook="registration-contact_person-number_suffix"]').prop('required', false);
            addressContainer.addClass('hide');
            break;
        case false:
            addressContainer.find('input, select').not('[data-hook="registration-contact_person-number_suffix"]').prop('required', true);
            addressContainer.removeClass('hide');
            break;
    }
}

// LISTENERS
$('body').on('click', '[data-hook="registration-contact_person-extra-address-checkbox"]', toggleAddressEqualsMainRelationAddress);
$('body').on('click', '[data-hook="remove-phonenumber"]', removePhoneNumber);
$('body').on('click', '[data-hook="add-phonenumber"]', addPhoneNumber);
$('body').on('click', '[data-hook="upload-file"]', uploadFile);
$('body').on('change', '[data-hook="registration-nationality"]', changeNationality);

// EXECUTE
$(document).ready(function() {
    handleAddressContainer($('[data-hook="registration-contact_person-extra-address-checkbox"]').hasClass('checked'));
});