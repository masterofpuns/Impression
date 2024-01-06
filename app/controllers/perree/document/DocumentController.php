<?php

namespace app\perree\document;

use app\CRUDController;
use app\h;
use app\m;
use app\perree\relation\MRelation;

class DocumentController extends CRUDController
{
    protected $dbObject = '\app\perree\document\MDocument';
    protected $viewFolder = 'document';
    protected $overviewUrl = 'document';

    protected $datatableFields = ['dateTimeCreated', 'name', 'actions'];

    public function actionSaveAjax($app, $extraVars = [])
    {
        $entityId = h::getV('entityId', 'int', null, "POST", true);
        $entityType = h::getV('entity', 'any', null, 'POST', true);
        $documentParams = h::getV('Document', 'any', null, "POST", true);

        $entity = null;

        $result = ['success' => true, 'message' => ''];
        try {
            switch ($entityType) {
                case 'relation':
                    $entity = new \app\perree\relation\MRelation($entityId);
                    break;
                case 'fund':
                    $entity = new \app\perree\fund\MFund($entityId);
                    break;
                case 'foundation':
                    $entity = new \app\perree\fund\MFoundation($entityId);
                    break;
            }

            $document = !empty($documentParams['id']) ? new \app\perree\document\MDocument($documentParams['id']) : new \app\perree\document\MDocument();
            $document->name = $documentParams['name'];
            $document->save();

            if (!empty($_FILES['Document']['tmp_name']['file'])) {
                $media = $this->handleFileUpload(
                    $_FILES['Document'],
                    $document,
                );
                $document->fileMediaId = $media->id;
                $document->save();
            }

            // here we store document en entity in related model
            if (empty($documentParams['id'])) {
                switch ((new \ReflectionClass($entity))->getShortName()) {
                    case 'MRelation':
                        $relationDocument = new \app\perree\document\MRelationDocument();
                        $relationDocument->relationId = $entity->id;
                        $relationDocument->documentId = $document->id;
                        $relationDocument->add();
                        break;
                    case 'MFund':
                        $relationDocument = new \app\perree\document\MFundDocument();
                        $relationDocument->fundId = $entity->id;
                        $relationDocument->documentId = $document->id;
                        $relationDocument->add();
                        break;
                    case 'MFoundation':
                        $relationDocument = new \app\perree\document\MFoundationDocument();
                        $relationDocument->foundationId = $entity->id;
                        $relationDocument->documentId = $document->id;
                        $relationDocument->add();
                        break;
                }
            }
        } catch (\Exception $e) {
            $result = ['success' => false, 'message' => $e->getMessage()];
        }

        $app->renderJSON($result);
    }

    public function actionDeleteAjax($app, $extraVars = [])
    {
        $documentId = h::getV('documentId', 'int', null, 'POST', true);

        $result = ['success' => true, 'message' => ''];
        try {
            $document = new \app\perree\document\MDocument($documentId);
            $document->delete();
        } catch (\Exception $e) {
            $result = ['success' => false, 'message' => $e->getMessage()];
        }

        $app->renderJSON($result);
    }

    public function actionFilter()
    {
        if (empty($this->datatableFields)) {
            throw new \Error('No Datatable fields identified');
        }

        if (empty($this->dbObject)) {
            throw new \Error('No object defined for use in Datatables');
        }

        $entityType = h::getSpecificP(0, ['relation', 'fund', 'foundation']);
        $entityId = h::getP(1, 'int', null);
        $entity = null;

        $datatable = new \app\CDatatable;
        $datatable->fields = $this->datatableFields;
        $datatable->object = new $this->dbObject;

        switch ($entityType) {
            case 'relation':
                $entity = new \app\perree\relation\MRelation($entityId);
                $datatable->joinSQL = 'INNER JOIN relation_document RD ON RD.documentId = document.id';
                $datatable->whereSQL = 'WHERE RD.relationId = ' . $entity->id;
                break;
            case 'fund':
                $entity = new \app\perree\fund\MFund($entityId);
                $datatable->joinSQL = 'INNER JOIN fund_document FD ON FD.documentId = document.id';
                $datatable->whereSQL = 'WHERE FD.fundId = ' . $entity->id;
                break;
            case 'foundation':
                $entity = new \app\perree\fund\MFoundation($entityId);
                $datatable->joinSQL = 'INNER JOIN foundation_document FD ON FD.documentId = document.id';
                $datatable->whereSQL = 'WHERE FD.foundationId = ' . $entity->id;
                break;
        }

        $datatable->renderFilter();
    }

    private function handleFileUpload(
        $fileParams,
        $document
    )
    {
        $oldFile = $document->file;

        // Process subscription form upload
        $extension = pathinfo($fileParams['name']['file'], PATHINFO_EXTENSION);
        $filename = h::toAscii($document->name) . '.' . $extension;
        $file = $fileParams['tmp_name']['file'];
        $fileSize = $fileParams['size']['file'];

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file);

        $media = new \app\perree\media\MMedia;
        $media->filename = $filename;
        $media->fileSize = $fileSize;
        $media->fileMimeType = $mimeType;
        $media->createdById = m::app()->user->id;
        $media->type = 'document';
        $media->dateTimeCreated = date('Y-m-d H:i:s');
        $media->add();

        if (!move_uploaded_file($file, $media->localLocation)) {
            $media->delete();
            throw new Exception('Unable to write to target folder.');
        }

        // Opschonen oude inschrijfformulier
        if (!empty($oldFile)) {
            $oldFile->delete();
        }

        return $media;
    }
}