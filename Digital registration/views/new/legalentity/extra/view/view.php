<div class="container main-content home text-center digital-registration-new">
    <div class="digital-registration-container">
        <?
            $app->renderPartial('registration/registration-steps', ['percentageComplete' => '10%', 'activeStep' => 'identification', 'fund' => $fund]);
        ?>
        <div class="digital-registration-formfields text-left">
            <h2>Extra bestuurder toevoegen</h2>
            <form data-hook="registration-form" method="POST" enctype="multipart/form-data">
                <? $app->renderPartial('registration/components/legalentity-contactperson', ['registration' => $registration, 'contactPersonIdx' => $contactPersonIdx]); ?>
            </form>
            <hr class="margin-t-small" />
            <? $app->renderPartial('registration/navigation-buttons', ['prevUrl' => $prevUrl, 'nextUrl' => $nextUrl]); ?>
        </div>
    </div>
</div>