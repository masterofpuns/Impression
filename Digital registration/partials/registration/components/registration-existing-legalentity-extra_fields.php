<div class="row flex flex-align-center" data-hook="bind-fields">
    <p class="col-xs-4 col-sm-5">
        Wat zijn de activiteiten van de rechtspersoon?
    </p>
    <p class="col-xs-8 col-sm-7">
		<input type="text" data-dummy="Registration[Relation][legalEntityActivities]" class="form-control" required value="<?= isset($registration['Relation']['legalEntityActivities']) ? $registration['Relation']['legalEntityActivities'] : '' ?>">
    </p>
</div>
<hr>
<div class="row flex flex-align-center" data-hook="bind-fields">
    <p class="col-xs-4 col-sm-5">
        Wat is de herkomst van middelen? <span class="info-tooltip" data-toggle="tooltip" title="Dit is een vereiste vanuit de Wet ter voorkoming van witwassen en financieren van terrorisme (Wwft)">i</span>
        <br><em>(bijv. winst uit onderneming/ingebracht door aandeelhouders)</em>
    </p>
    <p class="col-xs-8 col-sm-7">
        <input type="text" data-dummy="Registration[Relation][originOfResources]" class="form-control" required value="<?= isset($registration['Relation']['originOfResources']) ? $registration['Relation']['originOfResources'] : '' ?>">
    </p>
</div>