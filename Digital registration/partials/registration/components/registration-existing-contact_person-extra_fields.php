<?
use \app\h;
?>
<div data-hook="bind-fields">
    <div class="row flex flex-align-center">
        <p class="col-xs-12 text-wwtf">
            Onderstaande vragen zijn een vereiste vanuit de Wet ter voorkoming van witwassen en financieren van terrorisme (Wwft)
        </p>
    </div>
    <div class="row flex flex-align-center">
        <p class="col-xs-4 col-sm-5">
            PEP <span class="info-tooltip" data-toggle="tooltip" title="Een natuurlijk persoon die een prominente publieke functie bekleedt of bekleed heeft, alsmede directe familieleden en personen met wie zij een nauwe zakelijke relatie hebben">i</span>
        </p>
        <p class="col-xs-8 col-sm-7">
            <span class="dropdown-styling">
                <select class="form-control" data-dummy="Registration[ContactPerson][<?= $contactPersonRelation->getObject()->id; ?>][isPep]" required>
                    <option value="" hidden>Selecteer...</option>
                    <option value="0" <?= isset($registration['ContactPerson'][$contactPersonRelation->getObject()->id]['isPep']) && !is_null($registration['ContactPerson'][$contactPersonRelation->getObject()->id]['isPep']) && $registration['ContactPerson'][$contactPersonRelation->getObject()->id]['isPep'] == 0 ? 'selected' : ''; ?>>Nee</option>
                    <option value="1" <?= isset($registration['ContactPerson'][$contactPersonRelation->getObject()->id]['isPep']) && !is_null($registration['ContactPerson'][$contactPersonRelation->getObject()->id]['isPep']) && $registration['ContactPerson'][$contactPersonRelation->getObject()->id]['isPep'] == 1 ? 'selected' : ''; ?>>Ja</option>
                </select>
                <img class="hig-select" src="/app/templates/front/assets/images/icons/dropdown.svg">
            </span>
        </p>
    </div>

    <hr>
    <div class="row flex flex-align-center">
        <p class="col-xs-4 col-sm-5">
            Nationaliteit
        </p>
        <p class="col-xs-8 col-sm-7">
            <span class="dropdown-styling">
                <select data-dummy="Registration[ContactPerson][<?= $contactPersonRelation->getObject()->id; ?>][nationality]" class="form-control" required data-hook="registration-nationality">
                    <option value="" hidden>Selecteer...</option>
                    <? foreach ($app->getCountries("nationality") as $country): if (empty($country->nationality)) { continue; } ?>
                    <option
                        data-region="<?= $country->region; ?>"
                        value="<?=$country->nationality;?>"
                        <?= isset($registration['ContactPerson'][$contactPersonRelation->getObject()->id]['nationality']) && $registration['ContactPerson'][$contactPersonRelation->getObject()->id]['nationality'] == $country->nationality ? 'selected' : ''; ?>
                        >
                        <?=$country->nationality;?>
                    </option>
                    <? endforeach; ?>
                </select>
                <img class="hig-select" src="/app/templates/front/assets/images/icons/dropdown.svg">
            </span>
        </p>
    </div>

    <? if (empty($contactPersonRelation->getObject()->parent) || (!empty($contactPersonRelation->getObject()->parent) && $contactPersonRelation->getObject()->parent->type == 'collective')): ?>
    <hr>
    <div class="row flex flex-align-center">
        <p class="col-xs-4 col-sm-5">
            Bent u Amerikaans staatsburger of woonachtig in de Verenigde staten?
        </p>
        <p class="col-xs-8 col-sm-7">
            <span class="dropdown-styling">
                <select class="form-control" data-dummy="Registration[ContactPerson][<?= $contactPersonRelation->getObject()->id; ?>][residentOfUnitedStates]" required>
                    <option value="" hidden>Selecteer...</option>
                    <option value="0" <?= isset($registration['ContactPerson'][$contactPersonRelation->getObject()->id]['residentOfUnitedStates']) && !is_null($registration['ContactPerson'][$contactPersonRelation->getObject()->id]['residentOfUnitedStates']) && $registration['ContactPerson'][$contactPersonRelation->getObject()->id]['residentOfUnitedStates'] == 0 ? 'selected' : ''; ?>>Nee</option>
                    <option value="1" <?= isset($registration['ContactPerson'][$contactPersonRelation->getObject()->id]['residentOfUnitedStates']) && !is_null($registration['ContactPerson'][$contactPersonRelation->getObject()->id]['residentOfUnitedStates']) && $registration['ContactPerson'][$contactPersonRelation->getObject()->id]['residentOfUnitedStates'] == 1 ? 'selected' : ''; ?>>Ja</option>
                </select>
                <img class="hig-select" src="/app/templates/front/assets/images/icons/dropdown.svg">
            </span>
        </p>
    </div>
    <? endif; ?>

    <?
    $relationType = !empty($contactPersonRelation->object) && !empty($contactPersonRelation->object->parent) ? $contactPersonRelation->object->parent->type : $contactPersonRelation->type;
    switch ($relationType) {
        case 'contactPerson':
        case 'collective':
            ?>
            <hr>
            <div class="row flex flex-align-center">
                <p class="col-xs-4 col-sm-5">
                    Wat is de herkomst van middelen?
                    <br><em>(bijv. salaris / erfenis / verkoop onderneming / ondernemingsactiviteiten)</em>
                </p>
                <p class="col-xs-8 col-sm-7">
                    <input type="text" data-dummy="Registration[ContactPerson][<?= $contactPersonRelation->getObject()->id; ?>][originOfResources]" class="form-control" required value="<?= isset($registration['ContactPerson'][$contactPersonRelation->getObject()->id]['originOfResources']) ? $registration['ContactPerson'][$contactPersonRelation->getObject()->id]['originOfResources'] : '' ?>">
                </p>
            </div>
            <hr>
            <div class="row flex flex-align-center">
                <p class="col-xs-4 col-sm-5">
                    Wat is uw huidige beroep?
                </p>
                <p class="col-xs-8 col-sm-7">
                    <input type="text" data-dummy="Registration[ContactPerson][<?= $contactPersonRelation->getObject()->id; ?>][currentProfession]" class="form-control" required value="<?= isset($registration['ContactPerson'][$contactPersonRelation->getObject()->id]['currentProfession']) ? $registration['ContactPerson'][$contactPersonRelation->getObject()->id]['currentProfession'] : '' ?>">
                </p>
            </div>

            <hr>
            <div class="row flex flex-align-center">
                <p class="col-xs-4 col-sm-5">
                    In welke branche bent u werkzaam (geweest)?
                </p>
                <p class="col-xs-8 col-sm-7">
                    <input type="text" data-dummy="Registration[ContactPerson][<?= $contactPersonRelation->getObject()->id; ?>][industry]" class="form-control" required value="<?= isset($registration['ContactPerson'][$contactPersonRelation->getObject()->id]['industry']) ? $registration['ContactPerson'][$contactPersonRelation->getObject()->id]['industry'] : '' ?>">
                </p>
            </div>
            <?
            break;
        case 'organization':
            ?>
            <hr>
            <div class="row flex flex-align-center">
                <p class="col-xs-4 col-sm-5">
                    Heeft u nog andere dienstbetrekkingen?
                </p>
                <p class="col-xs-8 col-sm-7">
                    <span class="dropdown-styling">
                        <select class="form-control" data-toggle="Registration[ContactPerson][<?= $contactPersonRelation->getObject()->id; ?>][otherEmployment]" required data-hook="toggle-employment" data-dummy="Registration[ContactPerson][<?= $contactPersonRelation->getObject()->id; ?>][otherEmploymentToggle]">
                            <option value="" hidden>Selecteer...</option>
                            <option value="0" <?= isset($registration['ContactPerson'][$contactPersonRelation->getObject()->id]['otherEmployment']) && !is_null($registration['ContactPerson'][$contactPersonRelation->getObject()->id]['otherEmployment']) && $registration['ContactPerson'][$contactPersonRelation->getObject()->id]['otherEmployment'] == '' ? 'selected' : ''; ?>>Nee</option>
                            <option value="1" <?= isset($registration['ContactPerson'][$contactPersonRelation->getObject()->id]['otherEmployment']) && !is_null($registration['ContactPerson'][$contactPersonRelation->getObject()->id]['otherEmployment']) && $registration['ContactPerson'][$contactPersonRelation->getObject()->id]['otherEmployment'] != '' ? 'selected' : ''; ?>>Ja</option>
                        </select>
                        <img class="hig-select" src="/app/templates/front/assets/images/icons/dropdown.svg">
                    </span>
                </p>
            </div>
            <div class="row flex flex-align-center <?= !empty($registration['ContactPerson'][$contactPersonRelation->getObject()->id]['otherEmployment']) ? '' : 'hide'; ?>" data-hook="show-employment">
                <p class="col-xs-4 col-sm-5">
                    namelijk
                </p>
                <p class="col-xs-8 col-sm-7">
                    <input type="text" data-dummy="Registration[ContactPerson][<?= $contactPersonRelation->getObject()->id; ?>][otherEmployment]" class="form-control" value="<?= isset($registration['ContactPerson'][$contactPersonRelation->getObject()->id]['otherEmployment']) ? $registration['ContactPerson'][$contactPersonRelation->getObject()->id]['otherEmployment'] : '' ?>" data-hook="input-employment">
                </p>
            </div>
            <?
            break;
    }
    
    // wanneer er geen primair emailadres bestaat voor de relatie EN er een user bestaat die de registratie nog niet heeft afgerond
    // OF wanneer er geen primair emailadres bestaat EN geen user bestaat
    if (
        empty($contactPersonRelation->getPrimaryEmailAddress()) &&
        (
            (
	            !empty($contactPersonRelation->user) &&
	            is_int($contactPersonRelation->user->emailAddress)
            ) ||
            empty($contactPersonRelation->user)
        )
    ) {
        ?>
        <hr>
        <div class="row flex flex-align-center">
            <p class="col-xs-4 col-sm-5">
                E-mailadres t.b.v. het versturen van de e-mail voor ondertekening.
            </p>
            <p class="col-xs-8 col-sm-7">
                <input type="text" data-dummy="Registration[ContactPerson][<?= $contactPersonRelation->getObject()->id; ?>][emailAddressForInvitation]" class="form-control" required value="<?= isset($registration['ContactPerson'][$contactPersonRelation->getObject()->id]['emailAddressForInvitation']) ? $registration['ContactPerson'][$contactPersonRelation->getObject()->id]['emailAddressForInvitation'] : '' ?>">
            </p>
        </div>
        <?
    }
    ?>
</div>