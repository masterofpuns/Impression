<? 
    $participationsValue = empty($fund->currentValue) ? $fund->value : $fund->currentValue;
    $pseudoUboNames = [];
?>
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
                                    foreach ($contactPersonParams['phoneNumbers'] as $phoneNumber) {
                                        ?><?= t(strtoupper($phoneNumber['type'])); ?>: <?=$phoneNumber['number']?><br><?
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
                                    foreach ($contactPersonParams['phoneNumbers'] as $phoneNumber) {
                                        ?><?= t(strtoupper($phoneNumber['type'])); ?>: <?=$phoneNumber['number']?><br><?
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
                <? if ($registration['Relation']['type'] == 'organization') { ?>
                    <div class="row">
                        <div class="col-xs-12 col-sm-5">
                            Bestuurder<?= count($registration['ContactPerson']) > 1 ? 's' : '' ?>:
                        </div>
                        <div class="col-xs-12 col-sm-7">
                            <?
                            foreach($registration['ContactPerson'] as $key => $contactPersonParams) {
                                if (isset($contactPersonParams['isPseudoUbo']) && $contactPersonParams['isPseudoUbo'] == 1) {
                                    $pseudoUboNames[] = $contactPersonParams['name'];
                                }

                                ?><?=$contactPersonParams['name'];?><br><?
                                if (isset($contactPersonParams['street'])) {
                                    // Bestuurder die ook UBO is
                                    echo $contactPersonParams['street'].' '.$contactPersonParams['number'].$contactPersonParams['numberSuffix'].'<br />';
                                    echo $contactPersonParams['postalCode'].' '.$contactPersonParams['city'].'<br />';
                                    echo $contactPersonParams['country'].'<br /><br />';

                                }
                                foreach ($contactPersonParams['phoneNumbers'] as $phoneNumber) {
                                    ?><?= t(strtoupper($phoneNumber['type'])); ?>: <?=$phoneNumber['number']?><br><?
                                }
                                ?><?= $contactPersonParams['emailAddress']; ?><br><br><?
                            }
                            ?>
                        </div>
                    </div>
                    <? if (count($registration['Ubo']) > 0) { ?>
                        <div class="row">
                            <div class="col-xs-12 col-sm-5">
                                Additionele UBO<?= count($registration['Ubo']) > 1 ? "'s" : '' ?>:
                            </div>
                            <div class="col-xs-12 col-sm-7">
                                <?
                                foreach($registration['Ubo'] as $key => $uboParams) {
                                    ?><?=$uboParams['name'];?><br>
                                    <?
                                    if (isset($uboParams['street'])) {
                                        echo $uboParams['street'].' '.$uboParams['number'].$uboParams['numberSuffix'].'<br />';
                                        echo $uboParams['postalCode'].' '.$uboParams['city'].'<br />';
                                        echo $uboParams['country'].'<br />';
                                    }
                                    ?>
                                    <br />
                                    <?
                                }
                                ?>
                            </div>
                        </div>
                    <? } ?>                    
                    <? if (count($pseudoUboNames) > 0) { ?>
                        <div class="row">
                            <div class="col-xs-12 col-sm-5">
                                Pseudo UBO<?= count($pseudoUboNames) > 1 ? "'s" : '' ?>:
                            </div>
                            <div class="col-xs-12 col-sm-7">
                                <?
                                foreach($pseudoUboNames as $pseudoUboName) {
                                    ?><?= $pseudoUboName ?><br><?
                                }
                                ?><br />
                            </div>
                        </div>
                    <? } ?>

                <? } ?>
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