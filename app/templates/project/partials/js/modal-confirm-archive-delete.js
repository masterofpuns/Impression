// FUNCTIONS

function onShowModalConfirmArchiveDelete(event) {
    let modal = this;

    let confirmUrl = event.relatedTarget.getAttribute('data-confirm-href');
    let confirmMessage = event.relatedTarget.getAttribute('data-confirm-message');
    let confirmTitle = event.relatedTarget.getAttribute('data-confirm-title');

    modal.querySelector('[data-hook="modal-confirm-confirm"]').setAttribute("href", confirmUrl);
    modal.querySelector('[data-hook="modal-confirm-message"]').textContent = confirmMessage;
    modal.querySelector('[data-hook="modal-confirm-title"]').textContent = confirmTitle;
}

function modalConfirmArchiveDelete(event) {
    event.preventDefault();

    let button = event.target;
    let confirmUrl = button.getAttribute('href');

    ajax({
        url: confirmUrl,
        method: 'POST',
        data: {},
        success: function (data) {
            if (data.success) {
                location.reload();
            } else {
                console.log('Error: '+data.message);
            }
        },
        error: function (error) {
            console.log('Something went wrong.', error);
        }
    })


}

// LISTENERS
document.getElementById('modal-confirm').addEventListener('show.bs.modal', onShowModalConfirmArchiveDelete);
document.querySelector('[data-hook="modal-confirm-confirm"]').addEventListener('click', modalConfirmArchiveDelete);
