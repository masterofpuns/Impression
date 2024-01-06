// VARS
let formContactPersonRelation = document.querySelector('[data-hook="form-contact_person_relation"]');

let emailAddressElement = formContactPersonRelation.querySelector('[data-hook="form-email_address-placeholder"]');
let phoneNumberElement = formContactPersonRelation.querySelector('[data-hook="form-phone_number-placeholder"]');

let parentRelationType = '<?= $parentRelation->type ?>';

let btnSubmit = formContactPersonRelation.querySelector('button[type="submit"]');

// INCLUDES
<?php
$app->renderJavascript($app->doc->environment->template->getPath() . '/partials/js/handle-phonenumber.js');
$app->renderJavascript($app->doc->environment->template->getPath() . '/partials/js/handle-emailaddress.js');
$app->renderJavascript($app->doc->environment->template->getPath() . '/partials/js/handle-remove_file_template.js');
$app->renderJavascript($app->doc->environment->template->getPath() . '/partials/js/handle-participant.js');
$app->renderJavascript($app->doc->environment->template->getPath() . '/partials/js/handle-invalid_form.js');
?>

// FUNCTIONS
function pageFormContactPersonReady(event)
{
    emailAddressElement.remove();
    phoneNumberElement.remove();

    let section = formContactPersonRelation.querySelector('[data-section="contact-person"]');
    let variableContactPersonSections = {
        'delete': section.querySelector('[data-hook="form-contact_person-delete"]'),
        'proxy': section.querySelector('[data-form_group="proxy"]'),
        'manager': section.querySelector('[data-form_group="manager"]'),
        'participant': section.querySelector('[data-form_group="participant"]'),
        'participantHidden': section.querySelector('[data-form_group="hidden-participant"]')
    };
    let variableContactPersonSectionsToShow;

    // check whether or not we need to show additional type fields
    switch (parentRelationType) {
        case 'INDIVIDUAL':
            variableContactPersonSectionsToShow = ['proxy'];
            break;
        case 'ORGANIZATION':
            variableContactPersonSectionsToShow = ['manager'];
            break;
        case 'COLLECTIVE':
            variableContactPersonSectionsToShow = ['participant', 'proxy'];
            break;
        case 'ADVISOR':
            break;
    }

    Object.keys(variableContactPersonSections).forEach(section => {
        if (variableContactPersonSectionsToShow.includes(section)) {
            variableContactPersonSections[section].classList.remove('hide');
        } else {
            variableContactPersonSections[section].remove();
        }
    });

    <?php if (!empty($contactPersonRelation) && !empty($contactPersonRelation->typeObject) && !empty($contactPersonRelation->typeObject->idFile)) { ?>
    handleRemoveFileTemplate('ContactPersons[0][idFile]', 'idFile','<?= addslashes($contactPersonRelation->typeObject->idFile->filename) ?>')
    <? } ?>

    document.querySelectorAll('[data-hook="remove_file_template-delete"]').forEach(removeFileTemplateDelete => {
        removeFileTemplateDelete.addEventListener('click', handleRemoveFileTemplateDelete);
    });
}
function handleSubmit(event)
{
    event.preventDefault();

    let rules = {};

    let section = formContactPersonRelation.querySelector(`[data-section="contact-person"]`);
    let contactPersons = section.querySelectorAll(`[data-hook="form-contact_person"]`);
    contactPersons.forEach((contactPerson,idx) => {
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

        switch (parentRelationType) {
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
                Object.assign(rules, {
                    [`ContactPersons[${idx}][isParticipant]`]: {
                        required: true
                    }
                });
                Object.assign(rules, {
                    [`ContactPersons[${idx}][isProxy]`]: {
                        required: true
                    }
                });
                break;
        }
    });

    let formValidator = new BluemindsFormValidator(formContactPersonRelation, rules);

    if (formValidator.validate()) {
        formContactPersonRelation.submit();
    } else {
        scrollToInvalid()
    }
}

// LISTENERS
document.addEventListener('DOMContentLoaded', pageFormContactPersonReady);
formContactPersonRelation.querySelector('[data-hook="form-add_phone_number"]').addEventListener('click', addPhoneNumber);
formContactPersonRelation.querySelector('[data-hook="form-add_email_address"]').addEventListener('click', addEmailAddress);
formContactPersonRelation.querySelectorAll('[data-hook="form-delete_phone_number"]').forEach(btnDeletePhoneNumber => {
    btnDeletePhoneNumber.addEventListener('click', deletePhoneNumber);
});
formContactPersonRelation.querySelectorAll('[data-hook="form-delete_email_address"]').forEach(btnDeleteEmailAddress => {
    btnDeleteEmailAddress.addEventListener('click', deleteEmailAddress);
});
btnSubmit.addEventListener('click', handleSubmit);