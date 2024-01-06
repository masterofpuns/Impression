<?
if (is_array($params['values'])) {
    $options = $params['values'];
} else {
    $result = $app->db->query($params['values']);
    $options = $result->fetch_all(MYSQLI_ASSOC);
}
?>
<div class="col d-flex flex-column">
    <label for="<?= $field ?>Filter"><?= t($params['description']) ?></label>
    <select multiple name="<?= $field ?>Filter" id="select<?= $field ?>" title="<?= t($params['description']) ?>">
        <?php foreach($options as $option){

            if (is_null($option['id'])) {
                $quote = '"';
            } else {
                $quote = is_object(json_decode($option['id'])) ? "\'" : '"';
            }

            ?>
            <option value=<?= $quote . $option['id'] . $quote ?> <?= !empty($params['selected']) && in_array($option['id'], $params['selected']) ? ' selected' : '' ?>><?= t($option['description']) ?></option>
        <?}?>
    </select>
</div>