<?php

namespace app\perree\relation;

/**
 * MCountry
 *
 * @property $table string
 * @property $name string
 * @property $createdById int
 * @property $dateTimeCreated string
 * @property $updatedById int
 * @property $dateTimeUpdated string
 */

class MCountry extends \app\CDBRecord {

    protected $table = 'country';
    protected $name;
    protected $createdById;
    protected $dateTimeCreated;
    protected $updatedById;
    protected $dateTimeUpdated;

    protected $fields = [
        'name' => [
            'description' => 'NAME',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'NAME',
                    'placeholder' => 'NAME'
                ],
                'order' => 1
            ],
            'backend' => [
                'type' => 'text',
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
                'order' => 2
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
                'order' => 3
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
                'order' => 4
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
                'order' => 5
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ]
    ];

    public function getName() {
        return t($this->name);
    }
}