<h2>Inschrijven voor <?= $fund->name; ?></h2>
<hr class="margin-t-small" />
<div class="digital-registration-steps">
    <div class="digital-registration-step <? if($activeStep !== 'identification') {echo 'step-inactive';} ?>">
        Identificatie
    </div>
    <div class="digital-registration-step <? if($activeStep !== 'participation') {echo 'step-inactive';} ?>">
        Deelnamebedrag
    </div>
    <div class="digital-registration-step <? if($activeStep !== 'check') {echo 'step-inactive';} ?>">
        Controle
    </div>
    <div class="digital-registration-step <? if($activeStep !== 'declaration-sign') {echo 'step-inactive';} ?>">
        Verklaring en<br/>
        ondertekening
    </div>
</div>
<div class="digital-registration-progress">
    <div class="digital-registration-progressbar" style="width: <?= $percentageComplete ?>;"></div>
</div>