// FUNCTIONS
function onShowModalRelationNoteAddOrEdit(event) {
    let button = event.relatedTarget;
    let modal = document.getElementById('modal-note-add-edit');
    let confirmButton = modal.querySelector('[data-hook="modal-relation-note-add-edit-confirm"]');

    let confirmUrl = button.getAttribute('data-confirm-url');
    let relationId = button.getAttribute('data-relation-id');
    let modalTitle = modal.querySelector('[data-hook="modal-note-title"]');
    let noteId = button.getAttribute('data-note-id'); // alleen voor de edit gevuld

    if (noteId) {
        // EDIT
        let modalTitleText = modalTitle.getAttribute("data-translation-edit");
        let modalButtonText = confirmButton.getAttribute("data-translation-edit");
        modal.querySelector('[data-hook="modal-note-title"]').innerText = modalTitleText;
        confirmButton.innerText = modalButtonText;

        let noteDescriptionToEdit = document.querySelector('[data-hook="note-description-' + noteId + '"]').innerText;
        document.getElementById("form_relation-note-description").value = noteDescriptionToEdit;
        confirmButton.setAttribute("data-note-id", noteId);
    } else {
        // ADD
        let modalTitleText = modalTitle.getAttribute("data-translation-add");
        let modalButtonText = confirmButton.getAttribute("data-translation-add");
        modal.querySelector('[data-hook="modal-note-title"]').innerText = modalTitleText;
        confirmButton.innerText = modalButtonText;

    }

    confirmButton.setAttribute("data-confirm-url", confirmUrl);
    confirmButton.setAttribute("data-relation-id", relationId);
}

function onShowModalRelationNoteDelete(event) {
    let modal = document.getElementById('modal-note-delete');
    let button = event.relatedTarget;
    let confirmUrl = button.getAttribute('data-confirm-url');
    let relationId = button.getAttribute('data-relation-id');
    let noteId = button.getAttribute('data-note-id');
    let noteDescription = document.querySelector('[data-hook="note-description-' + noteId + '"]').innerText;
    let deleteMessage = 'Weet je zeker dat je de volgende notitie: "' + noteDescription + '" wilt verwijderen?';

    modal.querySelector('[data-hook="modal-relation-note-message"]').innerText = deleteMessage;
    modal.querySelector('[data-hook="modal-relation-note-delete-confirm"]').setAttribute("data-confirm-url", confirmUrl);
    modal.querySelector('[data-hook="modal-relation-note-delete-confirm"]').setAttribute("data-relation-id", relationId);
    modal.querySelector('[data-hook="modal-relation-note-delete-confirm"]').setAttribute("data-note-id", noteId);
}

function onHiddenModalRelationNoteAddOrEdit(event) {
    let modal = document.getElementById('modal-note-add-edit');
    let confirmButton = modal.querySelector('[data-hook="modal-relation-note-add-edit-confirm"]');
    let modalTitle = modal.querySelector('[data-hook="modal-note-title"]');

    // velden leeggooien
    modal.querySelector('[data-hook="modal-note-title"]').innerText = "";
    document.getElementById("form_relation-note-description").value = "";
    confirmButton.setAttribute('data-confirm-url', '');
    confirmButton.setAttribute('data-relation-id', '');
    confirmButton.removeAttribute('data-note-id');
}

function modalRelationNoteAddConfirm(event) {
    event.preventDefault();

    let button = document.querySelector('[data-hook="modal-relation-note-add-edit-confirm"]');
    let confirmUrl = button.getAttribute('data-confirm-url');
    let relationId = button.getAttribute('data-relation-id');
    let noteId = button.getAttribute('data-note-id');
    let noteDescription = document.getElementById("form_relation-note-description").value;

    if (noteDescription) {
        ajax({
            url: confirmUrl,
            data: {
                'relationId': relationId,
                'noteId': noteId,
                'noteDescription': noteDescription,
            },
            method: 'POST',
            success: function (data) {
                let modalElement = document.getElementById('modal-note-add-edit');
                let modal = bootstrap.Modal.getInstance(modalElement)
                modal.hide();

                if (noteId) {
                    // If noteId isn't empty we need to edit an item from the note list
                    let editedNoteDiv = document.querySelector('[data-hook="note-block-item-' + noteId + '"]');
                    let editedNoteDivUpdated = editedNoteDiv.querySelector(".note-block-item-updated");
                    if (editedNoteDivUpdated) {
                        // bestaat dus we gaan alleen de inhoud aanpassen
                        editedNoteDiv.querySelector(".note-block-item-updated").textContent = data.formatUpdated;
                    } else {
                        // bestaat nog niet dus we gaan het element toevoegen
                        let formatUpdateMessage = '<div class="note-block-item-updated">' + data.formatUpdated + '</div>';
                        editedNoteDiv.querySelector(".note-block-item-description").insertAdjacentHTML('afterend', formatUpdateMessage);
                    }
                    editedNoteDiv.querySelector(".note-block-item-description").textContent = data.note.description;
                } else {
                    // If noteId is empty we need to add a new note to the list
                    // Test to see if the browser supports the HTML template element by checking
                    // for the presence of the template element's content attribute.
                    if ("content" in document.createElement("template")) {
                        const divBlockContainer = document.querySelector(".note-block");
                        const divBlockFirstItem = divBlockContainer.firstChild;
                        const divBlockNoNotesProvided = document.getElementById('no-notes-provided');

                        // wanneer er een nieuw item teogevoegd wordt, moet de <p> met "geen notites opgegeven" weggehaald worden
                        if (divBlockNoNotesProvided) {
                            divBlockNoNotesProvided.remove();
                        }

                        const divBlockItem = document.querySelector("#note-block-item-template");
                        // Clone the template and insert it into the div
                        const clone = divBlockItem.content.cloneNode(true);
                        clone.querySelector(".note-block-item").setAttribute('data-hook', "note-block-item-" + data.note.id);
                        clone.querySelector(".note-block-item-created").textContent = data.formatCreated;
                        clone.querySelector(".note-block-item-description").textContent = data.note.description;
                        clone.querySelector(".note-block-item-description").setAttribute('data-hook', "note-description-" + data.note.id);
                        clone.querySelector(".note-block-item-btn-edit").setAttribute('data-note-id', data.note.id);
                        clone.querySelector(".note-block-item-btn-delete").setAttribute('data-note-id', data.note.id);

                        divBlockContainer.insertBefore(clone, divBlockFirstItem);
                    } else {
                        // the HTML template element is not supported.
                        location.reload();
                    }
                }
            },
            error: function (error) {
                console.log('error');
                console.log(error);
            }
        })
    }
}

function modalRelationNoteDeleteConfirm(event) {
    event.preventDefault();

    let button = document.querySelector('[data-hook="modal-relation-note-delete-confirm"]');
    let confirmUrl = button.getAttribute('data-confirm-url');
    let relationId = button.getAttribute('data-relation-id');
    let noteId = button.getAttribute('data-note-id');

    if (noteId) {
        ajax({
            url: confirmUrl,
            data: {
                'relationId': relationId,
                'noteId': noteId,
            },
            method: 'POST',
            success: function (data) {
                let modalElement = document.getElementById('modal-note-delete');
                let modal = bootstrap.Modal.getInstance(modalElement)
                modal.hide();

                // div verwijderen
                let formerNoteDiv = document.querySelector('[data-hook="note-block-item-' + noteId + '"]');
                formerNoteDiv.remove();

                const divBlockContainer = document.querySelector(".note-block");

                // als alle notites verwijderd zijn moet de tekst "geen notites opgegeven" erneer gezet worden
                if (divBlockContainer.textContent.trim() === '') {
                    // Clone the template and insert it into the div
                    const divBlockItem = document.querySelector("#note-block-empty-template");
                    const clone = divBlockItem.content.cloneNode(true);
                    divBlockContainer.appendChild(clone);
                }
            },
            error: function (error) {
                console.log('error');
                console.log(error);
            }
        })
    }
}

// LISTENERS
document.getElementById('modal-note-add-edit').addEventListener('show.bs.modal', onShowModalRelationNoteAddOrEdit);
document.getElementById('modal-note-add-edit').addEventListener('hidden.bs.modal', onHiddenModalRelationNoteAddOrEdit);
document.getElementById('modal-note-delete').addEventListener('show.bs.modal', onShowModalRelationNoteDelete);
document.querySelector('[data-hook="modal-relation-note-add-edit-confirm"]').addEventListener('click', modalRelationNoteAddConfirm);
document.querySelector('[data-hook="modal-relation-note-delete-confirm"]').addEventListener('click', modalRelationNoteDeleteConfirm);