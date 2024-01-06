<?php

namespace app\perree\document;

use app\m;
use app\h;
use framework\TraitReturnActions;

/**
 * MDocument
 *
 * @property $table string
 * @property $name string
 * @property $documentCategoryId int
 * @property $fileMediaId int
 * @property $dateTimeCreated string
 * @property $createdById int
 * @property $archived int
 * @property $archivedById int
 */

class MDocument extends \app\CDBRecord {

    use TraitReturnActions;

    protected $table = 'document';
    protected $name;
    protected $documentCategoryId;
    protected $fileMediaId;
    protected $dateTimeCreated;
    protected $createdById;
    protected $archived;
    protected $archivedById;

    protected $fields = [
        'name' => [
            'description' => 'DOCUMENT_NAME',
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
        'documentCategoryId' => [
            'description' => 'DOCUMENT_CATEGORY_ID',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'DOCUMENT_CATEGORY_ID',
                    'placeholder' => 'DOCUMENT_CATEGORY_ID'
                ],
                'order' => 2
            ],
            'backend' => [
                'type' => 'int',
                'typeParams' => []
            ]
        ],
        'fileMediaId' => [
            'description' => 'FILE_MEDIA_ID',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'FILE_MEDIA_ID',
                    'placeholder' => 'FILE_MEDIA_ID'
                ],
                'order' => 3
            ],
            'backend' => [
                'type' => 'int',
                'typeParams' => []
            ]
        ],
        'dateTimeCreated' => [
            'description' => 'DATE',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'DATE',
                    'placeholder' => 'DATE'
                ],
                'order' => 4
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
                'order' => 5
            ],
            'backend' => [
                'type' => 'int',
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
        ]
    ];

    // SERVICES
    /** @var \app\perree\document\CDocumentService */
    private $documentService;

    private $relatedEntity;
    private $referenceModel;
    /** @var \app\perree\media\MMedia */
    private $file;

    public function __construct($db_id = null)
    {
        parent::__construct($db_id);

        $this->documentService = m::app()->serviceManager->get('documentService');
    }

    public function deleteRelated()
    {
        $this->getReferenceModel();
        if (!empty($this->referenceModel)) {
            $this->referenceModel->delete();
        }
    }

    public function getDatatableRow() {
        $this->getFile();
        $nameLink = m::app()->renderPartial(
            'link/link',
            [
                'description' => $this->name,
                'url' => !empty($this->file) ? $this->file->getRelativeLocation() : '#',
                'target' => '_blank'
            ],
            true
        );

        return [
            date('d-m-Y', strtotime($this->dateTimeCreated)),
            $nameLink,
            $this->getActionsInTable()
        ];
    }

    public function getActions() {
        $actions = [];

        $action = new \app\CAction();
        $action->description = '';
        $action->icon = 'pencil';
        $action->class = 'btn-outline-secondary-700 border-0';
        $action->type = 'button';
        $action->params['data'] = [
            'bs-toggle' => 'modal',
            'bs-target' => '#modal-document-add-edit',
            'confirm-url' => m::app()->getDocByName('document-save-ajax')->url,
            'document-id' => $this->id,
            'document-name' => $this->name,
        ];
        $this->getAdditionalParamsForAction($action);
        $actions[] = $action;

        $action = new \app\CAction();
        $action->description = '';
        $action->icon = 'trash';
        $action->class = 'btn-outline-secondary-700 border-0';
        $action->type = 'button';
        $action->params['data'] = [
            'bs-toggle' => 'modal',
            'bs-target' => '#modal-document-delete',
            'confirm-url' => m::app()->getDocByName('document-delete-ajax')->url,
            'document-id' => $this->id,
            'document-name' => $this->name,
        ];
        $this->getAdditionalParamsForAction($action);
        $actions[] = $action;

        return $actions;
    }

    public function getRelatedEntity()
    {
        if (empty($this->relatedEntity)) {
            $this->getReferenceModel();

            switch ((new \ReflectionClass($this->referenceModel))->getShortName()) {
                case 'MRelationDocument':
                    $this->relatedEntity = new \app\perree\relation\MRelation($this->referenceModel->relationId);
                    break;
                case 'MFundDocument':
                    $this->relatedEntity = new \app\perree\fund\MFund($this->referenceModel->fundId);
                    break;
                case 'MFoundationDocument':
                    $this->relatedEntity = new \app\perree\fund\MFoundation($this->referenceModel->foundationId);
                    break;
            }
        }
    }

    public function getReferenceModel()
    {
        if (empty($this->referenceModel)) {
            $this->referenceModel = $this->documentService->getRerefenceModelForDocument($this->id);
        }
        return $this->referenceModel;
    }

    private function getAdditionalParamsForAction(\app\CAction &$action)
    {
        $this->getRelatedEntity();

        $action->params['data']['entity-id'] = $this->relatedEntity->id;
        switch ((new \ReflectionClass($this->relatedEntity))->getShortName()) {
            case 'MRelation':
                $action->params['data']['entity'] = 'relation';
                $action->params['data']['table'] = 'table-relation_document';
                break;
            case 'MFund':
                $action->params['data']['entity'] = 'fund';
                $action->params['data']['table'] = 'table-fund_document';
                break;
            case 'MFoundation':
                $action->params['data']['entity'] = 'foundation';
                $action->params['data']['table'] = 'table-foundation_document';
                break;
        }
    }

    public function getFile() {
        if (empty($this->file) && !empty($this->fileMediaId)) {
            $this->file = new \app\perree\media\MMedia($this->fileMediaId);
        }
        return $this->file;
    }
}