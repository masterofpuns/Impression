// VARS
let form = document.querySelector('[data-hook="form-foundation"]');
let steps = 1;

// INCLUDES
<?php
$app->renderJavascript($app->doc->environment->template->getPath() . '/partials/js/handle-base_functionalities_form.js');
$app->renderJavascript($app->doc->environment->template->getPath() . '/partials/js/handle-invalid_form.js');
?>

// FUNCTIONS
function pageFormReady()
{
    <?php if (!empty($section) && !empty($step)) { ?>
    // handle step
    handleSteps(<?=$step;?>);
    handleFormControl(<?=$step;?>);
    <?php } else { ?>
    handleSubTitle(1);
    <?php }  ?>
}
function determineRules(step)
{
    // reset rules
    rules = {};

    // determines rules based on step and relation type
    switch (step) {
        case 1:
            Object.assign(rules, {
                'Foundation[name]': {
                    required: true
                }
            });
            Object.assign(rules, {
                'Foundation[BankAccount][ascription]': {
                    required: function() {
                        return form.querySelector('[name="Foundation[BankAccount][iban]"]').value ? true : false;
                    }
                }
            });
            break;
    }

    return rules;
}
function handleSubTitle(step)
{
    let element = form.querySelector('[data-hook="form-sub_title"] span');
    element.textContent = "";

    switch (step) {
        case 1:
            element.textContent = '<?= t("FOUNDATION_DETAILS"); ?>'; break;
            break;
    }
}
function getStepTotal()
{
    return steps;
}
function retrieveSections() {
    let generalSections = ['general', 'details', 'bank-account'];
    let additionalSections = [];

    return generalSections.concat(additionalSections);
}

// LISTENERS
document.addEventListener('DOMContentLoaded', pageFormReady);

// EXECUTE
