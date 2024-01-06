<div class='section well' data-section='relation-category'>
    <strong><?= t('CATEGORY') ?></strong>

    <div class='form-group'>
        <?php
        foreach ($app->relationCategories as $category) { ?>
            <div class='form-check'>
                <label class="form-check-label" for='form_relation-relation_category-<?= strtolower($category->name); ?>'>
                    <input
                        type='radio'
                        name='Relation[Category][categoryId]'
                        id='form_relation-relation_category-<?= strtolower($category->name); ?>'
                        value='<?= $category->id; ?>'
                        class="form-check-input"
                        <?= !empty($relation->id) && !empty($relation->category) && $relation->category->name == $category->name ? 'checked' : ''; ?>
                    >
                    <?= t($category->name); ?>
                </label>
            </div>
        <?php } ?>
    </div>
</div>