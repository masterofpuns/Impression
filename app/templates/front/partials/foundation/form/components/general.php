<div class='section well' data-section='general'>
    <div class='form-group'>
        <div class="row">
            <div class='form-item col-12'>
                <label for='form-general-name' class="form-label"><?= t('NAME'); ?></label>
                <input
                    type="text"
                    name="Foundation[name]"
                    id="form-general-name"
                    class="form-control"
                    value="<?= !empty($foundation) && !empty($foundation->name) ? $foundation->name : ''; ?>"
                >
            </div>
        </div>
    </div>
</div>