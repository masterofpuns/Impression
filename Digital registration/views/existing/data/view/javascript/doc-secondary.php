// INCLUDES
<?
$app->renderPartial('registration/javascript/registration-general_functions');
$app->renderPartial(
    'relation/javascript/relation',
    ['phoneNumberTypes' => $app->getPhoneNumberTypes()]
);
?>
<? if ($relation->type == 'organization'): ?>
var rulesFileUpload = {
    <?
        foreach ($relation->getObject()->getManagerRelations() as $managerRelation):
    ?>
    'Registration[ContactPerson][<?= $managerRelation->getObject()->id; ?>][otherEmployment]': {
        required: {
            depends: function(element) {
                var name = $(element).attr('name');
                var toggleElement = $('[data-hook="toggle-employment"][data-toggle="'+name+'"]');
                var needsValue = parseInt(toggleElement.find(":selected").val());
                if (needsValue) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    },
    <? endforeach; ?>
};
$.extend(rulesForm, rulesFileUpload);
validator.settings.rules = rulesForm;
<? endif; ?>

//FUNCTIONS
function showEmploymentField(e) {
    var hasOtherEmployment = parseInt($(this).find('option:selected').val());
    $(this).closest('[data-replace-identifier="manager"]').find('[data-hook="show-employment"]').addClass('hide');
    if(hasOtherEmployment) {
        $(this).closest('[data-replace-identifier="manager"]').find('[data-hook="show-employment"]').removeClass('hide');
    } else {
        // Veld leegmaken anders wordt dit alsnog opgeslagen in POST
        $(this).closest('[data-replace-identifier="manager"]').find('[data-hook="input-employment"]').val('');
    }
}
function bindFields() {
    var name = $(this).attr('data-dummy');
    var value = $(this).val();
    var targetField = $('[data-hook="registration-form"]').find($('[name="'+name+'"]'));
    targetField.val(value);
}
function toggleHasVisitingAddress(contactPersonId) {
    var dummyElement = $('[data-dummy="Registration[ContactPerson]['+contactPersonId+'][hasVisitingAddress]"]');
    var hiddenElement = $('[name="Registration[ContactPerson]['+contactPersonId+'][hasVisitingAddress]"]');

    if (dummyElement.val()) { hiddenElement.val(dummyElement.val()); }
}

//LISTENERS
$('[data-hook="bind-fields"] select, [data-hook="bind-fields"] input').change(bindFields);
$('body').on('change', '[data-hook="toggle-employment"]', showEmploymentField);

//EXECUTE
validator.settings.errorPlacement = function(error, element) {
    var fieldName = element.attr('name');
    var targetError = $('[data-hook="bind-fields"] [data-dummy="'+fieldName+'"]');
    targetError.next('.error').remove();
    error.insertAfter(targetError);
}
validator.settings.success = function(label, element) {
    var fieldName = $(element).attr('name');
    var targetError = $('[data-hook="bind-fields"] [data-dummy="'+fieldName+'"]');
    targetError.next('.error').remove();
}
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
});