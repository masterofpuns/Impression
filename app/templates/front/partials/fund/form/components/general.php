<div class='section well' data-section='general'>
    <div class='form-group'>
        <div class="row">
            <div class='form-item col-12'>
                <label for='form-general-name' class="form-label"><?= t('FUND_NAME'); ?>*</label>
                <input
                    type="text"
                    name="Fund[name]"
                    id="form-general-name"
                    class="form-control"
                    value="<?= !empty($fund) && !empty($fund->name) ? $fund->name : ''; ?>"
                >
            </div>

            <div class='form-item col-12'>
                <label for='form-general-curator_name' class="form-label"><?= t('CURATOR'); ?></label>
                <input
                    type="text"
                    name="Fund[curatorName]"
                    id="form-general-curator_name"
                    data-hook="form-general-curator_name"
                    class="form-control"
                    placeholder="Zoeken"
                    value="<?= !empty($fund) && !empty($fund->curator) ? $fund->curator->name : ''; ?>"
                    >
                <input
                    type="hidden"
                    name="Fund[curatorId]"
                    id="form-general-curator_id"
                    class="form-control"
                    value="<?= !empty($fund) && !empty($fund->curator) ? $fund->curator->id : ''; ?>"
                    >
                <ul class="list-group" data-hook="form-general-curator-autocomplete_container"></ul>
            </div>
            <ul class='form-item col-12'>
                <label for='form-general-foundation_name' class="form-label"><?= t('FOUNDATION'); ?></label>
                <input
                    type="text"
                    name="Fund[foundationName]"
                    id="form-general-foundation_name"
                    data-hook="form-general-foundation_name"
                    class="form-control"
                    placeholder="Zoeken"
                    value="<?= !empty($fund) && !empty($fund->foundation) ? $fund->foundation->name : ''; ?>"
                    >
                <input
                    type="hidden"
                    name="Fund[foundationId]"
                    id="form-general-foundation_id"
                    class="form-control"
                    value="<?= !empty($fund) && !empty($fund->foundation) ? $fund->foundation->id : ''; ?>"
                    >
                <ul class="list-group" data-hook="form-general-foundation-autocomplete_container"></ul>
            </div>
        <p class="m-0 mt-2">*Verplicht</p>
        </div>
    </div>
</div>