// INCLUDES
<?php
    $app->renderJavascript($app->doc->environment->template->getPath() . '/partials/js/modal-confirm-archive-delete.js');
    $app->renderJavascript('framework/templates/blueminds/partials/table/js/custom-table_control.js');
?>

//VARS

// FUNCTIONS
function handleEditCurator(event)
{
    let section = this.getAttribute('data-section');
    let url = "<?= $curator->getUrlEdit(); ?>";

    let formEdit = document.createElement('form');
    formEdit.setAttribute('action', url);
    formEdit.setAttribute('method', 'POST');
    formEdit.setAttribute('hidden', true);

    let input = document.createElement('input');
    input.setAttribute('type', 'hidden');
    input.setAttribute('name', 'section');
    input.setAttribute('value', section);
    formEdit.appendChild(input);

    document.body.appendChild(formEdit);
    formEdit.submit();
}

// LISTENERS
document.querySelectorAll('[data-hook="view-curator-edit"]').forEach(btn => {
    btn.addEventListener('click', handleEditCurator);
})