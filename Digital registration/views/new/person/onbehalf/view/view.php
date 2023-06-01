<?
use \app\h;
use \app\m;
?>
<div class="container main-content home text-center digital-registration-new">
    <div class="digital-registration-container">
        <h2>Inschrijven voor <?= $fund ?></h2>
        <hr class="margin-t-small" />
        <div class="digital-registration-buttonblock">
            <a class="btn" href="<?= $nextUrl; ?>">
                Inschrijven voor mijzelf
            </a>
            <a class="btn" href="#" data-toggle="tooltip" title="Indien u namens een derde wenst in te schrijven, dient u momenteel gebruik te maken via het papieren inschrijfformulier. Hiervoor verzoeken wij u om contact op te nemen met Holland Immo Group. Telefoonnummer: 040 235 26 35">
                Inschrijven namens een derde
            </a>
        </div>
        <hr class="margin-t-small" />
        <div class="digital-registration-back text-left">
            <a href="<?= $prevUrl; ?>" class="btn btn-icon icon-left">
                    <span class="square-container">
                    <img class="square-hack" src="/app/templates/front/assets/images/square_dummy.png">
                    <span class="square-content-container">
                        <img data-hook="svg-inject" class="hig-user" src="/app/templates/front/assets/images/icons/back.svg">
                    </span>
                </span>
                <span class="btn-text">Vorige</span>
            </a>
        </div>
    </div>
</div>


