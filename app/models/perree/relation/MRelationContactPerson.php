<?php

namespace app\perree\relation;

/**
 * MRelationContactPerson
 *
 * @property $table string
 * @property $relationId int
 * @property $contactPersonRelationId int
 * @property $isProxy int
 * @property $isParticipating int
 * @property $isManager int
 * @property $createdById int
 * @property $dateTimeCreated string
 */

class MRelationContactPerson extends \app\CDBRecord {

    protected $table = 'relation_contact_person';
    protected $relationId;
    protected $contactPersonRelationId;
    protected $isProxy;
    protected $isParticipating;
    protected $isManager;
    protected $createdById;
    protected $dateTimeCreated;

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
        'contactPersonRelationId' => [
            'description' => 'CONTACT_PERSON_RELATION_ID',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'CONTACT_PERSON_RELATION_ID',
                    'placeholder' => 'CONTACT_PERSON_RELATION_ID'
                ],
                'order' => 2
            ],
            'backend' => [
                'type' => 'int',
                'typeParams' => []
            ]
        ],
        'isProxy' => [
            'description' => 'IS_PROXY',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'IS_PROXY',
                    'placeholder' => 'IS_PROXY'
                ],
                'order' => 3
            ],
            'backend' => [
                'type' => 'int',
                'typeParams' => []
            ]
        ],
        'isParticipating' => [
            'description' => 'IS_PARTICIPATING',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'IS_PARTICIPATING',
                    'placeholder' => 'IS_PARTICIPATING'
                ],
                'order' => 4
            ],
            'backend' => [
                'type' => 'int',
                'typeParams' => []
            ]
        ],
        'isManager' => [
            'description' => 'IS_MANAGER',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'IS_MANAGER',
                    'placeholder' => 'IS_MANAGER'
                ],
                'order' => 5
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
                    'label' =>  'DATE_TIME_CREATED',
                    'placeholder' => 'DATE_TIME_CREATED'
                ],
                'order' => 7
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ]
    ];

    private $relation;
    private $contactPersonRelation;

    public function getRelation()
    {
        if (empty($this->relation) && !empty($this->relationId))
        {
            $this->relation = new \app\perree\relation\MRelation($this->relationId);
        }
        return $this->relation;
    }

    public function getContactPersonRelation()
    {
        if (empty($this->contactPersonRelation) && !empty($this->contactPersonRelationId))
        {
            $this->contactPersonRelation = new \app\perree\relation\MRelation($this->contactPersonRelationId);
        }
        return $this->contactPersonRelation;
    }
}