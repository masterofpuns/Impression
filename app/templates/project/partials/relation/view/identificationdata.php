<?php
use app\h;
?>
<div class="block">
    <div class="flexer">
        <h5 class="mb-3"><?= t('IDENTIFICATION_DATA') ?></h5>
    </div>

<?php if ($relation->type != 'INDIVIDUAL') { ?>
    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-6">
<?php } ?>

            <div class="data-flex-container">
                <strong><?= t('PROOF_OF_IDENTIFICATION') ?></strong>
                <?php
                if(!$typeData->idType && !$typeData->birthPlace){
                    echo '<span class="text-secondary-600">'.t('NO_PROOF_OF_IDENTIFICATION_PROVIDED').'</span>';
                } else {
                    echo $typeData->idType ? '<span>'.t($typeData->idType).'</span>' : '<span class="text-secondary-600">'.t('NO_DOCUMENT_TYPE_PROVIDED').'</span>';
                    echo $typeData->idNumber ? '<span>'.$typeData->idNumber.'</span>' : '<span class="text-secondary-600">'.t('NO_DOCUMENTNUMBER_PROVIDED').'</span>';
                } ?>
            </div>
            <div class="data-flex-container">
                <strong><?= t('VALIDITY_OF_IDENTIFICATION') ?></strong>
                <?php
                    echo $typeData->idDateExpiration ? '<span>'.$typeData->idDateExpiration.'</span>' : '<span class="text-secondary-600">'.t('NO_VALIDITY_DATE_PROVIDED').'</span>';
                ?>
            </div>

<?php if ($relation->type != 'INDIVIDUAL') { ?>
        </div>
        <div class="col-md-12 col-lg-12 col-xl-6">
<?php } ?>

            <div class="data-flex-container">
                <strong><?= t('BIRTHDATE_AND_PLACE') ?></strong>
                <?php
                if(!$typeData->birthDate && !$typeData->birthPlace){
                    echo '<span class="text-secondary-600">'.t('NO_BIRTHDATE_AND_BIRTHPLACE_PROVIDED').'</span>';
                } else {
                    echo $typeData->birthDate ? '<span>'.$typeData->birthDate.'</span>' : '<span class="text-secondary-600">'.t('NO_BIRTHDATE_PROVIDED').'</span>';
                    echo $typeData->birthPlace ? '<span>'.$typeData->birthPlace.'</span>' : '<span class="text-secondary-600">'.t('NO_BIRTHPLACE_PROVIDED').'</span>';
                }
                ?>
            </div>
            <div class="data-flex-container">
                <strong><?= t('NATIONALITY') ?></strong>
                <?= $typeData->nationality ?: '<span class="text-secondary-600">'.t('NO_NATIONALITY_PROVIDED').'</span>'; ?>
            </div>
            <div class="data-flex-container">
                <strong>Download legitimatie</strong>
                <?php
                if (!empty($typeData->getIdFile())) { ?>
                    <div class='d-flex justify-content-start'>
                    <a href="<?= $typeData->getIdFile()->relativeLocation ?>"
                       download="<?= preg_replace("/\.[^.]*$|\s+/", "", $typeData->getIdFile()->filename) ?>">
                        <i class="bi p-1 bi-file-earmark-arrow-down text-secondary-700 blue-hover"></i>
                    </a>
                    <a href="<?=$typeData->getIdFile()->relativeLocation?>" target="_blank">
                        <i class="bi p-1 bi-eye text-secondary-700 blue-hover"></i>
                    </a>
                    </div>
                <?php
                } else {
                    echo '<span class="text-secondary-600">'.t('NO_ID_FILE_PROVIDED').'</span>';
                } ?>
            </div>

<?php if ($relation->type != 'INDIVIDUAL') { ?>
        </div>
    </div>
<?php } ?>

</div>