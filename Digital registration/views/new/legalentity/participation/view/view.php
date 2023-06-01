<? $participationValue = empty($fund->currentValue) ? $fund->value : $fund->currentValue; ?>
<? $numParticipations = !empty($registration['Participation']) && !empty($registration['Participation']['amount']) ? $registration['Participation']['amount'] : $fund->minimumParticipations; ?>
<div class="container main-content home text-center digital-registration-new">
    <div class="digital-registration-container">
        <? $app->renderPartial('registration/registration-steps', ['percentageComplete' => '37%', 'activeStep' => 'participation', 'fund' => $fund]); ?>
        <div class="digital-registration-formfields text-left">
            <h2>Met welk aantal participaties wenst u deel te nemen?</h2>
            <hr class="margin-t-small" />
            <div class="digital-registration-text">De participatiewaarde bedraagt <?= \app\h::formatCurrency($participationValue); ?>. U kunt deelnemen met een minimum aantal van <?= $fund->minimumParticipations; ?> participaties.</div>
            <div data-hook="custom-errormsg">
                <div class="participation-amount-container">
                    <button data-hook="participation-substract">-</button>
                    <form method="POST" data-hook="registration-form">
                        <input data-hook="participation-amount" class="participation-amount" name="Registration[Participation][amount]" type="number" min="<?= $fund->minimumParticipations; ?>" max="<?= $fund->numParticipations; ?>"  value="<?=$numParticipations;?>"/>
                    </form>
                    <button data-hook="participation-add">+</button>
                </div>
                <div class="customErrorContainer"></div>
            </div>
            <hr class="margin-t-small" />
            <div class="digital-registration-text <?!empty($registration['Participation']) && !empty($registration['Participation']['amount']) ? '' : 'hide';?>" data-hook="registration-participation_value_text">
                <div class="digital-registration-title">Deelnamebedrag:</div>
                <span data-hook="total-participation"><?= \app\h::formatCurrency($participationValue * $numParticipations); ?></span> <?= $registration['EmissionCost']; ?>
                <hr class="margin-t-small" />
            </div>
            <? $app->renderPartial('registration/navigation-buttons', ['button' => true, 'prevUrl' => $prevUrl, 'nextUrl' => $nextUrl]); ?>
        </div>
    </div>
</div>