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

        if (!empty($curator) && !empty($curator->id))
        {
            ?>
            <input type="hidden" name="Curator[id]" value="<?= $curator->id; ?>">
            <?php
        }

        ?>

        <div class="form-components">
            <?php
            $app->renderPartial(
                'curator/form/components/steps',
                [
                    'section' => $section ?? null,
                    'step' => $step ?? null
                ]
            );
            ?>

            <div data-hook="form-steps" data-step="1">
                <?php
                $app->renderPartial('curator/form/components/general', ['curator' => $curator]);
                ?>
            </div>

            <?php
            $app->renderPartial(
                'curator/form/components/form-control',
                [
                    'curator' => $curator,
                    'section' => $section ?? null,
                    'step' => $step ?? null
                ]
            );
            ?>
        </div>
    </div>
</div>