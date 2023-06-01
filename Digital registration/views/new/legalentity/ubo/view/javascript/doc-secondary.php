// INCLUDES
<? $app->renderPartial('registration/javascript/registration-general_functions'); ?>

// INCLUDES
$.validator.addMethod('atLeastOneManager', function(value,element) {
    var oneManagerFilled = false;
    $('[data-hook="registration-toggle-is_ubo"]').each(function() {
        oneManagerFilled = oneManagerFilled || $(this).val() == '1';
    });
    return oneManagerFilled;
},  'Selecteer minimaal één van onderstaande bestuurders');

var rulesManagerAsUbo = {
    'dummyHasManagerAsUbo': {
        atLeastOneManager: function() {
            // Is een van de bestuurders een UBO is met Ja ingevuld en daarmee moet ten minste 1 bestuurder gekozen worden
            if ($('[name="dummyHasManagerAsUbo"]').val() == '1') {
                return true;
            } else {
                return false;
            }
        }
    }
};

$.extend(rulesForm, rulesManagerAsUbo);


$.validator.addMethod('atLeastOneUbo', function(value,element) {
    var oneUboFilled = $('[data-hook="tr-ubo"]').length > 0;
    return oneUboFilled;
},  'Voeg minimaal één UBO toe');

var rulesUbo = {
    'dummyHasUbo': {
        atLeastOneUbo: function() {
            // Zijn er natuurlijke personen die UBO zijn is met Ja ingevuld en daarmee moet ten minste 1 UBO toegevoegd worden
            if ($('[name="dummyHasUbo"]').val() == '1') {
                return true;
            } else {
                return false;
            }
        }
    }
};

$.extend(rulesForm, rulesUbo);

$.validator.addMethod('atLeastOnePseudoUbo', function(value,element) {
    var onePseudoUboFilled = false;
    $('[data-hook="registration-toggle-is_pseudo_ubo"]').each(function() {
        onePseudoUboFilled = onePseudoUboFilled || $(this).val() == '1';
    });
    return onePseudoUboFilled;
},  'Selecteer minimaal één van onderstaande bestuurders als pseudo-UBO');

var rulesPseuboUbo = {
    'dummyPseudoUbo': {
        atLeastOnePseudoUbo: function() {
            // Bovenste twee dropdowns (Bestuurders en UBO's) is Nee ingevuld en daarmee moet ten minste 1 bestuurder gekozen worden als pseudo-UBO
            if ($('[name="dummyHasManagerAsUbo"]').val() == '0' && $('[name="dummyHasUbo"]').val() == '0') {
                return true;
            } else {
                return false;
            }
        }
    }
};

$.extend(rulesForm, rulesPseuboUbo);


validator.settings.rules = rulesForm;

// Op deze pagina alleen valideren onSubmit anders krijg je meteen een validatie fout als je Bestuurders zijn UBO's op Ja zet en wil klikken om een bestuurder te selecteren
validator.settings.onkeyup = false;
validator.settings.onclick = false;
validator.settings.onfocusout = false;
// VARS

// FUNCTIONS
function openToggle(e) {
    var target = e.target;
    if (!$(target).hasClass('open')) {
        // Sluit ander gedeelte af
        var otherSection = $(target).attr('data-section') == "login" ? "register" : "login";
        closeSection(otherSection);
    }
    $(target).toggleClass( "open" );
    $(target).parents('div').parents('.main-info-container').siblings('.more-info-container').find('.more-info-target').toggleClass( "open" );
}
function closeSection(section) {
    $('[data-target="'+ section +'"]').find('.more-info-target').removeClass('open');
    $('[data-target="'+ section +'"]').find('[data-section="' + section + '"]').removeClass('open');
}
function handleHasMayorIsUbo(e) {
    var isUbo = e.target.value;
    //Wanneer geen UBO, checkmarks verwijderen
    if(parseInt(isUbo) == 0) {

        // Vinkje bij bestuurders uitvinken
        $('[data-showubo="1"]').find('.custom-checkbox').removeClass('checked');
        $('[data-showubo="1"]').find('input[type="hidden"]').val(0).removeAttr('checked');

        // Adres en belang velden van bestuurders niet meer required maken
        $('[data-showubo="1"]').find('input[type="text"], input[type="number"], select').prop('required', false);

        // belang velden van bestuurders leegmaken
        $('[data-showubo="1"]').find('[data-hook="registration-contact_person-interest_rate"], [data-hook="registration-contact_person-ownership_rate"]').val('');

        // Adres verbergen
        $('[data-showubo="1"]').find('[data-hook="show-ubo_address"]').addClass('hide');

    }
    $('[data-showubo]').addClass('hide');
    $('[data-showubo="'+isUbo+'"]').removeClass('hide');
    checkShowPseudo();

}
function hasMayorInterest(e) {
    var value = e.target.value;
    $('[data-interest]').addClass('hide');
    $('[data-interest="'+value+'"]').removeClass('hide');
    checkShowPseudo();
}
function checkShowPseudo() {
    var hasMayor = parseInt($('[data-hook="interest"]').val());
    var isUbo = parseInt($('[data-hook="isubo"]').val());

    if(!isUbo && !hasMayor) {
        $('[data-hook="pseudo"]').removeClass('hide');
        $('[data-hook="pseudo"]').find('[data-hook="show-pseudo_ubo_address"]').addClass('hide');
    } else {
        $('[data-hook="pseudo"]').addClass('hide');

        // Vinkje bij bestuurders uitvinken
        $('[data-hook="pseudo"]').find('.custom-checkbox').removeClass('checked');
        $('[data-hook="pseudo"]').find('input[type="hidden"]').val(0).removeAttr('checked');

        // Adres en belang velden van pseudo ubo's niet meer required maken
        $('[data-hook="pseudo"]').find('input[type="text"], input[type="number"], select').prop('required', false);

        // Adres verbergen
        $('[data-hook="pseudo"]').find('[data-hook="show-ubo_address"]').addClass('hide');
    }
}
function setHiddenCheckbox() {
    var that = $(this);
    //@hack executie volgorde forceren ivm dom check van custom checkbox
    setTimeout(function() {
        var checked = that.hasClass("checked");
        var targetCheckbox = $('[name="'+that.attr('data-checkbox')+'"]');
        if(checked) {
            targetCheckbox.val(1);
        } else {
            targetCheckbox.val(0);
        }
    }, 0);
}
function editUbo(e) {
    e.preventDefault();
    $('[name="idxEditExtraUbo"]').remove();
    var id = $(this).attr('data-edit');
    var form = document.querySelector('[data-hook="registration-form"]')
    $(form).append($('<input type="hidden" name="idxEditExtraUbo" value='+id+' />'));
    form.action = $(this).data('action');
    $('[data-hook="registration-form"]').submit();
}

// Functie overrulen op deze pagina zodat validatie uitgezet kan worden voor add UBO knop.
function handlePrevNextAction(e) {
    e.preventDefault();
    var button = $(this);
    button.prop('disabled', true);

    // EXTRA ACTIE
    if (button.attr('data-button-type') == "add-ubo") {
        // Extra validatie pas uitvoeren bij volgende zodat UBO kan worden toegevoegd, ander komt hier meteen error op
        validator.settings.rules = {};
    }

    var form = document.querySelector('[data-hook="registration-form"]');

    if (form) {
        form.action = button.data('action');
        $('[data-hook="registration-form"]').submit();
    } else {
        // pagina zonder form, doorsturen naar action
        window.location.href = button.data('action');
    }
}

// LISTENERS
$('[data-hook="open"]').click(openToggle);
$('[data-hook="interest"]').change(hasMayorInterest);
$('[data-hook="isubo"]').change(handleHasMayorIsUbo);
$('.custom-checkbox').click(setHiddenCheckbox);
$('body').on('click', '[data-edit]', editUbo);

// EXECUTE
$(document).ready(function() {
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });
});

