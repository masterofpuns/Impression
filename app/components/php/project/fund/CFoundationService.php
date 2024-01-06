<?php

namespace app\perree\fund;

use \app\m;
use \app\h;

class CFoundationService
{
    public function findFoundations($string)
    {
        $foundations = m::app()->db->query(
            "
                SELECT *
                FROM foundation
                WHERE name LIKE :foundationName
            ",
            [":foundationName" => '%' . $string . '%'],
            '\app\perree\fund\MFoundation'
        );
        return $foundations;
    }

    public function getFoundationBankAccountModel($foundationId, $bankAccountId)
    {
        $foundationBankAccount = m::app()->db->querySingle(
            '
                SELECT *
                FROM foundation_bank_account                
                WHERE foundationId = :foundationId
                AND bankAccountId = :bankAccountId
                LIMIT 1
            ',
            [
                ':foundationId' => $foundationId,
                ':bankAccountId' => $bankAccountId,
            ],
            '\app\perree\fund\MFoundationBankAccount'
        );

        return $foundationBankAccount;
    }
}