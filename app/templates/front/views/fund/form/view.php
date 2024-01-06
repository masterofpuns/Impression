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

        if (!empty($fund) && !empty($fund->id))
        {
            ?>
            <input type="hidden" name="Fund[id]" value="<?= $fund->id; ?>">
            <?php
        }

        ?>

        <div class="form-components">
            <?php
            $app->renderPartial(
                'fund/form/components/steps',
                [
                    'section' => $section ?? null,
                    'step' => $step ?? null
                ]
            );
            ?>

            <div data-hook="form-steps" data-step="1">
                <?php
                $app->renderPartial('fund/form/components/general', ['fund' => $fund]);
                ?>
            </div>
            <div class="hide" data-hook="form-steps" data-step="2">
                <?php
                $app->renderPartial('fund/form/components/details', ['fund' => $fund]);
                $app->renderPartial(
                    'fund/form/components/bank-account',
                    [
                        'fund' => $fund,
                        'bankAccount' => !empty($fund->bankAccount) ? $fund->bankAccount : null,
                    ]
                );
                ?>
            </div>

            <?php
            $app->renderPartial(
                'fund/form/components/form-control',
                [
                    'fund' => $fund,
                    'section' => $section ?? null,
                    'step' => $step ?? null
                ]
            );
            ?>
        </div>
    </div>
</div>