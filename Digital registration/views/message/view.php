<?
use \app\h;
use \app\m;
?>
<div class="container main-content home text-center digital-registration-new">
    <div class="digital-registration-container">
        <h2>Inschrijven voor <?= $fund->name; ?></h2>
        <hr class="margin-t-small" />
        <p class="margin-b-big margin-t-big">
            <span class="digital-registration-message">
                <?= $message; ?>
            </span>
        </p>
        <hr class="margin-t-small" />
        <? if (!empty($prevUrl)): ?>
        <div class="digital-registration-buttons">
            <a href="<?= $prevUrl; ?>" class="btn btn-icon icon-left" data-hook="to-pre">
                <span class="square-container">
                    <img class="square-hack" src="/app/templates/front/assets/images/square_dummy.png">
                        <span class="square-content-container">
                            <img data-hook="svg-inject" class="hig-user" src="/app/templates/front/assets/images/icons/back.svg">
                        </span>
                </span>
                <span class="btn-text">Vorige</span>
            </a>
        </div>
        <? endif; ?>
    </div>
</div>