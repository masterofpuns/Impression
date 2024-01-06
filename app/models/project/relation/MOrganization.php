<?php

namespace app\perree\relation;

/**
 * MOrganization
 *
 * @property $table string
 * @property $name string
 * @property $website string
 * @property $language string
 * @property $chamberOfCommerceMediaId int
 * @property $chamberOfCommerceNumber int
 * @property $postalAddressEqualsVisitingAddress int
 * @property $emissionPercentage int
 * @property $correspondenceType string
 * @property $taxType string
 */

class MOrganization extends \app\CDBRecord {

    protected $table = 'organization';
    protected $name;
    protected $website;
    protected $language;
    protected $chamberOfCommerceMediaId;
    protected $chamberOfCommerceNumber;
    protected $postalAddressEqualsVisitingAddress;
    protected $emissionPercentage;
    protected $correspondenceType;
    protected $taxType;

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
        'website' => [
            'description' => 'WEBSITE',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'WEBSITE',
                    'placeholder' => 'WEBSITE'
                ],
                'order' => 2
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ],
        'language' => [
            'description' => 'LANGUAGE',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'LANGUAGE',
                    'placeholder' => 'LANGUAGE'
                ],
                'order' => 3
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ],
        'chamberOfCommerceMediaId' => [
            'description' => 'CHAMBER_OF_COMMERCE_MEDIA_ID',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'CHAMBER_OF_COMMERCE_MEDIA_ID',
                    'placeholder' => 'CHAMBER_OF_COMMERCE_MEDIA_ID'
                ],
                'order' => 4
            ],
            'backend' => [
                'type' => 'int',
                'typeParams' => []
            ]
        ],
        'chamberOfCommerceNumber' => [
            'description' => 'CHAMBER_OF_COMMERCE_NUMBER',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'CHAMBER_OF_COMMERCE_NUMBER',
                    'placeholder' => 'CHAMBER_OF_COMMERCE_NUMBER'
                ],
                'order' => 5
            ],
            'backend' => [
                'type' => 'int',
                'typeParams' => []
            ]
        ],
        'postalAddressEqualsVisitingAddress' => [
            'description' => 'POSTAL_ADDRESS_EQUALS_VISITING_ADDRESS',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'POSTAL_ADDRESS_EQUALS_VISITING_ADDRESS',
                    'placeholder' => 'POSTAL_ADDRESS_EQUALS_VISITING_ADDRESS'
                ],
                'order' => 6
            ],
            'backend' => [
                'type' => 'int',
                'typeParams' => []
            ]
        ],
        'emissionPercentage' => [
            'description' => 'EMISSION_PERCENTAGE',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'EMISSION_PERCENTAGE',
                    'placeholder' => 'EMISSION_PERCENTAGE'
                ],
                'order' => 7
            ],
            'backend' => [
                'type' => 'int',
                'typeParams' => []
            ]
        ],
        'correspondenceType' => [
            'description' => 'CORRESPONDENCE_TYPE',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'CORRESPONDENCE_TYPE',
                    'placeholder' => 'CORRESPONDENCE_TYPE'
                ],
                'order' => 8
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ],
        'taxType' => [
            'description' => 'TAX_TYPE',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'TAX_TYPE',
                    'placeholder' => 'TAX_TYPE'
                ],
                'order' => 9
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ],
    ];

    private $chamberOfCommerceFile ;

    public function getChamberOfCommerceFile () {
        if (empty($this->chamberOfCommerceFile) && !empty($this->chamberOfCommerceMediaId)) {
            $this->chamberOfCommerceFile = new \app\perree\media\MMedia($this->chamberOfCommerceMediaId);
        }
        return $this->chamberOfCommerceFile;
    }

    public function getNameSortable(): ?string
    {
        $nameSortable = $this->name;

        return $nameSortable;
    }
}