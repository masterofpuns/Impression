<?php

namespace app\perree\relation;

/**
 * MRelationBankAccount
 *
 * @property $table string
 * @property $relationId int
 * @property $bankAccountId int
 */

class MRelationBankAccount extends \app\CDBRecord {

    protected $table = 'relation_bank_account';
    protected $relationId;
    protected $bankAccountId;

    protected $fields = [
        'relationId' => [
            'description' => 'RELATION_ID',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'RELATION_ID',
                    'placeholder' => 'RELATION_ID'
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