<?php

namespace app\perree\bankaccount;

/**
 * MBankAccount
 *
 * @property $table string
 * @property $iban string
 * @property $ascription string
 * @property $bic string
 * @property $isPrimary int
 * @property $createdById int
 * @property $dateTimeCreated string
 * @property $updatedById int
 * @property $dateTimeUpdated string
 */
class MBankAccount extends \app\CDBRecord
{

    protected $table = 'bank_account';
    protected $iban;
    protected $ascription;
    protected $bic;
    protected $isPrimary;
    protected $createdById;
    protected $dateTimeCreated;
    protected $updatedById;
    protected $dateTimeUpdated;

    protected $fields = [
        'iban' => [
            'description' => 'IBAN',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' => 'IBAN',
                    'placeholder' => 'IBAN'
                ],
                'order' => 1
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ],
        'ascription' => [
            'description' => 'ASCRIPTION',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' => 'ASCRIPTION',
                    'placeholder' => 'ASCRIPTION'
                ],
                'order' => 2
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ],
        'bic' => [
            'description' => 'BIC',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' => 'BIC',
                    'placeholder' => 'BIC'
                ],
                'order' => 3
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ],
        'isPrimary' => [
            'description' => 'IS_PRIMARY',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' => 'IS_PRIMARY',
                    'placeholder' => 'IS_PRIMARY'
                ],
                'order' => 4
            ],
            'backend' => [
                'type' => 'int',
                'typeParams' => []
            ]
        ],
        'createdById' => [
            'description' => 'CREATED_BY_ID',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' => 'CREATED_BY_ID',
                    'placeholder' => 'CREATED_BY_ID'
                ],
                'order' => 6
            ],
            'backend' => [
                'type' => 'int',
                'typeParams' => []
            ]
        ],
        'dateTimeCreated' => [
            'description' => 'DATE_TIME_CREATED',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' => 'DATE_TIME_CREATED',
                    'placeholder' => 'DATE_TIME_CREATED'
                ],
                'order' => 7
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ],
        'updatedById' => [
            'description' => 'UPDATED_BY_ID',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' => 'UPDATED_BY_ID',
                    'placeholder' => 'UPDATED_BY_ID'
                ],
                'order' => 8
            ],
            'backend' => [
                'type' => 'int',
                'typeParams' => []
            ]
        ],
        'dateTimeUpdated' => [
            'description' => 'DATE_TIME_UPDATED',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' => 'DATE_TIME_UPDATED',
                    'placeholder' => 'DATE_TIME_UPDATED'
                ],
                'order' => 9
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ]
    ];

}