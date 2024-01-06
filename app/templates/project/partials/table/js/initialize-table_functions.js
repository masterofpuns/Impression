/** INITIALIZE DATATABLE */
// FUNCTIONS
function initDatatable(settings, json)
{
    <?php if (!empty($table->params['actions'])) { ?>
    let dataTablesActions = document.getElementById("dataTables_actions");
    dataTablesActions.innerHTML = "<?= $table->params['actions'] ?>";
    <?php } ?>
}
function initDatatableAjaxData(d)
{
    let dataTablesMultiSelect = document.getElementById("dataTables_multiselect");
    if (!dataTablesMultiSelect.innerHTML) {
        dataTablesMultiSelect.innerHTML = '<?= $datatable['multiFilterHtml']; ?>';
        MultiSelectDropdown();
    }


    dataTablesMultiSelect.querySelectorAll('select[multiple]').forEach(multiSelect => {
        multiSelect.addEventListener('change', onChangeMultiSelect);
    });

    let multiFilterFields = <?= json_encode($datatable['multiFilterFields']); ?>;
    Array.from(multiFilterFields, (field) => {
        let fieldElement = document.getElementById(`select${field}`);
        if (fieldElement !== null) {
            let selected = fieldElement.querySelectorAll("option:checked");
            let values = []
            selected.forEach((option) => {
                values.push(option.value);
            });
            d[`${field}Filter`] = values.join(',');
        }
    });

    /**
     * Below is used to retrieve whether we need to show records whom contain a status active, archived or if we need to show all records.
     * Not all datatables will have this feature.
     */
    let showStatusFilter = document.querySelector('input[name="showStatusFilter"]');
    if (showStatusFilter !== null) {
        d['showStatusFilter'] = showStatusFilter.value;
    }
}
function onChangeMultiSelect(e)
{
    let select = this;
    let wrapper = select.closest('.dataTables_wrapper');
    let table = wrapper.querySelector('.table.dataTable');
    let datatable = new DataTable(table, {retrieve: true})
    datatable.draw();
}
function customDrawCallback(settings)
{
    let paginateElement = settings.nTableWrapper.querySelector('.dataTables_paginate');
    if (settings._iDisplayLength >= settings.fnRecordsDisplay() || isNaN(settings.fnRecordsDisplay())) {
        paginateElement.classList.add('hide');
    } else {
        paginateElement.classList.remove('hide');
    }

    // determine column types and set number columns text align right
    if (settings && settings.json && settings.json.data) {
        settings.json.data.forEach(row => {
            row.forEach((column, idx) => {
                if (column !== null && column !== "" && !isNaN(column)) {
                    let c = settings.aoColumns[idx];
                    if (!c.sClass.includes('column-align-right')) {
                        if (typeof this.cells !== 'undefined') {
                            this.cells(null, idx, {page: 'current'})
                                .nodes()
                                .to$()
                                .addClass('column-align-right');

                            if (!c.nTh.classList.contains('column-align-right')) {
                                c.nTh.classList.add('column-align-right');
                            }
                        }
                    }
                }
            });
        });
    }
}