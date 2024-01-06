<?php

namespace app\perree\fund;

/**
 * MFundBankAccount
 *
 * @property $table string
 * @property $fundId int
 * @property $bankAccountId int
 */

class MFundBankAccount extends \app\CDBRecord {

    protected $table = 'fund_bank_account';
    protected $fundId;
    protected $bankAccountId;

    protected $fields = [
        'fundId' => [
            'description' => 'FUND_ID',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'FUND_ID',
                    'placeholder' => 'FUND_ID'
                ],
                'order' => 1
            ],
            'backend' => [
                'type' => 'int',
                'typeParams' => []
            ]
        ],
        'bankAccountId' => [
            'description' => 'BANK_ACCOUNT_ID',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'BANK_ACCOUNT_ID',
                    'placeholder' => 'BANK_ACCOUNT_ID'
                ],
                'order' => 2
            ],
            'backend' => [
                'type' => 'int',
                'typeParams' => []
            ]
        ]
    ];

}