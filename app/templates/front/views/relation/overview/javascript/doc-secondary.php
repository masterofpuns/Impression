function handleLabel(label, showCheckboxes) {
    const dropdownSearch = label.closest('div.col').querySelector('.multiselect-dropdown-search');
    const typeLabel = label.closest('div.col').querySelector('label[for]');
    const wrapper = label.closest('div.col').querySelector('.multiselect-dropdown-list-wrapper')
    const checkboxes = wrapper.querySelectorAll('.multiselect-dropdown input[type="checkbox"]');


    document.addEventListener('click', function(event) {
        // Als we buiten onze dropdown klikken moeten we zorgen dat het label terug animeert, maar alleen als er geen checkbox gecheckt is
        if (!dropdownSearch.contains(event.target) && !Array.from(checkboxes).some(checkbox => checkbox.checked)  ) {
            typeLabel.style.top = '-3px';
            typeLabel.style.fontSize = '14px';
            typeLabel.style.transform = 'translate(0, 0)';
            typeLabel.style.backgroundColor = '';
            wrapper.style.display = 'none';
        }
    });
    //Als er on page load een checkbox is geselecteerd moet het label opgeschoven zijn
    if (Array.from(checkboxes).some(checkbox => checkbox.checked) || showCheckboxes) {
        typeLabel.style.backgroundColor = '#fff';
        requestAnimationFrame(() => {
            typeLabel.style.transform = 'translate(-9px, -14px)';
            typeLabel.style.fontSize = '11px';
            if (showCheckboxes) {
                wrapper.style.display = 'block';
            }

        });
    }

}

document.addEventListener('DOMContentLoaded', function() {
        //Wanneer er op de multiselect geklikt wordt checkboxes laten zien (true)
        document.querySelectorAll('.multiselect-dropdown, label[for]').forEach(multiSelect => {
            multiSelect.addEventListener('click', function(event) {
                handleLabel(event.target,true);
            });
        });

        //Wanneer op pageload het label boven moet staan maar geen checkboxes moet laten zien
    document.querySelectorAll('.multiselect-dropdown, label[for]').forEach( multiSelect => {
        handleLabel(multiSelect,false);
    });


});


