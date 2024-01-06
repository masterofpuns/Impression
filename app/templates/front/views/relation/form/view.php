<div class='container-fluid ps-4 pe-5 mt-4'>
    <div class='d-flex justify-content-start flex-row mb-4'>
        <div class='col d-flex flex-row gap-5 align-items-center'>
            <?php $app->renderPartial(
                'page-header/page-header',
                ['heading' => isset($heading) ? $heading : $app->doc->heading]
            ); ?>
        </div>
    </div>
    <div class='container-content container-forms w-50'>
        <?php
        $form->params['enctype'] = 'multipart/form-data';
        $app->renderPartial(
            'form/start',
            array('form' => $form)
        );

        if (!empty($relation) && !empty($relation->id))
        {
            ?>
            <input type="hidden" name="Relation[id]" value="<?= $relation->id; ?>">
            <?php
        }

        ?>

        <div class="form-components">
            <?php
            $app->renderPartial(
                'relation/form/components/steps',
                [
                    'section' => $section ?? null,
                    'step' => $step ?? null
                ]
            );

            // placeholders
            $app->renderPartial(
                'relation/form/components/phonenumber',
                [
                    'placeholder' => true
                ]
            );
            $app->renderPartial(
                'relation/form/components/email-address',
                [
                    'placeholder' => true
                ]
            );
            $app->renderPartial(
                'relation/form/components/contact-person',
                [
                    'placeholder' => true
                ]
            );
            ?>

            <div data-hook="form-steps" data-step="1">
                <?php
                $app->renderPartial('relation/form/components/relation-type', ['relation' => $relation]);
                $app->renderPartial('relation/form/components/relation-category', ['relation' => $relation]);
                ?>
            </div>
            <?php if (empty($relation->id)) { ?>
            <div class="hide" data-hook='form-steps' data-step='collective'>
                <?php
                $app->renderPartial('relation/form/components/collective', ['relation' => $relation]);
                ?>
            </div>
            <?php } ?>
            <div class="hide" data-hook='form-steps' data-step='2'>
                <?php
                $app->renderPartial('relation/form/components/individual', ['relation' => $relation]);
                $app->renderPartial('relation/form/components/organization', ['relation' => $relation]);
                $app->renderPartial('relation/form/components/advisor', ['relation' => $relation]);

                $app->renderPartial('relation/form/components/phonenumbers', ['relation' => $relation, 'prefix' => 'Relation']);
                $app->renderPartial('relation/form/components/email-addresses', ['relation' => $relation, 'prefix' => 'Relation']);

                $app->renderPartial(
                    'relation/form/components/visiting-address',
                    [
                        'relation' => $relation,
                        'visitingAddress' => !empty($relation->id) ? $relation->getVisitingAddress(): []
                    ]
                );

                $app->renderPartial(
                    'relation/form/components/postal-address',
                    [
                        'relation' => $relation,
                        'postalAddress' => !empty($relation->id) && !empty($relation->typeObject) && !$relation->typeObject->postalAddressEqualsVisitingAddress ? $relation->getPostalAddress() : []
                    ]
                );

                $app->renderPartial('relation/form/components/identification', ['relation' => $relation]);
                ?>
            </div>
            <div class="hide" data-hook='form-steps' data-step='3'>
                <?php
                $app->renderPartial('relation/form/components/general', ['relation' => $relation]);
                $app->renderPartial(
                    'relation/form/components/bank-account',
                    [
                        'relation' => $relation,
                        'bankAccount' => !empty($relation->id) ? $relation->getPrimaryBankAccount() : []
                    ]
                );
                $app->renderPartial('relation/form/components/chamber-of-commerce', ['relation' => $relation]);
                ?>
            </div>
            <?php if (empty($relation->id)) { ?>
            <div class='hide' data-hook='form-steps' data-step='4'>
                <?php
                $app->renderPartial('relation/form/components/contact-persons', ['relation' => $relation]);
                ?>
            </div>
            <?php } ?>

            <?php
            $app->renderPartial(
                'relation/form/components/form-control',
                [
                    'relation' => $relation,
                    'section' => $section ?? null,
                    'step' => $step ?? null
                ]
            );
            ?>
        </div>
    </div>
</div>