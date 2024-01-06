<?php

namespace app\perree\fund;

use app\m;

class CFundService
{
    /**
     * @param int $fundId
     * @return \app\perree\bankaccount\MBankAccount
     */
    public function getBankAccountForFund(int $fundId): ?object
    {
        $bankAccount = m::app()->db->querySingle(
            '
                SELECT BC.*
                FROM bank_account BC
                INNER JOIN fund_bank_account FBC ON FBC.bankAccountId = BC.id
                WHERE FBC.fundId = :fundId
                AND BC.isPrimary = 1
                LIMIT 1
                ',
            [
                ':fundId' => $fundId,
            ],
            '\app\perree\bankaccount\MBankAccount'
        );

        return $bankAccount;
    }

    /**
     * @param int $foundationId
     * @return \app\perree\bankaccount\MBankAccount
     */
    public function getBankAccountForFoundation(int $foundationId): ?object
    {
        $bankAccount = m::app()->db->querySingle(
            '
                SELECT BC.*
                FROM bank_account BC
                INNER JOIN foundation_bank_account FBC ON FBC.bankAccountId = BC.id
                WHERE FBC.foundationId = :foundationId
                AND BC.isPrimary = 1
                LIMIT 1
                ',
            [
                ':foundationId' => $foundationId,
            ],
            '\app\perree\bankaccount\MBankAccount'
        );

        return $bankAccount;
    }

    /**
     * @param int $fundId
     * @param int $bankAccountId
     * @return \app\perree\fund\MFundBankAccount
     */
    public function getFundBankAccountModel(int $fundId, int $bankAccountId): ?MFundBankAccount
    {
        $fundBankAccount = m::app()->db->querySingle(
        '
                SELECT *
                FROM fund_bank_account                
                WHERE fundId = :fundId
                AND bankAccountId = :bankAccountId
                LIMIT 1
            ',
            [
                ':fundId' => $fundId,
                ':bankAccountId' => $bankAccountId,
            ],
            '\app\perree\fund\MFundBankAccount'
        );

        return $fundBankAccount;
    }
}