<?php
$relationTypes = ['INDIVIDUAL', 'ORGANIZATION', 'COLLECTIVE', 'ADVISOR'];
?>
<div class="section well" data-section="relation-type">
    <strong>Relatietype*</strong>

    <div class="form-group">
        <?php foreach ($relationTypes as $relationType) { ?>

        <div class="form-check">
            <label class="form-check-label" for="form_relation-relation_type-<?= strtolower($relationType); ?>">
                <input class="form-check-input" type="radio" name="Relation[type]" id="form_relation-relation_type-<?= strtolower($relationType); ?>" value="<?= $relationType; ?>" <?= !empty($relation) && $relation->type == $relationType ? 'checked' : ''; ?> <?= !empty($relation) && !empty($relation->id) && $relation->type !== $relationType ? 'disabled' : ''; ?>> <?= t($relationType); ?>
            </label>
        </div>

        <?php } ?>
    </div>
    <p class="m-0 mt-2"> * Verplicht</p>
</div>
