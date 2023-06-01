<div class="container main-content home text-center">
    <div class="digital-registration-container">
		<? $app->renderPartial('registration/registration-steps', ['percentageComplete' => '100%', 'activeStep' => 'declaration-sign', 'fund' => $fund]); ?>
        <div class="digital-registration-text text-left">
            <h2>Afronden inschrijving <?= $fund->name; ?></h2>
            <hr class="margin-t-small" />
            <p><?= $app->getCmsPartial('registration-complete-thank_you-message')->content; ?></p>
            <hr class="margin-t-small" />
        </div>
    </div>
</div>