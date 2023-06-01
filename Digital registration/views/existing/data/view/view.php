<div class="container main-content home text-center">
    <div class="digital-registration-container">
        <? $app->renderPartial('registration/registration-steps', ['percentageComplete' => '12%', 'activeStep' => 'identification', 'fund' => $fund]); ?>
        <div class="digital-registration-formfields text-left">
                <h2>Onderstaande gegevens van u zijn bij ons bekend, controleer deze en klik op volgende</h2>
                <hr class="margin-t-small" />
                
                <? $app->renderPartial("relation/components/relation-name", ['name' => $relation->getName()] ); ?>
                <div class="address-container" data-replace-identifier="addresses">
                    <?
                    $visitingAddress = $relation->getVisitingAddress();
                    $app->renderPartial(
                        "relation/components/visiting-address",
                        [
                            'language' => $relation->getObject()->language,
                            'visitingAddress' => $visitingAddress,
                            'urlChangeVisitingAddress' => $relation->getUrlEditVisitingAddress(),
                            'disableChangePer' => true
                        ]
                    );
                    ?>
                    <hr class="content-indent">
                    <?
                    $postalEqualsVisiting = !empty($relation->getObject()->postalAddressEqualsVisitingAddress) ? true : false;
                    $postalAddress = $relation->getPostalAddress();
                    $app->renderPartial(
                        "relation/components/correspondence-address",
                        [
                            'language' => $relation->getObject()->language,
                            'postalAddress' => $postalAddress,
                            'visitingAddress' => $visitingAddress,
                            'postalEqualsVisiting' => $postalEqualsVisiting,
                            'urlChangePostalAddress' => $relation->getUrlEditPostalAddress(),
                            'disableChangePer' => true
                        ]
                    );
                    ?>
                    <hr>
                    <?
                    // extra fields
                    if ($relation->type == 'organization') {
	                    $app->renderPartial(
		                    "registration/components/registration-existing-legalentity-extra_fields",
		                    ["relation" => $relation, 'registration' => $registration]
	                    );
                    }
                    ?>
                </div>
                <?
                switch ($relation->type) {
                    case 'contactPerson':
                        // telefoonnummers
                        $app->renderPartial(
                            "relation/components/phonenumbers",
                            [
                                'phoneNumbers' => $relation->getPhoneNumbers(),
                                'urlAddPhoneNumber' => $relation->getUrlAddPhoneNumber(),
                                'edit' => true,
                                'dataReplaceIdentifier' => 'phonenumbersMain'
                            ]
                        );
                        ?><hr><?
                        // emailadres
                        $relationEmail = $relation->getPrimaryEmailAddress();
                        $app->renderPartial(
                            "relation/components/email-address",
                            [
                                "emailAddress" => ($relationEmail ? $relationEmail->address : ''),
                                'edit' => false
                            ]
                        );
                        ?><hr><?
                        // extra fields
                        $app->renderPartial(
                            "registration/components/registration-existing-contact_person-extra_fields",
                            ["contactPersonRelation" => $relation, 'registration' => $registration]
                        );
                        ?>
                        <form data-hook="registration-form" method="POST">
                        <?
                        $app->renderPartial(
                            "registration/components/registration-existing-hidden_fields",
                            ["relation" => $relation, 'registration' => $registration]
                        );
                        ?>
                        </form>
                        <?
                        break;
                    case 'collective':
                        // contactpersonen
                        $idx = 1;
                        foreach ($relation->object->getSortedContactPersons() as $contactPersonRelation) {
                            ?>
                            <div class="info-block">
                                <h2>Gegevens persoon <?=$idx?></h2>
                                <hr class="margin-b-medium margin-t-small">
                                <? $app->renderPartial("relation/components/relation-name", ['name' => $contactPersonRelation->getName()]); ?>
                                
                                <?
                                $app->renderPartial(
                                    "relation/components/contact-person-visiting-address",
                                    [
                                        'contactPersonRelation' => $contactPersonRelation,
                                        'language' => $contactPersonRelation->getObject()->language,
                                        'visitingAddress' => $contactPersonRelation->getVisitingAddress(),
                                        'urlChangeVisitingAddress' => $contactPersonRelation->getUrlEditVisitingAddress(),
                                        'edit' => true,
                                        'dataReplaceIdentifier' => 'visitingAddress' . $contactPersonRelation->id
                                    ]
                                );
                                ?>
                                <hr>
                                
                                <?
                                $edit = !empty($app->user->getRelations()) && array_key_exists($contactPersonRelation->id, $app->user->getRelations()) ? true : false;
                                $app->renderPartial(
                                    "relation/components/phonenumbers",
                                    [
                                        'phoneNumbers' => $contactPersonRelation->getPhoneNumbers(),
                                        'urlAddPhoneNumber' => $contactPersonRelation->getUrlAddPhoneNumber(),
                                        'edit' => $edit,
                                        'dataReplaceIdentifier' => 'phonenumbers' . $contactPersonRelation->id
                                    ]
                                );
                                ?>
                                <hr>
                                <?
                                $emailAddress = !empty($contactPersonRelation->getPrimaryEmailAddress()) ? $contactPersonRelation->getPrimaryEmailAddress()->address : "";
                                $app->renderPartial(
                                    "relation/components/email-address",
                                    [
                                        "emailAddress" => $emailAddress,
                                        "edit" => false
                                    ]
                                );
                                ?>
                                <hr>
                                <?
                                // extra fields
                                $app->renderPartial(
                                    "registration/components/registration-existing-contact_person-extra_fields",
                                    ["contactPersonRelation" => $contactPersonRelation, 'registration' => $registration]
                                );
                                ?>
                            </div>
                            <?
                            $idx++;
                        }
                        ?>
                        <form data-hook="registration-form" method="POST">
                        <?
                            foreach ($relation->getObject()->getSortedContactPersons() as $contactPersonRelation):
                                $app->renderPartial(
                                    "registration/components/registration-existing-hidden_fields",
                                    ["relation" => $contactPersonRelation, 'registration' => $registration]
                                );
                            endforeach;
                        ?>
                        </form>
                        <?
                        break;
                    case 'organization':
                        // bestuurders
                        ?>
                        <br>
                        <?
                        foreach ($relation->getObject()->getManagerRelations() as $managerRelation):
                        ?>
                        <div data-replace-identifier="managers">
                            <div class="info-block">
                                <div class="row flex flex-align-end">
                                    <div class="col-xs-10">
                                        <h2 class="has-info-tooltip">Bestuurder</h2>
                                        <span class="info-tooltip info-tooltip-title" data-toggle="tooltip" title="Indien u een wijziging wilt doorgeven omtrent de bestuurder(s), verzoeken wij u contact met ons op te nemen via info@hollandimmogroup.nl of 040-2352635">i</span>
                                    </div>
                                </div>
                                <hr>
                                <div data-replace-identifier="manager">
                                    <div class="row">
                                        <p class="col-xs-5">Naam:</p>
                                        <p class="col-xs-5"><?= $managerRelation->getName(); ?></p>
                                    </div>
                                    <hr>
                                    <?
                                    $primaryEmailAddress = $managerRelation->getPrimaryEmailAddress();
                                    $app->renderPartial(
                                        "relation/components/email-address",
                                        [
                                            "emailAddress" => empty($primaryEmailAddress) ? null : $primaryEmailAddress->address,
                                            'edit' => false
                                        ]
                                    );
                                    ?>
                                    <hr>
                                    <?
                                    $app->renderPartial(
                                        "relation/components/phonenumbers",
                                        [
                                            'phoneNumbers' => $managerRelation->getPhoneNumbers(),
                                            'urlAddPhoneNumber' => $managerRelation->getUrlAddPhoneNumber(),
                                            'edit' => empty($managerChange) && $app->user->isParticipant() ? true : false,
                                            'dataReplaceIdentifier' => 'phonenumbers' . $managerRelation->object->id
                                        ]
                                    );
                                    ?>
                                    <hr>
                                    <?
                                    // extra fields
                                    $app->renderPartial(
                                        "registration/components/registration-existing-contact_person-extra_fields",
                                        ["contactPersonRelation" => $managerRelation, 'registration' => $registration]
                                    );
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?
                        endforeach;
                        ?>
                        <form data-hook="registration-form" method="POST">
                        <?
                            foreach ($relation->getObject()->getManagerRelations() as $managerRelation):
                                $app->renderPartial(
                                    "registration/components/registration-existing-hidden_fields",
                                    ["relation" => $managerRelation, 'registration' => $registration]
                                );
                            endforeach;
                        ?>
                            <input required type="hidden" name="Registration[Relation][legalEntityActivities]" value="<?= !empty($registration['Relation']['legalEntityActivities']) ? $registration['Relation']['legalEntityActivities'] : '' ?>">
                            <input required type="hidden" name="Registration[Relation][originOfResources]" value="<?= !empty($registration['Relation']['originOfResources']) ? $registration['Relation']['originOfResources'] : '' ?>">
                        </form>
                        <?
                        break;
                }
                ?>
                <hr>
                <? $app->renderPartial('registration/navigation-buttons', ['button' => true, 'prevUrl' => $prevUrl, 'nextUrl' => $nextUrl]); ?>
        </div>
    </div>
</div>
