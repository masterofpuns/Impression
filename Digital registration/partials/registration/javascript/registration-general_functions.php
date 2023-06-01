// VARS
$.validator.addMethod("notOnlyZero", function (value, element, param) {
    return this.optional(element) || parseInt(value) > 0;
});
$.validator.addMethod("noSpace", function (value, element, param) {
    return !(value === null);
}, "Veld mag niet leeg zijn");
$.validator.addMethod("alphanumOnly", function (value, element, param) {
    return !(value.match(/([\W]+)$/) !== null);
}, "Veld mag geen speciale tekens bevatten");

var messagesForm = {};
var rulesForm = {
    'Registration[BankAccount][iban]': { noSpace: true, alphanumOnly: true },
    'Registration[BankAccount][ascription]': { noSpace: true, alphanumOnly: true },
    <? if (isset($contactPersonIdx)): ?>
        'Registration[ContactPerson][<?= $contactPersonIdx; ?>][emailAddress]': {
            required: true,
            remote: {
                url: "<?= $app->getDocByName('registration-check-email_address')->getUrl($slug) ?>",
                type: "POST",
                dataType: 'json',
                data: {
                    emailAddress: function() {
                        return $('[name="Registration[ContactPerson][<?= $contactPersonIdx; ?>][emailAddress]"]').val();
                    }
                }
            }
        }
    <? endif; ?>
};

// FUNCTIONS
function handlePrevNextAction(e) {
    e.preventDefault();
    var button = $(this);
    button.prop('disabled', true);

    var form = document.querySelector('[data-hook="registration-form"]');

    if (form) {
        form.action = button.data('action');
        $('[data-hook="registration-form"]').submit();
    } else {
        // pagina zonder form, doorsturen naar action
        window.location.href = button.data('action');
    }
}
function submitRegistrationForm(e) {
    e.preventDefault();
}
function uploadFile(e) {
    e.preventDefault();
    $(this).parent().find('[data-hook="registration-upload-file_input"]').click();
}
function changeFileUpload(e) {
    var fileData = this.files[0];
    var fileName = fileData.name;
    var fileWrapper = `
        <div class="formfield form-control">
            ${fileName}
            <i data-hook="registration-upload-file_delete-wrapper" class="glyphicon glyphicon-trash"></i>
        </div>
    `;

    var container = $(this).closest('.registration-file_upload-container');
    container.prepend(fileWrapper);
    container.find('[data-hook="registration-file_upload-input-wrapper"]').addClass('hide');
}
function deleteUpload(e) {
    var container = $(this).closest('.registration-file_upload-container');
    container.find('[data-hook="registration-file_upload-input-wrapper"]').removeClass('hide');
    container.find('[data-hook="registration-upload-file_input"]').val('');

    $(this).closest('.form-control').remove();
}
function deleteContactPerson(e) {
    e.preventDefault();
    var contactPersonIdx = $(this).attr('data-delete');
    var formUrl = $(this).attr('data-action');
    var that = this;

    $('[data-hook="registration-extra_person-error_msg"]').empty();

    $.ajax({
        method: 'POST',
        url: formUrl,
        data: {
            contactPersonIdx: contactPersonIdx
        },
        success: function(response) {
            if (response.success) {
                $(that).closest('.digital-registration-extraperson').remove();
            } else {
                $('[data-hook="registration-extra_person-error_msg"]').append(response.message);
            }
        }
    });

}
function deleteUbo(e) {
    e.preventDefault();
    var uboIdx = $(this).attr('data-delete-ubo');
    var formUrl = $(this).attr('data-action');
    var that = this;

    $('[data-hook="registration-extra_person-error_msg"]').empty();

    $.ajax({
        method: 'POST',
        url: formUrl,
        data: {
            uboIdx: uboIdx
        },
        success: function(response) {
            if (response.success) {
                $(that).closest('tr').remove();
            } else {
                $('[data-hook="registration-extra_ubo-error_msg"]').append(response.message);
            }
        }
    });

}
function toggleUbo(e) {
    e.preventDefault();

    var that = this;

    setTimeout(function() {
        var row = $(that).closest('tr');
        var checked = parseInt(that.value);
        switch (checked) {
            case 1:
                // tonen van adres voor UBO
                row.next('[data-hook="show-ubo_address"]').removeClass('hide').addClass('bottom');
                // velden verplicht maken
                row.next('[data-hook="show-ubo_address"]').find('input, select').not('[data-hook="registration-contact_person-number_suffix"]').prop('required', true);
                // velden zeggenschap en belang verplicht maken
                row.find('[data-hook="registration-contact_person-interest_rate"]').prop('required', true);
                row.find('[data-hook="registration-contact_person-ownership_rate"]').prop('required', true);
                break;
            case 0:
                // verbergen adres
                row.addClass('bottom');
                row.next('[data-hook="show-ubo_address"]').removeClass('bottom').addClass('hide');
                // opheffen verplichte velden voor adres
                row.next('[data-hook="show-ubo_address"]').find('input, select').prop('required', false);
                // opheffen verplicht zijn van zeggenschap en belang
                row.find('[data-hook="registration-contact_person-interest_rate"]').prop('required', false);
                row.find('[data-hook="registration-contact_person-ownership_rate"]').prop('required', false);
                break;
        }
    }, 0);
}
function togglePseudoUbo(e) {
    e.preventDefault();

    var that = this;

    setTimeout(function() {
        var row = $(that).closest('tr');
        var checked = parseInt(that.value);
        switch (checked) {
            case 1:
                // tonen van adres voor UBO
                row.next('[data-hook="show-pseudo_ubo_address"]').removeClass('hide');
                // velden verplicht maken
                row.next('[data-hook="show-pseudo_ubo_address"]').find('input, select').not('[data-hook="registration-contact_person-number_suffix"]').prop('required', true);
                break;
            case 0:
                // verbergen adres
                row.next('[data-hook="show-pseudo_ubo_address"]').addClass('hide');
                // opheffen verplichte velden voor adres
                row.next('[data-hook="show-pseudo_ubo_address"]').find('input, select').prop('required', false);
                break;
        }
    }, 0);
}

// LISTENERS
$('body').on('click', '[data-hook="registration-submit_form"]', handlePrevNextAction);
$('[data-hook="registration-form"]').on('submit', submitRegistrationForm);
$('body').on('click', '[data-delete]', deleteContactPerson);
$('body').on('click', '[data-delete-ubo]', deleteUbo);
$('body').on('change', '[data-hook="registration-upload-file_input"]', changeFileUpload);
$('body').on('click', '[data-hook="registration-upload-file_trigger"]', uploadFile);
$('body').on('click', '[data-hook="registration-upload-file_delete-wrapper"]', deleteUpload);
$('body').on('change', '[data-hook="registration-toggle-is_ubo"]', toggleUbo);
$('body').on('change', '[data-hook="registration-toggle-is_pseudo_ubo"]', togglePseudoUbo);

// EXECUTE
var validator = $('[data-hook="registration-form"]').validate({
    rules: rulesForm,
    ignore: [],
    messages: messagesForm,
    errorPlacement: function(error, element) {
        var hasCustomError = $(element).closest('[data-hook="custom-errormsg"]');
        if(hasCustomError.length > 0) {
            hasCustomError.find('.customErrorContainer').html(error);
        } else {
            error.insertAfter(element);
        }
    },
    submitHandler: function(form, e) {
        form.submit();
    },
    invalidHandler: function(form, validator) {
        for (var i=0;i<validator.errorList.length;i++){
            console.log(validator.errorList[i]);
        }
        // opheffen disabled state wanneer form niet valid is, voorkomen dat gebruiker niet door kan gaan
        $('.digital-registration-buttons .btn').prop('disabled', false);
        $('[data-hook="registration-submit_form"]').attr('disabled', false);
    }
});

jQuery.extend(jQuery.validator.messages, {
    required: "<?= $app->getCmsPartial('validate-required')->getContentForJs(); ?>",
    email: "<?= $app->getCmsPartial('validate-email')->getContentForJs(); ?>",
    equalTo: "<?= $app->getCmsPartial('validate-equal')->getContentForJs(); ?>",
    remote: "<?= $app->getCmsPartial('validate-password')->getContentForJs(); ?>",
});

$(document).ready(function(){
     $('[data-hook="registration-toggle-is_ubo"]').trigger('change');
     $('[data-hook="registration-toggle-is_pseudo_ubo"]').trigger('change');
});