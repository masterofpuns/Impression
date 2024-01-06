<?php

namespace app\perree\relation;

/**
 * MIndividual
 *
 * @property $table string
 * @property $salutationId int
 * @property $titleBefore string
 * @property $initials string
 * @property $firstName string
 * @property $lastNamePrefix string
 * @property $lastName string
 * @property $titleAfter string
 * @property $language string
 * @property $idMediaId int
 * @property $idType string
 * @property $idNumber int
 * @property $idDateExpiration string
 * @property $nationality string
 * @property $birthPlace string
 * @property $birthDate string
 * @property $postalAddressEqualsVisitingAddress int
 * @property $emissionPercentage int
 * @property $correspondenceType string
 * @property $taxType string
 */

class MIndividual extends \app\CDBRecord {
    use \app\TraitReturnNameForModel;

    protected $table = 'individual';
    protected $salutationId;
    protected $titleBefore;
    protected $initials;
    protected $firstName;
    protected $lastNamePrefix;
    protected $lastName;
    protected $titleAfter;
    protected $language;
    protected $idMediaId;
    protected $idType;
    protected $idNumber;
    protected $idDateExpiration;
    protected $nationality;
    protected $birthPlace;
    protected $birthDate;
    protected $postalAddressEqualsVisitingAddress;
    protected $emissionPercentage;
    protected $correspondenceType;
    protected $taxType;

    protected $fields = [
        'salutationId' => [
            'description' => 'SALUTATION_ID',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'SALUTATION_ID',
                    'placeholder' => 'SALUTATION_ID'
                ],
                'order' => 1
            ],
            'backend' => [
                'type' => 'int',
                'typeParams' => []
            ]
        ],
        'titleBefore' => [
            'description' => 'TITLE_BEFORE',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'TITLE_BEFORE',
                    'placeholder' => 'TITLE_BEFORE'
                ],
                'order' => 2
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ],
        'initials' => [
            'description' => 'INITIALS',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'INITIALS',
                    'placeholder' => 'INITIALS'
                ],
                'order' => 3
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ],
        'firstName' => [
            'description' => 'FIRST_NAME',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'FIRST_NAME',
                    'placeholder' => 'FIRST_NAME'
                ],
                'order' => 4
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ],
        'lastNamePrefix' => [
            'description' => 'LAST_NAME_PREFIX',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'LAST_NAME_PREFIX',
                    'placeholder' => 'LAST_NAME_PREFIX'
                ],
                'order' => 5
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ],
        'lastName' => [
            'description' => 'LAST_NAME',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'LAST_NAME',
                    'placeholder' => 'LAST_NAME'
                ],
                'order' => 6
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ],
        'titleAfter' => [
            'description' => 'TITLE_AFTER',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'TITLE_AFTER',
                    'placeholder' => 'TITLE_AFTER'
                ],
                'order' => 7
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
                'order' => 8
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ],
        'idMediaId' => [
            'description' => 'ID_MEDIA_ID',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'ID_MEDIA_ID',
                    'placeholder' => 'ID_MEDIA_ID'
                ],
                'order' => 9
            ],
            'backend' => [
                'type' => 'int',
                'typeParams' => []
            ]
        ],
        'idType' => [
            'description' => 'ID_TYPE',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'ID_TYPE',
                    'placeholder' => 'ID_TYPE'
                ],
                'order' => 10
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ],
        'idNumber' => [
            'description' => 'ID_NUMBER',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'ID_NUMBER',
                    'placeholder' => 'ID_NUMBER'
                ],
                'order' => 11
            ],
            'backend' => [
                'type' => 'int',
                'typeParams' => []
            ]
        ],
        'idDateExpiration' => [
            'description' => 'ID_DATE_EXPIRATION',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'ID_DATE_EXPIRATION',
                    'placeholder' => 'ID_DATE_EXPIRATION'
                ],
                'order' => 12
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ],
        'nationality' => [
            'description' => 'NATIONALITY',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'NATIONALITY',
                    'placeholder' => 'NATIONALITY'
                ],
                'order' => 13
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ],
        'birthPlace' => [
            'description' => 'BIRTH_PLACE',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'BIRTH_PLACE',
                    'placeholder' => 'BIRTH_PLACE'
                ],
                'order' => 14
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ],
        'birthDate' => [
            'description' => 'BIRTH_DATE',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'BIRTH_DATE',
                    'placeholder' => 'BIRTH_DATE'
                ],
                'order' => 15
            ],
            'backend' => [
                'type' => 'text',
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
                'order' => 16
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
                'order' => 17
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
                'order' => 18
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
                'order' => 19
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ],
    ];

    private $idFile;

    public function getNameSortable(): ?string
    {
        $nameSortable = $this->lastName;

        if (!empty($this->initials))
        {
            $nameSortable .= ", " . $this->initials;
        }
        if (!empty($this->lastNamePrefix))
        {
            $nameSortable .= (!empty($this->initials) ? ' ' : ', ') . $this->lastNamePrefix;
        }

        return $nameSortable;
    }

    public function getIdFile() {
        if (empty($this->idFile) && !empty($this->idMediaId)) {
            $this->idFile = new \app\perree\media\MMedia($this->idMediaId);
        }
        return $this->idFile;
    }

    public function convertBackendValues()
    {
        parent::convertBackendValues();

        if (!empty($this->idDateExpiration)) {
            $this->idDateExpiration = date('d-m-Y', strtotime($this->idDateExpiration));
        }
        if (!empty($this->birthDate)) {
            $this->birthDate = date('d-m-Y', strtotime($this->birthDate));
        }
    }

    public function convertPostValues()
    {
        parent::convertPostValues();

        if (!empty($this->idDateExpiration)) {
            $this->idDateExpiration = date('Y-m-d H:i:s', strtotime($this->idDateExpiration));
        } else {
            $this->idDateExpiration = null;
        }
        if (!empty($this->birthDate)) {
            $this->birthDate = date('Y-m-d H:i:s', strtotime($this->birthDate));
        } else {
            $this->birthDate = null;
        }
    }
}