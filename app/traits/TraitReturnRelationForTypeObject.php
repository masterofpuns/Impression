<?php

namespace app;

Trait TraitReturnRelationForTypeObject {
    /** @var \app\perree\relation\MRelation $relation */
    private $relation;
    /** @var \app\perree\relation\CRelationService $relationService */
    private $relationService;

    public function getRelationService() {
        if (empty($this->relationService)) {
            $this->relationService = m::app()->serviceManager->get('relationService');
        }
        return $this->relationService;
    }

    public function getRelation() {
        if (empty($this->relation)) {
            $this->relation = new \app\perree\relation\MRelation($this->id);
        }
        return $this->relation;
    }
}