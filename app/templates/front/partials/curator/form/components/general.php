<div class='section well' data-section='general'>
    <div class='form-group'>
        <div class="row">
            <div class='form-item col-12'>
                <label for='form-general-name' class="form-label"><?= t('NAME'); ?></label>
                <input
                    type="text"
                    name="Curator[name]"
                    id="form-general-name"
                    class="form-control"
                    value="<?= !empty($curator) && !empty($curator->name) ? $curator->name : ''; ?>"
                >
            </div>
        </div>
    </div>
</div>