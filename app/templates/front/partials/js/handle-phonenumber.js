function addPhoneNumber(event)
{
    let prefix = event.target.getAttribute('data-prefix');
    let section = event.target.closest('[data-section="phonenumbers"]');
    let phoneNumberContainer = section.querySelector('[data-hook="form-phone_number_container"]');
    let totalPhoneNumbers = section.querySelectorAll('[data-hook="form-phone_number"]');

    let phoneNumberClone = phoneNumberElement.cloneNode(true);
    phoneNumberClone.setAttribute('data-hook', phoneNumberClone.getAttribute('data-hook').replace('-placeholder', ''));
    phoneNumberClone.innerHTML = phoneNumberClone.innerHTML.replaceAll('PREFIX', prefix);
    phoneNumberClone.innerHTML = phoneNumberClone.innerHTML.replaceAll("PN_IDX", totalPhoneNumbers.length);
    phoneNumberContainer.append(phoneNumberClone);

    phoneNumberClone.querySelector('[data-hook="form-delete_phone_number"]').addEventListener('click', deletePhoneNumber);
}

function deletePhoneNumber(event)
{
    let section = event.target.closest('[data-hook="form-phone_number"]');
    section.remove();
}