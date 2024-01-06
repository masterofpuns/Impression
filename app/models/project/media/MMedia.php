<?php

namespace app\perree\media;

use \app\h;
use \app\m;

/**
 * MMedia
 *
 * @property $table string
 * @property $filename string
 * @property $fileSize int
 * @property $fileMimeType string
 * @property $type string
 * @property $createdById int
 * @property $dateTimeCreated string
 * @property $updatedById int
 * @property $dateTimeUpdated string
 */

class MMedia extends \app\CDBRecord {

    protected $table = 'media';
    protected $filename;
    protected $fileSize;
    protected $fileMimeType;
    protected $type;
    protected $createdById;
    protected $dateTimeCreated;
    protected $updatedById;
    protected $dateTimeUpdated;

    protected $fields = [
        'filename' => [
            'description' => 'FILENAME',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'FILENAME',
                    'placeholder' => 'FILENAME'
                ],
                'order' => 1
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ],
        'fileSize' => [
            'description' => 'FILE_SIZE',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'FILE_SIZE',
                    'placeholder' => 'FILE_SIZE'
                ],
                'order' => 2
            ],
            'backend' => [
                'type' => 'int',
                'typeParams' => []
            ]
        ],
        'fileMimeType' => [
            'description' => 'FILE_MIME_TYPE',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'FILE_MIME_TYPE',
                    'placeholder' => 'FILE_MIME_TYPE'
                ],
                'order' => 3
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ],
        'type' => [
            'description' => 'TYPE',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'TYPE',
                    'placeholder' => 'TYPE'
                ],
                'order' => 4
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ],
        'createdById' => [
            'description' => 'CREATED_BY_ID',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'CREATED_BY_ID',
                    'placeholder' => 'CREATED_BY_ID'
                ],
                'order' => 5
            ],
            'backend' => [
                'type' => 'int',
                'typeParams' => []
            ]
        ],
        'dateTimeCreated' => [
            'description' => 'DATE_TIME_CREATED',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'DATE_TIME_CREATED',
                    'placeholder' => 'DATE_TIME_CREATED'
                ],
                'order' => 6
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ],
        'updatedById' => [
            'description' => 'UPDATED_BY_ID',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'UPDATED_BY_ID',
                    'placeholder' => 'UPDATED_BY_ID'
                ],
                'order' => 7
            ],
            'backend' => [
                'type' => 'int',
                'typeParams' => []
            ]
        ],
        'dateTimeUpdated' => [
            'description' => 'DATE_TIME_UPDATED',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'DATE_TIME_UPDATED',
                    'placeholder' => 'DATE_TIME_UPDATED'
                ],
                'order' => 8
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ]
    ];

    public function getLocalLocation() {
        $filename = h::pad($this->id) . '.' . pathinfo($this->filename, PATHINFO_EXTENSION);

        if (
            ENVIRONMENT == 'develop' &&
            !file_exists(DOC_STORAGE_LOCATION . $filename)
        ) {
            $filename = 'dummy.txt';
        }

        return DOC_STORAGE_LOCATION . $filename;
    }

    public function getRelativeLocation() {
        $filename = h::pad($this->id) . '.' . pathinfo($this->filename, PATHINFO_EXTENSION);

        return '/media/' . $filename;
    }

    public function getExternalLocation(){
        if (substr($this->filename, 0, 1) === '/') {
            return ROOT . 'media/' . $this->id . $this->filename;
        }
        return ROOT . 'media/' . $this->id . '/' . $this->filename;
    }

    /**
     * Delete media file from harddrive
     */
    public function deleteRelated(){

        $file = $this->getLocalLocation();

        if(file_exists($file)){
            unlink($file);
        }
    }

}