<?php

namespace app\perree\participation;

use app\h;
use app\m;
use framework\CTable;

class ParticipationController extends \app\CRUDController
{
    protected $dbObject = '\app\perree\participation\MParticipation';
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
        $participation = new $this->dbObject();

        $table = new CTable;
        $table->params = [
            'id' => 'table-fund-' . $participation->table,
            'class' => ''
        ];

        // load datatable
        $datatable = new \app\CDatatable();
        $datatable->fields = $this->datatableFieldsFund;
        $datatable->ajaxSource = $app->getDocByName('participation-fund-filter')->getUrl([$fund->id]);
        $datatable->object = $participation;
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
            'fund/participation/overview',
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