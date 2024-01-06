function handleRemoveFileTemplate(field, fileType, filename)
{
    let element = document.querySelector('[name="'+field+'"]');
    let parent = element.parentNode;
    element.classList.add('hide');

    let removeFileTemplate = document.createElement('div');
    removeFileTemplate.setAttribute('data-hook', 'remove_file_template-container');
    removeFileTemplate.innerHTML = `${filename} <i class="bi bi-trash" style="cursor:pointer" data-hook="remove_file_template-delete" data-field="${field}"></i>`;

    parent.insertBefore(removeFileTemplate, element);
}
function handleRemoveFileTemplateDelete(event)
{
    let btn = this;
    let field = btn.getAttribute('data-field');
    let container = this.parentNode;

    container.remove();
    document.querySelector('[name="'+field+'"]').classList.remove('hide');
}