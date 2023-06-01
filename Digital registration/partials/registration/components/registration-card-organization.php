<?
/** @var \app\hig\MRegistration $registration; */

$colspan = count($registration->registrationCard->contactPersons) - 1;
$registrationCard = $registration->registrationCard;
?>
<style>
    td {
        width: 175px;
    }
    #table-copy_of_registration {
        margin: 25px 0;
        border: none;
        padding: 0;
        border-spacing: 0;
        width: 100%;
    }
    #table-copy_of_registration .heading {
        font-weight: bold;
    }
    #table-copy_of_registration tr {
        padding: 0;
        margin: 0;
    }
    #table-copy_of_registration td {
        vertical-align: top;
        margin: 0;
        padding: 0;
        word-wrap:break-word;
        overflow: auto;
    }
    .heading {
        width: 350px;
    }
    .offset-top {
        padding-top: 20px;
    }
    .subcat {
        font-weight: normal;
        font-style: italic;
    }
    .descriptive {
        word-wrap: break-word;
    }
</style>
<table class="table" id="table-copy_of_registration">
	<? // RELATION ?>
    <tr>
        <td class="heading">Participant</td>
        <td colspan="<?=$colspan;?>"></td>
    </tr>
    <tr>
        <td class="heading subcat">Naam</td>
        <td class="descriptive" colspan="<?=$colspan;?>"><?= $registrationCard->relation->name; ?></td>
    </tr>
	<? // ADDRESS ?>
    <tr>
        <td class="heading subcat">Vestigingsadres</td>
        <td colspan="<?=$colspan;?>">
			<?= $registrationCard->address->street; ?> <?= $registrationCard->address->number; ?><?= $registrationCard->address->numberSuffix; ?> <br />
			<?= $registrationCard->address->postalCode; ?> <?= $registrationCard->address->city; ?> <br />
			<? if ($registrationCard->address->country !== strtolower('nederland')): ?>
			<?= $registrationCard->address->country; ?>
			<? endif; ?>
        </td>
    </tr>
	<? // BANK ACCOUNT ?>
    <tr>
        <td class="heading subcat">IBAN-rekeningnummer t.b.v. betaling en uitkering</td>
        <td colspan="<?=$colspan;?>"><?= $registrationCard->bankAccount->iban; ?> t.n.v. <?= $registrationCard->bankAccount->ascription; ?></td>
    </tr>
	<? // CHAMBER OF COMMERCE ?>
    <tr>
        <td class="heading subcat">KvK nummer</td>
        <td colspan="<?=$colspan;?>"><?= $registrationCard->relation->cocNumber; ?></td>
    </tr>
    <? // LEGALENTITY ACTIVITIES ?>
    <tr>
        <td class="heading subcat">Activiteiten van rechtspersoon</td>
        <td colspan="<?=$colspan;?>"><?= $registrationCard->relation->legalEntityActivities; ?></td>
    </tr>
    <? // LEGALENTITY ACTIVITIES ?>
    <tr>
        <td class="heading subcat">Wat is de herkomst van middelen?</td>
        <td colspan="<?=$colspan;?>"><?= $registrationCard->relation->originOfResources; ?></td>
    </tr>
	<? // CORRESPONDENCE TYPE ?>
    <tr>
        <td class="heading subcat">Correspondentiewijze</td>
        <td colspan="<?=$colspan;?>"><?= $registrationCard->relation->transactionalMail == 1 ? 'Per post' : 'Digitaal'; ?></td>
    </tr>

    <? // CONTACTPERSONEN ?>
    <tr>
        <td class="heading offset-top">Bestuurders</td>
        <td>&nbsp;</td>
    </tr>
    <? foreach ($registrationCard->contactPersons as $contactPerson): ?>
        <tr>
            <td class="heading subcat">Naam</td>
            <td colspan="<?=$colspan;?>"><?= $contactPerson->name; ?></td>
        </tr>
        <? if (
            (
                (isset($contactPerson->isUbo) && $contactPerson->isUbo) ||
                (isset($contactPerson->isPseudoUbo) && $contactPerson->isPseudoUbo)
            ) &&
            !empty($contactPerson->address)
        ): ?>
        <tr>
            <td class="heading subcat">Woonadres</td>
            <td colspan="<?=$colspan;?>">
                <?= $contactPerson->address->street; ?> <?= $contactPerson->address->number; ?><?= $contactPerson->address->numberSuffix; ?> <br />
                <?= $contactPerson->address->postalCode; ?> <?= $contactPerson->address->city; ?> <br />
                <? if ($contactPerson->address->country !== strtolower('nederland')): ?>
                <?= $contactPerson->address->country; ?>
                <? endif; ?>
            </td>
        </tr>
        <? endif; ?>
        <tr>
            <td class="heading subcat">Telefoonnummer(s)</td>
            <td>
                <? foreach ($contactPerson->getPhoneNumbers() as $phoneNumber): ?>
                    <?= $phoneNumber->number; ?><br />
                <? endforeach; ?>
            </td>
        </tr>
        <tr>
            <td class="heading subcat">E-mailadres</td>
            <td colspan="<?=$colspan;?>"><?= $contactPerson->emailAddress; ?></td>
        </tr>
        <tr>
            <td class="heading subcat">Nationaliteit</td>
            <td colspan="<?=$colspan;?>"><?= $contactPerson->nationality; ?></td>
        </tr>
        <tr>
            <td class="heading subcat">PEP</td>
            <td colspan="<?=$colspan;?>"><?= isset($contactPerson->isPep) && $contactPerson->isPep ? 'Ja' : 'Nee'; ?></td>
        </tr>
        <tr>
            <td class="heading subcat">Bent u een Amerikaanse staatsburger of woonachtig in de Verenigde Staten?</td>
            <td colspan="<?=$colspan;?>"><?= isset($contactPerson->residentOfUnitedStates) && $contactPerson->residentOfUnitedStates ? 'Ja' : 'Nee'; ?></td>
        </tr>
        <tr>
            <td class="heading subcat">Heeft u nog andere dienstbetrekkingen?</td>
            <td colspan="<?=$colspan;?>"><?= !empty($contactPerson->otherEmployment) ? $contactPerson->otherEmployment : "Nee"; ?></td>
        </tr>
        <? if (isset($contactPerson->isUbo)): ?>
            <tr>
                <td class="heading subcat">UBO</td>
                <td colspan="<?=$colspan;?>"><?= $contactPerson->isUbo ? 'Ja' : 'Nee'; ?></td>
            </tr>
            <tr>
                <td class="heading subcat">(Indirect) Belang</td>
                <td colspan="<?=$colspan;?>"><?= isset($contactPerson->interestRate) ? $contactPerson->interestRate : 0; ?>%</td>
            </tr>
            <tr>
                <td class="heading subcat">Zeggenschap</td>
                <td colspan="<?=$colspan;?>"><?= isset($contactPerson->ownershipRate) ? $contactPerson->ownershipRate : 0; ?>%</td>
            </tr>
        <? endif; ?>
        <? if (isset($contactPerson->isPseudoUbo)): ?>
            <tr>
                <td class="heading subcat">Pseudo UBO</td>
                <td colspan="<?=$colspan;?>"><?= $contactPerson->isPseudoUbo ? 'Ja' : 'Nee'; ?></td>
            </tr>
        <? endif; ?>
	    <? if (empty($contactPerson->relationId)) { ?>
        <tr>
            <td class="heading subcat" style="padding-bottom: 10px;">Legitimatiebewijs ge&uuml;pload</td>
            <td  style="padding-bottom: 10px;"><?= $contactPerson->hasUploadedIdFile() ? 'Ja' : 'Nee'; ?></td>
        </tr>
        <? } ?>
    <? endforeach; ?>


    <? // UBOS ?>
    <? if (count($registrationCard->ubos) > 0) { ?>
        <tr>
            <td class="heading">Additionele UBO<?= count($registrationCard->ubos) > 1 ? "'s" : '' ?>:</td>
            <td>&nbsp;</td>
        </tr>
        <? foreach ($registrationCard->ubos as $registrationUbo): ?>
            <tr>
                <td class="heading subcat" style="padding-bottom: 10px;">Naam</td>
                <td colspan="<?=$colspan;?>"><?= $registrationUbo->name; ?></td>
            </tr>
            <tr>
                <td class="heading subcat" style="padding-bottom: 10px;">Woonadres</td>
                <td colspan="<?=$colspan;?>"><?= !empty($registrationUbo->address) ? $registrationUbo->address : "Onbekend"; ?></td>
            </tr>
            <tr>
                <td class="heading subcat">Nationaliteit</td>
                <td colspan="<?=$colspan;?>"><?= $registrationUbo->nationality; ?></td>
            </tr>
            <tr>
                <td class="heading subcat">PEP</td>
                <td colspan="<?=$colspan;?>"><?= isset($registrationUbo->isPep) && $registrationUbo->isPep ? 'Ja' : 'Nee'; ?></td>
            </tr>
            <tr>
                <td class="heading subcat">Bent u een Amerikaanse staatsburger of woonachtig in de Verenigde Staten?</td>
                <td colspan="<?=$colspan;?>"><?= isset($registrationUbo->residentOfUnitedStates) && $registrationUbo->residentOfUnitedStates ? 'Ja' : 'Nee'; ?></td>
            </tr>
            <tr>
                <td class="heading subcat">(Indirect) Belang</td>
                <td colspan="<?=$colspan;?>"><?= isset($registrationUbo->interestRate) ? $registrationUbo->interestRate : 0; ?>%</td>
            </tr>
            <tr>
                <td class="heading subcat">Zeggenschap</td>
                <td  style="padding-bottom: 10px;" colspan="<?=$colspan;?>"><?= isset($registrationUbo->ownershipRate) ? $registrationUbo->ownershipRate : 0; ?>%</td>
            </tr>
        <? endforeach; ?>
    <? } ?>

</table>