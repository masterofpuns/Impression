<?

/***
 * @var \app\CAction $button
 */
$button;

if (!isset($showDescription)) {
    $showDescription = true;
}

if (!isset($tooltip)) {
    $tooltip = true;
}

$button->setProperties(
    [
        'class' => 'btn-default'
    ],
    false
);

if (!empty($size)) {

    // Convert size to Bootstrap-specific class
    switch ($size) {
        case 'extra-small':
            $button->class .= ' btn-xs';
            break;
        case 'large':
            $button->class .= ' btn-lg';
            break;
        case 'small':
            $button->class .= ' btn-sm';
            break;
    }

}

$dataAttribute = '';

if (!empty($button->params['data'])) {
    foreach($button->params['data'] as $attr => $value) {
        if (!is_string($value)) {
            $dataAttribute .= ' data-' . $attr . '=\'' . json_encode($value) . '\'';
        } else {
            $dataAttribute .= ' data-' . $attr . '="' . $value . '"';
        }

    }
} else{
    if ($tooltip) {
        $dataAttribute .= ' data-bs-toggle="tooltip" data-bs-container="body"';
    }
}

$cssId = '';
if (!empty($button->params['cssId'])) {
    $cssId .= ' id="' . $button->params['cssId'] . '"';
}

switch ($button->type) {

    case 'button':
        // @todo frontend: add support for button type
        $elStart = '<button type="button"';
        $elEnd = '</button>';
        break;

    case 'link':
        $elStart = '<a href="' . $button->url . '"';
        $elEnd = '</a>';
        break;

    case 'external':
        $elStart = '<a href="' . $button->url . '" target="_blank"';
        $elEnd = '</a>';
        break;

    case 'submit':
        $elStart = '<button type="submit"';
        $elEnd = '</button>';
        $button->class .= ' btn-primary';
        break;

    default:
        throw new \Error('Invalid button type');
        break;

}

if (!empty($button->disabled)) {
    $attDisabled = ' disabled';
} else {
    $attDisabled = '';
}

echo $elStart . ' title="' . t($button->description) . '" ' . $cssId . ' class="btn ' . $button->class . '" ' . $dataAttribute . $attDisabled . '>';
?>

<?php if (!is_null($button->icon)) { ?>
    <i class="bi bi-<?= $button->icon ?>" aria-hidden="true"></i>
<?php } ?>

<?
if ($showDescription) {
    echo t($button->description);
}
?>

<?
echo $elEnd;