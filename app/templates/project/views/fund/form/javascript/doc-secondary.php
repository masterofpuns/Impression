// VARS
let form = document.querySelector('[data-hook="form-fund"]');

let autocompleteCurator = form.querySelector('[data-hook="form-general-curator_name"]');
let autocompleteContainerCurator = form.querySelector('[data-hook="form-general-curator-autocomplete_container"]');
let autocompleteFoundation = form.querySelector('[data-hook="form-general-foundation_name"]');
let autocompleteContainerFoundation = form.querySelector('[data-hook="form-general-foundation-autocomplete_container"]');

let steps = 2;

// INCLUDES
<?php
$app->renderJavascript($app->doc->environment->template->getPath() . '/partials/js/handle-base_functionalities_form.js', ['section' => $section ?? null, 'step' => $step ?? null]);
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
function getStepTotal()
{
    return steps;
}
function determineRules(step)
{
    // reset rules
    rules = {};

    // determines rules based on step and relation type
    switch (step) {
        case 1:
            Object.assign(rules, {
                'Fund[name]': {
                    required: true
                }
            });
            break;
        case 2:
            Object.assign(rules, {
                'Fund[type]': {
                    required: true
                }
            });
            Object.assign(rules, {
                'Fund[BankAccount][ascription]': {
                    required: function() {
                        return form.querySelector('[name="Fund[BankAccount][iban]"]').value ? true : false;
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
            element.textContent = '<?= t("FUND_DETAILS"); ?>'; break;
            break;
        case 2:
            element.textContent = '<?= t("FUND_CONTENT"); ?>'; break;
            break;
    }
}
function retrieveSections() {
    let generalSections = ['general', 'details', 'bank-account'];
    let additionalSections = [];

    return generalSections.concat(additionalSections);
}
function handleAutocompleteCurator(event)
{
    let search = this.value;
    if (search.length > 2) {
        ajax({
            url: '<?= \app\m::app()->getDocByName('curator-autocomplete')->url; ?>',
            method: 'POST',
            data: { search: search },
            success: function(response) {
                if (response.success) {
                    if (typeof response.curators !== 'undefined' && Object.keys(response.curators).length > 0) {
                        handleAutocompleteSuggestions(response.curators, 'curator', autocompleteContainerCurator);
                    }
                }
            },
            error: function(error) {
                console.log('error');
                console.log(error);
            }
        });
    }
}
function handleAutocompleteFoundation(event)
{
    let search = this.value;
    if (search.length > 2) {
        ajax({
            url: '<?= \app\m::app()->getDocByName('foundation-autocomplete')->url; ?>',
            method: 'POST',
            data: {
                search: search
            },
            success: function(response) {
                if (response.success) {
                    if (typeof response.foundations !== 'undefined' && Object.keys(response.foundations).length > 0) {
                        handleAutocompleteSuggestions(response.foundations, 'foundation', autocompleteContainerFoundation);
                    }
                }
            },
            error: function(error) {
                console.log('error');
                console.log(error);
            }
        });
    }
}
function selectCurator(event)
{
    let curatorId = event.target.getAttribute('data-id');
    let curatorName = event.target.textContent;

    form.querySelector('[name="Fund[curatorName]"]').value = curatorName;
    form.querySelector('[name="Fund[curatorId]"]').value = curatorId;

    resetAutocompleteContainer(autocompleteContainerCurator);
}
function selectFoundation(event)
{
    let foundationId = event.target.getAttribute('data-id');
    let foundationName = event.target.textContent;

    form.querySelector('[name="Fund[foundationName]"]').value = foundationName;
    form.querySelector('[name="Fund[foundationId]"]').value = foundationId;

    resetAutocompleteContainer(autocompleteContainerFoundation);
}
function handleAutocompleteSuggestions(suggestions, type, autocompleteContainer)
{
    resetAutocompleteContainer(autocompleteContainer);

    Object.keys(suggestions).forEach(key => {
        let entity = suggestions[key];

        let element = document.createElement('li');
        element.classList.add('list-group-item');
        element.setAttribute('data-hook', `select-${type}`);
        element.setAttribute('data-id', entity.id);
        element.textContent = entity.name;

        switch (type) {
            case 'curator': element.addEventListener('click', selectCurator); break;
            case 'foundation': element.addEventListener('click', selectFoundation); break;
        }

        autocompleteContainer.append(element);
    });
}
function resetAutocompleteContainer(container)
{
    container.innerHTML = "";
}
function handleDocumentClickEvent(event) {
    if (!autocompleteContainerCurator.contains(event.target) && !autocompleteContainerFoundation.contains((event.target))){
        resetAutocompleteContainer(autocompleteContainerCurator);
        resetAutocompleteContainer(autocompleteContainerFoundation);
    }
}

// LISTENERS
document.addEventListener('DOMContentLoaded', pageFormReady);

autocompleteCurator.addEventListener('keyup', handleAutocompleteCurator);
autocompleteCurator.addEventListener('focus', handleAutocompleteCurator);

autocompleteFoundation.addEventListener('keyup', handleAutocompleteFoundation);
autocompleteFoundation.addEventListener('focus', handleAutocompleteFoundation);

document.addEventListener('click', handleDocumentClickEvent);

// EXECUTE
