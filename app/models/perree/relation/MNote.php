<?php

namespace app\perree\relation;

use framework\MUser;

/**
 * MNote
 *
 * @property $table string
 * @property $relationId int
 * @property $description string
 * @property $createdById int
 * @property $dateTimeCreated string
 * @property $updatedById int
 * @property $dateTimeUpdated string
 */

class MNote extends \app\CDBRecord implements \JsonSerializable {

    protected $table = 'note';
    protected $relationId;
    protected $description;
    protected $createdById;
    protected $dateTimeCreated;
    protected $updatedById;
    protected $dateTimeUpdated;

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
        'description' => [
            'description' => 'DESCRIPTION',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'DESCRIPTION',
                    'placeholder' => 'DESCRIPTION'
                ],
                'order' => 2
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
                'order' => 3
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
                'order' => 4
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
                'order' => 5
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
                'order' => 6
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ]
    ];

    private $user;
    private $userUpdated;

    public function getUser() {
        if (is_null($this->user) && !is_null($this->createdById)) {
            $this->user = new MUser($this->createdById);
        }

        return $this->user;
    }

    public function getUserUpdated() {
        if (!is_null($this->updatedById)) {
            $this->userUpdated = new MUser($this->updatedById);
        }

        return $this->userUpdated;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description
        ];
    }
}