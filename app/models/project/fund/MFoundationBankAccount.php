<?php

namespace app\perree\fund;

/**
 * MFoundationBankAccount
 *
 * @property $table string
 * @property $foundationId int
 * @property $bankAccountId int
 */

class MFoundationBankAccount extends \app\CDBRecord {

    protected $table = 'foundation_bank_account';
    protected $foundationId;
    protected $bankAccountId;

    protected $fields = [
        'foundationId' => [
            'description' => 'FOUNDATION_ID',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'FOUNDATION_ID',
                    'placeholder' => 'FOUNDATION_ID'
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