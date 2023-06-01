<div class="container main-content home text-center">
    <div class="digital-registration-container">
	    <? $app->renderPartial('registration/registration-steps', ['percentageComplete' => '10%', 'activeStep' => 'identification', 'fund' => $fund]); ?>
        <div class="digital-registration-text text-left">
            <h2>Ubo verklaring</h2>
            <hr class="margin-t-small margin-b-big">
            <p>Hierbij verklaart <?= $registration['Relation']['name'] ?> dat:</p>
            <?= $app->getCmsPartial('registration-declaration-ubo')->content; ?>
        </div>
        <form class="text-left" data-hook="registration-form" method="POST">
            <label class="custom-checkbox" for="Registration[Relation][acceptDeclarationUbo]">
                <span class="custom-checkbox-indicator"></span>
                <span class="custom-checkbox-text">
                    Ik heb bovenstaande verklaring gelezen en ga hiermee akkoord.
                </span>
            </label>
            <input type="checkbox" name="Registration[Relation][acceptDeclarationUbo]" <?= isset($registration['Relation']['acceptDeclarationUbo']) && $registration['Relation']['acceptDeclarationUbo'] ? 'checked' : ''; ?> required>
        </form>
        <hr class="margin-t-small" />
	    <? $app->renderPartial('registration/navigation-buttons', ['button' => true, 'prevUrl' => $prevUrl, 'nextUrl' => $nextUrl]); ?>
    </div>
</div>