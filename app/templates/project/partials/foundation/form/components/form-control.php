<div class="section well d-flex justify-content-between">
    <div class="d-flex gap-2">
        <a href="<?= !empty($foundation) && !empty($foundation->id) ? $foundation->getUrlView() : \app\m::app()->getDocByName('foundations-overview')->url; ?>" data-hook='form-cancel' class='btn grey-hover btn-outline-secondary-600'>
            Annuleren
        </a>
        <button type='button' data-hook='form-submit' class='btn btn-success'>
            <i class='bi bi-check-circle me-2'></i>
            <?= !empty($foundation->id) ? t('FOUNDATION_EDIT') : t('FOUNDATION_ADD'); ?>
        </button>
    </div>


    <div class=" d-flex justify-content-end">

</div>