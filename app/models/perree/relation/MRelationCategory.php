<?php

namespace app\perree\relation;

/**
 * MRelationCategory
 *
 * @property $table string
 * @property $relationId int
 * @property $categoryId int
 */

class MRelationCategory extends \app\CDBRecord {

    protected $table = 'relation_category';
    protected $relationId;
    protected $categoryId;

    protected $fields = [
        'relationId' => [
            'description' => 'RELATION_ID',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'RELATION_ID',
                    'placeholder' => 'RELATION_ID'
                ],
                'order' => 1
            ],
            'backend' => [
                'type' => 'int',
                'typeParams' => []
            ]
        ],
        'categoryId' => [
            'description' => 'CATEGORY_ID',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'CATEGORY_ID',
                    'placeholder' => 'CATEGORY_ID'
                ],
                'order' => 2
            ],
            'backend' => [
                'type' => 'int',
                'typeParams' => []
            ]
        ],
    ];

}