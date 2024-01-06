<?php

namespace app\perree\fund;

use app\CRUDController;
use app\h;
use app\m;

class CuratorController extends CRUDController
{
    protected $dbObject = '\app\perree\fund\MCurator';
    protected $viewFolder = 'fund/curator';
    protected $overviewUrl = 'curators';
    protected $filterUrl = 'curator/filter';
    protected $datatableFields = ['name'];

    /** ORDER */
    protected $datatableFieldsOrder = [['column' => 0, 'dir' => 'asc']]; // ID
    protected $datatableFieldsOrderable = ['name'];
    protected $datatableOrderDirection = 'asc';

    protected $datatableWhereSQL = "WHERE (curator.archived IS NULL OR curator.archived = 0)";

    /** SEARCH */
    protected $datatableFieldsSearchable = [
        'curator.name',
    ];

    /** SERVICES */
    /** @var \app\perree\fund\CCuratorService $curatorService; */
    private $curatorService;

    public function __construct() {
        $this->curatorService = m::app()->serviceManager->get('curatorService');
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
        $action->description = t('CURATOR_ADD');
        $action->url = m::app()->getDocByName('curator-add')->getUrl();
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
                $curators = $this->curatorService->findCurators($search);
                $result['curators'] = $curators;
            }
        } catch (\Error $error) {
            $result = ['success' => false, 'message' => $error->getTraceAsString()];
        }

        $app->renderJSON($result);
    }

    public function actionView($app, $extraVars = [])
    {
        $curatorId = h::getP(0, 'int', -1, 'GET');
        $curator = new \app\perree\fund\MCurator($curatorId);
        $curator->convertBackendValues();

        // DOCUMENT DATATABLE
        // load table
        $tableFund = new \framework\CTable;
        $tableFund->params = [
            'id' => 'table-fund',
            'class' => 'table'
        ];

        // load datatable
        $filterUrl = $app->getDocByName('fund-filter')->url . '?identifierTable=curator&identifierId='.$curator->id;

        $datatableFund = new \app\CDatatable();
        $datatableFund->identifier = "fund";
        $datatableFund->ajaxSource = $filterUrl;
        $datatableFund->object = new \app\perree\fund\MFund();
        $datatableFund->dom = '<"table-controls" <"row" <"col-md-12 col-sm-12" <"d-flex flex-row flex-wrap justify-content-start" <"#dataTables_multiselect"><"#dataTables_actions"> > > > ><"table-responsive" t><"table-controls" lp>';
        $datatableFund->fields = ['name', 'bondAmount', 'participants', 'bondVolume', 'status', 'actions'];
        $datatableFund->order = [['column' => 1, 'dir' => 'asc']];
        $datatableFund->fieldsOrderable = ['name', 'bondAmount', 'bondVolume', 'status'];
        $datatableFund->orderDirection = 'asc';
        $datatableFund->whereSQL = "WHERE (fund.curatorId = '.$curator->id.')";


        // define document actions
        $fundActions = [];

        // define view vars
        $extraVars['tableFund'] = $tableFund;
        $extraVars['datatableFund'] = $datatableFund->datatable();
        $extraVars['fundActions'] = $fundActions;

        $vars = array_merge(
            ['curator' => $curator],
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
        $curatorPostParams = h::getV('Curator', 'array', [], 'POST', true);
        if (empty($curatorPostParams)) {
            throw new \Error('Incorrect params for saving curator. Please try again');
        }

        $curator = new \app\perree\fund\MCurator();
        $curator->fillFromPost('Curator');
        $curator->save();

        // handle file upload etc.
        $this->afterSave($curator, 'update');

        $app->redirectToDoc('curator-view', [$curator->id]);
    }

}