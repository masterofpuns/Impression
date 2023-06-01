<div class="container main-content home text-center digital-registration-new">
    <div class="digital-registration-container">
		<? $app->renderPartial('registration/registration-steps', ['percentageComplete' => '85%', 'activeStep' => 'complete', 'fund' => $fund]); ?>
        <div class="digital-registration-text text-left">
            <hr class="margin-t-small" />
	        <?= $content; ?>
            <hr class="margin-t-small" />
        </div>
    </div>
</div>