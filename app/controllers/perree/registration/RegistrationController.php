<?php

namespace app\perree\registration;

use app\h;
use app\m;
use framework\CTable;

class RegistrationController extends \app\CRUDController
{
    protected $dbObject = '\app\perree\registration\MRegistration';
    protected $datatableFieldsFund = ['name', 'fund', 'relation', 'bondAmount', 'bondValue', 'status']; // aantal deelnemers moet nog worden toegevoegd na bondAmount

    /** ORDER */

    /** SEARCH */

    /** FILTER */

    /** SERVICES */

    public function __construct() {
        // get possible selected multiFilters
        /*$multiFilterKeys = array_keys($this->datatableMultiFilters);
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
        }*/
    }

    public function actionOverviewFund($app, $extraVars = array())
    {
        $fundId = h::getP(0, 'int', null);
        $fund = new \app\perree\fund\MFund($fundId);
        $registration = new $this->dbObject();

        $action = new \app\CAction();
        $action->icon = 'plus';
        $action->class = 'btn btn-outline-success';
        $action->type = 'button';
        $action->description = t('REGISTRATION_ADD');
        $action->params = [
            'data' => [
                'bs-toggle' => 'modal',
                'bs-target' => '#modal-registration_add_edit'
            ]
        ];
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

        $table = new CTable;
        $table->params = [
            'id' => 'table-fund-' . $registration->table,
            'class' => 'table',
            'actions' => addslashes($actionsInTable)
        ];

        // load datatable
        $datatable = new \app\CDatatable();
        $datatable->fields = $this->datatableFieldsFund;
        $datatable->ajaxSource = $app->getDocByName('registration-fund-filter')->getUrl([$fund->id]);
        $datatable->object = $registration;
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

        $vars = array_merge(
            array(
                'fund' => $fund,
                'table' => $table,
            ),
            $extraVars
        );

        // render
        m::app()->renderView(
            'fund/registration/overview',
            $vars
        );
    }
    public function actionFilterFund()
    {
        $fundId = h::getP(0, 'int', null);
        $fund = new \app\perree\fund\MFund($fundId);

        $datatable = new \app\CDatatable;
        $datatable->fields = $this->datatableFieldsFund;
        $datatable->object = new $this->dbObject();

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

        $datatable->renderFilter();
    }
}