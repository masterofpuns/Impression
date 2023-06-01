<?php
/** @var \app\hig\MRegistration $registration; */

$colspan = count($registration->registrationCard->contactPersons) - 1;
$registrationCard = $registration->registrationCard;

$registrationUboEntities = [];
foreach ($registrationCard->contactPersons as $contactPerson) {
    if (
        !(
            (!is_null($contactPerson->isUbo) && $contactPerson->isUbo) ||
            (!is_null($contactPerson->isPseudoUbo) && $contactPerson->isPseudoUbo)
        )
    ) {
        continue;
    }

    $registrationUboEntities[] = $contactPerson;
}
foreach ($registrationCard->ubos as $registrationUbo) {
    $registrationUboEntities[] = $registrationUbo;
}

?>
<style>
    td {
    width: 250px;
}
    #table-ubo_section {
    margin: 25px 0;
    border: none;
    padding: 0;
    border-spacing: 0;
    width: 100%;
}
    #table-ubo_section .heading {
    font-weight: bold;
}
    #table-ubo_section tr {
    padding: 0;
    margin: 0;
}
    #table-ubo_section td {
    vertical-align: top;
    margin: 0;
    padding: 0;
    word-wrap:break-word;
    overflow: auto;
}
    .heading {
    width: 380px;
}
    .offset-top {
    padding-top: 20px;
}
    .subcat {
    font-weight: normal;
    font-style: italic;
}
</style>
<table class="table" id="table-ubo_section">
    <? // UBOS ?>
    <?
    if (!empty($registrationUboEntities)) {
        foreach ($registrationUboEntities as $registrationUboEntity) {
            ?>
            <tr>
                <td class="heading subcat">Naam</td>
                <td colspan="<?=$colspan;?>"><?= $registrationUboEntity->getName(); ?></td>
            </tr>
            <tr>
                <td class="heading subcat">PEP</td>
                <td colspan="<?=$colspan;?>"><?= isset($registrationUboEntity->isPep) && $registrationUboEntity->isPep ? 'Ja' : 'Nee'; ?></td>
            </tr>
            <? if (!empty($registrationUboEntity->getAddress())) { ?>
                <tr>
                    <td class="heading subcat">Woonadres</td>
                    <td colspan="<?=$colspan;?>">
                        <?= $registrationUboEntity->getAddress()->street; ?> <?= $registrationUboEntity->getAddress()->number; ?><?= $registrationUboEntity->getAddress()->numberSuffix; ?> <br />
                        <?= $registrationUboEntity->getAddress()->postalCode; ?> <?= $registrationUboEntity->getAddress()->city; ?> <br />
                        <? if ($registrationUboEntity->getAddress()->country !== strtolower('nederland')) { ?>
                            <?= $registrationUboEntity->getAddress()->country; ?>
                        <? } ?>
                    </td>
                </tr>
            <? } ?>
            <? if (!property_exists($registrationUboEntity, 'isPseudoUbo') || !$registrationUboEntity->isPseudoUbo) { ?>
            <tr>
                <td class="heading subcat">(Indirect) Belang</td>
                <td colspan="<?=$colspan;?>"><?= isset($registrationUboEntity->interestRate) ? $registrationUboEntity->interestRate : 0; ?>%</td>
            </tr>
            <tr>
                <td class="heading subcat">Zeggenschap</td>
                <td colspan="<?=$colspan;?>"><?= isset($registrationUboEntity->ownershipRate) ? $registrationUboEntity->ownershipRate : 0; ?>%</td>
            </tr>
            <? } ?>
            <tr>
                <td class="heading subcat">Bent u een Amerikaanse staatsburger of woonachtig in de Verenigde Staten?</td>
                <td colspan="<?=$colspan;?>"><?= isset($registrationUboEntity->residentOfUnitedStates) && $registrationUboEntity->residentOfUnitedStates ? 'Ja' : 'Nee'; ?></td>
            </tr>
            <? if (isset($registrationUboEntity->isPseudoUbo)) { ?>
                <tr>
                    <td class="heading subcat">Pseudo UBO</td>
                    <td colspan="<?=$colspan;?>"><?= $registrationUboEntity->isPseudoUbo ? 'Ja' : 'Nee'; ?></td>
                </tr>
            <? } ?>
            <tr><td colspan="2">&nbsp;</td></tr>
        <? } ?>
    <? } ?>
</table>