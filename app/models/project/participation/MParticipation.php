<?php

namespace app\perree\participation;

/**
 * MParticipation
 *
 * @property $table string
 * @property $name string
 * @property $fundId int
 * @property $relationId int
 * @property $advisorId int
 * @property $bankAccountId int
 * @property $status string
 * @property $bondAmount int
 * @property $bondValue int
 * @property $bondVolume int
 * @property $interestRate string
 * @property $startDateTime string
 * @property $endDateTime string
 * @property $createdById int
 * @property $dateTimeCreated string
 * @property $updatedById int
 * @property $dateTimeUpdated string
 * @property $archived int
 * @property $archivedById int
 * @property $dateTimeArchived string
 */

class MParticipation extends \app\CDBRecord {

    protected $table = 'participation';
    protected $name;
    protected $fundId;
    protected $relationId;
    protected $advisorId;
    protected $bankAccountId;
    protected $status;
    protected $bondAmount;
    protected $bondValue;
    protected $bondVolume;
    protected $interestRate;
    protected $startDateTime;
    protected $endDateTime;
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
        'fundId' => [
            'description' => 'FUND_ID',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'FUND_ID',
                    'placeholder' => 'FUND_ID'
                ],
                'order' => 2
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
                'order' => 3
            ],
            'backend' => [
                'type' => 'int',
                'typeParams' => []
            ]
        ],
        'advisorId' => [
            'description' => 'ADVISOR_ID',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'ADVISOR_ID',
                    'placeholder' => 'ADVISOR_ID'
                ],
                'order' => 4
            ],
            'backend' => [
                'type' => 'int',
                'typeParams' => []
            ]
        ],
        'bankAccountId' => [
            'description' => 'BANK_ACCOUNT_ID',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'BANK_ACCOUNT_ID',
                    'placeholder' => 'BANK_ACCOUNT_ID'
                ],
                'order' => 5
            ],
            'backend' => [
                'type' => 'int',
                'typeParams' => []
            ]
        ],
        'status' => [
            'description' => 'STATUS',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'STATUS',
                    'placeholder' => 'STATUS'
                ],
                'order' => 6
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ],
        'bondAmount' => [
            'description' => 'BOND_AMOUNT',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'BOND_AMOUNT',
                    'placeholder' => 'BOND_AMOUNT'
                ],
                'order' => 7
            ],
            'backend' => [
                'type' => 'int',
                'typeParams' => []
            ]
        ],
        'bondValue' => [
            'description' => 'BOND_VALUE',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'BOND_VALUE',
                    'placeholder' => 'BOND_VALUE'
                ],
                'order' => 8
            ],
            'backend' => [
                'type' => 'int',
                'typeParams' => []
            ]
        ],
        'bondVolume' => [
            'description' => 'BOND_VOLUME',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'BOND_VOLUME',
                    'placeholder' => 'BOND_VOLUME'
                ],
                'order' => 9
            ],
            'backend' => [
                'type' => 'int',
                'typeParams' => []
            ]
        ],
        'interestRate' => [
            'description' => 'INTEREST_RATE',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'INTEREST_RATE',
                    'placeholder' => 'INTEREST_RATE'
                ],
                'order' => 10
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ],
        'startDateTime' => [
            'description' => 'START_DATE_TIME',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'START_DATE_TIME',
                    'placeholder' => 'START_DATE_TIME'
                ],
                'order' => 11
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ],
        'endDateTime' => [
            'description' => 'END_DATE_TIME',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'END_DATE_TIME',
                    'placeholder' => 'END_DATE_TIME'
                ],
                'order' => 12
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
                'order' => 13
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
                'order' => 14
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
                'order' => 15
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
                'order' => 16
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
                'order' => 17
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
                'order' => 18
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
                'order' => 19
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ],

        'fund' => ['description' => 'FUND'],
        'relation' => ['description' => 'RELATION']
    ];

    private $fund;
    private $relation;

    public function getFund()
    {
        if (empty($this->fund) && !empty($this->fundId)) {
            $this->fund = new \app\perree\fund\MFund($this->fundId);
        }
        return $this->fund;
    }

    public function getRelation()
    {
        if (empty($this->relation) && !empty($this->relationId)) {
            $this->relation = new \app\perree\relation\MRelation($this->relationId);
        }
        return $this->relation;
    }

    public function getDatatableRow()
    {
        $this->getFund();
        $this->getRelation();

        return [
            $this->name,
            $this->fund->name,
            $this->relation->name,
            $this->bondAmount,
            $this->bondValue,
            $this->status
        ];
    }
}