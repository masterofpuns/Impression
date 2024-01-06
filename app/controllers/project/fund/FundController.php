<?php

namespace app\perree\fund;

use app\CRUDController;
use app\h;
use app\m;

class FundController extends CRUDController
{
    protected $dbObject = '\app\perree\fund\MFund';
    protected $viewFolder = 'fund';
    protected $overviewUrl = 'fund';
    protected $filterUrl = 'fund/filter';
    protected $datatableFields = ['name', 'bondAmount', 'participants', 'bondValue', 'status']; // aantal deelnemers moet nog worden toegevoegd na bondAmount

    /** ORDER */
    protected $datatableFieldsOrder = [['column' => 0, 'dir' => 'asc']]; // ID
    protected $datatableFieldsOrderable = ['name', 'bondAmount', 'bondValue', 'status'];
    protected $datatableOrderDirection = 'asc';

    protected $datatableWhereSQL = "WHERE (fund.archived IS NULL OR fund.archived = 0)";

    /** SEARCH */
    protected $datatableFieldsSearchable = [
        'fund.name',
    ];

    /** FILTER */
    protected $datatableMultiFilters = [
        'status' => [
            'values' => [
                [
                    'id' => 'FOUNDING',
                    'description' => 'FOUNDING'
                ],
                [
                    'id' => 'ACTIVE',
                    'description' => 'FUND_ACTIVE'
                ],
                [
                    'id' => 'SOLD',
                    'description' => 'SOLD'
                ],
            ],
            'queryField' => 'fund.status',
            'description' => 'STATUS'
        ],
    ];

    /** SERVICES */
    /** @var \app\perree\fund\CFundService $fundService */
    private $fundService;

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

        $this->fundService = m::app()->serviceManager->get('fundService');
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
        $action->description = t('FUND_ADD');
        $action->url = m::app()->getDocByName('fund-add')->getUrl();
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

    public function actionView($app, $extraVars = [])
    {
        $fundId = h::getP(0, 'int', -1, 'GET');
        $fund = new \app\perree\fund\MFund($fundId);
        $fund->convertBackendValues();

        // DOCUMENT DATATABLE
        // load table
        $tableDocument = new \framework\CTable;
        $tableDocument->params = [
            'id' => 'table-fund_document',
            'class' => 'table'
        ];

        // load datatable
        $datatableDocument = new \app\CDatatable();
        $datatableDocument->identifier = "fund";
        $datatableDocument->ajaxSource = $app->getDocByName('document-filter')->getUrl(['fund', $fund->id]);
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
                'entity-id' => $fund->id,
                'entity' => 'fund',
                'table' => 'table-fund_document'
            ]
        ];
        $documentActions[] = $action;

        // define view vars
        $extraVars['tableDocument'] = $tableDocument;
        $extraVars['datatableDocument'] = $datatableDocument->datatable();
        $extraVars['documentActions'] = $documentActions;

        $vars = array_merge(
            ['fund' => $fund],
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
        $fundPostParams = h::getV('Fund', 'array', [], 'POST', true);
        if (empty($fundPostParams)) {
            throw new \Error('Incorrect params for saving fund. Please try again');
        }
        
        $fund = new \app\perree\fund\MFund();
        $fund->fillFromPost('Fund');
        if (empty($fund->status)) {
            $fund->status = 'FOUNDING';
        }
        $fund->save();

        // save bank account
        if (array_key_exists('BankAccount', $fundPostParams)) {
            $this->saveBankAccountForFund($fund, $fundPostParams['BankAccount']);
        }

        // handle file upload etc.
        $this->afterSave($fund, 'update');

        $app->redirectToDoc('fund-view', [$fund->id]);
    }

    private function saveBankAccountForFund(\app\perree\fund\MFund $fund, array $params)
    {
        if (!empty($params) && !empty($params['iban']) && !empty($params['ascription'])) {
            $bankAccount = new \app\perree\bankaccount\MBankAccount();
            $bankAccount->fillFromArray($params);
            $bankAccount->isPrimary = 1;
            $bankAccount->save();

            // determine link with fund
            $fundBankAccount = $this->fundService->getFundBankAccountModel($fund->id, $bankAccount->id);
            if (empty($fundBankAccount)) {
                $fundBankAccount = new \app\perree\fund\MFundBankAccount();
                $fundBankAccount->fundId = $fund->id;
                $fundBankAccount->bankAccountId= $bankAccount->id;
                $fundBankAccount->add();
            }
        }
    }

    public function actionFilter()
    {
        if (empty($this->datatableFields)) {
            throw new \Error('No Datatable fields identified');
        }

        if (empty($this->dbObject)) {
            throw new \Error('No object defined for use in Datatables');
        }
        $datatable = new \app\CDatatable;

        // required
        $datatable->fields = $this->datatableFields;
        $datatable->object = new $this->dbObject;

        //optional
        $datatable->multiFilters = !empty($this->datatableMultiFilters) ? $this->datatableMultiFilters : array();
        $datatable->fieldsSearchable = !empty($this->datatableFieldsSearchable) ? $this->datatableFieldsSearchable : array();
        $datatable->searchJoins = !empty($this->datatableSearchJoins) ? $this->datatableSearchJoins : array();
        $datatable->orderJoins = !empty($this->datatableOrderJoins) ? $this->datatableOrderJoins : array();
        $datatable->whereSQL = !empty($this->datatableWhereSQL) ? $this->datatableWhereSQL : '';
        $datatable->joinSQL = !empty($this->datatableJoinSQL) ? $this->datatableJoinSQL : '';
        $datatable->resultCallback = !empty($this->datatableResultCallback) ? $this->datatableResultCallback : '';
        $datatable->groupByColumn = !empty($this->datatableGroupByColumn) ? $this->datatableGroupByColumn : null;
        $datatable->identifier = !empty($this->datatableIdentifier) ? $this->datatableIdentifier : 'id';

        // controleren of showStatusFilter is gezet
        $showStatusFilter = h::getV('showStatusFilter', 'any', null, 'GET', false);
        if (!empty($showStatusFilter)) {
            if (property_exists($datatable->object, 'archived')) {
                $additionSQL = '';
                switch ($showStatusFilter) {
                    case 'all':
                        break;
                    case 'archived':
                        $additionSQL = $datatable->object->table . '.archived = 1';
                        break;
                    case 'active':
                        $additionSQL = '(' . $datatable->object->table . '.archived IS NULL OR ' . $datatable->object->table . '.archived = 0)';
                        break;
                }
            } else {
                if (property_exists($datatable->object, 'status')) {
                    $additionSQL = '';
                    switch ($showStatusFilter) {
                        case 'all':
                            break;
                        case 'archived':
                            $additionSQL = $datatable->object->table . ".status = 'archived'";
                            break;
                        case 'active':
                            $additionSQL = $datatable->object->table . ".status <> 'archived'";
                            break;
                    }
                }
            }

            if (!empty($additionSQL)) {
                $datatable->whereSQL .= (!empty($datatable->whereSQL) ? ' AND ' : 'WHERE ') . $additionSQL;
            }
        }

        // controleren of showStatusFilter is gezet
        $identifierTableFromUrl = h::getV('identifierTable', 'any', null, 'GET', false);
        $identifierIdFromUrl = h::getV('identifierId', 'any', null, 'GET', false);
        if (!empty($identifierTableFromUrl) && !empty($identifierIdFromUrl)) {
            switch ($identifierTableFromUrl) {
                case 'curator':
                    if(!empty($datatable->whereSQL)){
                        $datatable->whereSQL .= " AND (fund.curatorId = ".$identifierIdFromUrl.")";
                    } else {
                        $datatable->whereSQL = "WHERE (fund.curatorId = ".$identifierIdFromUrl.")";
                    }
                    break;
            }
        }
        $datatable->renderFilter();
    }
}