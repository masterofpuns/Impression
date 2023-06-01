<? $participationsValue = empty($fund->currentValue) ? $fund->value : $fund->currentValue; ?>
<div class="container main-content home text-center">
    <div class="digital-registration-container">
        <? $app->renderPartial('registration/registration-steps', ['percentageComplete' => '65%', 'activeStep' => 'check', 'fund' => $fund]); ?>
        <div class="digital-registration-formfields text-left">
            <h2>Controleer uw inschrijving</h2>
            <hr class="margin-t-small" />

            <div class="digital-registration-text">
                <div class="row checkblock-margin">
                    <div class="col-xs-12 col-sm-5">
                        Participant:
                    </div>
                    <div class="col-xs-12 col-sm-7">
                        <?= $registration['Relation']['name']; ?>
                        <br />
                        <?= $registration['PostalAddress']['street'] ?> <?= $registration['PostalAddress']['number'] ?> <?= isset($registration['PostalAddress']['numberSuffix']) ? $registration['PostalAddress']['numberSuffix'] : ''; ?><br>
                        <?= $registration['PostalAddress']['postalCode'] ?> <?= $registration['PostalAddress']['city'] ?>
                        <br />
                        <?
                        switch ($registration['Relation']['type']) {
                            case 'contactPerson':
                                foreach($registration['ContactPerson'] as $key => $contactPersonParams) {
                                    // telefoonnummer(s)
                                    if (!empty($contactPersonParams['phoneNumbers'])) {
                                        foreach ($contactPersonParams['phoneNumbers'] as $phoneNumber) {
                                            ?><?= t(strtoupper($phoneNumber['type'])); ?>: <?=$phoneNumber['number']?><br><?
                                        }
                                    }
                                    // emailadres
                                    ?><?= $contactPersonParams['emailAddress']; ?><br><br><?
                                }
                                break;
                            case 'collective':
                                // contactpersonen
                                ?><br><?
                                foreach($registration['ContactPerson'] as $key => $contactPersonParams) {
                                    ?><?=$contactPersonParams['name'];?><br><?
                                    if (isset($contactPersonParams['street'])) {
                                        // Contactpersoon met afwijkend adres
                                        echo $contactPersonParams['street'].' '.$contactPersonParams['number'].$contactPersonParams['numberSuffix'].'<br />';
                                        echo $contactPersonParams['postalCode'].' '.$contactPersonParams['city'].'<br />';
                                        echo $contactPersonParams['country'].'<br /><br />';

                                    }
                                    if (!empty($contactPersonParams['phoneNumbers'])) {
                                        foreach ($contactPersonParams['phoneNumbers'] as $phoneNumber) {
                                            ?><?= t(strtoupper($phoneNumber['type'])); ?>: <?=$phoneNumber['number']?><br><?
                                        }
                                    }
	                                ?><?= $contactPersonParams['emailAddress']; ?><br><br><?
                                }
                                break;
                            case 'organization':
                                // bestuurders
                                ?><br><?
		                        foreach($registration['ContactPerson'] as $key => $contactPersonParams) {
		                            ?><?=$contactPersonParams['name'];?><br><?
                                    if (!empty($contactPersonParams['phoneNumbers'])) {
                                        foreach ($contactPersonParams['phoneNumbers'] as $phoneNumber) {
                                            ?><?= t(strtoupper($phoneNumber['type'])); ?>: <?=$phoneNumber['number']?><br><?
                                        }
                                    }
		                            ?><?= $contactPersonParams['emailAddress']; ?><br><br><?
                                }
                                break;
                        }
                        ?>
                        
                        <?= $registration['BankAccount']['iban'] ?><br>
	                    t.n.v. <?= $registration['BankAccount']['ascription']; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-5">
                        Fonds:
                    </div>
                    <div class="col-xs-12 col-sm-7">
                        <?= $fund->name; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-5">
                        Aantal participaties:
                    </div>
                    <div class="col-xs-12 col-sm-7">
                        <?= $registration['Participation']['amount'] ?> zijnde
                        <?= \app\h::formatCurrency($registration['Participation']['amount'] * $participationsValue); ?> <?= $registration['EmissionCost']; ?>
                    </div>
                </div>
            </div>
            <hr class="margin-t-small" />
            <?
                $app->renderPartial('registration/navigation-buttons', ['button' => true, 'prevUrl' => $prevUrl, 'nextUrl' => $nextUrl]);
            ?>
        </div>
    </div>
</div>