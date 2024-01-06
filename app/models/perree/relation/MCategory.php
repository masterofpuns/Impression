<?php

namespace app\perree\relation;

use \app\m;

/**
 * MCategory
 *
 * @property $table string
 * @property $name string
 */

class MCategory extends \app\CDBRecord {

    protected $table = 'category';
    protected $name;

    protected $fields = [
        'name' => [
            'description' => 'NAME',
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

        'total' => ['description' => 'TOTAL']
    ];

    // SERVICES
    /** @var \app\perree\relation\CRelationService */
    private $relationService;

    // ADDITIONAL VARS
    private $totalRelationCount;

    public function __construct($db_id = null)
    {
        parent::__construct($db_id);

        $this->relationService = m::app()->serviceManager->get('relationService');
    }

    public function getDatatableRow() {
        $this->getTotalRelationCount();

        $totalPerCategoryLink = m::app()->renderPartial(
            'link/link',
            [
                'description' => $this->totalRelationCount,
                'url' => m::app()->getDocByName('relation-overview')->url . '?categoryFilter=' . $this->name
            ],
            true
        );

        return [
            t($this->name),
            $totalPerCategoryLink
        ];
    }

    public function getTotalRelationCount() {
        if (empty($this->totalRelationCount)) {
            $this->totalRelationCount = $this->relationService->getTotalRelationCountForCategory($this->id);
        }
        return $this->totalRelationCount;
    }
}