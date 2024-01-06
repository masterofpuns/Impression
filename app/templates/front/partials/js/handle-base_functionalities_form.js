// VARS
let btnCancel = form.querySelector('[data-hook="form-cancel"]');
let btnPrev = form.querySelector('[data-hook="form-prev"]');
let btnNext = form.querySelector('[data-hook="form-next"]');
let btnSubmit = form.querySelector('[data-hook="form-submit"]');

let stepContainer = form.querySelector('[data-hook="form-nav_step_container"]');

let rules = {};

// FUNCTIONS
function handleNext(event)
{
    let currentStep = parseInt(stepContainer.querySelector('.active').getAttribute('data-step'));
    let nextStep = parseInt(currentStep) + 1;

    let currentRules = determineRules(currentStep);
    let formValidator = new BluemindsFormValidator(form, currentRules);

    if (formValidator.validate()) {
        handleSteps(nextStep);
        handleFormControl(nextStep);
        window.scrollTo({ top: 0, behavior: 'instant' });
    } else {
        scrollToInvalid();
    }

}
function handlePrev(event)
{
    let currentStep = stepContainer.querySelector('.active').getAttribute('data-step');
    let prevStep = parseInt(currentStep) - 1;
    window.scrollTo({ top: 0, behavior: 'instant' });
    handleSteps(prevStep);
    handleFormControl(prevStep);

}
function handleCancel(event)
{

}
function handleSubmit(event)
{
    let currentStep = parseInt(stepContainer.querySelector('.active').getAttribute('data-step'));
    let currentRules = determineRules(currentStep);

    let formValidator = new BluemindsFormValidator(form, currentRules);

    if (formValidator.validate()) {
        form.submit();
        window.scrollTo({ top: 0, behavior: 'instant' });
    } else {
        scrollToInvalid()
    }
}
function handleSteps(step)
{
    resetSteps();

    // check whether we need to configure steps for collective
    if (typeof prepareSteps === 'function') {
        prepareSteps();
    }

    // set active step
    stepContainer.querySelector('[data-step="'+step+'"]').classList.add('active');

    // handle steps that are done
    for (let i = step - 1; i > 0; i--)
    {
        stepContainer.querySelector('[data-step="'+i+'"]').classList.add('done');

        let checkMark = document.createElement("i");
        checkMark.className = "bi bi-check";
        checkMark.style.color = "black";
        stepContainer.querySelector('[data-step="'+i+'"]').textContent = '';
        stepContainer.querySelector('[data-step="'+i+'"]').appendChild(checkMark);
    }

    // handle show step
    form.querySelector('[data-hook="form-steps"][data-step="'+step+'"]').classList.remove('hide');

    // apply form config to show / hide corresponding elements based on relation type
    handleFormSections();

    // apply subtitle for step and relation type
    handleSubTitle(step);
}
function resetSteps()
{
    // reset all steps
    stepContainer.querySelectorAll('[data-step]').forEach(button => {
        button.classList.remove('active');
        button.classList.remove('done');
        button.textContent = button.getAttribute("data-step");
    });
    form.querySelectorAll('[data-hook="form-steps"]').forEach(container => {
        container.classList.add('hide');
    });

    // reset validation
    resetValidation();
}
function handleFormControl(step)
{
    btnCancel.classList.remove('hide');
    btnNext.classList.add('hide');
    btnPrev.classList.add('hide');
    btnSubmit.classList.add('hide');

    // check step total for type
    let stepTotal = getStepTotal();

    <?php if (!empty($section) && !empty($step)) { ?>
    btnSubmit.classList.remove('hide');
    <?php } else { ?>
    // handle step logiks
    if (step === 1) {
        btnNext.classList.remove('hide');
    } else if (step === stepTotal) {
        btnPrev.classList.remove('hide');
        btnSubmit.classList.remove('hide');
    } else {
        btnPrev.classList.remove('hide');
        btnNext.classList.remove('hide');
        btnCancel.classList.add('hide');
    }
    <?php } ?>
}
function resetValidation()
{
    form.querySelectorAll('.is-invalid').forEach(element => {
        element.classList.remove('is-invalid');
    });
}
function handleFormSections()
{
    resetFormSections();

    let sections = retrieveSections();
    sections.forEach(identifier => {
        let sections = form.querySelectorAll('[data-section="'+identifier+'"]');
        sections.forEach(section => {
            showSection(section);
        });
    });
}
function resetFormSections()
{
    let sections = form.querySelectorAll('[data-section]');
    sections.forEach(section => {
        hideSection(section);
    });
}
function showSection(section)
{
    if (section.classList.contains('hide')) {
        section.classList.remove('hide');
        section.querySelectorAll("input, select").forEach(element => {
            element.removeAttribute('disabled');
        });
        section.querySelectorAll('[data-section]').forEach(subSection => {
            subSection.classList.remove('hide');
        })

        if (typeof handleAdditionalContactPersonActions === 'function') {
            handleAdditionalContactPersonActions(section);
        }
    }
}
function hideSection(section)
{
    section.classList.add('hide');
    section.querySelectorAll("input, select").forEach(element => {
        element.setAttribute('disabled', true);
    })
}
function getCurrentStep()
{
    let currentStepContainer = form.querySelector('[data-hook="form-steps"]:not(.hide)');
    return parseInt(currentStepContainer.getAttribute('data-step'));
}
function invalidateSection(section, message)
{
    section.classList.add('is-invalid');
    if (message !== null && section.querySelector('[data-section] [data-hook="form-section-invalid_message"]')) {
        section.querySelector('[data-section] [data-hook="form-section-invalid_message"]').innerHTML = message;
    }
}

// LISTENERS
if (btnNext !== null) {
    btnNext.addEventListener('click', handleNext);
}
if (btnPrev !== null) {
    btnPrev.addEventListener('click', handlePrev);
}
if (btnSubmit !== null) {
    btnSubmit.addEventListener('click', handleSubmit);
}
