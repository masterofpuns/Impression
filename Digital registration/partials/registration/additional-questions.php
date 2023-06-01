<div class="row small-inner-margins">
    <div class="form-group col-xs-12">
        <strong><span class="underlined"> Aanvullende vragen</span>
            <span class="info-tooltip" data-toggle="tooltip" title="Dit is een vereiste vanuit de Wet ter voorkoming van witwassen en financieren van terrorisme (Wwft)">i</span>
         </strong>
    </div>
    <div class="form-group xs-hide col-sm-6"></div>
</div>
<div class="row small-inner-margins">
    <div class="form-group  col-xs-12 col-sm-8">
        Wat is uw huidige beroep?
    </div>
    <div class="form-group  col-xs-12 col-sm-4">
        <input type="text" name="Registration[ContactPerson][<?= $contactPersonIdx; ?>][currentProfession]" class="form-control" required value="<?= isset($registration['ContactPerson'][$contactPersonIdx]['currentProfession']) ? $registration['ContactPerson'][$contactPersonIdx]['currentProfession'] : '' ?>">
    </div>
</div>
<div class="row small-inner-margins">
    <div class="form-group  col-xs-12 col-sm-8">
        In welke branche bent u werkzaam (geweest)?
    </div>
    <div class="form-group  col-xs-12 col-sm-4">
        <input type="text" name="Registration[ContactPerson][<?= $contactPersonIdx; ?>][industry]" class="form-control" required value="<?= isset($registration['ContactPerson'][$contactPersonIdx]['industry']) ? $registration['ContactPerson'][$contactPersonIdx]['industry'] : '' ?>">
    </div>
</div>
<div class="row small-inner-margins">
    <div class="form-group  col-xs-12 col-sm-8">
        Wat is de herkomst van middelen? <br><em>(bijv. salaris / erfenis / verkoop onderneming / ondernemingsactiviteiten)</em>
    </div>
    <div class="form-group  col-xs-12 col-sm-4">
        <input type="text" name="Registration[ContactPerson][<?= $contactPersonIdx; ?>][originOfResources]" class="form-control" required value="<?= isset($registration['ContactPerson'][$contactPersonIdx]['originOfResources']) ? $registration['ContactPerson'][$contactPersonIdx]['originOfResources'] : '' ?>">
    </div>
</div>
<div class="row small-inner-margins">
    <div class="form-group  col-xs-12 col-sm-8">
        Bent u een Amerikaanse staatsburger of woonachtig in de Verenigde Staten?
    </div>
    <div class="form-group col-xs-12 col-sm-4">
        <span class="dropdown-styling">
            <select class="form-control" name="Registration[ContactPerson][<?= $contactPersonIdx; ?>][residentOfUnitedStates]" required>
                <option value="" hidden>Selecteer...</option>
                <option value="1" <?= isset($registration['ContactPerson'][$contactPersonIdx]['residentOfUnitedStates']) && $registration['ContactPerson'][$contactPersonIdx]['residentOfUnitedStates'] == 1 ? 'selected' : ''; ?>>Ja</option>
                <option value="0" <?= isset($registration['ContactPerson'][$contactPersonIdx]['residentOfUnitedStates']) && $registration['ContactPerson'][$contactPersonIdx]['residentOfUnitedStates'] == 0 ? 'selected' : ''; ?>>Nee</option>
            </select>
            <img class="hig-select" src="/app/templates/front/assets/images/icons/dropdown.svg">
        </span>
    </div>
</div>
<div class="row small-inner-margins">
    <div class="form-group  col-xs-12 col-sm-8">
        PEP <span class="info-tooltip" data-toggle="tooltip" title="Een natuurlijk persoon die een prominente publieke functie bekleedt of bekleed heeft, alsmede directe familieleden en personen met wie zij een nauwe zakelijke relatie hebben">i</span>
    </div>
    <div class="form-group col-xs-12 col-sm-4">
        <span class="dropdown-styling">
            <select class="form-control" name="Registration[ContactPerson][<?= $contactPersonIdx; ?>][isPep]" required>
                <option value="" hidden>Selecteer...</option>
                <option value="1" <?= isset($registration['ContactPerson'][$contactPersonIdx]['isPep']) && $registration['ContactPerson'][$contactPersonIdx]['isPep'] == 1 ? 'selected' : ''; ?>>Ja</option>
                <option value="0" <?= isset($registration['ContactPerson'][$contactPersonIdx]['isPep']) && $registration['ContactPerson'][$contactPersonIdx]['isPep'] == 0 ? 'selected' : ''; ?>>Nee</option>
            </select>
            <img class="hig-select" src="/app/templates/front/assets/images/icons/dropdown.svg">
        </span>
    </div>
</div>