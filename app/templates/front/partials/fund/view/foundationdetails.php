<div class="block">
    <div class="flexer">
        <h5 class="mb-3"><?= t('FOUNDATION_DETAILS') ?></h5>
        <button type='button' class='btn btn-outline-secondary-700 border-0 blue-hover p-1'
                data-hook='view-foundation-edit' data-section='contact'><i class='bi bi-pencil'></i></button>
    </div>
    <div class="data-flex-container">
        <strong><?= t('NAME') ?></strong>
        <?= $foundation->name ?>
    </div>
    <div class="data-flex-container">
        <strong><?= t('BANKING_ACCOUNT') ?></strong>
        <?php
        if (!empty($foundation->getBankAccount())) {
            $app->renderPartial('elements/bankingAccount', [
                'bankingAccount' => $foundation->getBankAccount(),
            ]);
        } else {
            echo '<span class="text-secondary-600">'.t('NO_BANKING_ACCOUNT_PROVIDED').'</span>';
        } ?>
    </div>
</div>