<?php

namespace app\perree\fund;

use app\h;
use app\m;
use framework\TraitReturnActions;

/**
 * MFoundation
 *
 * @property $table string
 * @property $name string
 * @property $createdById int
 * @property $dateTimeCreated string
 * @property $updatedById int
 * @property $dateTimeUpdated string
 * @property $archived int
 * @property $archivedById int
 * @property $dateTimeArchived string
 */

class MFoundation extends \app\CDBRecord implements \JsonSerializable {

    protected $table = 'foundation';
    protected $name;
    protected $createdById;
    protected $dateTimeCreated;
    protected $updatedById;
    protected $dateTimeUpdated;
    protected $archived;
    protected $archivedById;
    protected $dateTimeArchived;

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
        ],
        'archived' => [
            'description' => 'ARCHIVED',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'ARCHIVED',
                    'placeholder' => 'ARCHIVED'
                ],
                'order' => 6
            ],
            'backend' => [
                'type' => 'int',
                'typeParams' => []
            ]
        ],
        'archivedById' => [
            'description' => 'ARCHIVED_BY_ID',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'ARCHIVED_BY_ID',
                    'placeholder' => 'ARCHIVED_BY_ID'
                ],
                'order' => 7
            ],
            'backend' => [
                'type' => 'int',
                'typeParams' => []
            ]
        ],
        'dateTimeArchived' => [
            'description' => 'DATE_TIME_ARCHIVED',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'DATE_TIME_ARCHIVED',
                    'placeholder' => 'DATE_TIME_ARCHIVED'
                ],
                'order' => 8
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ]
    ];

    /** SERVICES */
    /** @var \app\perree\fund\CFundService $fundService */
    private $fundService;

    /** ADDITIONAL VARS */
    private $bankAccount;

    public function __construct($db_id = null)
    {
        parent::__construct($db_id);

        $this->fundService = m::app()->serviceManager->get('fundService');
    }

    public function getDatatableRow(): array
    {
        $nameLink = m::app()->renderPartial(
            'link/link',
            [
                'description' => $this->name,
                'url' => $this->getUrlView()
            ],
            true
        );

        return [
            $nameLink,
        ];
    }

    public function getBankAccount(): ?object
    {
        if (empty($this->bankAccount) && !empty($this->id))
        {
            $this->bankAccount = $this->fundService->getBankAccountForFoundation($this->id);
        }
        return $this->bankAccount;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }
}