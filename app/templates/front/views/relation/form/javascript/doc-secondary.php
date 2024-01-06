// VARS
let form = document.querySelector('[data-hook="form-relation"]');

let steps = {
    'INDIVIDUAL': <?= !empty($relation->id) ? 3 : 4 ?>,
    'ORGANIZATION': <?= !empty($relation->id) ? 3 : 4 ?>,
    'COLLECTIVE': <?= !empty($relation->id) ? 3 : 5 ?>,
    'ADVISOR': 2
};

let relationTypeSelected = form.querySelector('[name="Relation[type]"]:checked');
let relationTypeElement = form.querySelectorAll('[name="Relation[type]"]');
let relationType;

let emailAddressElement = form.querySelector('[data-hook="form-email_address-placeholder"]');
let phoneNumberElement = form.querySelector('[data-hook="form-phone_number-placeholder"]');
let contactPersonElement = form.querySelector('[data-hook="form-contact_person-placeholder"]');

let postalAddressEqualsVisitingAddress = form.querySelector('[data-hook="form_relation-postal_address-postal_address_equals_visiting_address"]');
let postalAddressContainer = form.querySelector('[data-hook="form-postal_address_container"]');

// INCLUDES
<?php
$app->renderJavascript($app->doc->environment->template->getPath() . '/partials/js/handle-base_functionalities_form.js', ['section' => $section ?? null, 'step' => $step ?? null]);
$app->renderJavascript($app->doc->environment->template->getPath() . '/partials/js/handle-phonenumber.js');
$app->renderJavascript($app->doc->environment->template->getPath() . '/partials/js/handle-emailaddress.js');
$app->renderJavascript($app->doc->environment->template->getPath() . '/partials/js/handle-remove_file_template.js');
$app->renderJavascript($app->doc->environment->template->getPath() . '/partials/js/handle-participant.js');
$app->renderJavascript($app->doc->environment->template->getPath() . '/partials/js/handle-invalid_form.js');
?>

// FUNCTIONS
function pageFormReady()
{
    // remove all placeholders from DOM
    emailAddressElement.remove();
    phoneNumberElement.remove();
    contactPersonElement.remove();

    postalAddressEqualsVisitingAddress.dispatchEvent(new Event('change'));

    handleVisitingAddress(form.querySelector('[data-section="visiting-address"]'));

    <?php if (!empty($relation) && !empty($relation->typeObject) && !empty($relation->typeObject->idFile)) { ?>
    handleRemoveFileTemplate('Relation[idFile]', 'idFile','<?= addslashes($relation->typeObject->idFile->filename) ?>')
    <? } ?>

    document.querySelectorAll('[data-hook="remove_file_template-delete"]').forEach(removeFileTemplateDelete => {
        removeFileTemplateDelete.addEventListener('click', handleRemoveFileTemplateDelete);
    });

    if (relationTypeSelected !== null) {
        relationTypeSelected.dispatchEvent(new Event('change'));
    }

    <?php if (!empty($section) && !empty($step)) { ?>
    // handle step
    handleSteps(<?=$step;?>);
    handleFormControl(<?=$step;?>);
    <?php } ?>
}
function addContactPerson(event)
{
    let section = event.target.closest('[data-section]');
    let contactPersonContainer = section.querySelector('[data-hook="form-contact_person_container"]');
    let totalContactPersons = form.querySelectorAll('[data-hook="form-contact_person"]');
    let contactPersonIndex = 0;
    if (totalContactPersons) {
        totalContactPersons.forEach(contactPerson => {
            contactPersonIndex = parseInt(contactPerson.getAttribute('data-contact_person_index')) + 1;
        });
    }

    let contactPersonClone = contactPersonElement.cloneNode(true);
    contactPersonClone.setAttribute('data-hook', contactPersonClone.getAttribute('data-hook').replace('-placeholder', ''));
    contactPersonClone.setAttribute('data-contact_person_index', contactPersonIndex);
    contactPersonClone.innerHTML = contactPersonClone.innerHTML.replaceAll("CP_IDX", contactPersonIndex);

    let variableContactPersonSections = {
        'proxy': contactPersonClone.querySelector('[data-form_group="proxy"]'),
        'manager': contactPersonClone.querySelector('[data-form_group="manager"]'),
        'participant': contactPersonClone.querySelector('[data-form_group="participant"]'),
        'correspondence': contactPersonClone.querySelector('[data-form_group="correspondence"]'),
        'participantHidden': contactPersonClone.querySelector('[data-form_group="hidden-participant"]'),
        'correspondenceHidden': contactPersonClone.querySelector('[data-form_group="hidden-correspondence"]'),
        'managerHidden': contactPersonClone.querySelector('[data-form_group="hidden-manager"]')
    };

    // check whether or not we need to show additional type fields
    let variableContactPersonSectionsToShow = [];
    let formGroups;
    switch (relationType) {
        case 'INDIVIDUAL':
            formGroups = contactPersonClone.querySelectorAll('[data-form_group="proxy"], [data-form_group="correspondence"]');
            variableContactPersonSectionsToShow = ['proxy', 'correspondence'];
            break;
        case 'ORGANIZATION':
            formGroups = contactPersonClone.querySelectorAll('[data-form_group="manager"], [data-form_group="correspondence"]');
            if (totalContactPersons.length !== 0) {
                variableContactPersonSectionsToShow = ['manager', 'correspondence', 'correspondenceHidden'];
            } else {
                variableContactPersonSectionsToShow = ['managerHidden', 'correspondenceHidden'];
            }
            break;
        case 'COLLECTIVE':
            switch (getCurrentStep()) {
                case 2:
                    formGroups = contactPersonClone.querySelectorAll('[data-form_group="hidden-participant"], [data-form_group="hidden-correspondence"]');
                    variableContactPersonSectionsToShow = ['participantHidden', 'correspondenceHidden'];
                    contactPersonClone.querySelector('#form_relation-contact_person-hidden_participant').value = 1;
                    break;
                case 5:
                    formGroups = contactPersonClone.querySelectorAll('[data-form_group="hidden-participant"], [data-form_group="proxy"], [data-form_group="hidden-correspondence"]');
                    variableContactPersonSectionsToShow = ['participantHidden', 'proxy', 'correspondence'];
                    contactPersonClone.querySelector('#form_relation-contact_person-hidden_participant').value = 0;
                    break;
            }
            break;
        case 'ADVISOR':
            break;
    }

    Object.keys(variableContactPersonSections).forEach(section => {
        if (variableContactPersonSectionsToShow.includes(section)) { return; }
        variableContactPersonSections[section].remove();
    });

    if (formGroups !== null) {
        formGroups.forEach(formGroup => {
            formGroup.classList.remove('hide');
            formGroup.querySelectorAll('input, select').forEach(input => {
                input.removeAttribute('disabled');
            });
        })
    }

    // append clone to container
    contactPersonContainer.append(contactPersonClone);

    contactPersonClone.querySelector('[data-hook="form-add_phone_number"]').addEventListener('click', addPhoneNumber);
    contactPersonClone.querySelector('[data-hook="form-add_email_address"]').addEventListener('click', addEmailAddress);
    contactPersonClone.querySelector('[data-hook="form-contact_person-delete"]').addEventListener('click', deleteContactPerson);
    if (contactPersonClone.querySelector('[data-hook="form_relation-contact_person-is_manager"]')) {
        contactPersonClone.querySelectorAll('[data-hook="form_relation-contact_person-is_manager"]').forEach(radio => {
            radio.addEventListener('change', handleIsManager);
        });
    }

    <?php if (empty($relation) || empty($relation->id)) { ?>
    if (
        (
            !totalContactPersons.length &&
            section.getAttribute("data-section") === 'contact-persons' &&
            relationType === 'ORGANIZATION'
        ) ||
        (
            totalContactPersons.length < 2 &&
            section.getAttribute("data-section") === 'collective' &&
            relationType === 'COLLECTIVE'
        )
    ) {
        contactPersonClone.querySelector('[data-hook="form-contact_person-delete"]').remove();
    }
    <?php } ?>

    if (contactPersonClone.querySelector('[data-form_group="participant"] input[type="radio"]:checked') !== null)
    {
        contactPersonClone.querySelector('[data-form_group="participant"] input[type="radio"]:checked').dispatchEvent(new Event('change'));
    }
    if (contactPersonClone.querySelector('[data-form_group="manager"] input[type="radio"]:checked') !== null)
    {
        contactPersonClone.querySelector('[data-form_group="manager"] input[type="radio"]:checked').dispatchEvent(new Event('change'));
    }


    contactPersonClone.querySelectorAll('.datepicker').forEach(datePickerElement => {
        datepicker(
            datePickerElement,
            {
                formatter: (input, date, instance) => {
                    const value = date.toLocaleDateString()
                    input.value = value // => '1/1/2099'
                }
            }
        );
    });
    contactPersonClone.querySelectorAll('[data-form_group="participant"] input[type="radio"]').forEach(input => {
        input.addEventListener('change', handleParticipantSettings);
    })
}
function deleteContactPerson(event)
{
    this.closest('[data-hook="form-contact_person"]').remove();
}
function handlePostalAddress()
{
    switch (postalAddressEqualsVisitingAddress.checked) {
        case true:
            postalAddressContainer.classList.add('hide');
            break;
        case false:
            postalAddressContainer.classList.remove('hide');
            break;
    }
}
function determineRules(step)
{
    // reset rules
    rules = {};

    // determines rules based on step and relation type
    switch (step) {
        case 1:
            Object.assign(rules, {
                'Relation[type]': {
                    required: true
                }
            });
            break;
        case 2:
            switch (relationType) {
                case 'INDIVIDUAL':
                    Object.assign(rules, {
                        'Relation[Individual][salutationId]': {
                            required: true
                        },
                        'Relation[Individual][initials]': {
                            required: true
                        },
                        'Relation[Individual][lastName]': {
                            required: true
                        },
                        'Relation[VisitingAddress][street]': {
                            required: true
                        },
                        'Relation[VisitingAddress][number]': {
                            required: true
                        },
                        'Relation[VisitingAddress][postalCode]': {
                            required: true
                        },
                        'Relation[VisitingAddress][city]': {
                            required: true
                        },
                        'Relation[VisitingAddress][countryId]': {
                            required: true
                        }
                    });

                    // check whether phonenumbers are present
                    //validatePhoneNumbers(step);
                    break;
                case 'ORGANIZATION':
                    Object.assign(rules, {
                        'Relation[Organization][name]': {
                            required: true
                        },
                        'Relation[VisitingAddress][street]': {
                            required: true
                        },
                        'Relation[VisitingAddress][number]': {
                            required: true
                        },
                        'Relation[VisitingAddress][postalCode]': {
                            required: true
                        },
                        'Relation[VisitingAddress][city]': {
                            required: true
                        },
                        'Relation[VisitingAddress][countryId]': {
                            required: true
                        }
                    });

                    // check whether phonenumbers are present
                    //validatePhoneNumbers(step);
                    break;
                case 'COLLECTIVE':
                    <?php if (empty($relation->id)) { ?>
                    // check whether contactpersons are present
                    validateContactPersons(step);
                    <?php } else { ?>
                    Object.assign(rules, {
                        'Relation[VisitingAddress][street]': {
                            required: true
                        },
                        'Relation[VisitingAddress][number]': {
                            required: true
                        },
                        'Relation[VisitingAddress][postalCode]': {
                            required: true
                        },
                        'Relation[VisitingAddress][city]': {
                            required: true
                        },
                        'Relation[VisitingAddress][countryId]': {
                            required: true
                        }
                    });
                    <?php } ?>
                    break;
                case 'ADVISOR':
                    Object.assign(rules, {
                        'Relation[Advisor][salutationId]': {
                            required: true
                        },
                        'Relation[Advisor][initials]': {
                            required: true
                        },
                        'Relation[Advisor][lastName]': {
                            required: true
                        },
                    });
                    break;
            }
            break;
        case 3:

            switch (relationType) {
                case 'INDIVIDUAL':
                    Object.assign(rules, {
                        'Relation[General][correspondenceType]': {
                            required: true,
                            conditional: additionalCorrespondenceValidation
                        },
                        'Relation[General][language]': {
                            required: true
                        },
                    });
                    break;
                case 'ORGANIZATION':
                    Object.assign(rules, {
                        'Relation[General][correspondenceType]': {
                            required: true,
                            conditional: additionalCorrespondenceValidation
                        },
                        'Relation[General][language]': {
                            required: true
                        },
                    });
                    break;
                case 'COLLECTIVE':
                    <?php if (empty($relation->id)) { ?>
                    // check whether contactpersons are present
                    Object.assign(rules, {
                        'Relation[VisitingAddress][street]': {
                            required: true
                        },
                        'Relation[VisitingAddress][number]': {
                            required: true
                        },
                        'Relation[VisitingAddress][postalCode]': {
                            required: true
                        },
                        'Relation[VisitingAddress][city]': {
                            required: true
                        },
                        'Relation[VisitingAddress][countryId]': {
                            required: true
                        }
                    });
                    <?php } else { ?>
                    Object.assign(rules, {
                        'Relation[General][correspondenceType]': {
                            required: true,
                            conditional: additionalCorrespondenceValidation
                        },
                        'Relation[General][language]': {
                            required: true
                        },
                    });
                    <?php } ?>
                    break;
                case 'ADVISOR':
                    break;
            }

            break;
        case 4:
            switch (relationType) {
                case 'INDIVIDUAL':
                    // check whether contactpersons are present
                    validateContactPersons(step);
                    break;
                case 'ORGANIZATION':
                    // check whether contactpersons are present
                    validateContactPersons(step);
                    break;
                case 'COLLECTIVE':
                    <?php if (empty($relation->id)) { ?>
                    Object.assign(rules, {
                        'Relation[General][correspondenceType]': {
                            required: true,
                            conditional: additionalCorrespondenceValidation
                        },
                        'Relation[General][language]': {
                            required: true
                        },
                    });
                    <?php } ?>
                    break;
                case 'ADVISOR':
                    break;
            }
            break;
        case 5:

            switch (relationType) {
                case 'INDIVIDUAL':
                    break;
                case 'ORGANIZATION':
                    break;
                case 'COLLECTIVE':
                    <?php if (empty($relation->id)) { ?>
                    // check whether contactpersons are present
                    validateContactPersons(step);
                    <?php } ?>
                    break;
                case 'ADVISOR':
                    break;
            }

            break;
    }

    return rules;
}
function getStepTotal()
{
    return steps[relationType];
}
function validatePhoneNumbers(step)
{
    let phoneNumberContainer = form.querySelector(`[data-step="${step}"] [data-hook="form-phone_number_container"]`);
    let phoneNumbers = phoneNumberContainer.querySelectorAll(`[data-hook="form-phone_number"]`);
    let section = phoneNumberContainer.parentNode;

    section.classList.remove('is-invalid');
    if (!phoneNumbers.length) {
        section.classList.add('is-invalid');
    } else {
        phoneNumbers.forEach((phoneNumber, idx) => {
            Object.assign(rules, {
                [`Relation[PhoneNumbers][${idx}][number]`]: {
                    required: true
                }
            });
        });
    }
}
function validateContactPersons(step)
{
    let contactPersonContainer = form.querySelector(`[data-step="${step}"] [data-hook="form-contact_person_container"]`);
    let contactPersons = contactPersonContainer.querySelectorAll(`[data-hook="form-contact_person"]`);
    let section = contactPersonContainer.parentNode;
    let totalContactPersons = contactPersons.length;

    section.classList.remove('is-invalid');
    if (section.querySelector('[data-section] [data-hook="form-section-invalid_message"]')) {
        section.querySelector('[data-section] [data-hook="form-section-invalid_message"]').innerHTML = "";
    }


    if (!totalContactPersons && (relationType === 'ORGANIZATION' || (relationType === 'COLLECTIVE' && step === 2)))
    {
        invalidateSection(section, '<?= t('INVALID_CONTACT_PERSONS_NO_ENTRIES'); ?>');
    }
    else if (contactPersons.length < 2 && relationType === 'COLLECTIVE' && step === 2)
    {
        invalidateSection(section, '<?= t('INVALID_CONTACT_PERSONS_MINIMUM_OF_TWO'); ?>');
    }
    else
    {
        contactPersons.forEach(contactPerson => {
            let idx = contactPerson.getAttribute('data-contact_person_index');

            Object.assign(rules, {
                [`ContactPersons[${idx}][salutationId]`]: {
                    required: true
                },
                [`ContactPersons[${idx}][initials]`]: {
                    required: true
                },
                [`ContactPersons[${idx}][lastName]`]: {
                    required: true
                }
            });

            switch (relationType) {
                case 'INDIVIDUAL':
                    Object.assign(rules, {
                        [`ContactPersons[${idx}][isProxy]`]: {
                            required: true
                        }
                    });
                    break;
                case 'ORGANIZATION':
                    Object.assign(rules, {
                        [`ContactPersons[${idx}][isManager]`]: {
                            required: true
                        }
                    });
                    break;
                case 'COLLECTIVE':
                    switch (step) {
                        case 2:
                            Object.assign(rules, {
                                [`ContactPersons[${idx}][isParticipant]`]: {
                                    required: true
                                }
                            });
                            break;
                        case 5:
                            Object.assign(rules, {
                                [`ContactPersons[${idx}][isProxy]`]: {
                                    required: true
                                }
                            });
                            break;
                    }
                    break;
            }
        });
    }
}
function retrieveSections()
{
    let generalSections = ['relation-type', 'relation-category', 'general', 'bank-account'];
    let additionalSections = [];

    switch(relationType) {
        case 'INDIVIDUAL':
            additionalSections = ['individual', 'phonenumbers', 'email-addresses', 'visiting-address', 'postal-address', 'identification', 'contact-persons'];
            break;
        case 'ORGANIZATION':
            additionalSections = ['organization', 'phonenumbers', 'email-addresses', 'visiting-address', 'postal-address', 'chamber-of-commerce', 'contact-persons'];
            break;
        case 'COLLECTIVE':
            additionalSections = ['collective', 'visiting-address', 'postal-address', 'contact-persons'];
            break;
        case 'ADVISOR':
            generalSections = ['relation-type'];
            additionalSections = ['advisor', 'phonenumbers', 'email-addresses', 'visiting-address', 'postal-address', 'identification'];
            break;
    }

    return generalSections.concat(additionalSections);
}
function handleRelationType(event)
{
    if (typeof relationType !== 'undefined' && relationType !== this.value) {
        // clear any contactpersons that have been set previously
        clearContactPersons();
    }
    relationType = this.value;

    // toggle relation category section
    let section = form.querySelector('[data-section="relation-category"]');

    hideSection(section);
    if (relationType !== 'ADVISOR') {
        showSection(section);
    }

    // determine whether we need to show additional steps
    determineTotalSteps();

    handleVisitingAddress(form.querySelector('[data-section="visiting-address"]'));
}
function determineTotalSteps()
{
    // determine whether to show 4th step or not=
    let stepTotal = getStepTotal();

    form.querySelectorAll('[data-hook="form-nav_step_container"] [data-step]').forEach(btn => {
        let step = btn.getAttribute('data-step');

        btn.classList.add('hide');
        if (step <= stepTotal)
        {
            btn.classList.remove('hide');
        }
    });
}
function handleSubTitle(step)
{
    let element = form.querySelector('[data-hook="form-sub_title"] span');
    element.textContent = "";

    switch (relationType) {
        case 'INDIVIDUAL':
            switch(step) {
                case 2: element.textContent = '<?= t("PARENT_RELATION"); ?>'; break;
                case 3: element.textContent = '<?= t("GENERAL_DATA"); ?>'; break;
                case 4: element.textContent = '<?= t("CONTACT_PERSONS"); ?>'; break;
            }
            break;
        case 'ORGANIZATION':
            switch(step) {
                case 2: element.textContent = '<?= t("COMPANY_DATA"); ?>'; break;
                case 3: element.textContent = '<?= t("GENERAL_DATA"); ?>'; break;
                case 4: element.textContent = '<?= t("CONTACT_PERSONS"); ?>'; break;
            }
            break;
        case 'COLLECTIVE':
            switch(step) {
                case 2: element.textContent = '<?= !empty($relation->id) ? t("ADRES_DATA") : t("PARTICIPATING_CONTACT_PERSONS"); ?>'; break;
                case 3: element.textContent = '<?= !empty($relation->id) ? t("GENERAL_DATA") : t("ADRES_DATA"); ?>'; break;
                case 4: element.textContent = '<?= t("GENERAL_DATA"); ?>'; break;
                case 5: element.textContent = '<?= t("CONTACT_PERSONS"); ?>'; break;
            }
            break;
        case 'ADVISOR':
            switch(step) {
                case 2: element.textContent = '<?= t("CONTACT_DATA"); ?>'; break;
            }
            break;
    }
}
function clearContactPersons()
{
    let sections = form.querySelectorAll('[data-section="contact-persons"], [data-section="collective"]');
    sections.forEach(section => {
        let contactPersonsContainer = section.querySelector('[data-hook="form-contact_person_container"]');
        if (contactPersonsContainer !== null)
        {
            contactPersonsContainer.innerHTML = "";
        }
    });
}
function handleIsManager(event)
{
    let contactPersonSection = this.closest('[data-hook="form-contact_person"]');
    switch (parseInt(this.value)) {
        case 1:
            hideSection(contactPersonSection.querySelector('[data-form_group="correspondence"]'));
            showSection(contactPersonSection.querySelector('[data-form_group="hidden-correspondence"]'));
            break;
        case 0:
            hideSection(contactPersonSection.querySelector('[data-form_group="hidden-correspondence"]'));
            showSection(contactPersonSection.querySelector('[data-form_group="correspondence"]'));
            break;
    }
}
function additionalCorrespondenceValidation()
{
    let emailAddresses = [];
    switch (relationType) {
        case 'INDIVIDUAL':
        case 'ORGANIZATION':
            if (form.querySelectorAll('[name^="Relation[EmailAddresses]"]').length) {
                form.querySelectorAll('[name^="Relation[EmailAddresses]"]').forEach(emailAddress => {
                    if (emailAddress.value !== '') {
                        emailAddresses.push(emailAddress);
                    }
                });
            }
            break;
        case 'COLLECTIVE':
            // in case we are editting a collective we retrieve any emailaddresses stored with saved contact persons
            // because contact persons steps will not be present when editting the main relation and therefor this check
            // would otherwise always return false
            <?php
            if (!empty($relation) && !empty($relation->id))
            {
                foreach ($relation->typeObject->getParticipatingContactPersons() as $participatingContactPersonRelation)
                {
                    if (!empty($participatingContactPersonRelation->emailAddresses))
                    {
                        foreach ($participatingContactPersonRelation->emailAddresses as $emailAddress)
                        {
                            ?>
                            emailAddresses.push('<?= $emailAddress->address; ?>');
                            <?php
                        }
                    }
                }
            }
            else
            {
                ?>
                let contactPersons = form.querySelectorAll('[data-hook="form-contact_person"]');
                contactPersons.forEach(contactPerson => {
                    let index = parseInt(contactPerson.getAttribute('data-contact_person_index'));

                    if (
                        contactPerson.querySelectorAll(`[data-form_group="hidden-participant"]`).length &&
                        parseInt(contactPerson.querySelector('#form_relation-contact_person-hidden_participant').value) &&
                        contactPerson.querySelectorAll('[name^="ContactPersons['+index+'][EmailAddresses]"]').length
                    ) {
                        contactPerson.querySelectorAll('[name^="ContactPersons['+index+'][EmailAddresses]"]').forEach(emailAddress => {
                            if (emailAddress.value !== '') {
                                emailAddresses.push(emailAddress);
                            }
                        });
                    }
                });
                <?php
                }
            ?>
            break;
    }

    console.log(emailAddresses);

    let checkedElement = form.querySelector('[name="Relation[General][correspondenceType]"]:checked');
    let result = {success: true, message: ''};

    if (checkedElement !== null && checkedElement.value === 'DIGITAL' && !emailAddresses.length) {
        result.success = false;
        result.message = 'Voor deze optie dient ten minste één e-mailadres in te zijn gevuld';
    }

    return result;
}
function prepareSteps()
{
    let collectiveStep = form.querySelector('[data-hook="form-steps"][data-step="collective"]');
    form.querySelectorAll('[data-hook="form-steps"]').forEach(container => {
        let step = container.getAttribute('data-step');

        if (step == 1) { return; }
        <?php if (empty($relation->id)) { ?>
            if (relationType == 'COLLECTIVE') {
                if (collectiveStep) {
                    switch (container.getAttribute('data-step')) {
                        case 'collective':
                            step = 2;
                            break;
                        default:
                            step = parseInt(container.getAttribute('data-step')) + 1;
                            break;
                    }
                }
            } else {
                if (!collectiveStep) {
                    switch (container.getAttribute('data-step')) {
                        case '2':
                            step = 'collective';
                            break;
                        default:
                            step = parseInt(container.getAttribute('data-step')) - 1;
                            break;
                    }
                }
            }
            <?php } ?>

        container.setAttribute('data-step', step);

    });
}
function handleAdditionalContactPersonActions(section)
{
    // check if section involves contact persons
    <?php if (empty($relation) || empty($relation->id)) { ?>
    if (!section.querySelectorAll('[data-hook="form-contact_person"]').length) {
        if (section.getAttribute("data-section") === 'contact-persons' && relationType === 'ORGANIZATION') {
            section.querySelector('[data-hook="form-add_contact_person"]').dispatchEvent(new Event('click'));
        }
        if (section.getAttribute("data-section") === 'collective' && relationType === 'COLLECTIVE') {
            section.querySelector('[data-hook="form-add_contact_person"]').dispatchEvent(new Event('click'));
            section.querySelector('[data-hook="form-add_contact_person"]').dispatchEvent(new Event('click'));
        }
    }
    <?php } ?>
}
function handleVisitingAddress(section)
{
    section.querySelectorAll('[data-hook="required"]').forEach(span => {
        if (relationType === 'ADVISOR') {
            span.classList.add('hide');
        } else {
            span.classList.remove('hide');
        }
    });
}

// LISTENERS
document.addEventListener('DOMContentLoaded', pageFormReady);
form.querySelectorAll('[data-hook="form-add_phone_number"]').forEach(btnAddPhoneNumber => {
    btnAddPhoneNumber.addEventListener('click', addPhoneNumber);
});
form.querySelectorAll('[data-hook="form-delete_phone_number"]').forEach(btnDeletePhoneNumber => {
    btnDeletePhoneNumber.addEventListener('click', deletePhoneNumber);
});
form.querySelectorAll('[data-hook="form-add_email_address"]').forEach(btnAddEmailAddress => {
    btnAddEmailAddress.addEventListener('click', addEmailAddress);
});
form.querySelectorAll('[data-hook="form-delete_email_address"]').forEach(btnDeleteEmailAddress => {
    btnDeleteEmailAddress.addEventListener('click', deleteEmailAddress);
});
form.querySelectorAll('[data-hook="form-add_contact_person"]').forEach(btnAddContactPerson => {
    btnAddContactPerson.addEventListener('click', addContactPerson);
});
postalAddressEqualsVisitingAddress.addEventListener('change', handlePostalAddress);
relationTypeElement.forEach(radio => {
    radio.addEventListener('change', handleRelationType);
})

// EXECUTE
