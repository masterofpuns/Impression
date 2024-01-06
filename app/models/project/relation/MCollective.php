<?php

namespace app\perree\relation;

use app\TraitReturnNameForModel;
use app\TraitReturnRelationForTypeObject;

/**
 * MCollective
 *
 * @property $table string
 * @property $postalAddressEqualsVisitingAddress int
 * @property $emissionPercentage int
 * @property $correspondenceType string
 * @property $taxType string
 */

class MCollective extends \app\CDBRecord {
    use TraitReturnNameForModel;
    use TraitReturnRelationForTypeObject;

    protected $table = 'collective';
    protected $language;
    protected $postalAddressEqualsVisitingAddress;
    protected $emissionPercentage;
    protected $correspondenceType;
    protected $taxType;

    protected $fields = [
        'postalAddressEqualsVisitingAddress' => [
            'description' => 'POSTAL_ADDRESS_EQUALS_VISITING_ADDRESS',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'POSTAL_ADDRESS_EQUALS_VISITING_ADDRESS',
                    'placeholder' => 'POSTAL_ADDRESS_EQUALS_VISITING_ADDRESS'
                ],
                'order' => 1
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
                'order' => 2
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
                'order' => 3
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
                'order' => 4
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ],
    ];

    private $participatingContactPersons;
    private $nonParticipatingContactPersons;

    public function getNameSortable(): ?string
    {
        $nameSortable = "";
        $this->getParticipatingContactPersons();

        if (!empty($this->participatingContactPersons)) {
            foreach ($this->participatingContactPersons as $participatingContactPerson) {
                $nameSortable.= empty($nameSortable) ? '' : ' ' . t('AND', [], false) . ' ';
                $nameSortable.= $participatingContactPerson->typeObject->getFullNameStartingWithLastNameInclInitials();
            }
        } else {
            $nameSortable = t('COLLECTIVE_WITHOUT_PARTICIPATING_CONTACT_PERSONS');
        }

        if (!empty($this->initials)) {
            $nameSortable .= ', ' . $this->initials;
        }
        if (!empty($this->lastNamePrefix)) {
            $nameSortable .= (!empty($this->initials) ? ' ' : ', ') . $this->lastNamePrefix;
        }

        return $nameSortable;
    }

    public function getParticipatingContactPersons(): ?array
    {
        if (empty($this->participatingContactPersons)) {
            $this->getRelation();
            $this->getRelationService();

            $this->participatingContactPersons = $this->relationService->getParticipatingContactPersonRelationsForRelation($this->relation->id);
        }
        return $this->participatingContactPersons;
    }

    public function getNonParticipatingContactPersons(): ?array
    {
        if (empty($this->nonParticipatingContactPersons)) {
            $this->getRelation();
            $this->getRelationService();

            $this->nonParticipatingContactPersons = $this->relationService->getNonParticipatingContactPersonRelationsForRelation($this->relation->id);
        }
        return $this->nonParticipatingContactPersons;
    }
}