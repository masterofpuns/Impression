<div class="container main-content home text-center digital-registration-new">
    <div class="digital-registration-container">
        <? $app->renderPartial('registration/registration-steps', ['percentageComplete' => '19%', 'activeStep' => 'identification', 'fund' => $fund]); ?>
        <div class="digital-registration-formfields text-left">
            <h2>Met welk iban rekeningnummer wenst u de inleg te betalen en de toekomstige uitkeringen te ontvangen?</h2>
            <hr class="margin-t-small" />
            <form class="digital-registration-bankaccount" method="POST" data-hook="registration-form">
                <div class="row ">
                    <div class="form-group col-xs-12 col-sm-10">
                        <label for="Registration[BankAccount][iban]">Bankrekeningnummer:</label>
                        <input
                            type="text"
                            name="Registration[BankAccount][iban]"
                            class="form-control"
                            value="<?= isset($registration['BankAccount']) && empty($registration['BankAccount']['id']) && !empty($registration['BankAccount']['iban']) ? $registration['BankAccount']['iban'] : ''?>"
                            required
                            >
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xs-12 col-sm-10">
                        <label for="Registration[BankAccount][ascription]">Ten name van:</label>
                        <input
                            type="text"
                            name="Registration[BankAccount][ascription]"
                            class="form-control"
                            value="<?= isset($registration['BankAccount']) && empty($registration['BankAccount']['id']) && !empty($registration['BankAccount']['ascription']) ? $registration['BankAccount']['ascription'] : ''?>"
                            required
                            >
                    </div>
                </div>
            </form>
            <hr class="margin-t-small" />
            <?
                $app->renderPartial('registration/navigation-buttons', ['button' => true, 'prevUrl' => $prevUrl, 'nextUrl' => $nextUrl]);
            ?>
        </div>
    </div>
</div>
