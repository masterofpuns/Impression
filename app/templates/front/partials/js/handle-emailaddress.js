function addEmailAddress(event)
{
    let prefix = event.target.getAttribute('data-prefix');
    let section = event.target.closest('[data-section="email-addresses"]');
    let emailAddressContainer = section.querySelector('[data-hook="form-email_address_container"]');
    let totalEmailAddresses = section.querySelectorAll('[data-hook="form-email_address"]');

    let emailAddressClone = emailAddressElement.cloneNode(true);
    emailAddressClone.setAttribute('data-hook', emailAddressClone.getAttribute('data-hook').replace('-placeholder', ''));
    emailAddressClone.innerHTML = emailAddressClone.innerHTML.replaceAll('PREFIX', prefix);
    emailAddressClone.innerHTML = emailAddressClone.innerHTML.replaceAll("EA_IDX", totalEmailAddresses.length);
    emailAddressContainer.append(emailAddressClone);
    emailAddressClone.querySelector('[data-hook="form-delete_email_address"]').addEventListener('click', deleteEmailAddress);
}

function deleteEmailAddress(event)
{
    let section = event.target.closest('[data-hook="form-email_address"]');
    section.remove();
}