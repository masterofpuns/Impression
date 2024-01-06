<?php

namespace app\perree\document;

/**
 * MDocumentCategory
 *
 * @property $table string
 * @property $name string
 */

class MDocumentCategory extends \app\CDBRecord {

    protected $table = 'document_category';
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
        ]
    ];

}