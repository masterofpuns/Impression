<div class="digital-registration-buttons">
    <? if(isset($button) && $button): ?>
    <button class="btn btn-icon icon-left" data-hook="registration-submit_form" data-action="<?=$prevUrl;?>">
    <? else: ?>
    <a href="<?=$prevUrl;?>" class="btn btn-icon icon-left">
    <? endif; ?>
        <span class="square-container">
            <img class="square-hack" src="/app/templates/front/assets/images/square_dummy.png">
            <span class="square-content-container">
                <img data-hook="svg-inject" class="hig-user" src="/app/templates/front/assets/images/icons/back.svg">
            </span>
        </span>
        <span class="btn-text">Vorige</span>
    <? if (isset($button) && $button): ?>
    </button>
    <? else: ?>
    </a>
    <? endif; ?>

    <? // Next button is altijd submit van formulier ?>
    <button class="btn btn-icon icon-right" data-hook="registration-submit_form" data-action="<?=$nextUrl;?>">
        <span class="btn-text">Volgende</span>
        <span class="square-container">
            <img class="square-hack" src="/app/templates/front/assets/images/square_dummy.png">
            <span class="square-content-container">
                <img data-hook="svg-inject" class="hig-user" src="/app/templates/front/assets/images/icons/forward.svg">
            </span>
        </span>
    </button>
</div>