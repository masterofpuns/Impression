<?php

namespace app\perree\document;

/**
 * MFundDocument
 *
 * @property $table string
 * @property $fundId int
 * @property $documentId int
 */

class MFundDocument extends \app\CDBRecord {

    protected $table = 'fund_document';
    protected $fundId;
    protected $documentId;

    protected $fields = [
        'fundId' => [
            'description' => 'FUND_ID',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'FUND_ID',
                    'placeholder' => 'FUND_ID'
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