<?php
use \app\h;

$tableConst = 'table' . (!empty($datatable['identifier']) ? h::toPascalCase($datatable['identifier']) : '');
$tableElement = 'tableElement' . (!empty($datatable['identifier']) ? h::toPascalCase($datatable['identifier']) : '');
$tableData = 'tableData' . (!empty($datatable['identifier']) ? h::toPascalCase($datatable['identifier']) : '');
$onLoad = 'onLoadTable' . (!empty($datatable['identifier']) ? h::toPascalCase($datatable['identifier']) : '');
?>
function <?= $onLoad ?>(event)
{
    const <?= $tableElement; ?> = document.getElementById('<?= $table->params['id']; ?>');

    const <?= $tableData; ?> = <?= json_encode($datatable); ?>;
    <?= $tableData; ?>.language.search = "";
    <?= $tableData; ?>.language.searchPlaceholder = "Zoeken...";

    <?= $tableData; ?>.initComplete = initDatatable;
    <?= $tableData; ?>.drawCallback = customDrawCallback;
    <?= $tableData; ?>.ajax.data = initDatatableAjaxData;

    const <?= $tableConst; ?> = new DataTable(<?= $tableElement; ?>, <?= $tableData; ?>);
}
document.addEventListener('DOMContentLoaded', <?= $onLoad; ?>);