<?php

namespace app\perree\relation;

/**
 * MAddress
 *
 * @property $table string
 * @property $type string
 * @property $street string
 * @property $number int
 * @property $numberSuffix string
 * @property $postalCode string
 * @property $city string
 * @property $countryId int
 * @property $relationId int
 * @property $createdById int
 * @property $dateTimeCreated string
 * @property $updatedById int
 * @property $dateTimeUpdated string
 */

class MAddress extends \app\CDBRecord {

    protected $table = 'address';
    protected $type;
    protected $street;
    protected $number;
    protected $numberSuffix;
    protected $postalCode;
    protected $city;
    protected $countryId;
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
        'street' => [
            'description' => 'STREET',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'STREET',
                    'placeholder' => 'STREET'
                ],
                'order' => 2
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
                'order' => 3
            ],
            'backend' => [
                'type' => 'int',
                'typeParams' => []
            ]
        ],
        'numberSuffix' => [
            'description' => 'NUMBER_SUFFIX',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'NUMBER_SUFFIX',
                    'placeholder' => 'NUMBER_SUFFIX'
                ],
                'order' => 4
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ],
        'postalCode' => [
            'description' => 'POSTAL_CODE',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'POSTAL_CODE',
                    'placeholder' => 'POSTAL_CODE'
                ],
                'order' => 5
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ],
        'city' => [
            'description' => 'CITY',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'CITY',
                    'placeholder' => 'CITY'
                ],
                'order' => 6
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ],
        'countryId' => [
            'description' => 'COUNTRY_ID',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'COUNTRY_ID',
                    'placeholder' => 'COUNTRY_ID'
                ],
                'order' => 7
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
                'order' => 8
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
                'order' => 9
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
                'order' => 10
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
                'order' => 11
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
                'order' => 12
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ]
    ];

    private $countryName;

    public function getCountry() {
        if (!is_null($this->countryId)) {
            $this->countryName = new MCountry($this->countryId);
        }

        return $this->countryName;
    }

}