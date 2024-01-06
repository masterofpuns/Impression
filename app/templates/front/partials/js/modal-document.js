// INCLUDES
<?php
$app->renderJavascript($app->doc->environment->template->getPath() . '/partials/js/handle-remove_file_template.js');
?>

// VARS
const formUploadDocument = document.querySelector('[data-hook="modal-document-form_document_upload_document"]');
const formUploadDocumentRules = {
    'Document[name]': {
        required: true
    },
    'Document[file]': {
        required: function() {
            return formUploadDocument.querySelector('[name="Document[id]"]').value ? false : true;
        }
    }
}

// FUNCTIONS
function onShowModalDocumentAddOrEdit(event) {
    let button = event.relatedTarget;
    let modal = this;
    let confirmButton = modal.querySelector('[data-hook="modal-document-add-edit-confirm"]');

    let confirmUrl = button.getAttribute('data-confirm-url');
    let entityId = button.getAttribute('data-entity-id');
    let entity = button.getAttribute('data-entity');
    let table = button.getAttribute('data-table');

    let documentId = button.getAttribute('data-document-id'); // only set when editting document

    let modalTitle = modal.querySelector('[data-hook="modal-document-title"]');
    let modalUploadLabel = modal.querySelector('[data-hook="modal-document-label-upload"]');

    let action = (typeof documentId !== undefined && documentId !== null && documentId !== "") ? 'edit' : 'add';

    let modalTitleText = modalTitle.getAttribute(`data-translation-${action}`);
    let modalUploadLabelText = modalUploadLabel.getAttribute(`data-translation-${action}`);
    let modalButtonText = confirmButton.getAttribute(`data-translation-${action}`);

    if (action === 'edit') {
        let documentNameToEdit = button.getAttribute('data-document-name');
        formUploadDocument.querySelector('[name="Document[name]"]').value = documentNameToEdit;
        formUploadDocument.querySelector('[name="Document[id]"]').value = documentId;

        handleRemoveFileTemplate('Document[file]', 'file', documentNameToEdit);
        formUploadDocument.querySelector('[data-hook="remove_file_template-delete"]').addEventListener('click', handleRemoveFileTemplateDelete);
    }

    modalTitle.innerText = modalTitleText;
    modalUploadLabel.innerText = modalUploadLabelText;
    confirmButton.innerText = modalButtonText;
    confirmButton.setAttribute("data-confirm-url", confirmUrl);
    confirmButton.setAttribute("data-entity-id", entityId);
    confirmButton.setAttribute("data-entity", entity);
    confirmButton.setAttribute("data-table", table);
}

function onShowModalDocumentDelete(event) {
    let modal = this;
    let button = event.relatedTarget;
    let confirmUrl = button.getAttribute('data-confirm-url');
    let table = button.getAttribute('data-table');
    let documentId = button.getAttribute('data-document-id');
    let documentName = button.getAttribute('data-document-name');
    let deleteMessage = 'Je staat op het punt document "' + documentName + '" te verwijderen. Weet je het zeker?';

    modal.querySelector('[data-hook="modal-document-delete_document_message"]').innerText = deleteMessage;
    modal.querySelector('[data-hook="modal-document-delete-confirm"]').setAttribute("data-confirm-url", confirmUrl);
    modal.querySelector('[data-hook="modal-document-delete-confirm"]').setAttribute("data-document-id", documentId);
    modal.querySelector('[data-hook="modal-document-delete-confirm"]').setAttribute("data-table", table);
}

function onHiddenModalDocumentAddOrEdit(event) {
    let modal = document.getElementById('modal-document-add-edit');

    let form = modal.querySelector('form[data-hook="modal-document-form_document_upload_document"]');
    let confirmButton = modal.querySelector('[data-hook="modal-document-add-edit-confirm"]');
    let modalTitle = modal.querySelector('[data-hook="modal-document-title"]');
    let modalUploadLabel = modal.querySelector('[data-hook="modal-document-label-upload"]');

    // velden leeggooien
    modalTitle.innerText = "";
    modalUploadLabel.innerText = "";

    form.reset();
    formUploadDocument.querySelector('[name="Document[id]"]').value = "";
    if (formUploadDocument.querySelector('[data-hook="remove_file_template-delete"]') !== null) {
        formUploadDocument.querySelector('[data-hook="remove_file_template-delete"]').dispatchEvent(new Event('click'));
    }

    confirmButton.setAttribute('data-confirm-url', '');
    confirmButton.setAttribute('data-entity-id', '');
    confirmButton.setAttribute('data-entity', '');
    confirmButton.setAttribute("data-table", '');
}

function modalDocumentAddConfirm(event) {
    event.preventDefault();

    let formValidator = new BluemindsFormValidator(formUploadDocument, formUploadDocumentRules);

    let modal = this.closest('.modal');
    let button = document.querySelector('[data-hook="modal-document-add-edit-confirm"]');
    let confirmUrl = button.getAttribute('data-confirm-url');
    let entityId = button.getAttribute('data-entity-id');
    let entity = button.getAttribute('data-entity');
    let table = button.getAttribute('data-table');
    let datatable = new DataTable(document.querySelector('table#' + table), { 'retrieve' : true });

    let formData = new FormData(formUploadDocument);
    formData.append('entityId', entityId);
    formData.append('entity', entity);

    if (formValidator.validate()) {
        ajax({
            url: confirmUrl,
            data: formData,
            method: 'POST',
            success: function (response) {

                if (response.success) {
                    datatable.draw();
                    bootstrap.Modal.getInstance(modal).hide();
                }
            },
            error: function (error) {
                console.log('error');
                console.log(error);
            }
        })
    }
}

function modalDocumentDeleteConfirm(event) {
    event.preventDefault();

    let modal = this.closest('.modal');
    let button = document.querySelector('[data-hook="modal-document-delete-confirm"]');
    let confirmUrl = button.getAttribute('data-confirm-url');
    let documentId = button.getAttribute('data-document-id');
    let table = button.getAttribute('data-table');
    let datatable = new DataTable(document.querySelector('table#' + table), { 'retrieve' : true });

    let formData = new FormData();
    formData.append('documentId', documentId);

    if (documentId) {
        ajax({
            url: confirmUrl,
            data: formData,
            method: 'POST',
            success: function (response) {

                if (response.success) {
                    datatable.draw();
                    bootstrap.Modal.getInstance(modal).hide();
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
document.getElementById('modal-document-add-edit').addEventListener('show.bs.modal', onShowModalDocumentAddOrEdit);
document.getElementById('modal-document-add-edit').addEventListener('hidden.bs.modal', onHiddenModalDocumentAddOrEdit);
document.getElementById('modal-document-delete').addEventListener('show.bs.modal', onShowModalDocumentDelete);
document.querySelector('[data-hook="modal-document-add-edit-confirm"]').addEventListener('click', modalDocumentAddConfirm);
document.querySelector('[data-hook="modal-document-delete-confirm"]').addEventListener('click', modalDocumentDeleteConfirm);