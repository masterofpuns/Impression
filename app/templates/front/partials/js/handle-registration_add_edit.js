// VARS
let formRegistration = document.querySelector('[data-hook="form-registration_add_edit"]');

let autocompleteRelation = formRegistration.querySelector('[data-hook="form-registration_add_edit-relation_name"]');
let autocompleteContainerRelation = formRegistration.querySelector('[data-hook="form-registration_add_edit-autocomplete_container"]');

// FUNCTIONS
function handleAutocompleteRelation(event)
{
    let search = this.value;
    if (search.length > 2) {
        ajax({
            url: '<?= \app\m::app()->getDocByName('relation-autocomplete')->url; ?>',
            method: 'POST',
            data: { search: search },
            success: function(response) {
                if (response.success) {
                    if (typeof response.relations !== 'undefined' && Object.keys(response.relations).length > 0) {
                        handleAutocompleteSuggestions(response.relations, 'relation', autocompleteContainerRelation);
                    }
                }
            },
            error: function(error) {
                console.log('error');
                console.log(error);
            }
        });
    }
}
function handleAutocompleteSuggestions(suggestions, type, autocompleteContainer)
{
    resetAutocompleteContainer(autocompleteContainer);

    Object.keys(suggestions).forEach(key => {
        let entity = suggestions[key];

        let element = document.createElement('li');
        element.classList.add('list-group-item');
        element.setAttribute('data-hook', `select-${type}`);
        element.setAttribute('data-id', entity.id);
        element.textContent = entity.name;

        switch (type) {
            case 'relation': element.addEventListener('click', selectRelation); break;
            //case 'fund': element.addEventListener('click', selectFoundation); break;
        }

        autocompleteContainer.append(element);
    });
}
function resetAutocompleteContainer(container)
{
    container.innerHTML = "";
}
function selectRelation(event)
{
    let relationId = event.target.getAttribute('data-id');
    let relationName = event.target.textContent;

    formRegistration.querySelector('[name="Registration[relationName]"]').value = relationName;
    formRegistration.querySelector('[name="Registration[relationId]"]').value = relationId;

    resetAutocompleteContainer(autocompleteContainerRelation);
}


// LISTENERS
autocompleteRelation.addEventListener('keyup', handleAutocompleteRelation);
autocompleteRelation.addEventListener('focus', handleAutocompleteRelation);
