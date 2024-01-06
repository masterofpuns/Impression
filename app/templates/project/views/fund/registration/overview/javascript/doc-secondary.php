// VARS

// INCLUDES
<?php
$app->renderJavascript($app->doc->environment->template->getPath() . '/partials/js/handle-registration_add_edit.js', []);
?>

// FUNCTIONS
function pageReady()
{

}


// LISTENERS
document.addEventListener('DOMContentLoaded', pageReady);

// EXECUTE
