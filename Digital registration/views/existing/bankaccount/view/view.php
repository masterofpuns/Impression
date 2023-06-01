<div class="container main-content home text-center">
    <div class="digital-registration-container">
        <? $app->renderPartial('registration/registration-steps', ['percentageComplete' => '19%', 'activeStep' => 'identification', 'fund' => $fund]); ?>
        <div class="digital-registration-formfields text-left">
            <h2>Met welk iban rekeningnummer wenst u de inleg te betalen en de toekomstige uitkeringen te ontvangen?</h2>
            <hr class="margin-t-small" />
            <form class="digital-registration-bankaccount" method="POST" data-hook="registration-form">
                <div class="row">
                    <div class="form-group col-xs-12 col-sm-10">
                        Selecteer een reeds bij ons bekend bankrekeningnummer:
                        <span class="dropdown-styling">
                            <select class="form-control" name="Registration[BankAccount][id]">
                                <option value="">Maak een selectie</option>
                                <? foreach ($relation->bankAccounts as $bankAccount): ?>
                                    <option
                                        value="<?= $bankAccount->id; ?>"
                                        <?= isset($registration['BankAccount']) && !empty($registration['BankAccount']['id']) && $registration['BankAccount']['id'] == $bankAccount->id ? 'selected' : ''?>>
                                        <?= $bankAccount->iban; ?> t.n.v. <?= $bankAccount->ascription; ?>
                                    </option>
                                <? endforeach; ?>
                            </select>
                            <img class="hig-select" src="/app/templates/front/assets/images/icons/dropdown.svg">
                        </span>
                    </div>
                </div>
                <div class="row digital-registration-changebank">
                    <div class="form-group col-xs-12">
                        Indien u een ander bankrekeningnummer wilt gebruiken, vult u deze hieronder in. <strong>Let op:</strong> Dit bankrekeningnummer moet op uw naam staan.
                    </div>
                </div>
                <div class="row ">
                    <div class="form-group col-xs-12 col-sm-10">
                        <label for="Registration[BankAccount][iban]">Nieuw bankrekeningnummer:</label>
                        <input
                            type="text"
                            name="Registration[BankAccount][iban]"
                            class="form-control"
                            value="<?= isset($registration['BankAccount']) && empty($registration['BankAccount']['id']) && !empty($registration['BankAccount']['iban']) ? $registration['BankAccount']['iban'] : ''?>"
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
