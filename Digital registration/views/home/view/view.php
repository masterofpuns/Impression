<?
use \app\h;
use \app\m;
?>
<div class="container main-content home text-center digital-registration-new">
    <div class="digital-registration-container">
        <h2>Inschrijven voor <?= $fund->name; ?></h2>
        <hr class="margin-t-small" />
        <div class="digital-registration-buttonblock">
            <a class="btn" href="<?= $urlExistingUser; ?>">
                Ik maak gebruik van Mijn-IMMO*
            </a>
            <a class="btn" href="<?= $urlNewUser; ?>">
                Ik maak (nog) geen gebruik van Mijn-IMMO*
            </a>
        </div>
        <hr class="margin-t-small" />
        <div class="digital-registration-disclaimer text-left">
            * Mijn-IMMO is beschikbaar voor relaties die reeds participant zijn en zich hiervoor geregistreerd hebben.
        </div>
    </div>
</div>