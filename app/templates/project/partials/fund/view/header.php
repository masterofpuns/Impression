<h5 class="page-header">
    <?php if($fund->archived == 1){ ?>
        <span class="archived-status"><?=t('ARCHIVED')?> - </span>
    <?php } ?>

    <?= t($fund->name) ?>
</h5>