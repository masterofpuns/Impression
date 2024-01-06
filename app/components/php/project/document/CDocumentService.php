<?php

namespace app\perree\document;

use app\m;
use app\h;

class CDocumentService
{
    public function getRerefenceModelForDocument(int $documentId): ?object
    {
        $entity = null;
        $entityTypes = [
            'relation' => '\\app\\perree\\document\\MRelationDocument',
            'fund' => '\\app\\perree\\document\\MFundDocument',
            'foundation' => '\\app\\perree\\document\\MFoundationDocument',
        ];
        // per entity we check if document reference is present. If so, this is the entity we need
        foreach ($entityTypes as $entityType => $resultObject) {
            $table = $entityType . '_document';
            $sql = "SELECT * FROM $table WHERE documentId = :documentId LIMIT 1";
            $entity = m::app()->db->querySingle($sql, [":documentId" => $documentId], $resultObject);
            if (!empty($entity)) { break;}
        }

        return $entity;
    }
}