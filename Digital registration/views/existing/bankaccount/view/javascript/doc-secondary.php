// INCLUDES
<? $app->renderPartial('registration/javascript/registration-general_functions'); ?>

// VARS
var rulesBankAccount = {
    'Registration[BankAccount][id]': {
        required: function(element) {
            return $('[name="Registration[BankAccount][iban]"]').is(":blank");
        }
    },
    'Registration[BankAccount][iban]': {
        required: function(element) {
            return $('[name="Registration[BankAccount][id]"]').val() == '';
        },
        noSpace: true,
        alphanumOnly: true
    },
    'Registration[BankAccount][ascription]': {
        required: function(element) {
            return $('[name="Registration[BankAccount][id]"]').val() == '';
        },
        noSpace: true,
        alphanumOnly: true
    },
};
$.extend(rulesForm, rulesBankAccount);
validator.settings.rules = rulesForm;

// FUNCTIONS
function handleBankAccountSelect(e) {
    var value = this.value
    var select = $('[name="Registration[BankAccount][id]"]');

    if (value !== '' && select.val() !== '') {
        select.val('');
    }
}
function handleNewBankAccount(e) {
    var value = this.value;
    var inputs = [
        '[name="Registration[BankAccount][iban]"]',
        '[name="Registration[BankAccount][ascription]"]'
    ];

    if (value !== '') {
        $.each(inputs, function(idx, input) {
            $(input).val('');
        });
    }
}

// LISTENERS
$('body').on('change, keyup', '[name="Registration[BankAccount][iban]"], [name="Registration[BankAccount][ascription]"]', handleBankAccountSelect);
$('body').on('change', '[name="Registration[BankAccount][id]"]', handleNewBankAccount);

// EXECUTE
