<div class="container main-content home text-center">
    <div class="digital-registration-container">
		<? $app->renderPartial('registration/registration-steps', ['percentageComplete' => '100%', 'activeStep' => 'declaration-sign', 'fund' => $fund]); ?>
        <div class="digital-registration-text text-left">
            <h2>Ondertekenen inschrijving <?= $fund->name; ?></h2>
            <hr class="margin-t-small" />
            <form class="text-left" data-hook="registration-form" method="POST">
                <label class="custom-checkbox checked" for="Declaration[sign]">
                    <span class="custom-checkbox-indicator"></span>
                    <span class="custom-checkbox-text">
                        &nbsp;
                    </span>
                </label>
                <input <?= !empty($declaration) && isset($declaration['sign']) && $declaration['sign'] ? '' : '';  ?> checked="" class="hide" type="checkbox" name="Declaration[sign]" >
            </form>
            <hr class="margin-t-small" />
        </div>
        <div class="digital-registration-buttons">
            <a href="<?= $prevUrl; ?>" class="btn btn-icon icon-left">
                <span class="square-container">
                    <img class="square-hack" src="/app/templates/front/assets/images/square_dummy.png">
                        <span class="square-content-container">
                            <img data-hook="svg-inject" class="hig-user" src="/app/templates/front/assets/images/icons/back.svg">
                        </span>
                </span>
                <span class="btn-text">Vorige</span>
            </a>
            <button class="btn btn-icon icon-right" data-hook="registration-submit_form" data-action="<?=$nextUrl;?>">
                <span class="btn-text">Verzenden</span>
                <span class="square-container">
                    <img class="square-hack" src="/app/templates/front/assets/images/square_dummy.png">
                        <span class="square-content-container">
                            <img data-hook="svg-inject" class="hig-user" src="/app/templates/front/assets/images/icons/send.svg">
                        </span>
                </span>
            </button>
        </div>
    </div>
</div>