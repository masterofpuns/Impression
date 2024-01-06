<div class="block">
    <div class="flexer">
        <h5 class="mb-3"><?= t('FUND_DETAILS') ?></h5>
        <button type='button' class='btn btn-outline-secondary-700 border-0 blue-hover p-1'
                data-hook='view-fund-edit' data-section='contact'><i class='bi bi-pencil'></i></button>
    </div>
    <div class="data-flex-container">
        <strong><?= t('NAME') ?></strong>
        <?= $fund->name ?>
    </div>
    <div class="data-flex-container">
        <strong><?= t('CURATOR') ?></strong>
        <?= $fund->getCurator()->name ?: '<span class="text-secondary-600">'.t('NO_CURATOR_PROVIDED').'</span>'; ?>
    </div>
    <div class="data-flex-container">
        <strong><?= t('FOUNDATION') ?></strong>
        <?= $fund->getFoundation()->name ?: '<span class="text-secondary-600">'.t('NO_FOUNDATION_PROVIDED').'</span>'; ?>
    </div>
</div>