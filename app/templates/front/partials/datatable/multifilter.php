<?php
foreach($multifilters as $field => $params){
    $app->renderPartial(
        'datatable/multifilter_item',
        array(
            'field' => $field,
            'params' => $params
        )
    );
}
?>