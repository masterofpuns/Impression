// FUNCTIONS
function handleParticipantSettings(event)
{
    let section = event.target.closest('[data-section="contact-person"]');
    let formGroup = section.querySelector('[data-form_group="proxy"]');

    switch (parseInt(event.target.value)) {
        case 1:
            formGroup.classList.add('hide');
            formGroup.querySelectorAll('input, select').forEach(input => {
                input.setAttribute('disabled', '');
            });
            break;
        case 0:
            formGroup.classList.remove('hide');
            formGroup.querySelectorAll('input, select').forEach(input => {
                input.removeAttribute('disabled');
            });
            break;
    }
}

// LISTENERS
document.querySelectorAll('[data-form_group="participant"] input[type="radio"]').forEach(input => {
    input.addEventListener('change', handleParticipantSettings);
});
