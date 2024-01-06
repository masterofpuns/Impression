<?= $bankingAccount->isPrimary ? '<span><i>Primaire bankrekening</i></span>' : ''; ?>
<?= $bankingAccount->iban ? '<span>'.$bankingAccount->iban.'</span>' : ''; ?>
<?= $bankingAccount->ascription ? '<span>'.t('ASCRIPTION_AFFIX') . ' ' . $bankingAccount->ascription.'</span>' : ''; ?>
<?= $bankingAccount->bic ? '<span>BIC: '.$bankingAccount->bic.'</span>' : ''; ?>
