//INCLUDES
<? $app->renderPartial('registration/javascript/registration-general_functions'); ?>

//LISTENERS
$('[name="Declaration[acceptDeclaration]"]').change(function(){
    var checked = $(this);
    console.log('CHECK');
    console.log(checked.is(":checked"))
    if(checked.is(":checked")) {
        $('[data-hook="registration-submit_form"]').attr('disabled', false)
    } else {
        $('[data-hook="registration-submit_form"]').attr('disabled', true)
    }
});