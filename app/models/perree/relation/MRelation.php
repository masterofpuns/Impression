<?php

namespace app\perree\relation;

use app\h;
use app\m;
use app\perree\bankaccount\MBankAccount;
use framework\TraitReturnActions;

/**
 * MRelation
 *
 * @property $table string
 * @property $type string
 * @property $nameSortable string
 * @property $createdById int
 * @property $dateTimeCreated string
 * @property $updatedById int
 * @property $dateTimeUpdated string
 * @property $archived int
 * @property $archivedById int
 * @property $dateTimeArchived string
 */

class MRelation extends \app\CDBRecord implements \JsonSerializable
{
    use TraitReturnActions;

    protected $table = 'relation';
    protected $type;
    protected $nameSortable;
    protected $search;
    protected $createdById;
    protected $dateTimeCreated;
    protected $updatedById;
    protected $dateTimeUpdated;
    protected $archived;
    protected $archivedById;
    protected $dateTimeArchived;

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
        'nameSortable' => [
            'description' => 'NAME_SORTABLE',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'NAME_SORTABLE',
                    'placeholder' => 'NAME_SORTABLE'
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
        ],
        'archived' => [
            'description' => 'ARCHIVED',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'ARCHIVED',
                    'placeholder' => 'ARCHIVED'
                ],
                'order' => 7
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
                'order' => 8
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
                'order' => 9
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ],

        'parent' => ['description' => 'PARENT_RELATION'],
        'category' => ['description' => 'CATEGORY'],
    ];

    /** SERVICES */
    /** @var \app\perree\relation\CRelationService $relationService */
    private $relationService;

    /** ADDITIONAL VARS */
    private $typeObject;
    private $parent;
    private $company;
    private $category;
    private $notes;
    private $typeObjectModelClass;
    private $relationCategory;
    private $advisors;
    private $phoneNumbers;
    private $emailAddresses;
    private $visitingAddress;
    private $postalAddress;
    private $bankAccounts;
    private $primaryBankAccount;
    private $contactPersons;

    public function __construct($db_id = null)
    {
        parent::__construct($db_id);

        $this->relationService = m::app()->serviceManager->get('relationService');
    }

    public function getDatatableRow(): array
    {
        $this->getCategory();
        $this->getActions();

        $nameSortableLink = m::app()->renderPartial(
            'link/link',
            [
                'description' => $this->nameSortable,
                'url' => $this->getUrlView()
            ],
            true
        );

        $parentNameSortableLink = "";
        if(!empty($this->parent)){
            $parentNameSortableLink = m::app()->renderPartial(
                'link/link',
                [
                    'description' => $this->parent->nameSortable,
                    'url' => $this->parent->getUrlView()
                ],
                true
            );
        }


        return [
            $nameSortableLink,
            $parentNameSortableLink,
            !empty($this->type) ? t($this->type) : "",
            !empty($this->category) ? t($this->category->name) : "",
            $this->getActionsInTable()
        ];
    }

    public function getTypeObject(): ?object
    {
        if (!empty($this->type) && empty($this->typeObject)) {
            try {
                $this->getTypeObjectModelClass();
                $this->typeObject = new $this->typeObjectModelClass($this->id);
            } catch (\Error $e) {
                $this->typeObject = null;
            }
        }
        return $this->typeObject;
    }

    public function getTypeObjectModelClass(): string
    {
        if (empty($this->typeObjectModelClass)) {
            $this->typeObjectModelClass = '\app\perree\relation\M' . h::toPascalCase(strtolower($this->type));
        }
        return $this->typeObjectModelClass;
    }

    public function getParent(): ?MRelation
    {
        if (empty($this->parent) && $this->type == 'CONTACT_PERSON') {
            $this->getTypeObject();
            if (!empty($this->typeObject) && !empty($this->typeObject->parentRelationId)) {
                $this->parent = new MRelation($this->typeObject->parentRelationId);
            }
        }
        return $this->parent;
    }

    public function getCategory(): ?MCategory
    {
        if (empty($this->category)) {
            $this->category = $this->relationService->getCategoryForRelation($this->id);
        }
        return $this->category;
    }

    public function getNotes(): array
    {
        if (empty($this->notes)){
            $this->notes = $this->relationService->getNotesForRelation($this->id);
        }
        return $this->notes;
    }

    public function getRelationCategory(): ?MRelationCategory
    {
        if (empty($this->relationCategory)) {
            $this->relationCategory = $this->relationService->getRelationCategoryForRelation($this->id);
        }
        return $this->relationCategory;
    }

    public function getAdvisors(): ?array {
        if (empty($this->advisors))
        {
            $this->advisors = $this->relationService->getAdvisorsForRelation($this->id);
        }
        return $this->advisors;
    }

    public function getAdvisor($advisorRelationId): ?MRelation
    {
        if (!empty($advisorRelationId)) {
            $this->getAdvisors();
            if (array_key_exists($advisorRelationId, $this->advisors)) {
                return $this->advisors[$advisorRelationId];
            }
        }
        return null;
    }

    public function updateIndexFields()
    {
        $typeObject = $this->getTypeObject();
        $this->nameSortable = $this->typeObject->getNameSortable();

        // prepare search field
        $search = [];
        $search[] = $this->nameSortable;
        switch ($this->type) {
            case 'INDIVIDUAL':
            case 'ADVISOR':
            case 'CONTACT_PERSON':
                $search[] = $typeObject->getFullLastName() . ", " . $typeObject->firstName;
                $search[] = $typeObject->getFullName();
                break;
            case 'ORGANIZATION':
                break;
            case 'COLLECTIVE':
                /** @var \app\perree\relation\MCollective $typeObject */
                if (!empty($typeObject->getParticipatingContactPersons()))
                {
                    foreach ($typeObject->getParticipatingContactPersons() as $participatingContactPerson) {
                        $search[] = $participatingContactPerson->typeObject->getFullLastName() . ", " . $participatingContactPerson->typeObject->firstName;
                        $search[] = $participatingContactPerson->typeObject->getFullName();
                    }
                }
                break;
        }
        $this->search = implode(', ', $search);

        /*****************************************
         * Extended search
         ****************************************
        $searchExtended = '';

        $addresses = $this->getAddresses();
        if (!empty($addresses)) {
            foreach ($addresses as $address) {
                $searchExtended .= $searchExtended ? ', ' : '';
                $searchExtended .= implode(' ', [
                    $address->street,
                    $address->number,
                    $address->numberSuffix,
                    $address->postalCode,
                    $address->city,
                    $address->country,
                ]);
            }
        }

        $phoneNumbers = $this->getPhoneNumbers();
        if (!empty($phoneNumbers)) {
            foreach ($phoneNumbers as $phoneNumber) {
                $searchExtended .= $searchExtended ? ', ' : '';
                $searchExtended .= implode(' ', [
                    $phoneNumber->number,
                ]);
            }
        }

        $bankAccounts = $this->getBankAccounts();
        if (!empty($bankAccounts)) {
            foreach ($bankAccounts as $bankAccount) {
                $searchExtended .= $searchExtended ? ', ' : '';
                $searchExtended .= implode(' ', [
                    $bankAccount->iban,
                    $bankAccount->relationNumber,
                ]);
            }
        }

        $emailAddresses = $this->getEmailAddresses();
        if (!empty($emailAddresses)) {
            foreach ($emailAddresses as $emailAddress) {
                $searchExtended .= $searchExtended ? ', ' : '';
                $searchExtended .= implode(' ', [
                    $emailAddress->address
                ]);
            }
        }

        $this->searchExtended = !empty($searchExtended) ? $searchExtended : null;
        */

        $this->update();
    }

    public function getPhoneNumbers(): ?array
    {
        if (empty($this->phoneNumbers)) {
            $this->phoneNumbers = $this->relationService->getPhoneNumbersForRelation($this->id);
        }
        return $this->phoneNumbers;
    }

    public function getPhoneNumber($phoneNumber): ?MPhoneNumber
    {
        $this->getPhoneNumbers();
        if (!empty($this->phoneNumbers)) {
            return array_column($this->phoneNumbers, null, 'number')[$phoneNumber] ?? null;
        }
        return null;
    }

    public function getEmailAddresses(): ?array
    {
        if (empty($this->emailAddresses)) {
            $this->emailAddresses = $this->relationService->getEmailAddressesForRelation($this->id);
        }
        return $this->emailAddresses;
    }

    public function getEmailAddress($emailAddress): ?MEmailAddress
    {
        $this->getEmailAddresses();
        if (!empty($this->emailAddresses)) {
            return array_column($this->emailAddresses, null, 'address')[$emailAddress] ?? null;
        }
        return null;
    }

    public function getVisitingAddress(): ?MAddress
    {
        if (empty($this->visitingAddress)) {
            $this->visitingAddress = $this->relationService->getAddressForRelation($this->id, 'VISITING');
        }
        return $this->visitingAddress;
    }

    public function getPostalAddress(): ?MAddress
    {
        $this->getTypeObject();
        if (!empty($this->typeObject) && $this->typeObject->postalAddressEqualsVisitingAddress) {
            $this->postalAddress = $this->getVisitingAddress();
        } else if (empty($this->postalAddress)) {
            $this->postalAddress = $this->relationService->getAddressForRelation($this->id, 'POSTAL');
        }
        return $this->postalAddress;
    }

    public function getBankAccounts(): ?array
    {
        if (empty($this->bankAccounts))
        {
            $this->bankAccounts = $this->relationService->getBankAccountsForRelation($this->id);
        }
        return $this->bankAccounts;
    }

    public function getPrimaryBankAccount(): ?MBankAccount
    {
        if (empty($this->primaryBankAccount))
        {
            $this->primaryBankAccount = $this->relationService->getPrimaryBankAccountForRelation($this->id);
        }
        return $this->primaryBankAccount;
    }

    public function convertBackendValues()
    {
        parent::convertBackendValues();
        $this->getTypeObject();
        if (!empty($this->typeObject)) {
            $this->typeObject->convertBackendValues();
        }
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

        return $actions;
    }

    public function getContactPersons(): ?array
    {
        if (empty($this->contactPersons)) {
            $this->contactPersons = $this->relationService->getContactPersonRelationsForRelation($this->id);
            /*switch ($this->type) {
                case 'ADVISOR':
                case 'INDIVIDUAL':
                    $this->contactPersons = $this->relationService->getContactPersonRelationsForRelation($this->id);
                    break;
                case 'ORGANIZATION':
                    $this->contactPersons = $this->relationService->getManagerContactPersonRelationsForRelation($this->id);
                    break;
                case 'COLLECTIVE':
                    $this->contactPersons = $this->relationService->getParticipatingContactPersonRelationsForRelation($this->id);
                    break;
            }*/
        }
        return $this->contactPersons;
    }
    
    public function getUrlEdit()
    {
        $doc = m::app()->getDocByName('relation-edit');
        $params = [$this->id];

        $this->getParent();
        if ($this->type === 'CONTACT_PERSON' && !empty($this->parent))
        {
            $doc = m::app()->getDocByName('relation-contactperson-edit');
            $params = [
                $this->parent->id,
                $this->id
            ];
        }

        return $doc->getUrl($params);
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->nameSortable
        ];
    }
}