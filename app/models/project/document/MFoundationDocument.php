<?php

namespace app\perree\document;

/**
 * MFoundationDocument
 *
 * @property $table string
 * @property $foundationId int
 * @property $documentId int
 */

class MFoundationDocument extends \app\CDBRecord {

    protected $table = 'foundation_document';
    protected $foundationId;
    protected $documentId;

    protected $fields = [
        'foundationId' => [
            'description' => 'FOUNDATION_ID',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'FOUNDATION_ID',
                    'placeholder' => 'FOUNDATION_ID'
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