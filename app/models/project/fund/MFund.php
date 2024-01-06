<?php

namespace app\perree\fund;

use app\h;
use app\m;
use framework\TraitReturnActions;
use framework\TraitReturnCrudUrls;
use NumberFormatter;

/**
 * MFund
 *
 * @property $table string
 * @property $curatorId int
 * @property $foundationId int
 * @property $name string
 * @property $status string
 * @property $type string
 * @property $bondAmount int
 * @property $bondVolume int
 * @property $bondValue int
 * @property $interestRate string
 * @property $daysFiscalYear int
 * @property $fundStartDate string
 * @property $fundEndDate string
 * @property $chamberOfCommerceNumber int
 * @property $repayment string
 * @property $firstPayment string
 * @property $createdById int
 * @property $dateTimeCreated string
 * @property $updatedById int
 * @property $dateTimeUpdated string
 * @property $archived int
 * @property $archivedById int
 * @property $dateTimeArchived string
 */

class MFund extends \app\CDBRecord {

    use TraitReturnCrudUrls;
    use TraitReturnActions;

    protected $table = 'fund';
    protected $curatorId;
    protected $foundationId;
    protected $name;
    protected $status;
    protected $type;
    protected $bondAmount;
    protected $bondVolume;
    protected $bondValue;
    protected $interestRate;
    protected $daysFiscalYear;
    protected $fundStartDate;
    protected $fundEndDate;
    protected $chamberOfCommerceNumber;
    protected $repayment;
    protected $firstPayment;
    protected $createdById;
    protected $dateTimeCreated;
    protected $updatedById;
    protected $dateTimeUpdated;
    protected $archived;
    protected $archivedById;
    protected $dateTimeArchived;

    protected $fields = [
        'curatorId' => [
            'description' => 'CURATOR_ID',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'CURATOR_ID',
                    'placeholder' => 'CURATOR_ID'
                ],
                'order' => 1
            ],
            'backend' => [
                'type' => 'int',
                'typeParams' => []
            ]
        ],
        'foundationId' => [
            'description' => 'FOUNDATION_ID',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'FOUNDATION_ID',
                    'placeholder' => 'FOUNDATION_ID'
                ],
                'order' => 2
            ],
            'backend' => [
                'type' => 'int',
                'typeParams' => []
            ]
        ],
        'name' => [
            'description' => 'NAME',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'NAME',
                    'placeholder' => 'NAME'
                ],
                'order' => 3
            ],
            'backend' => [
                'type' => 'text',
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
                'order' => 4
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ],
        'type' => [
            'description' => 'TYPE',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'TYPE',
                    'placeholder' => 'TYPE'
                ],
                'order' => 5
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
                'order' => 6
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
        'interestRate' => [
            'description' => 'INTEREST_RATE',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'INTEREST_RATE',
                    'placeholder' => 'INTEREST_RATE'
                ],
                'order' => 9
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ],
        'daysFiscalYear' => [
            'description' => 'DAYS_FISCAL_YEAR',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'DAYS_FISCAL_YEAR',
                    'placeholder' => 'DAYS_FISCAL_YEAR'
                ],
                'order' => 10
            ],
            'backend' => [
                'type' => 'int',
                'typeParams' => []
            ]
        ],
        'fundStartDate' => [
            'description' => 'FUND_START_DATE',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'FUND_START_DATE',
                    'placeholder' => 'FUND_START_DATE'
                ],
                'order' => 11
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ],
        'fundEndDate' => [
            'description' => 'FUND_END_DATE',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'FUND_END_DATE',
                    'placeholder' => 'FUND_END_DATE'
                ],
                'order' => 12
            ],
            'backend' => [
                'type' => 'text',
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
                'order' => 13
            ],
            'backend' => [
                'type' => 'int',
                'typeParams' => []
            ]
        ],
        'repayment' => [
            'description' => 'REPAYMENT',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'REPAYMENT',
                    'placeholder' => 'REPAYMENT'
                ],
                'order' => 14
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ],
        'firstPayment' => [
            'description' => 'FIRST_PAYMENT',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'FIRST_PAYMENT',
                    'placeholder' => 'FIRST_PAYMENT'
                ],
                'order' => 15
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
                'order' => 16
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
                'order' => 17
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
                'order' => 18
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
                'order' => 19
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
                'order' => 20
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
                'order' => 21
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
                'order' => 22
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ],
        'participants' => [
            'description' => 'PARTICIPANTS',
        ]
    ];

    /** SERVICES */
    /** @var \app\perree\fund\CFundService $fundService */
    private $fundService;

    /** ADDITIONAL VARS */
    private $bankAccount;
    private $curator;
    private $foundation;
    private $fundTerm;


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

        $statusText = $this->status;
        if($this->status === 'ACTIVE'){
            $statusText = 'FUND_ACTIVE';
        }

        return [
            $nameLink,
            $this->bondAmount,
            'in ontwikkeling',
            $this->bondValue,
            t($statusText),
            $this->getActionsInTable()
        ];
    }

    public function getActions(): ?array
    {
        $actions = [];

        $action = new \app\CAction();
        $action->description = '';
        $action->icon = 'pencil';
        $action->class = 'btn-outline-secondary-700 border-0';
        $action->type = 'link';
        $action->url = $this->getUrlEdit();
        $actions[] = $action;

        $action = new \app\CAction();
        $action->icon = 'archive';
        $action->class = 'btn btn-outline-secondary-700 border-0';
        $action->type = 'button';
        $action->description = '';
        $action->params = [
            'data' => [
                'bs-toggle' => 'modal',
                'bs-target' => '#modal-confirm',
                'hook' => 'modal-confirm-archive',
                'cssId' => 'modal-confirm',
                'confirm-href' => $this->getUrlArchiveAjax(),
                'confirm-title' => t('FUND_ARCHIVE'),
                'confirm-message' => 'Weet je zeker dat je het fonds &quot;'.$this->name.'&quot; wilt archiveren?'
            ]
        ];
        $actions[] = $action;

        return $actions;
    }


    public function getBankAccount(): ?object
    {
        if (empty($this->bankAccount) && !empty($this->id))
        {
            $this->bankAccount = $this->fundService->getBankAccountForFund($this->id);
        }
        return $this->bankAccount;
    }

    public function getCurator(): ?MCurator
    {
        if (empty($this->curator))
        {
            $this->curator = new \app\perree\fund\MCurator($this->curatorId);
            $this->curator->convertBackendValues();
        }
        return $this->curator;
    }

    public function getFoundation(): ?MFoundation
    {
        if (empty($this->foundation))
        {
            $this->foundation = new \app\perree\fund\MFoundation($this->foundationId);
            $this->foundation->convertBackendValues();
        }
        return $this->foundation;
    }

    public function getRepayments()
    {
        return [];
    }

    public function getFundTerm()
    {
        if(empty($this->fundTerm) && !empty($this->fundStartDate) && !empty($this->fundEndDate)){
            $fundStartDate = new \DateTime($this->fundStartDate);
            $fundEndDate = new \DateTime($this->fundEndDate);
            $difference = $fundStartDate->diff($fundEndDate);
            $this->fundTerm = $difference->y.' '.t('YEAR').', '.$difference->m.' '.t('MONTHS').', '.$difference->d.' '.t('DAYS');
        }
        return $this->fundTerm;
    }

    public function convertPostValues()
    {
        parent::convertPostValues();

        if (empty($this->foundationId))
        {
            $this->foundationId = null;
        }
        if (empty($this->curatorId))
        {
            $this->curatorId = null;
        }
        if (!empty($this->fundStartDate)) {
            $this->fundStartDate = date('Y-m-d H:i:s', strtotime($this->fundStartDate));
        } else {
            $this->fundStartDate = null;
        }
        if (!empty($this->fundEndDate)) {
            $this->fundEndDate = date('Y-m-d H:i:s', strtotime($this->fundEndDate));
        } else {
            $this->fundEndDate = null;
        }
    }

    public function convertBackendValues()
    {
        parent::convertBackendValues();

        $currencyFormat = new NumberFormatter('nl_NL',  NumberFormatter::CURRENCY);

        if (!empty($this->bondValue)) {
            $this->bondValue = $currencyFormat->formatCurrency($this->bondValue, 'EUR');
        }
        if (!empty($this->bondVolume)) {
            $this->bondVolume = $currencyFormat->formatCurrency($this->bondVolume, 'EUR');
        }
        if (!empty($this->fundStartDate)) {
            $this->fundStartDate = date('d-m-Y', strtotime($this->fundStartDate));
        }
        if (!empty($this->fundEndDate)) {
            $this->fundEndDate = date('d-m-Y', strtotime($this->fundEndDate));
        }
        if (!empty($this->repayment)) {
            $this->repayment = date('d-m-Y', strtotime($this->repayment)); // TODO: volgens mij moet deze anders ingevoerd worden
        }
        if (!empty($this->firstPayment)) {
            $this->firstPayment = date('d-m-Y', strtotime($this->firstPayment));
        }
        if (!empty($this->dateTimeCreated)) {
            $this->dateTimeCreated = date('d-m-Y', strtotime($this->dateTimeCreated));
        }
        if (!empty($this->dateTimeUpdated)) {
            $this->dateTimeUpdated = date('d-m-Y', strtotime($this->dateTimeUpdated));
        }

    }
}