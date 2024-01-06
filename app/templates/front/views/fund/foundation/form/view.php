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

        if (!empty($foundation) && !empty($foundation->id))
        {
            ?>
            <input type="hidden" name="Foundation[id]" value="<?= $foundation->id; ?>">
            <?php
        }

        ?>

        <div class="form-components">
            <?php
            $app->renderPartial(
                'foundation/form/components/steps',
                [
                    'section' => $section ?? null,
                    'step' => $step ?? null
                ]
            );
            ?>

            <div data-hook="form-steps" data-step="1">
                <?php
                $app->renderPartial('foundation/form/components/general', ['foundation' => $foundation]);
                $app->renderPartial(
                    'foundation/form/components/bank-account',
                    [
                        'foundation' => $foundation,
                        'bankAccount' => !empty($foundation->bankAccount) ? $foundation->bankAccount : null,
                    ]
                );
                ?>
            </div>

            <?php
            $app->renderPartial(
                'foundation/form/components/form-control',
                [
                    'foundation' => $foundation,
                    'section' => $section ?? null,
                    'step' => $step ?? null
                ]
            );
            ?>
        </div>
    </div>
</div>