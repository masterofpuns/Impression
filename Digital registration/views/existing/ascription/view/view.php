<div class="container main-content home text-center">
    <div class="digital-registration-container">
        <? $app->renderPartial('registration/registration-steps', ['percentageComplete' => '4%', 'activeStep' => 'identification', 'fund' => $fund]); ?>
        <div class="digital-registration-formfields text-left">
            <p class="switch-user margin-t-big margin-b-big text-center">Ingelogd als: <?=$app->user->emailAddress;?></p>
            <p class="switch-select-text  margin-t-big margin-b-small">
                Selecteer de tenaamstelling waarmee u wenst deel te nemen in <?= $fund ?>
            </p>
            <hr class="no-margin">
            <div class="switch-participants">
                <? foreach ($accessibleEnvironmentRelationsForUser as $relation): ?>
                <p
                    class="participant<?= !empty($app->user->getSelectedRelation()) && $relation->id === $app->user->getSelectedRelation()->id ? " active" : "" ?>"
                    data-hook="set-selected-relation"
                    data-id="<?=$relation->id;?>"
                    data-redirect="<?= $nextUrl; ?>"
                    >
                    <?=$relation->name;?>
                </p>
                <hr class="no-margin">
                <? endforeach; ?>

                <div class="btn-group flex flex-center center-btns margin-t-big">
                    <a href="<?= $app->getDocByName('registration-new_user')->getUrl($slug); ?>" class="btn margin-b-small btn-icon">
                        <div class="hig-add"></div>
                        <span class="btn-text">Inschrijven met een nieuwe tenaamstelling <span class="asterix">*</span></span>
                    </a>
                </div>

                <div class="flex flex-center" id="registration-additional_text-new_ascription">
                    <p>
                        * Wanneer de gewenste participaties in dit fonds aan u worden toegewezen, ontvangt u nieuwe registratiegegevens voor Mijn-IMMO en kunt u de nieuwe tenaamstelling toevoegen aan uw huidige account.
                    </p>
                </div>
                
            </div>
            <div class="digital-registration-buttons">
                <a href="<?= $prevUrl ?>" class="btn btn-icon icon-left" data-hook="to-pre">
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
</div>