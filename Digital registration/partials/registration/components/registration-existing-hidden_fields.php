<?
$relationType = !empty($relation->object) && !empty($relation->object->parent) ? $relation->object->parent->type : $relation->type;
switch ($relationType) {
    case 'contactPerson':
    case 'collective':
        ?>
        <input required type="hidden" name="Registration[ContactPerson][<?= $relation->getObject()->id; ?>][currentProfession]" value="<?= isset($registration['ContactPerson'][$relation->getObject()->id]['currentProfession']) ? $registration['ContactPerson'][$relation->getObject()->id]['currentProfession'] : '' ?>" />
        <input required type="hidden" name="Registration[ContactPerson][<?= $relation->getObject()->id; ?>][industry]" value="<?= isset($registration['ContactPerson'][$relation->getObject()->id]['industry']) ? $registration['ContactPerson'][$relation->getObject()->id]['industry'] : '' ?>" />
        <input required type="hidden" name="Registration[ContactPerson][<?= $relation->getObject()->id; ?>][originOfResources]" value="<?= isset($registration['ContactPerson'][$relation->getObject()->id]['originOfResources']) ? $registration['ContactPerson'][$relation->getObject()->id]['originOfResources'] : '' ?>" />
        <?
        break;
    case 'organization':
        $otherEmploymentToggle = '';
        if (isset($registration['ContactPerson'][$relation->getObject()->id]['otherEmployment'])) {
            if (!is_null($registration['ContactPerson'][$relation->getObject()->id]['otherEmployment']) && $registration['ContactPerson'][$relation->getObject()->id]['otherEmployment'] != '') {
                $otherEmploymentToggle = 1;
            }
	        if (!is_null($registration['ContactPerson'][$relation->getObject()->id]['otherEmployment']) && $registration['ContactPerson'][$relation->getObject()->id]['otherEmployment'] == '') {
		        $otherEmploymentToggle = 0;
	        }
        }
        
        ?>
        <input type="hidden" name="Registration[ContactPerson][<?= $relation->getObject()->id; ?>][otherEmployment]" value="<?= isset($registration['ContactPerson'][$relation->getObject()->id]['otherEmployment']) ? $registration['ContactPerson'][$relation->getObject()->id]['otherEmployment'] : '' ?>">
        <input required type="hidden" name="Registration[ContactPerson][<?= $relation->getObject()->id; ?>][otherEmploymentToggle]" value="<?= $otherEmploymentToggle; ?>">
        <?
        break;
}
?>

<input required type="hidden" name="Registration[ContactPerson][<?= $relation->getObject()->id; ?>][isPep]" value="<?= isset($registration['ContactPerson'][$relation->getObject()->id]['isPep']) ? $registration['ContactPerson'][$relation->getObject()->id]['isPep'] : "" ?>" />
<input required type="hidden" name="Registration[ContactPerson][<?= $relation->getObject()->id; ?>][nationality]" value="<?= isset($registration['ContactPerson'][$relation->getObject()->id]['nationality']) ? $registration['ContactPerson'][$relation->getObject()->id]['nationality'] : "" ?>" />

<? if (array_key_exists('residentOfUnitedStates', $registration['ContactPerson'][$relation->getObject()->id]) && $relationType != 'organization'): ?>
<input required type="hidden" name="Registration[ContactPerson][<?= $relation->getObject()->id; ?>][residentOfUnitedStates]" value="<?= isset($registration['ContactPerson'][$relation->getObject()->id]['residentOfUnitedStates']) ? $registration['ContactPerson'][$relation->getObject()->id]['residentOfUnitedStates'] : "" ?>" />
<? endif; ?>
<?
// wanneer er geen primair emailadres bestaat voor de relatie EN er een user bestaat die de registratie nog niet heeft afgerond
// OF wanneer er geen primair emailadres bestaat EN geen user bestaat
if (
    empty($relation->getPrimaryEmailAddress()) &&
    (
        (
            !empty($relation->user) &&
            is_int($relation->user->emailAddress)
        ) ||
        empty($relation->user)
    )
) {
?>
<input required type="hidden" name="Registration[ContactPerson][<?= $relation->getObject()->id; ?>][emailAddressForInvitation]" value="<?= isset($registration['ContactPerson'][$relation->getObject()->id]['emailAddressForInvitation']) ? $registration['ContactPerson'][$relation->getObject()->id]['emailAddressForInvitation'] : '' ?>">
<?
}
?>
<? if ($relationType !== 'organization'): ?>
<input required type="hidden" name="Registration[ContactPerson][<?= $relation->getObject()->id; ?>][hasVisitingAddress]" value="<?= !empty($relation->getVisitingAddress()) ? true : false ?>" />
<? endif; ?>