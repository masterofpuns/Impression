<div class="section well d-flex justify-content-between">
    <div class="d-flex gap-2">
        <a href="<?= !empty($fund) && !empty($fund->id) ? $fund->getUrlView() : \app\m::app()->getDocByName('funds-overview')->url; ?>" data-hook='form-cancel' class='btn grey-hover btn-outline-secondary-600'>
            Annuleren
        </a>
        <button type='button' data-hook='form-prev' class='btn btn-outline-secondary-600 hide'>
            <i class='bi bi-arrow-left-circle me-2'></i>
            Vorige
        </button>
        <button type='button' data-hook='form-next' class='btn btn-outline-success'>
            Volgende
            <i class='bi bi-arrow-right-circle ms-2'></i>
        </button>
        <button type='button' data-hook='form-submit' class='btn btn-success hide'>
            <i class='bi bi-check-circle me-2'></i>
            <?= !empty($fund->id) ? t('FUND_EDIT') : t('FUND_ADD'); ?>
        </button>
    </div>


    <div class=" d-flex justify-content-end">

</div>