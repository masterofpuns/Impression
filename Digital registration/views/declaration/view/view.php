<div class="container main-content home text-center">
    <div class="digital-registration-container">
		<? $app->renderPartial('registration/registration-steps', ['percentageComplete' => '95%', 'activeStep' => 'declaration-sign', 'fund' => $fund]); ?>
        <div class="digital-registration-text text-left">
            <hr class="margin-t-small" />
			<?= $content; ?>
            <hr class="margin-t-small" />
        </div>
        <form class="text-left" data-hook="registration-form" method="POST">
            <label class="custom-checkbox" for="Declaration[acceptDeclaration]">
                <span class="custom-checkbox-indicator"></span>
                <span class="custom-checkbox-text">
                    Ik heb bovenstaande verklaring gelezen en ga hiermee akkoord.
                </span>
            </label>
            <input type="checkbox" name="Declaration[acceptDeclaration]" required>
            <textarea name="Declaration[comments]" placeholder="Vragen en/of opmerkingen"></textarea>
        </form>
        <hr class="margin-t-small" />
        <div class="digital-registration-back text-left">
            <button class="btn btn-icon icon-right pull-right" data-hook="registration-submit_form" data-action="<?=$nextUrl;?>">
                <span class="btn-text">Akkoord</span>
                <span class="square-container">
                    <img class="square-hack" src="/app/templates/front/assets/images/square_dummy.png">
                        <span class="square-content-container">
                            <img data-hook="svg-inject" class="hig-user" src="/app/templates/front/assets/images/icons/forward.svg">
                        </span>
                </span>
            </button>
        </div>
    </div>
</div>