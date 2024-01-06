<?php

namespace app\perree\document;

/**
 * MRelationDocument
 *
 * @property $table string
 * @property $relationId int
 * @property $documentId int
 */

class MRelationDocument extends \app\CDBRecord {

    protected $table = 'relation_document';
    protected $relationId;
    protected $documentId;

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
        'documentId' => [
            'description' => 'DOCUMENT_ID',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'DOCUMENT_ID',
                    'placeholder' => 'DOCUMENT_ID'
                ],
                'order' => 2
            ],
            'backend' => [
                'type' => 'int',
                'typeParams' => []
            ]
        ]
    ];

}