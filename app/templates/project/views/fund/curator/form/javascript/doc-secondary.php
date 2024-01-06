// VARS
let form = document.querySelector('[data-hook="form-curator"]');
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
                'Curator[name]': {
                    required: true
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
            element.textContent = '<?= t("CURATOR_DETAILS"); ?>'; break;
            break;
    }
}
function getStepTotal()
{
    return steps;
}
function retrieveSections() {
    let generalSections = ['general'];
    let additionalSections = [];

    return generalSections.concat(additionalSections);
}

// LISTENERS
document.addEventListener('DOMContentLoaded', pageFormReady);

// EXECUTE
