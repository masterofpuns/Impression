<div class="row small-inner-margins">
    <div class="form-group col-xs-12">
        <label for="Registration[ContactPerson][<?= $contactPersonIdx ?>][emailAddress]">E-mailadres:</label>
        <input type="email" name="Registration[ContactPerson][<?= $contactPersonIdx ?>][emailAddress]" class="form-control" required value="<?= isset($registration['ContactPerson'][$contactPersonIdx]['emailAddress']) ? $registration['ContactPerson'][$contactPersonIdx]['emailAddress'] : '' ?>">
    </div>
</div>