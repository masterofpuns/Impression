<?php

namespace app\perree\fund;

use app\CRUDController;
use app\h;
use app\m;

class FoundationController extends CRUDController
{
    protected $dbObject = '\app\perree\fund\MFoundation';
    protected $viewFolder = 'fund/foundation';
    protected $overviewUrl = 'foundations';
    protected $filterUrl = 'foundation/filter';
    protected $datatableFields = ['name'];

    /** ORDER */
    protected $datatableFieldsOrder = [['column' => 0, 'dir' => 'asc']]; // ID
    protected $datatableFieldsOrderable = ['name'];
    protected $datatableOrderDirection = 'asc';

    protected $datatableWhereSQL = "WHERE (foundation.archived IS NULL OR foundation.archived = 0)";

    /** SEARCH */
    protected $datatableFieldsSearchable = [
        'foundation.name',
    ];

    /** SERVICES */
    /** @var \app\perree\fund\CFoundationService $foundationService; */
    private $foundationService;

    public function __construct() {
        $this->foundationService = m::app()->serviceManager->get('foundationService');
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
        $action->description = t('FOUNDATION_ADD');
        $action->url = m::app()->getDocByName('foundation-add')->getUrl();
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
        $datatable->actionTitle = $this->actionsTitle;
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

    public function actionAutocomplete($app, $extraVars = []) {
        $search = h::getV('search', 'any', null, 'POST');
        $result = ['success' => true, 'message' => ''];

        try {
            if (!empty($search)) {
                $foundations = $this->foundationService->findFoundations($search);
                $result['foundations'] = $foundations;
            }
        } catch (\Error $error) {
            $result = ['success' => false, 'message' => $error->getTraceAsString()];
        }

        $app->renderJSON($result);
    }

    public function actionView($app, $extraVars = [])
    {
        $foundationId = h::getP(0, 'int', -1, 'GET');
        $foundation = new \app\perree\fund\MFoundation($foundationId);
        $foundation->convertBackendValues();

        // DOCUMENT DATATABLE
        // load table
        $tableDocument = new \framework\CTable;
        $tableDocument->params = [
            'id' => 'table-foundation_document',
            'class' => 'table'
        ];

        // load datatable
        $datatableDocument = new \app\CDatatable();
        $datatableDocument->identifier = "foundation";
        $datatableDocument->ajaxSource = $app->getDocByName('document-filter')->getUrl(['foundation', $foundation->id]);
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
                'entity-id' => $foundation->id,
                'entity' => 'foundation',
                'table' => 'table-foundation_document'
            ]
        ];
        $documentActions[] = $action;

        // define view vars
        $extraVars['tableDocument'] = $tableDocument;
        $extraVars['datatableDocument'] = $datatableDocument->datatable();
        $extraVars['documentActions'] = $documentActions;

        $vars = array_merge(
            ['foundation' => $foundation],
            $extraVars
        );

        // render
        $app->renderView(
            $this->viewFolder . '/view',
            $vars
        );
    }

    public function actionSave($app, $alternativeUrl = null)
    {
        $foundationPostParams = h::getV('Foundation', 'array', [], 'POST', true);
        if (empty($foundationPostParams)) {
            throw new \Error('Incorrect params for saving foundation. Please try again');
        }

        $foundation = new \app\perree\fund\MFoundation();
        $foundation->fillFromPost('Foundation');
        $foundation->save();

        // save bank account
        if (array_key_exists('BankAccount', $foundationPostParams)) {
            $this->saveBankAccountForFoundation($foundation, $foundationPostParams['BankAccount']);
        }

        // handle file upload etc.
        $this->afterSave($foundation, 'update');

        $app->redirectToDoc('foundation-view', [$foundation->id]);
    }

    private function saveBankAccountForFoundation(\app\perree\fund\MFoundation $foundation, array $params)
    {
        if (!empty($params) && !empty($params['iban']) && !empty($params['ascription'])) {
            $bankAccount = new \app\perree\bankaccount\MBankAccount();
            $bankAccount->fillFromArray($params);
            $bankAccount->isPrimary = 1;
            $bankAccount->save();

            // determine link with fund
            $foundationBankAccount = $this->foundationService->getFoundationBankAccountModel($foundation->id, $bankAccount->id);
            if (empty($foundationBankAccount)) {
                $foundationBankAccount = new \app\perree\fund\MFoundationBankAccount();
                $foundationBankAccount->foundationId = $foundation->id;
                $foundationBankAccount->bankAccountId = $bankAccount->id;
                $foundationBankAccount->add();
            }
        }
    }
}