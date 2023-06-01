// INCLUDES

// VARS
var participationValue = "<?= empty($fund->currentValue) ? $fund->value : $fund->currentValue; ?>";

function calculateTotalAmount() {
    var participationAmount = parseInt($('[data-hook="participation-amount"]').val());
    var minValue = parseInt($('[data-hook="participation-amount"]').attr('min'));
    var maxValue = parseInt($('[data-hook="participation-amount"]').attr('max'));
    if(participationAmount < 0) {
        $('[data-hook="participation-amount"]').val(0);
        participationAmount = 0;
    }
    if(minValue && participationAmount < minValue) {
        $('[data-hook="participation-amount"]').val(minValue);
        participationAmount = minValue;
    }
    if(maxValue && participationAmount > maxValue) {
        $('[data-hook="participation-amount"]').val(maxValue);
        participationAmount = maxValue;
    }

    var totalParticipation = participationValue*participationAmount;
    $('[data-hook="total-participation"]').html('&euro; '+numberDotNotaion(totalParticipation));

    toggleTextParticipationsValue();
}
function toggleTextParticipationsValue() {
    var amount = $('[data-hook="participation-amount"]').val();
    var textContainer = $('[data-hook="registration-participation_value_text"]');

    if (amount > 0 && textContainer.hasClass('hide')) {
        textContainer.removeClass('hide');
    }
    if (amount == 0 && !textContainer.hasClass('hide')) {
        textContainer.addClass('hide');
    }
}
function changeParticipationAmount(e) {
    e.preventDefault();
    calculateTotalAmount();
}
function subtractParticipation(e) {
    var amountElement = $('[data-hook="participation-amount"]');
    amountElement.val(parseInt(amountElement.val())-1);
    calculateTotalAmount();
}
function addParticipation(e) {
    var amountElement = $('[data-hook="participation-amount"]');
    amountElement.val(parseInt(amountElement.val())+1);
    calculateTotalAmount();
}

/* LISTENERS */
$('body').on('change', '[data-hook="participation-amount"]', changeParticipationAmount);
$('body').on('click', '[data-hook="participation-substract"]', subtractParticipation);
$('body').on('click', '[data-hook="participation-add"]', addParticipation);