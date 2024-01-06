<div class='section' data-section='contact-persons'>
    <div data-hook="form-section-invalid_message"></div>
    <div data-hook="form-contact_person_container">
        <?php
        if (!empty($relation) && !empty($relation->id))
        {
            $contactPersonRelations = $relation->getContactPersons();
            if ($relation->type === 'COLLECTIVE')
            {
                $contactPersonRelations = $relation->typeObject->getNonParticipatingContactPersons();
            }

            $contactPersonIdx = 0;
            foreach ($contactPersonRelations as $contactPersonRelation)
            {
                $contactPersonRelation->convertBackendValues();
                $app->renderPartial(
                    'relation/form/components/contact-person',
                    [
                        'contactPersonRelation' => $contactPersonRelation,
                        'contactPersonIdx' => $contactPersonIdx++
                    ]
                );
            }
        }
        ?>
    </div>

    <div class="well">
        <div class="input-group">
            <button type="button" class="btn btn-outline-secondary-600" data-hook="form-add_contact_person"><i class='bi bi-plus me-1'></i><?= t('ADD_CONTACT_PERSON'); ?></button>
        </div>
    </div>
</div>