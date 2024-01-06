<?php

namespace app\perree\relation;

/**
 * MPhoneNumber
 *
 * @property $table string
 * @property $type string
 * @property $number string
 * @property $isPrimary int
 * @property $relationId int
 * @property $createdById int
 * @property $dateTimeCreated string
 * @property $updatedById int
 * @property $dateTimeUpdated string
 */

class MPhoneNumber extends \app\CDBRecord {

    protected $table = 'phone_number';
    protected $type;
    protected $number;
    protected $isPrimary;
    protected $relationId;
    protected $createdById;
    protected $dateTimeCreated;
    protected $updatedById;
    protected $dateTimeUpdated;

    protected $fields = [
        'type' => [
            'description' => 'TYPE',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'TYPE',
                    'placeholder' => 'TYPE'
                ],
                'order' => 1
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ],
        'number' => [
            'description' => 'NUMBER',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'NUMBER',
                    'placeholder' => 'NUMBER'
                ],
                'order' => 2
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
                    'label' =>  'IS_PRIMARY',
                    'placeholder' => 'IS_PRIMARY'
                ],
                'order' => 3
            ],
            'backend' => [
                'type' => 'int',
                'typeParams' => []
            ]
        ],
        'relationId' => [
            'description' => 'RELATION_ID',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'RELATION_ID',
                    'placeholder' => 'RELATION_ID'
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
                    'label' =>  'CREATED_BY_ID',
                    'placeholder' => 'CREATED_BY_ID'
                ],
                'order' => 5
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
                    'label' =>  'DATE_TIME_CREATED',
                    'placeholder' => 'DATE_TIME_CREATED'
                ],
                'order' => 6
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
                    'label' =>  'UPDATED_BY_ID',
                    'placeholder' => 'UPDATED_BY_ID'
                ],
                'order' => 7
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
                    'label' =>  'DATE_TIME_UPDATED',
                    'placeholder' => 'DATE_TIME_UPDATED'
                ],
                'order' => 8
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ]
    ];

}