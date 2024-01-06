// VARS
const collapseButtons = document.querySelectorAll('[data-bs-toggle="collapse"]');
const datePickers = document.querySelectorAll('.datepicker');

// FUNCTIONS
/*
function handleCollapse(collapseButton) {
    const collapseElement = document.querySelector(collapseButton.getAttribute('data-bs-target'));

    collapseButton.addEventListener('click', function () {
        if (collapseElement.classList.contains('show')) {
            collapseButton.classList.remove('active');
        } else {
            collapseButton.classList.add('active');
        }
    });

    collapseElement.addEventListener('hidden.bs.collapse', function () {
        collapseButton.classList.remove('active');
    });
}
*/
function handleCollapse(event) {
    let collapseButton = this;
    let collapseElement = document.querySelector(collapseButton.getAttribute('data-bs-target'));

    collapseButton.classList.remove('active');
    if (!collapseElement.classList.contains('show')) {
        collapseButton.classList.add('active');
    }

    collapseElement.addEventListener('hidden.bs.collapse', function () {
        collapseButton.classList.remove('active');
    });
}

// LISTENERS
/*collapseButtons.forEach(function (collapseButton) {
    handleCollapse(collapseButton);
});*/

collapseButtons.forEach(collapseButton => {
   collapseButton.addEventListener('click', handleCollapse);
});
datePickers.forEach(datePickerElement => {
    datepicker(
        datePickerElement,
        {
            formatter: (input, date, instance) => {
                const value = date.toLocaleDateString()
                input.value = value // => '1/1/2099'
            }
        }
    );
});