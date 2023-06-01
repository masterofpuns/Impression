
<div class="container main-content home text-center digital-registration-new">
    <div class="digital-registration-container">
        <h2>Inschrijven voor <?= $fund->name; ?></h2>
        <hr class="margin-t-small" />
        <div class="digital-registration-login">
            <form class="form" id="form-login" method="POST">
                <input type="hidden" name="Login[type]" value="email">
                <h2 class="text-left">Inloggen bij Mijn-IMMO</h2>
                <hr class="margin-t-small">
                <div class="digital-registration-loginfields">
                    <div class="row">
                        <div class="icon col-xs-1 text-center">
                            <img class="hig-user" src="/app/templates/front/assets/images/icons/login_user.svg">
                        </div>
                        <div class="form-group col-xs-10 field-email">
                            <input type="text" class="form-control" name="Login[identification]" placeholder="E-mailadres">
                        </div>
                    </div>
                    <div class="row">
                        <div class="icon col-xs-1 text-center">
                            <img class="hig-password" src="/app/templates/front/assets/images/icons/password.svg">
                        </div>
                        <div class="form-group col-xs-10 field-password">
                            <input id="password-input" type="password" class="form-control" name="Login[password]" placeholder="<?= t('PASSWORD') ?>">
                            <img class="hig-eye" data-hook="password-view-mode" src="/app/templates/front/assets/images/icons/eye.svg">
                        </div>
                    </div>
                    <? if (!empty($messages)) { $app->renderPartial('form/validation-errors', ['messages' => $messages]); } ?>
                </div>
                <hr class="margin-t-small" />
            </form>
            <div class="digital-registration-back text-left">
                <a href="<?= $homeUrl ?>" class="btn btn-icon icon-left">
                    <span class="square-container">
                        <img class="square-hack" src="/app/templates/front/assets/images/square_dummy.png">
                        <span class="square-content-container">
                            <img data-hook="svg-inject" class="hig-user" src="/app/templates/front/assets/images/icons/back.svg">
                        </span>
                    </span>
                    <span class="btn-text">Vorige</span>
                </a>
                <button class="btn btn-icon icon-right pull-right" data-hook="registration-submit_login_form">
                    <span class="btn-text">Inloggen</span>
                    <span class="square-container">
                        <img class="square-hack" src="/app/templates/front/assets/images/square_dummy.png">
                            <span class="square-content-container">
                                <img data-hook="svg-inject" class="hig-user" src="/app/templates/front/assets/images/icons/forward.svg">
                            </span>
                    </span>
                </button>
                <br /><br />
                <a class="forgot-password underlined" href="/wachtwoord-vergeten/">Wachtwoord vergeten?</a>
            </div>
        </div>
    </div>
</div>