<div class="section well d-flex justify-content-between">
    <div class="d-flex gap-2">
        <a href="<?= !empty($curator) && !empty($curator->id) ? $curator->getUrlView() : \app\m::app()->getDocByName('curators-overview')->url; ?>" data-hook='form-cancel' class='btn grey-hover btn-outline-secondary-600'>
            Annuleren
        </a>
        <button type='button' data-hook='form-submit' class='btn btn-success'>
            <i class='bi bi-check-circle me-2'></i>
            <?= !empty($curator->id) ? t('CURATOR_EDIT') : t('CURATOR_ADD'); ?>
        </button>
    </div>


    <div class=" d-flex justify-content-end">

</div>