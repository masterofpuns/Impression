<?php

if (!empty($emailAddresses)) {
    foreach ($emailAddresses as $emailAddress) {
        echo '<span>' . $emailAddress->address . '</span>';
    }
} else {
    echo '<span class="text-secondary-600">'.t('NO_EMAIL_ADDRESS_PROVIDED').'</span>';
} ?>