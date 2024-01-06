<?php

namespace app\perree\relation;

use app\CRUDController;
use app\h;
use app\m;

class RelationController extends CRUDController
{
    protected $dbObject = '\app\perree\relation\MRelation';
    protected $viewFolder = 'relation';
    protected $overviewUrl = 'relation';
    protected $filterUrl = 'relation/filter';
    protected $datatableFields = ['nameSortable', 'parent', 'type', 'category', 'actions'];
    protected $datatableJoinSQL = 'LEFT JOIN relation_category RC ON RC.relationId = relation.id';

    /** ORDER */
    protected $datatableFieldsOrder = [['column' => 0, 'dir' => 'asc']]; // ID
    protected $datatableFieldsOrderable = ['nameSortable', 'parent', 'type', 'category'];
    protected $datatableOrderDirection = 'asc';
    protected $datatableOrderJoins = [
        'parent' => [
            'sql' => '
                LEFT JOIN contact_person CP ON CP.id = relation.id
                LEFT JOIN relation R2 ON R2.id = CP.parentRelationId
            ',
            'queryField' => 'R2.nameSortable'
        ],
        'category' => [
            'sql' => '
                LEFT JOIN category C ON C.id = RC.categoryId
            ',
            'queryField' => 'C.name'
        ]
    ];
    protected $datatableWhereSQL = "WHERE (relation.archived IS NULL OR relation.archived = 0)";

    /** SEARCH */
    protected $datatableFieldsSearchable = [
        'relation.search',
        'R2.nameSortable',
        'relation.type',
        'C.name'
    ];
    protected $datatableSearchJoins = [
        'LEFT JOIN relation_category RC ON RC.relationId = relation.id',
        'LEFT JOIN category C ON C.id = RC.categoryId',
        'LEFT JOIN contact_person CP ON CP.id = relation.id',
        'LEFT JOIN relation R2 ON R2.id = CP.parentRelationId'
    ];

    /*
     * FILTER
     */
    protected $datatableMultiFilters = [
        'type' => [
            'values' => [
                [
                    'id' => 'INDIVIDUAL',
                    'description' => 'INDIVIDUAL'
                ],
                [
                    'id' => 'ORGANIZATION',
                    'description' => 'ORGANIZATION'
                ],
                [
                    'id' => 'COLLECTIVE',
                    'description' => 'COLLECTIVE'
                ],
                [
                    'id' => 'ADVISOR',
                    'description' => 'ADVISOR'
                ],
                [
                    'id' => 'CONTACT_PERSON',
                    'description' => 'CONTACT_PERSON'
                ],
            ],
            'queryField' => 'relation.type',
            'description' => 'TYPE'
        ],
        'category' => [
            'values' => [
                [
                    'id' => 1,
                    'description' => 'PARTICIPANT'
                ],
                [
                    'id' => 2,
                    'description' => 'POTENTIAL_PARTICIPANT'
                ],
                [
                    'id' => 3,
                    'description' => 'EX_PARTICIPANT'
                ]
            ],
            'queryField' => 'RC.categoryId',
            'description' => 'CATEGORY'
        ],
    ];

    /** @var \app\perree\relation\CRelationService $relationService */
    private $relationService;

    public function __construct() {
        // get possible selected multiFilters
        $multiFilterKeys = array_keys($this->datatableMultiFilters);
        if (!empty($multiFilterKeys)) {
            foreach ($multiFilterKeys as $multiFilterName) {
                $requestMultiFilter = h::getV($multiFilterName . 'Filter', 'any', null, 'GET', false);
                if (!empty($requestMultiFilter)) {
                    $selected = [];
                    $values = explode(',', $requestMultiFilter);
                    foreach ($values as $value) {
                        $key = array_search($value, array_column($this->datatableMultiFilters[$multiFilterName]['values'], 'description'));
                        $selected[] = $this->datatableMultiFilters[$multiFilterName]['values'][$key]['id'];
                    }

                    $this->datatableMultiFilters[$multiFilterName]['selected'] = $selected;
                }
            }
        }

        $this->relationService = m::app()->serviceManager->get('relationService');
    }

    public function actionOverview($app, $extraVars = [])
    {
        // load model for later use
        $model = new $this->dbObject();

        // actions
        $actions = [];

        $action = new \app\CAction();
        $action->icon = 'plus';
        $action->class = 'btn btn-outline-success';
        $action->type = 'link';
        $action->description = t('RELATION_ADD');
        $action->url = m::app()->getDocByName('relation-add')->getUrl();
        $actions[] = $action;

        $actionsInTable = $app->renderPartial(
            'button/button-group',
            [
                'align' => 'right',
                'buttons' => $actions,
                'showDescription' => null
            ],
            true
        );
        $actionsInTable = str_replace(["\r", "\n"], '', $actionsInTable);

        // load table
        $table = new \framework\CTable;
        $table->params = [
            'id' => 'table-' . $model->table,
            'class' => 'table',
            'actions' => addslashes($actionsInTable)
        ];

        // load datatable
        $datatable = new \app\CDatatable();
        $datatable->fields = $this->datatableFields;
        $datatable->ajaxSource = $this->filterUrl;
        $datatable->object = new $this->dbObject;
        $datatable->dom = $this->datatableDom;
        $datatable->length = !empty($this->datatableLength) ? $this->datatableLength : 10;

        //optional
        $datatable->orderDirection = !empty($this->datatableOrderDirection) ? $this->datatableOrderDirection : 'asc';
        $datatable->fieldsOrderable = !empty($this->datatableFieldsOrderable) ? $this->datatableFieldsOrderable : [];
        $datatable->fieldsSearchable = !empty($this->datatableFieldsSearchable) ? $this->datatableFieldsSearchable : [];
        $datatable->multiFilters = !empty($this->datatableMultiFilters) ? $this->datatableMultiFilters : [];

        // define view vars
        $extraVars['table'] = $table;
        $extraVars['datatable'] = $datatable->datatable();

        // render view
        $app->renderView(
            $this->viewFolder . '/overview',
            $extraVars
        );
    }
    
    public function actionEdit($app, $extraVars = [])
    {
        $relationId = h::getP(0, 'int', null);
        $relation = new \app\perree\relation\MRelation($relationId);

        $section = h::getV('section', 'any', null, 'POST', false);
        $extraVars['section'] = $section;

        // here we need to determine the active step to show
        if (!empty($section)) {
            switch ($section) {
                case 'contact':
                    $step = 2;
                    break;
                default:
                    $step = 1;
                    break;
            }
            $extraVars['step'] = $step;
        }

        parent::actionEdit($app, $extraVars);
    }

    public function actionSave($app, $alternativeUrl = null)
    {
        $relationPostParams = h::getV('Relation', 'array', [], 'POST', true);
        $contactPersonPostParams = h::getV('ContactPersons', 'array', [], 'POST', false);
        if (empty($relationPostParams)) {
            throw new \Error('Incorrect params for saving relation. Please try again');
        }

        // determine relation type
        $relationType = $relationPostParams['type'];

        // save relation
        $relation = $this->saveRelation($relationPostParams);

        // determine if postal address equals visiting address
        $postalAddressEqualsVisitingAddress = true;
        if (empty($relationPostParams['PostalAddress']['postalAddressEqualsVisitingAddress'])) {
            $postalAddressEqualsVisitingAddress = false;
        }

        // save relation type object
        $typeObjectParams = array_merge(
            (!empty($relationPostParams[ucfirst(strtolower($relationType))]) ? $relationPostParams[ucfirst(strtolower($relationType))] : []),
            ['postalAddressEqualsVisitingAddress' => $postalAddressEqualsVisitingAddress]
        );
        $this->saveTypeObjectForRelation($relation, $typeObjectParams);
        // save relation type object
        if (array_key_exists('General', $relationPostParams)) {
            $this->saveGeneralDataForRelation($relation, $relationPostParams['General']);
        }

        switch ($relationType) {
            case 'ADVISOR':
            case 'INDIVIDUAL':
                // save relation type object
                if (array_key_exists('Identification', $relationPostParams)) {
                    $this->saveIdentificationDataForRelation($relation, $relationPostParams['Identification']);
                }
                break;
            case 'ORGANIZATION':
                // save relation type object
                if (array_key_exists('ChamberOfCommerce', $relationPostParams)) {
                    $this->saveChamberOfCommerceDataForRelation($relation, $relationPostParams['ChamberOfCommerce']);
                }
                break;
            case 'CONTACT_PERSON':
            case 'COLLECTIVE':
                break;
        }

        // save relation category
        if (array_key_exists('Category', $relationPostParams)) {
            $this->saveRelationCategoryForRelation($relation, $relationPostParams['Category']);
        }

        // save phonenumbers
        $this->savePhoneNumbersForRelation($relation, (!empty($relationPostParams['PhoneNumbers']) ? $relationPostParams['PhoneNumbers'] : []));

        // save emailaddresses
        $this->saveEmailAddressesForRelation($relation, (!empty($relationPostParams['EmailAddresses']) ? $relationPostParams['EmailAddresses'] : []));

        // save visiting address
        if (
            array_key_exists('VisitingAddress', $relationPostParams) &&
            !empty($relationPostParams['VisitingAddress']['street']) &&
            !empty($relationPostParams['VisitingAddress']['number']) &&
            !empty($relationPostParams['VisitingAddress']['postalCode']) &&
            !empty($relationPostParams['VisitingAddress']['city']) &&
            !empty($relationPostParams['VisitingAddress']['countryId'])
        ) {
            $this->saveAddressForRelation($relation, $relationPostParams['VisitingAddress']);
        }

        // save potential postal address
        if (
            !$postalAddressEqualsVisitingAddress &&
            array_key_exists('PostalAddress', $relationPostParams) &&
            !empty($relationPostParams['PostalAddress']['street']) &&
            !empty($relationPostParams['PostalAddress']['number']) &&
            !empty($relationPostParams['PostalAddress']['postalCode']) &&
            !empty($relationPostParams['PostalAddress']['city']) &&
            !empty($relationPostParams['PostalAddress']['countryId'])
        ) {
            $this->saveAddressForRelation($relation, $relationPostParams['PostalAddress']);
        } else {
            // when postaladdress is incorrect in some way, we save postalAddressEqualsVisitingAddress as true so the correct visiting
            // address will be used for the postal address as well.
            $this->saveTypeObjectForRelation($relation, ['postalAddressEqualsVisitingAddress' => true]);
        }

        // in case postal address equals visiting address we check if we can delete current postal address from relation
        if ($postalAddressEqualsVisitingAddress && !empty($relation->id)) {
            $this->cleanupAddressForRelation($relation, 'POSTAL');
        }
        
        // save bank account
        if (array_key_exists('BankAccount', $relationPostParams)) {
            $this->saveBankAccountForRelation($relation, $relationPostParams['BankAccount']);
        }

        // save contact persons
        if (!empty($contactPersonPostParams)) {
            $this->saveContactPersonsForRelation($relation, $contactPersonPostParams);
        }

        // update index fields
        $relation->updateIndexFields();

        // handle file upload etc.
        $this->afterSave($relation, 'update');

        $app->redirectToDoc('relation-view', [$relation->id]);
    }

    public function afterSave($relation, $action)
    {
        try {
            $fileTypes = ['ID_FILE', 'CHAMBER_OF_COMMERCE_FILE'];

            foreach ($fileTypes as $fileType) {
                if (!empty($_FILES['Relation']['tmp_name'][h::toCamelCase(strtolower($fileType))])) {
                    $this->handleFileUpload(
                        $fileType,
                        $relation,
                        $_FILES['Relation']['tmp_name'][h::toCamelCase(strtolower($fileType))],
                        $_FILES['Relation']['name'][h::toCamelCase(strtolower($fileType))],
                        $_FILES['Relation']['size'][h::toCamelCase(strtolower($fileType))]
                    );
                }
            }
        } catch (\Exception $e) {
            h::dump($e);
        }
    }

    public function actionView($app, $extraVars = [])
    {
        $relationId = h::getP(0, 'int', -1, 'GET');
        $relation = new \app\perree\relation\MRelation($relationId);
        $relation->convertBackendValues();
        $category = $relation->getCategory();
        $notes = $relation->getNotes();

        $typeData = [];
        switch ($relation->type) {
            case 'INDIVIDUAL':
                $typeData = new \app\perree\relation\MIndividual($relationId);
                break;
            case 'ORGANIZATION':
                $typeData = new \app\perree\relation\MOrganization($relationId);
                break;
            case 'COLLECTIVE':
                $typeData = new \app\perree\relation\MCollective($relationId);
                break;
            case 'ADVISOR':
                $typeData = new \app\perree\relation\MAdvisor($relationId);
                break;
            case 'CONTACT_PERSON':
                $typeData = new \app\perree\relation\MContactPerson($relationId);
                break;
        }
        $typeData->convertBackendValues();

        $tableContactPerson = [];
        $datatableContactPerson = [];

        if($relation->type == 'INDIVIDUAL' || $relation->type == 'ORGANIZATION' || $relation->type == 'COLLECTIVE') {
            // get de data based on relation type
            $datatable = new \app\CDatatable();
            $this->getDatatableDataForContactPerson($datatable, $relation->type);

            // load table
            $table = new \framework\CTable;
            $table->params = [
                'id' => 'table-relation_contact_person',
                'class' => 'table'
            ];

            // load datatable
            $datatable->identifier = 'contact_person';
            $datatable->ajaxSource = $app->getDocByName('relation-contactperson-filter')->getUrl([$relation->id]);
            $datatable->object = new \app\perree\relation\MContactPerson();
            $datatable->dom = '<"table-controls" <"row" <"col-md-12 col-sm-12" <"d-flex flex-row flex-wrap justify-content-start" <"#dataTables_multiselect"><"#dataTables_actions"> > > > ><"table-responsive" t><"table-controls" lp>';
            $datatable->order = [['column' => 1, 'dir' => 'asc']];
            $datatable->orderDirection = 'asc';

            $tableContactPerson = $table;
            $datatableContactPerson = $datatable->datatable();
        }

        // DOCUMENT DATATABLE
        // load table
        $tableDocument = new \framework\CTable;
        $tableDocument->params = [
            'id' => 'table-relation_document',
            'class' => 'table'
        ];

        // load datatable
        $datatableDocument = new \app\CDatatable();
        $datatableDocument->identifier = "relation";
        $datatableDocument->ajaxSource = $app->getDocByName('document-filter')->getUrl(['relation', $relation->id]);
        $datatableDocument->object = new \app\perree\document\MDocument();
        $datatableDocument->dom = '<"table-controls" <"row" <"col-md-12 col-sm-12" <"d-flex flex-row flex-wrap justify-content-start" <"#dataTables_multiselect"><"#dataTables_actions"> > > > ><"table-responsive" t><"table-controls" lp>';
        $datatableDocument->fields = ['dateTimeCreated', 'name', 'actions'];
        $datatableDocument->order = [['column' => 1, 'dir' => 'desc']];
        $datatableDocument->fieldsOrderable = ['dateTimeCreated', 'name'];
        $datatableDocument->orderDirection = 'asc';

        // define document actions
        $documentActions = [];

        $action = new \app\CAction();
        $action->icon = 'plus';
        $action->class = 'btn btn-outline-success btn-sm';
        $action->type = 'button';
        $action->description = t('DOCUMENT_ADD');
        $action->params = [
            'data' => [
                'bs-toggle' => 'modal',
                'bs-target' => '#modal-document-add-edit',
                'confirm-url' => $app->getDocByName('document-save-ajax')->url,
                'entity-id' => $relation->id,
                'entity' => 'relation',
                'table' => 'table-relation_document'
            ]
        ];
        $documentActions[] = $action;

        // define view vars
        $extraVars['category'] = $category;
        $extraVars['typeData'] = $typeData;
        $extraVars['add_note_url'] = $app->getDocByName('relation-note-add')->url;
        $extraVars['delete_note_url'] = $app->getDocByName('relation-note-delete')->url;
        $extraVars['notes'] = $notes;
        $extraVars['tableContactPerson'] = $tableContactPerson;
        $extraVars['datatableContactPerson'] = $datatableContactPerson;
        $extraVars['tableDocument'] = $tableDocument;
        $extraVars['datatableDocument'] = $datatableDocument->datatable();
        $extraVars['documentActions'] = $documentActions;

        $vars = array_merge(
            ['relation' => $relation],
            $extraVars
        );

        // render
        $app->renderView(
            $this->viewFolder . '/view',
            $vars
        );
    }

    public function actionFilterContactPerson($app, $extraVars = [])
    {
        $relationId = h::getP(0, 'int', -1, 'GET');
        $relation = new \app\perree\relation\MRelation($relationId);
        $relation->convertBackendValues();
        $datatable = new \app\CDatatable;
        $this->getDatatableDataForContactPerson($datatable, $relation->type);

        $datatable->object = new \app\perree\relation\MContactPerson();
        $datatable->fieldsSearchable = !empty($datatableContactPersonData['fieldsSearchable']) ? $datatableContactPersonData['fieldsSearchable'] : [];
        $datatable->searchJoins = !empty($datatableContactPersonData['searchJoins']) ? $datatableContactPersonData['searchJoins'] : [];
        $extraWhereSQL = " contact_person.parentRelationId = " . $relation->id;

        $datatable->whereSQL = !empty($datatableContactPersonData['whereSQL']) ? $datatableContactPersonData['whereSQL'] . ' AND ' . $extraWhereSQL : 'WHERE ' . $extraWhereSQL;
        $datatable->multiFilters = !empty($datatableContactPersonData['multiFilters']) ? $datatableContactPersonData['multiFilters'] : [];

        // controleren of showStatusFilter is gezet
        $showStatusFilter = h::getV('showStatusFilter', 'any', null, 'GET', false);

        //standaard filteren op active contactpersonen
        $additionSQL = "(R.archived IS NULL OR R.archived = 0)";

        if (!empty($showStatusFilter)) {
            $additionSQL = "";

            switch ($showStatusFilter) {
                case 'all':
                    break;
                case 'archived':
                    $additionSQL = "R.archived = 1";
                    break;
                case 'active':
                    $additionSQL = "(R.archived IS NULL OR R.archived = 0)";
                    break;
            }
        }
        if (!empty($additionSQL)) {
            $datatable->whereSQL .= (!empty($datatable->whereSQL) ? ' AND ' : 'WHERE ') . $additionSQL;
        }

        $datatable->renderFilter();
    }

    public function actionArchiveAjax(\app\CApp $app, $extraVars = [])
    {
        \framework\m::app()->writeErrorsIn = 'json';

        $id = \framework\h::getP(0, "int", -1);

        $record = new $this->dbObject($id);

        $record->archive();

        if($record->table == 'relation'){
            $record->getContactPersons();

            if(!empty($record->getContactPersons())){
                foreach($record->getContactPersons() as $relationContactPerson){
                    $relationContactPerson->archive();
                }
            }
        }

        m::app()->renderJSON(
            array(
                "success" => 1,
                "message" => ""
            )
        );
    }

    public function actionRelationNoteAdd(\app\CApp $app)
    {
        $relationId = h::getV('relationId', 'int', 0, "POST", true);
        $noteDescription = h::getV('noteDescription', 'any', '', "POST", true);
        $noteId = h::getV('noteId', 'int', '', "POST", false);

        try {
            $note = !empty($noteId) ? new \app\perree\relation\MNote($noteId) : new \app\perree\relation\MNote();
            $note->relationId = $relationId;
            $note->description = $noteDescription;
            $note->save();

            $formatModified = "";
            if (!empty($note->dateTimeUpdated) && !empty($note->updatedById)) {
                $formatModified = 'Bewerkt op ' . date(
                        'd-m-Y',
                        strtotime($note->dateTimeUpdated)
                    ) . ' door Gebruiker ' . $note->getUserUpdated();
            }

            $resultObj = new \stdClass();
            $resultObj->success = 1;
            $resultObj->note = $note;
            $resultObj->formatCreated = 'Toegevoegd op ' . date(
                    'd-m-Y',
                    strtotime($note->dateTimeCreated)
                ) . ' door Gebruiker ' . $note->getUser();
            $resultObj->formatUpdated = $formatModified;
        } catch (Exception $e) {
            $resultObj = new \stdClass();
            $resultObj->success = 0;
            $resultObj->error = $e->getMessage();
        }

        $app->renderJSON($resultObj);
    }

    public function actionRelationNoteDelete(\app\CApp $app)
    {
        $relationId = h::getV('relationId', 'int', 0, "POST", true);
        $noteId = h::getV('noteId', 'int', 0, "POST", true);

        try {
            $note = new \app\perree\relation\MNote($noteId);

            if ($note->relationId == $relationId) {
                $note->delete();
            }

            $resultObj = new \stdClass();
            $resultObj->success = 1;
        } catch (Exception $e) {
            $resultObj = new \stdClass();
            $resultObj->success = 0;
            $resultObj->error = $e->getMessage();
        }

        $app->renderJSON($resultObj);
    }

    public function actionAddContactPerson(\app\CApp $app, $extraVars = [])
    {
        $parentRelationId = h::getP(0, 'int', -1, 'GET');
        $parentRelation = new \app\perree\relation\MRelation($parentRelationId);

        $extraVars['parentRelation'] = $parentRelation;

        $app->renderView(
            $this->viewFolder . '/contact-person/form',
            $extraVars
        );
    }

    public function actionEditContactPerson(\app\CApp $app)
    {
        $parentRelationId = h::getP(0, 'int', -1, 'GET');
        $contactPersonRelationId = h::getP(1, 'int', -1, 'GET');

        $parentRelation = new \app\perree\relation\MRelation($parentRelationId);
        $contactPersonRelation = new \app\perree\relation\MRelation($contactPersonRelationId);

        $parentRelation->convertBackendValues();
        $contactPersonRelation->convertBackendValues();

        $extraVars['parentRelation'] = $parentRelation;
        $extraVars['contactPersonRelation'] = $contactPersonRelation;

        $app->renderView(
            $this->viewFolder . '/contact-person/form',
            $extraVars
        );
    }
    
    public function actionSaveContactPerson(\app\CApp $app)
    {
        $parentRelationId = h::getP(0, 'int', -1, 'GET');
        $parentRelation = new \app\perree\relation\MRelation($parentRelationId);

        // params should always be just one for we are editting / adding one contact person and not multiple
        $contactPersonPostParams = h::getV('ContactPersons', 'array', [], 'POST', false);
        if (!empty($contactPersonPostParams)) {
            $this->saveContactPersonsForRelation($parentRelation, $contactPersonPostParams);
        }

        $app->redirectToDoc('relation-view', [$parentRelation->id]);
    }
    
    public function actionAutocomplete(\app\CApp $app)
    {
        $search = h::getV('search', 'any', null, 'POST');
        $result = ['success' => true, 'message' => ''];

        try {
            if (!empty($search)) {
                $relations = $this->relationService->findRelation($search);
                $result['relations'] = $relations;
            }
        } catch (\Error $error) {
            $result = ['success' => false, 'message' => $error->getTraceAsString()];
        }

        $app->renderJSON($result);
    }


    // additional functions to process relation data
    private function saveRelation(array $params): MRelation
    {
        $relation = new \app\perree\relation\MRelation();
        $relation->fillFromArray($params);
        $relation->convertPostValues();
        $relation->save();

        return $relation;
    }

    private function saveTypeObjectForRelation(\app\perree\relation\MRelation $relation, array $params)
    {
        // check whether relation type object already exists for relation
        $action = 'update';
        $typeObject = $relation->getTypeObject();
        if (empty($typeObject)) {
            $action = 'add';

            $typeObjectModelClass = $relation->getTypeObjectModelClass();
            $typeObject = new $typeObjectModelClass();
            $typeObject->id = $relation->id;
        }

        $typeObject->fillFromArray($params);
        $typeObject->convertPostValues();
        $typeObject->$action();
    }

    private function savePhoneNumbersForRelation(\app\perree\relation\MRelation $relation, array $params)
    {
        // first we check current phonenumbers
        $currentPhoneNumbers = $relation->getPhoneNumbers();
        $newPhoneNumbers = [];

        if (!empty($params)) {
            foreach ($params as $phoneNumberParams) {
                if (empty($phoneNumberParams['number'])) {
                    continue;
                }

                $phoneNumber = new \app\perree\relation\MPhoneNumber();
                if (!empty($relation->getPhoneNumber($phoneNumberParams['number']))) {
                    $phoneNumber = $relation->getPhoneNumber($phoneNumberParams['number']);
                }
                $phoneNumber->fillFromArray($phoneNumberParams);
                $phoneNumber->relationId = $relation->id;
                $phoneNumber->save();

                $newPhoneNumbers[$phoneNumber->id] = $phoneNumber;
            }
        }

        // match current phonenumbers to new phonenumbers
        $phoneNumberIdsToDelete = array_diff_key($currentPhoneNumbers, $newPhoneNumbers);
        if (!empty($phoneNumberIdsToDelete))
        {
            foreach ($phoneNumberIdsToDelete as $id => $phoneNumber)
            {
                $phoneNumber->delete();
            }
        }
    }

    private function saveEmailAddressesForRelation(\app\perree\relation\MRelation $relation, array $params)
    {

        // first we check current phonenumbers
        $currentEmailAddresses = $relation->getEmailAddresses();
        $newEmailAddresses = [];

        if (!empty($params)) {
            foreach ($params as $emailAddressParams) {
                if (empty($emailAddressParams['address'])) {
                    continue;
                }

                $emailAddress = new \app\perree\relation\MEmailAddress();
                if (!empty($relation->getEmailAddress($emailAddressParams['address']))) {
                    $emailAddress = $relation->getEmailAddress($emailAddressParams['address']);
                }
                $emailAddress->fillFromArray($emailAddressParams);
                $emailAddress->relationId = $relation->id;
                $emailAddress->save();

                $newEmailAddresses[$emailAddress->id] = $emailAddress;
            }
        }

        // match current phonenumbers to new phonenumbers
        $emailAddressIdsToDelete = array_diff_key($currentEmailAddresses, $newEmailAddresses);
        if (!empty($emailAddressIdsToDelete))
        {
            foreach ($emailAddressIdsToDelete as $id => $emailAddress)
            {
                $emailAddress->delete();
            }
        }
    }

    private function saveAddressForRelation(\app\perree\relation\MRelation $relation, array $params)
    {
        if (!empty($params)) {
            $address = new \app\perree\relation\MAddress();
            $address->fillFromArray($params);
            $address->relationId = $relation->id;
            $address->save();
        }
    }

    private function saveBankAccountForRelation(\app\perree\relation\MRelation $relation, array $params)
    {
        if (!empty($params) && !empty($params['iban']) && !empty($params['ascription'])) {
            $bankAccount = new \app\perree\bankaccount\MBankAccount();
            $bankAccount->fillFromArray($params);
            if (
                empty($relation->getPrimaryBankAccount()) ||
                $relation->getPrimaryBankAccount()->id === $bankAccount->id
            ) {
                $bankAccount->isPrimary = true;
            }
            $bankAccount->save();

            $relationBankAccount = $this->relationService->getRelationBankAccountModelForRelationAndBankAccount($relation->id, $bankAccount->id);
            if (empty($relationBankAccount)) {
                $relationBankAccount = new \app\perree\relation\MRelationBankAccount();
                $relationBankAccount->relationId = $relation->id;
                $relationBankAccount->bankAccountId = $bankAccount->id;
                $relationBankAccount->add();
            }
        }
    }

    private function saveGeneralDataForRelation(\app\perree\relation\MRelation $relation, array $params)
    {
        // when saving this information relation type object should already exist
        $typeObject = $relation->getTypeObject();
        if (empty($typeObject)) {
            throw new \Error('Something went wrong saving relation, please try again');
        }

        $typeObject->fillFromArray($params);
        $typeObject->convertPostValues();
        $typeObject->update();

        if (!empty($params['advisorRelationId'])) {
            $this->saveAdvisorForRelation($relation, $params['advisorRelationId']);
        }
    }

    private function saveIdentificationDataForRelation(\app\perree\relation\MRelation $relation, array $params)
    {
        // when saving this information relation type object should already exist
        $typeObject = $relation->getTypeObject();
        if (empty($typeObject)) {
            throw new \Error('Something went wrong saving relation, please try again');
        }

        $typeObject->fillFromArray($params);
        $typeObject->convertPostValues();
        $typeObject->update();
    }

    private function saveRelationCategoryForRelation(\app\perree\relation\MRelation $relation, array $params)
    {
        $relationCategory = $relation->getRelationCategory();
        if (empty($relationCategory)) {
            $relationCategory = new \app\perree\relation\MRelationCategory();
            $relationCategory->relationId = $relation->id;
        }
        $relationCategory->categoryId = $params['categoryId'];
        $relationCategory->save();
    }

    private function saveAdvisorForRelation(\app\perree\relation\MRelation $relation, int $advisorRelationId)
    {
        $advisorRelation = $relation->getAdvisor($advisorRelationId);
        if (empty($advisorRelation)) {
            $relationAdvisor = new \app\perree\relation\MRelationAdvisor();
            $relationAdvisor->relationId = $relation->id;
            $relationAdvisor->advisorRelationId = $advisorRelationId;
            $relationAdvisor->add();
        }
    }

    private function saveChamberOfCommerceDataForRelation(\app\perree\relation\MRelation $relation, array $params)
    {
        // when saving this information relation type object should already exist
        $typeObject = $relation->getTypeObject();
        if (empty($typeObject)) {
            throw new \Error('Something went wrong saving relation, please try again');
        }

        $typeObject->fillFromArray($params);
        $typeObject->convertPostValues();
        $typeObject->update();
    }

    private function saveContactPersonsForRelation(\app\perree\relation\MRelation $relation, array $params)
    {
        foreach ($params as $idx => $contactPersonParams) {

            if (!empty($contactPersonParams['id'])) {
                $contactPersonRelation = new \app\perree\relation\MRelation($contactPersonParams['id']);
            } else {
                $contactPersonRelation = new \app\perree\relation\MRelation();
                $contactPersonRelation->type = 'CONTACT_PERSON';

            }
            $contactPersonRelation->fillFromArray($contactPersonParams);
            $contactPersonRelation->save();

            // add parentRelationId to params for correct handling of data
            $contactPersonParams['parentRelationId'] = $relation->id;
            $contactPersonParams['language'] = $relation->typeObject->language;
            $this->saveTypeObjectForRelation($contactPersonRelation, $contactPersonParams);

            // save phonenumbers
            $this->savePhoneNumbersForRelation($contactPersonRelation, (!empty($contactPersonParams['PhoneNumbers']) ? $contactPersonParams['PhoneNumbers'] : []));

            // save emailaddresses
            $this->saveEmailAddressesForRelation($contactPersonRelation, (!empty($contactPersonParams['EmailAddresses']) ? $contactPersonParams['EmailAddresses'] : []));

            // handle file upload for contact person
            if (!empty($_FILES['ContactPersons']['tmp_name'][$idx]['idFile'])) {
                $this->handleFileUpload(
                    'ID_FILE',
                    $contactPersonRelation,
                    $_FILES['ContactPersons']['tmp_name'][$idx]['idFile'],
                    $_FILES['ContactPersons']['name'][$idx]['idFile'],
                    $_FILES['ContactPersons']['size'][$idx]['idFile']
                );
            }

            $contactPersonRelation->updateIndexFields();
        }
    }

    private function handleFileUpload(
        string $fileType,
        \app\perree\relation\MRelation $relation,
        string $file,
        string $name,
        int $fileSize
    )
    {
        $oldFile = null;

        $property = h::toCamelCase(strtolower($fileType));
        $prefix = t($fileType);
        $mediaIdProperty = h::toCamelCase(strtolower(str_replace('_FILE', '', $fileType))) . 'MediaId';

        // controleren of er een inschrijfformulier bestaat voor registratie
        if (!empty($relation->typeObject->$property)) {
            // opslaan oude inschrijfformulier
            $oldFile = $relation->typeObject->$property;
        }

        // Process subscription form upload
        $extension = pathinfo($name, PATHINFO_EXTENSION);

        $filenamePrefix = $prefix . ' - ' . $relation->typeObject->name;
        $filename = $filenamePrefix . '.' . $extension;

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file);

        $media = new \app\perree\media\MMedia;
        $media->filename = $filename;
        $media->fileSize = $fileSize;
        $media->fileMimeType = $mimeType;
        $media->createdById = m::app()->user->id;
        $media->type = $property;
        $media->dateTimeCreated = date('Y-m-d H:i:s');
        $media->add();

        if (!move_uploaded_file($file, $media->localLocation)) {
            $media->delete();
            throw new Exception('Unable to write to target folder.');
        }

        $relation->typeObject->$mediaIdProperty = $media->id;
        $relation->typeObject->update();

        // Opschonen oude inschrijfformulier
        if (!empty($oldFile)) {
            $oldFile->delete();
        }
    }

    private function getDatatableDataForContactPerson(&$datatable, $relationType){
        //Datatabel variabele contactPersonen

        $datatable->joinSQL = 'INNER JOIN relation R ON R.id = contact_person.id';

        switch ($relationType) {
            case 'INDIVIDUAL':
                // Datatable instellingen
                $datatable->fields = ['nameSortable', 'receivesCorrespondence', 'isProxy', 'actions'];
                $datatable->orderJoins = [
                    'nameSortable' => [
                        'sql' => 'INNER JOIN relation R ON R.id = contact_person.id',
                        'queryField' => 'R.nameSortable'
                    ],
                ];
                $datatable->fieldsOrderable = ['nameSortable', 'isProxy'];

                break;
            case 'ORGANIZATION':
                // Datatable instellingen
                $datatable->fields = ['nameSortable', 'receivesCorrespondence', 'isManager', 'actions'];
                $datatable->orderJoins = [
                    'nameSortable' => [
                        'sql' => 'INNER JOIN relation R ON R.id = contact_person.id',
                        'queryField' => 'R.nameSortable'
                    ],
                ];
                $datatable->fieldsOrderable = ['nameSortable', 'isManager'];
                break;
            case 'COLLECTIVE':
                $datatable->fields = ['nameSortable', 'receivesCorrespondence', 'isParticipant', 'isProxy', 'actions'];
                $datatable->orderJoins = [
                    'nameSortable' => [
                        'sql' => 'INNER JOIN relation R ON R.id = contact_person.id',
                        'queryField' => 'R.nameSortable'
                    ],
                ];
                $datatable->fieldsOrderable = ['nameSortable', 'isParticipant'];
                break;
        }
    }

    private function cleanupAddressForRelation(\app\perree\relation\MRelation $relation, $addressType) {
        $address = $this->relationService->getAddressForRelation($relation->id, $addressType);
        if (!empty($address)) {
            $address->delete();
        }
    }
}