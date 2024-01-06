<?php

namespace app\perree\relation;

use app\CRUDController;
use app\h;

class RelationCategoryController extends CRUDController {
    protected $dbObject = '\app\perree\relation\MCategory';
    protected $viewFolder = 'relation-category';
    protected $overviewUrl = 'relation-category';
    protected $filterUrl = 'relation-category/filter';
    protected $datatableFields = ['name', 'total'];

    /** ORDER */
    protected $datatableFieldsOrder = [['column' => 0, 'dir' => 'asc']]; // ID
    protected $datatableFieldsOrderable = ['name'];
    protected $datatableOrderDirection = 'asc';
    protected $datatableWhereSQL;

    /** SEARCH */
    protected $datatableFieldsSearchable = ['name'];

    public function actionOverview($app, $extraVars = array())
    {
        // load model for later use
        $model = new $this->dbObject();

        // load table
        $table = new \framework\CTable;
        $table->params = ['id' => 'table-' . $model->table, 'class' => 'table'];

        // load datatable
        $datatable = new \app\CDatatable();
        $datatable->fields = $this->datatableFields;
        $datatable->ajaxSource = $this->filterUrl;
        $datatable->object = new $this->dbObject;
        $datatable->dom = '<"table-controls" <"row" <"col-md-12 col-sm-12" <"d-flex flex-row flex-wrap justify-content-start" <"#dataTables_multiselect"><"#dataTables_actions"> > > > ><"table-responsive" t><"table-controls" lp>';

        //optional
        $datatable->orderDirection = !empty($this->datatableOrderDirection) ? $this->datatableOrderDirection : 'asc';
        $datatable->fieldsOrderable = !empty($this->datatableFieldsOrderable) ? $this->datatableFieldsOrderable : array();
        $datatable->fieldsSearchable = !empty($this->datatableFieldsSearchable) ? $this->datatableFieldsSearchable : array();
        $datatable->multiFilters = !empty($this->datatableMultiFilters) ? $this->datatableMultiFilters : array();

        // define view vars
        $extraVars['table'] = $table;
        $extraVars['datatable'] = $datatable->datatable();

        // render view
        $app->renderView(
            $this->viewFolder . '/overview',
            $extraVars
        );
    }
}