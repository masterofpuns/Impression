<div class="block">
    <div class="flexer">
        <h5 class="mb-3"><?= t('REPAYMENT') ?></h5>
    </div>
    <?php
    $repayments = $fund->getRepayments();
    if (!empty($repayments)) {
        foreach ($repayments as $repayment) {
            echo '<span>' . $repayment->id . '</span>';
        }
    } else {
        echo '<span class="text-secondary-600">'.t('NO_REPAYMENT_OCCURED').'</span>';
    }
    ?>
</div>