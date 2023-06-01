//INCLUDES
<? $app->renderPartial('registration/javascript/registration-general_functions'); ?>
var rulesFileUpload = {
    'Registration[Ubo][<?= $uboIdx; ?>][idFile]': {
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
var idTypeValueContactPerson = '<?= isset($registration['Ubo'][$uboIdx]['idType']) ? $registration['Ubo'][$uboIdx]['idType'] : ''; ?>';

// FUNCTIONS
function uploadFile(e) {
    e.preventDefault();
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

// LISTENERS
$('body').on('click', '[data-hook="upload-file"]', uploadFile);
$('body').on('change', '[data-hook="registration-nationality"]', changeNationality);

// EXECUTE
$(document).ready(function() {
    $('[data-hook="registration-nationality"]').trigger('change');
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });
});