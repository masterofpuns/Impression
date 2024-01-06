<?php
if (!empty($phoneNumbers)) {
    foreach ($phoneNumbers as $phoneNumber) {
        echo '<span>' . $phoneNumber->number . '</span>';
    }
} else {
    echo '<span class="text-secondary-600">'.t('NO_PHONE_NUMBER_PROVIDED').'</span>';
}
?>