<?php

namespace app\perree\relation;

use app\m;
use app\h;
use app\TraitReturnNameForModel;
use framework\TraitReturnActions;


/**
 * MContactPerson
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
 * @property $parentRelationId int
 * @property $isProxy int
 * @property $isManager int
 * @property $isParticipant int
 * @property $receivesCorrespondence int
 */
class MContactPerson extends \app\CDBRecord
{
    use TraitReturnActions;
    use TraitReturnNameForModel;

    protected $table = 'contact_person';
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
    protected $parentRelationId;
    protected $isProxy;
    protected $isManager;
    protected $isParticipant;
    protected $receivesCorrespondence;

    protected $fields = [
        'salutationId' => [
            'description' => 'SALUTATION_ID',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' => 'SALUTATION_ID',
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
                    'label' => 'TITLE_BEFORE',
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
                    'label' => 'INITIALS',
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
                    'label' => 'FIRST_NAME',
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
                    'label' => 'LAST_NAME_PREFIX',
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
                    'label' => 'LAST_NAME',
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
                    'label' => 'TITLE_AFTER',
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
                    'label' => 'LANGUAGE',
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
                    'label' => 'ID_MEDIA_ID',
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
                    'label' => 'ID_TYPE',
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
                    'label' => 'ID_NUMBER',
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
                    'label' => 'ID_DATE_EXPIRATION',
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
                    'label' => 'NATIONALITY',
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
                    'label' => 'BIRTH_PLACE',
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
                    'label' => 'BIRTH_DATE',
                    'placeholder' => 'BIRTH_DATE'
                ],
                'order' => 15
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ],
        'parentRelationId' => [
            'description' => 'PARENT_RELATION_ID',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' => 'PARENT_RELATION_ID',
                    'placeholder' => 'PARENT_RELATION_ID'
                ],
                'order' => 16
            ],
            'backend' => [
                'type' => 'int',
                'typeParams' => []
            ]
        ],
        'isProxy' => [
            'description' => 'IS_PROXY_DATATABLE',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' => 'IS_PROXY',
                    'placeholder' => 'IS_PROXY'
                ],
                'order' => 17
            ],
            'backend' => [
                'type' => 'int',
                'typeParams' => []
            ]
        ],
        'isManager' => [
            'description' => 'IS_MANAGER_DATATABLE',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' => 'IS_MANAGER',
                    'placeholder' => 'IS_MANAGER'
                ],
                'order' => 18
            ],
            'backend' => [
                'type' => 'int',
                'typeParams' => []
            ]
        ],
        'isParticipant' => [
            'description' => 'IS_PARTICIPANT_DATATABLE',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' => 'IS_PARTICIPANT',
                    'placeholder' => 'IS_PARTICIPANT'
                ],
                'order' => 19
            ],
            'backend' => [
                'type' => 'int',
                'typeParams' => []
            ]
        ],
        'receivesCorrespondence' => [
            'description' => 'CORRESPONDENCE',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'RECEIVES_CORRESPONDENCE',
                    'placeholder' => 'RECEIVES_CORRESPONDENCE'
                ],
                'order' => 20
            ],
            'backend' => [
                'type' => 'int',
                'typeParams' => []
            ]
        ],
        'nameSortable' => [
            'description' => 'NAME',
        ],
    ];

    /** SERVICES */
    /** @var \app\perree\relation\CRelationService $relationService */
    private $relationService;

    private $relation;

    private $idFile;


    public function __construct($db_id = null)
    {
        parent::__construct($db_id);

        $this->relationService = m::app()->serviceManager->get('relationService');
    }

    public function getNameSortable(): ?string
    {
        $nameSortable = $this->lastName;

        if (!empty($this->initials)) {
            $nameSortable .= ', ' . $this->initials;
        }
        if (!empty($this->lastNamePrefix)) {
            $nameSortable .= (!empty($this->initials) ? ' ' : ', ') . $this->lastNamePrefix;
        }

        return $nameSortable;
    }

    public function getDatatableRow(): array
    {
        $this->getActions();
        $this->getRelation();

        $nameSortableLink = m::app()->renderPartial(
            'link/link',
            [
                'description' => $this->relation->nameSortable,
                'url' => $this->relation->getUrlView()
            ],
            true
        );

        $return = [];

        switch ($this->relation->parent->type) {
            case 'INDIVIDUAL':
                $return = [
                    $nameSortableLink,
                    $this->receivesCorrespondence ? t('YES') : t('NO'),
                    !empty($this->isProxy) ? t('YES') : t('NO'),
                    $this->getActionsInTable()
                ];

                break;
            case 'ORGANIZATION':
                // Datatable instellingen
                $return = [
                    $nameSortableLink,
                    $this->receivesCorrespondence ? t('YES') : t('NO'),
                    !empty($this->isManager) ? t('YES') : t('NO'),
                    $this->getActionsInTable()
                ];
                break;
            case 'COLLECTIVE':
                $proxyText = t('NO');
                if($this->isParticipant){
                    $proxyText = t('NVT', [], false);
                } else {
                    $proxyText = !empty($this->isProxy) ? t('YES') : t('NO');
                }

                $return = [
                    $nameSortableLink,
                    $this->receivesCorrespondence ? t('YES') : t('NO'),
                    !empty($this->isParticipant) ? t('YES') : t('NO'),
                    $proxyText,
                    $this->getActionsInTable()
                ];
                break;
        }

        return $return;
    }

    public function getRelation(): ?MRelation
    {
        if (empty($this->relation)) {
            $this->relation = new MRelation($this->id);
        }
        return $this->relation;
    }

    public function getActions(): ?array
    {
        $this->getRelation();

        $actions = [];

        $action = new \app\CAction();
        $action->description = '';
        $action->icon = 'pencil';
        $action->class = 'btn-outline-secondary-700 border-0';
        $action->type = 'link';
        $action->url = $this->relation->getUrlEdit();
        $actions[] = $action;

        $action = new \app\CAction();
        $action->description = '';
        $action->icon = 'archive';
        $action->class = 'btn-outline-secondary-700 border-0';
        $action->type = 'button';
        $action->params['data'] = [
            'hook' => 'modal-duplicate-questionnaire',
            'bs-toggle' => 'modal',
            'bs-target' => '#modal-confirm',
            'confirm-href' => $this->relation->getUrlArchiveAjax(),
            'confirm-title' => t('CONTACTPERSON_ARCHIVE'),
            'confirm-subject' => t('CONTACTPERSON'),
            'confirm-message' => "Weet je zeker dat je contactpersoon '". $this->relation->nameSortable."' wilt archiveren?",
            'cssId' => ''
        ];
        $actions[] = $action;

        return $actions;
    }

    /**
     *  Function to retrieve the full lastname of a contactperson
     *
     * @return string
     */
    public function getFullLastName()
    {
        $fullLastNameArray = [];

        if (!empty($this->lastNamePrefix)) {
            $fullLastNameArray[] = $this->lastNamePrefix;
        }

        if (!empty($this->lastName)) {
            $fullLastNameArray[] = ucfirst($this->lastName);
        }

        return implode(' ', $fullLastNameArray);
    }

    /**
     *  Function to retrieve the full name of a contactperson
     *
     * @return string
     */
    public function getFullName()
    {
        $fullNameArray = [];

        if (!empty($this->firstName)) {
            $fullNameArray[] = ucfirst($this->firstName);
        }

        if (!empty($this->getFullLastName())) {
            $fullNameArray[] = $this->getFullLastName();
        }

        return implode(' ', $fullNameArray);
    }

    /**
     *  Function to retrieve the full name of a contactperson
     *
     * @return string
     */
    public function getFullNameInclInitials()
    {
        $fullNameArray = [];

        if (!empty($this->initials)) {
            $fullNameArray[] = strtoupper($this->initials);
        }

        if (!empty($this->getFullLastName())) {
            $fullNameArray[] = $this->getFullLastName();
        }

        return implode(' ', $fullNameArray);
    }

    /**
     *  Function to retrieve the full name of a contactperson including titles
     *
     * @return string
     */
    public function getFullNameInclTitle()
    {
        $fullNameArray = [];

        if (!empty($this->titleBefore)) {
            $fullNameArray[] = $this->titleBefore;
        }

        if (!empty($this->getFullName())) {
            $fullNameArray[] = $this->getFullName();
        }
        if (!empty($this->titleAfter)) {
            $fullNameArray[] = $this->titleAfter;
        }

        return implode(' ', $fullNameArray);
    }

    /**
     *  Function to retrieve the full name of a contactperson including titles and initials instead of firstName
     *
     * @return string
     */
    public function getFullNameInclInitialsAndTitle()
    {
        $fullNameArray = [];

        if (!empty($this->titleBefore)) {
            $fullNameArray[] = $this->titleBefore;
        }

        if (!empty($this->getFullNameInclInitials())) {
            $fullNameArray[] = $this->getFullNameInclInitials();
        }
        if (!empty($this->titleAfter)) {
            $fullNameArray[] = $this->titleAfter;
        }

        return implode(' ', $fullNameArray);
    }

    public function getIdFile()
    {
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
        if (empty($this->receivesCorrespondence)) {
            $this->receivesCorrespondence = 0;
        }
    }
}