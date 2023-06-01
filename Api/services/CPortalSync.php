<?php

namespace instance;

error_reporting(E_ALL);

class CPortalSync {
    // array van berichten met wat er mis is gegaan tijdens de sync
    protected $response;
    // true / false, bepaalt succes van synchronisatie
    protected $status;

    protected $logDir = '/log/sync/';
    protected $lockDir = '/lock/sync/';

    protected $lockFile;
    protected $logFile;

    // instance van CLog
    protected $log;

    public function __construct() {
        $this->status = true;
        $this->response = [];

        // instellen pad naar lockDir en logDir
        $this->lockDir = LOCAL_PATH . $this->lockDir;
        $this->logDir = LOCAL_PATH . $this->logDir;

        // controleren of lockDir bestaat
        if (!is_dir($this->lockDir)) {
            mkdir($this->lockDir, 0777, true);
        }

        // controleren of logDir bestaat
        if (!is_dir($this->logDir)) {
            mkdir($this->logDir, 0777, true);
        }

        $this->logFile = $this->logDir . 'portalSync.log';
        $this->lockFile = $this->lockDir . 'portalSync.lock';

        $this->log = new \instance\CLog();
    }

    public function getStatus() {
        return $this->status;
    }

    public function sync() {
        // shutdown functie voor portal sync veranderen naar lokale class functie
        register_shutdown_function([$this, 'handleShutdownError']);

        $this->writeToLog('Start sync');
        $this->writeToLog('Determining if lock file already exists');

        // controleren of lock is gezet
        if ($this->getLock() === false) {

            // zetten lock file
            if ($this->setLock() !== false) {
				
                // uitvoeren sync van portal users & portal user relations
                if ($this->runBatch(SYNC_EXECUTABLE_PORTAL_USER) !== false)  {
	
	                // ophalen lijst van alle bestanden die moeten worden gedownload van portal naar crm
	                $mediaExportFiles = $this->getMediaExportFiles();

                    // uitvoeren sync van portal naar crm
                    if ($this->runBatch(SYNC_EXECUTABLE_PORTAL) !== false)  {
	                    // export files matchen aan gedownloade bestanden
	                    $this->cleanupMediaExportFiles($mediaExportFiles);
						
                        // echo tbv logging
                        $this->writeToLog('Start processing changes');

                        // verwerken changes
                        $this->processChanges();

                        $this->writeToLog('End processing changes');
                        $this->writeToLog('Start processing new registrations');


                        // verwerken nieuwe registraties
                        $this->processNewRegistrations();


                        // echo tbv logging
                        $this->writeToLog('End processing new registrations');
                        $this->writeToLog('Start processing relation assigned documents');


                        // verwerken relation assigned document
                        $this->processRelationAssignedDocuments();


                        // echo tbv logging
                        $this->writeToLog('End processing relation assigned documents');


                        // ophalen over te zetten PDF's
                        $this->countFiles(MEDIA_EXPORT_LOCATION);


                        // uitvoeren sync van crm naar portal
                        if ($this->runBatch(SYNC_EXECUTABLE_CRM) !== false) {
                            // bijwerken van dateTimeLastSync voor models
                            $this->updateLastSyncDateTime();

                            // versturen notificatiemails
                            $this->sendNotificationMails();
                        }
                    }
                }

                // altijd verwijderen lockfile
                $this->unsetLock();
            }
        }

        $this->writeToLog('End sync');

        // shutdown functie herstellen naar globale functie
        register_shutdown_function([m::app(), 'handleShutdownError']);
    }

    public function getLock() {
        if (file_exists($this->lockFile) !== false) {
            $this->status = false;

            // controleren of notificatie al is verstuurd
            $this->checkNotificationSend();

            $this->setResponse("Synchronisatie is momenteel al bezig");
            $this->writeToLog('Lock file already exists');

            return true;
        }

        $this->writeToLog('No lock file found, attempting to create one');
        return false;
    }

    private function setLock() {
        if (($fp = fopen($this->lockFile, "w+")) !== false) {
            fwrite($fp, date('d-m-Y H:i:s'));
            fclose($fp);
            $this->writeToLog('Creating lockfile succesfull, proceeding with sync');
            return true;
        } else {
            $this->status = false;

            if (DEBUG_MODE == 1) {
                $this->setResponse("Lockfile kon niet worden aangemaakt");
            } else {
                $this->setResponse("Er ging iets mis bij het uitvoeren van de synchronisatie");
            }


            $this->writeToLog('Could not create lockfile, abort sync');
            return false;
        }
    }

    private function unsetLock() {
        if (file_exists($this->lockFile) !== false) {
            unlink($this->lockFile);
            $this->writeToLog('Lock file has been unset');
        } else {
            $this->writeToLog('Could not unset lock file: file does not exist (anymore)');
        }
    }

    private function checkNotificationSend() {
        /*if (($fp = fopen($this->lockFile, "rb")) !== false) {
            $file = fread($fp, filesize($this->lockFile));
            fclose($fp);

            $timestamp = strtotime($file);
            if ($timestamp < strtotime("-30 minutes")) {
                $result = $this->doPortalApiCall("email", "sendSyncNotification");
            }
        }*/

        $this->writeToLog('Checking if notifcation "sync jammed" has been send');

        // ophalen parameter laatst gelukte synchronisatie
        $parameter = m::app()->db->querySingle("SELECT * FROM parameter WHERE name = 'dateTimeLastSync' LIMIT 0,1", [], '\\instance\\MParameter');
        if (!empty($parameter)) {
            // controleren of notificatie al is verstuurd
            $notificationParameter = m::app()->db->querySingle("SELECT * FROM parameter WHERE name = 'portalSyncNotification' LIMIT 0,1", [], '\\instance\\MParameter');
            $action = 'update';
            if (empty($notificationParameter)) {
                $notificationParameter = new \instance\MParameter();
                $notificationParameter->name = "portalSyncNotification";
                $notificationParameter->value = 0;
                $action = 'add';
            }

            // interval staat op een x aantal uur ivm het mogelijk
            // langer verwerken van de upload naar de portal
            if (strtotime($parameter->value) < strtotime("-3 hours")) {
                // versturen notificatie
                $result = $this->doPortalApiCall("email", "sendSyncNotification");
                if ($result->success) {
                    $this->writeToLog('Notification send succesfully');
                    $notificationParameter->value = 1;
                    $notificationParameter->$action();
                }
            }
        }
    }

    private function runBatch($file) {
        $logFile = LOCAL_PATH."/log/sync/portalSync.log";
        $runShScript = LOCAL_PATH."sync/run_sh_script.sh";
        system("cmd /c $runShScript $file $logFile", $returnVar);

        if ($returnVar > 0) {
            $this->status = false;
            if (DEBUG_MODE == 1) {
                $this->setResponse("Er ging iets mis bij het uitvoeren van sync batch script ".$file);
                $this->setResponse("De volgende foutcode is daarbij opgetreden: ".$returnVar);
            } else {
                $this->setResponse("Er ging iets mis bij het uitvoeren van de synchronisatie");
            }
            $this->writeToLog('Error occured running batch file "'.$file.'", error code: '.$returnVar);
            return false;
        }

        $this->writeToLog('Running batch file batch file "'.$file.'" successful');
        return true;
    }

    private function setResponse($response) {
        if (is_array($response)) {
            array_merge($this->response, $response);
        } else {
            $this->response[] = $response;
        }
    }

    public function processChanges() {
        // ophalen alle change records waar dateTimeProcessed NULL is
        $this->writeToLog('Getting changes');
        $changes = m::app()->db->query("
                SELECT *
                FROM `change`
                WHERE dateTimeProcessed IS NULL
                AND (
                    dateTimeScheduled <= NOW() OR
                    dateTimeScheduled IS NULL
                )
                AND status = 'no-approval-needed'
                ORDER BY dateTimeCreated ASC
            ",
            [],
            '\instance\MChange'
        );

        $this->writeToLog('Found ' . count($changes) . ' changes.');

        // verwerken changes
        foreach ($changes as $change) {
            $this->processChange($change);
        }
    }

    /**
     * functie geeft mogelijkheid om een enkele change van buitenaf
     * ook te laten verwerken
     *
     * @param \instance\MChange $change
     */
    public function processChange(\instance\MChange $change) {
        $this->writeToLog('Start processing MChange: ' . $change->id);
        if (empty($change)) { return false; }

        // instantieren relation
        $relation = null;
        if (!empty($change->relationId)) {
            $relation = new \instance\MRelation($change->relationId);
            $this->writeToLog('Getting relation for change. Relation ID: ' . $relation->id);
            $relation->updateRelated = false;
        }

        // ophalen change_value
        $changeValues = $change->getChangeValues();
        $this->writeToLog('Getting change values for change');

        // model class bepalen
        $modelClass = $change->entity;
        $this->writeToLog('Getting model class for change: ' . $modelClass);

        // bepalen database actie van change
        $action = $change->type;
        $this->writeToLog('Setting action: ' . $action);

        // starten transactie
        m::app()->db->startTransaction();
        $this->writeToLog('Starting transaction for processing ');

        $changedByObj = new \stdClass;
        $changedByObj->userType = 'portal';
        $changedByObj->userId = $change->createdById;
        $this->writeToLog('Setting changedBy object');


        // instantieren model
        $model = new $modelClass();

        // controleren of model MRelation heeft
        if (is_callable([$model, 'getRelation']) && !empty($model->getRelation())) {
            // wanneer MRelation bestaat, uitzetten updateRelated
            // update doen we aan het einde van change om te voorkomen
            // dat relatie meer dan 1 keer in index wordt geupdatet
            $model->getRelation()->updateRelated = false;
        }

        $this->writeToLog('Initiating new model');
        if ($action !== 'add') {
            try {
                // instantieren model met ID uit change
                $model = new $modelClass($change->entityId);
                $this->writeToLog('Initiating model by class and id. Model ID:' . $model->id);
            } catch (\Exception $e) {
                m::app()->db->rollbackTransaction();

                // in het geval van een delete en entiteit MChange
                if ($action === 'delete' && $modelClass === 'MChange') {
                    $change->dateTimeProcessed = date('Y-m-d H:i:s');
                    $change->update();
                }

                if ($modelClass == 'MRelationAssignedDocument') {
                    $change->dateTimeProcessed = date('Y-m-d H:i:s');
                    $change->update();
                }

                $message = "Instantieren van model ".$change->entity." niet gelukt." . PHP_EOL;
                $message .= $e->getMessage() . PHP_EOL;
                $message .= $e->getFile() . PHP_EOL;
                $message .= $e->getLine() . PHP_EOL;

                $this->writeToLog($message);

                return false;
            }
        }

        // controleren of instellingen voor transactionele mailings is gewijzigd
        if (property_exists($model, 'transactionalMail') !== false) {
            $transactionalMailSettingOld = $model->transactionalMail;
            $this->writeToLog('Storing old model transactional mail setting for future reference');
        }

        // itereren door properties van change om deze toe te voegen aan model
        $this->writeToLog('iterate through values from changes for model');
        foreach ($changeValues as $changeValue) {
            if ($changeValue->property == 'loggedInCrmUserId') {
                $changedByObj->userType = 'crm';
                $changedByObj->userId = $changeValue->newValue;
                $this->writeToLog('Altering changedBy object, changes made by CRM users');
            }

            $property = $changeValue->property;
            // als betreffende property voor model niet bestaat, overslaan
            if (property_exists($model, $property) === false) { continue; }
            $model->$property = $changeValue->newValue;
            $this->writeToLog('Setting ' . $property . ' new value: ' . $changeValue->newValue);
        }

        // uitvoeren actie op model wanneer actie add is
        if ($action === 'add') {
            try {
                $model->$action(false);
                $this->writeToLog('Adding model to DB, not logging action yet');

                switch ($modelClass) {
                    case 'MContactPerson':
                        // controleren of deze al relatie heeft, zo niet: toevoegen
                        if (empty($model->getRelation())) {
                            $modelRelation = new \instance\MRelation();
                            $modelRelation->type = 'contactPerson';
                            $modelRelation->typeId = $model->id;
                            $modelRelation->add();
                            $modelRelation->updateIndexFields();
                        }
                        break;
                }

            } catch (\Exception $e) {
                // wanneer model toe is gevoegd aan index, ook verwijderen uit index
                if (is_callable([$model, "deleteFromIndex"])) {
                    $model->deleteFromIndex();
                }

                $message = "Toevoege van " . $change->entity . " niet gelukt." . PHP_EOL;
                $message .= $e->getMessage() . PHP_EOL;
                $message .= $e->getFile() . PHP_EOL;
                $message .= $e->getLine() . PHP_EOL;
                $this->writeToLog($message);

                return false;
            }
        }

        // controleren te loggen model
        $this->writeToLog('Determining log action');
        try {
            switch ($modelClass) {
                case 'MContactPerson':
                    $this->writeToLog('Log action for MContactPerson');

                    // zetten van parent bij model, om te voorkomen dat actie
                    // niet wordt gelogd bij hoofdrelatie
                    if (empty($model->getParent())) {
                        $model->setParent($relation);
                        $this->writeToLog('Setting parent');
                    }

                    $relation = $model->getRelation();
                    $relation->updateRelated = false;
                    $this->writeToLog('Re-initiate relation');

                    // loggen van actie
                    $this->log->doLog(
                        $model,
                        $action,
                        $relation->id,
                        $changedByObj->userId,
                        $changedByObj->userType
                    );
                    $this->writeToLog('Succesfully logged action');

                    break;
                case 'MAddress':
                    // controleren of "description" bestaat in change values
                    $changeValue = $change->getChangeValueByProperty('description');
                    $this->writeToLog('Log action for MAdres');

                    if (empty($changeValue)) {

                        // wanneer de property description niet bestaat, kunnen we het model loggen
                        $this->log->doLog(
                            $model,
                            $action,
                            $relation->id,
                            $changedByObj->userId,
                            $changedByObj->userType
                        );
                        $this->writeToLog('Succesfully logged action');

                    } else {
                        // in deze case loggen we vanuit de aanvullende acties voor een change
                    }
                    break;
                case 'MWebHook':
                    // do nothing
                    break;
	            case 'MRegistration':
	            case 'MRegistrationAddress':
	            case 'MRegistrationBankAccount':
	            case 'MRegistrationContactPerson':
	            case 'MRegistrationContactPersonPhoneNumber':
	            case 'MRegistrationRelation':
	            case 'MRegistrationUbo':
	            	// do nothing yet
	            	break;
                default:
                    $this->writeToLog('Default log action for ' . $modelClass);

                    // loggen van actie
                    $this->log->doLog(
                        $model,
                        $action,
                        $relation->id,
                        $changedByObj->userId,
                        $changedByObj->userType
                    );
                    $this->writeToLog('Succesfully logged action');
                    break;
            }
        } catch (\Exception $e) {
            $this->writeToLog($e->getMessage());
            return false;
        }

        // uitvoeren actie op model wanneer actie NIET add is
        if ($action !== 'add') {
            try {
                $model->$action(false);
                $this->writeToLog($action . ' for model successfully');

            } catch (\Exception $e) {
                m::app()->db->rollbackTransaction();

                $message = "Uitvoeren van actie: " . $action . " voor " . $change->entity . " niet gelukt." . PHP_EOL;
                $message .= $e->getMessage() . PHP_EOL;
                $message .= $e->getFile() . PHP_EOL;
                $message .= $e->getLine() . PHP_EOL;
                $this->writeToLog($message);

                return false;
            }
        }

        // committen transactie op db
        m::app()->db->commitTransaction();
        $this->writeToLog('Commiting first transaction');
	
	    // controleren of model MRelation heeft
	    if (is_callable([$model, 'getRelation']) && !empty($model->getRelation())) {
		    $this->writeToLog("Updating MRelation for $modelClass");
		    // bijwerken relatie van model voor we door gaan
		    $updateRelation = $model->getRelation();
		    $updateRelation->updateRelated = false;
		    $updateRelation->update();
	    }
     
	    try {
            // aanvullende acties uitvoeren
            $this->writeToLog('Initiating additional changes for change');
            $this->doAdditionalChanges(
                $model,
                $change,
                $relation
            );
            $this->writeToLog('Finished processing additional changes for change');
			
            // aanvullende acties voor relatie waarvan propery transactionalMail is gewijzigd
            if (
                property_exists($model, 'transactionalMail') !== false &&
                $model->transactionalMail !== $transactionalMailSettingOld
            ) {
                $this->writeToLog('Updating transactional mail setting for relation and contactpersons due to difference in new and old setting');
                // zetten van transactionalMail setting bij contactpersonen
                $relation->updateContactPersonsTransactionalMail($model->transactionalMail);
                $this->writeToLog('Finished updating transactional mail setting');
            }

            // controleren of model MRelation heeft
            if (
                stripos($change->entity, 'MRegistration') === false &&
                is_callable([$model, 'getRelation']) &&
                !empty($model->getRelation()) &&
                $model->getRelation()->id != $relation->id
            ) {
	            m::app()->elasticManager->addEntityToProcess($model->getRelation(), 'update');
            }

            // updaten change record
            $change->dateTimeProcessed = date('Y-m-d H:i:s');
            $change->update();

		    $this->writeToLog('Updating proces date and time for change');

            // wanneer change direct verwerkt mag worden EN is verwerkt,
            // changeValues verwijderen
            if ($change->status == 'no-approval-needed') {
                $this->writeToLog('Initiating delete for values of change');
                foreach ($change->getChangeValues() as $changeValue) {
                    // verwijderen change values
                    $changeValue->delete();
                    $this->writeToLog('Value for change deleted');
                }
            }

            $this->writeToLog("End processing change: ". $change->id);

        } catch (\Exception $e) {

            $message = "Uitvoeren van additionele acties voor " . $change->entity . " niet gelukt." . PHP_EOL;
            $message .= $e->getMessage() . PHP_EOL;
            $message .= $e->getFile() . PHP_EOL;
            $message .= $e->getLine() . PHP_EOL;
            $this->writeToLog($message);

            return false;

        }

        m::app()->elasticManager->processEntities();

        return $model;
    }

    /**
     * Additionele wijzigingen die betrekking hebben op het aan te maken model
     *
     * @param mixed $model
     * @param mixed $change
     * @param mixed $relationId
     */
    public function doAdditionalChanges(
        $model,
        $change,
        $relation
    ) {

        $changedByObj = new \stdClass;
        $changedByObj->userType = 'portal';
        $changedByObj->userId = $change->createdById;
        $this->writeToLog('Setting changedBy object');

        // ophalen change_value
        $changeValues = $change->getChangeValues();

        // itereren door properties van change om deze toe te voegen aan model
        foreach ($changeValues as $changeValue) {
            if ($changeValue->property == 'loggedInCrmUserId') {
                $changedByObj->userType = 'crm';
                $changedByObj->userId = $changeValue->newValue;
                $this->writeToLog('Altering changedBy object, changes made by CRM users');
            }
        }

        switch ($change->entity) {
            case 'MAddress':
                $this->writeToLog('Additional changes for MAddress');
                // controleren of properties bestaan in change en
                // ophalen betreffende changeValue model
                $this->writeToLog('Attempting to retrieve change values for `description` and `isPrimary`');
                $changeValues = $change->getChangeValuesByProperties(['description', 'isPrimary']);

                // wanneer changeValues niet leeg is, toevoegen
                if (!empty($changeValues)) {
                    $relationAddress = new \instance\MRelationAddress();
                    $this->writeToLog('Initiated new MRelationAddress');
                    $relationAddress->relationId = $relation->id;
                    $relationAddress->addressId = $model->id;
                    foreach ($changeValues as $changeValue) {
                        $relationAddress->{$changeValue->property} = $changeValue->newValue;
                    }
                    $relationAddress->add(false);
                    $this->writeToLog('MRelationAddress added');

                    $this->log->doLog(
                        $model,
                        'add',
                        $relation->id,
                        $changedByObj->userId,
                        $changedByObj->userType
                    );
                    $this->writeToLog('Succesfully logged action');
                }

                break;

            case 'MBankAccount':
                // opslaan log die is toegevoegd bij toevoegen model
                $modelLog = $this->log->getLastLog();

                $this->writeToLog('Additional change for MBankAccount');
                // controleren of fundIds zijn gezet, zo ja, voor betreffende
                // fondsen bankrekening wijzigen
                $this->writeToLog('Attempting to retrieve change value for `fundIds`');
                $changeValue = $change->getChangeValueByProperty('fundIds');
                if (!empty($changeValue)) {
                    $fundIds = json_decode($changeValue->newValue);

                    foreach ($fundIds as $fundId) {
                        $this->writeToLog('Retrieving particpation for fund: ' . $fundId . ' and relation: ' . $relation->id);
                        // ophalen participatie van participant voor fonds
                        $participation = m::app()->db->querySingle(
                            "
                                SELECT P.*
                                FROM participation P
                                INNER JOIN fund F ON F.id = P.fundId
                                WHERE P.relationId = :rid
                                AND P.fundId = :fundId
                            ",
                            [
                                ':rid' => $relation->id,
                                ':fundId' => $fundId
                            ],
                            '\instance\MParticipation'
                        );

                        if (!$participation) {
                            $this->writeToLog('Participation not found for fund, proceeding...');
                            continue;
                        }

                        // opslaan id oude bankrekening
                        $oldBankAccountId = $participation->bankAccountId;
                        $participation->bankAccountId = $model->id;

                        // toevoegen log bij relatie met correcte userId
                        $this->log->doLog(
                            $participation,
                            'update',
                            $relation->id,
                            $changedByObj->userId,
                            $changedByObj->userType
                        );
                        $this->writeToLog('Succesfully logged action');

                        // opslaan wijziging
                        $participation->update(false);
                        $this->writeToLog('Updated bankaccount for participation: ' . $participation->id);


                        // controleren of oude bankrekening nog gekoppelde fondsen heeft
                        $oldBankAccount = new \instance\MBankAccount($oldBankAccountId);
                        if (count($oldBankAccount->getFunds()) == 0) {
                            // Als er geen fondsen meer aan een rekeningnummer zijn gekoppeld, bankrekening inactief maken
                            $oldBankAccount->active = 0;
                            $oldBankAccount->update(false);
                            $this->writeToLog('Archived bankaccount: ' . $oldBankAccount->id);

                            // zetten oude waarde van toegevoegde log voor bankrekening
                            if (!empty($modelLog->oldValue)) {
                                $modelLog->oldValue .= PHP_EOL;
                                $modelLog->oldValue .= $oldBankAccount;
                            } else {
                                $modelLog->oldValue = $oldBankAccount;
                            }
                            $modelLog->update();
                            $this->writeToLog('Added old bankaccount to log, log ID: ' . $modelLog->id);
                        }
                    }
                }
	
				// controleren of er een registrationId bestaat voor bankrekening
	            $changeValue = $change->getChangeValueByProperty('registrationId');
	            if (!empty($changeValue)) {
		            $registration = new \instance\MRegistration($changeValue->newValue);
		            $registration->bankAccountId = $model->id;
		            $registration->update();
	            }

                // uitsturen van bevestigingsmail
                $data = [
                    'iban' => $model->iban,
                    'bic' => $model->bic,
                    'relationNumber' => $model->relationNumber,
                    'ascription' => $model->ascription,
                    'relationId' => $relation->id
                ];
                $result = $this->doPortalApiCall("email", "sendBankAccountConfirmation", $data);

                break;

            case 'MContactPerson':
                /*********************************************
                 * KOPPEL MODELLEN VOOR CONTACT PERSOON
                 **********************************************/
                $this->writeToLog('Do additional changes for MContactPerson');
                if (in_array($change->entity, unserialize(MODELS_WITH_LINKED_MODEL)) && $change->type == 'add') {
                    $this->writeToLog('Model has linked model and action is `add`');
                    $linkedModel = null;
                    $properties = [];

                    // bepalen aan welk relatie type contact persoon toegevoegd moet worden
                    switch ($model->getParent()->type) {
                        case 'contactPerson':
                            $properties = [];
                            $linkedModel = new \instance\MContactPersonRelation();
                            $linkedModel->contactPersonId = $model->getParent()->id;
                            $linkedModel->contactPersonRelationId = $relation->id;
                            break;
                        case 'organization':
                            $properties = ['combinedContactPerson', 'rank', 'isManager'];
                            $linkedModel = new \instance\MOrganizationRelation();
                            $linkedModel->organizationRelationId = $model->getParent()->id;
                            $linkedModel->contactPersonRelationId = $relation->id;
                            break;
                        case 'collective':
                            $properties = ['isParticipating', 'rank'];
                            $linkedModel = new \instance\MCollectiveRelation();
                            $linkedModel->collectiveRelationId = $model->getParent()->id;
                            $linkedModel->contactPersonRelationId = $relation->id;
                            break;
                        case 'intemediary':
                            $properties = [];
                            $linkedModel = new \instance\MIntermediaryRelation();
                            $linkedModel->intermediaryRelationId = $model->getParent()->id;
                            $linkedModel->contactPersonRelationId = $relation->id;
                            break;
                    }

                    if (!empty($properties)) {
                        // controleren of properties bestaan in change en
                        // ophalen betreffende changeValue model
                        $changeValues = $change->getChangeValuesByProperties($properties);
                    }


                    if (!empty($linkedModel)) {
                        // wanneer changeValues niet leeg is, toevoegen
                        if (!empty($changeValues)) {
                            $this->writeToLog('Attemting to set values for properties for linked model');
                            foreach ($changeValues as $changeValue) {
                                if (property_exists($linkedModel, $changeValue->property) === false) { continue; }
                                $this->writeToLog('Setting ' . $changeValue->property . ', new value: ' . $changeValue->newValue);
                                $linkedModel->{$changeValue->property} = $changeValue->newValue;
                            }
                        }

                        $linkedModel->add();
                    }
                }


                /*********************************************
                 * BESTUURDER
                 **********************************************/
                $this->writeToLog('Attempting to retrieve value `isManager` for change');
                $changeValue = $change->getChangeValueByProperty('isManager');
                if (!empty($changeValue)) {

                    // nieuwe contact person als primaire contactpersoon instellen
                    $model->getParent()->object->primaryContactPersonId = $model->getRelation()->id;
                    $model->getParent()->object->update();
                    $this->writeToLog('Succesfully updated primary contactperson for model');

                    $this->writeToLog('Attempting to retrieve value `oldEntityId` for change');
                    $changeValue = $change->getChangeValueByProperty('oldEntityId');
                    if (!empty($changeValue)) {
                        // controleren of oldEntityId is gezet, zo ja ophalen MOrganizationRelation
                        $this->writeToLog('Initiating old contactperson object');
                        $oldContactPerson = new \instance\MContactPerson($changeValue->newValue);

                        // wijze van versturen transactionele mailings aanpassen voor oude bestuurder
                        $oldContactPerson->transactionalMail = 1;
                        $this->writeToLog('Alter transactional mail setting for old manager');

                        // toevoegen log bij relatie met correcte userId
                        $this->log->doLog(
                            $oldContactPerson,
                            'update',
                            $oldContactPerson->getRelation()->id,
                            $changedByObj->userId,
                            $changedByObj->userType
                        );
                        $this->writeToLog('Succesfully logged action');

                        $oldContactPerson->update(false);

                        $organizationRelation = m::app()->db->query(
                            "
                                SELECT *
                                FROM organization_relation
                                WHERE organizationRelationId = :orid
                                AND contactPersonRelationId = :cprid
                                AND isManager = 1
                            ",
                            [
                                ":orid" => $model->getParent()->id,
                                ":cprid" => $oldContactPerson->getRelation()->id
                            ],
                            '\instance\MOrganizationRelation'
                        );

                        // oude bestuurder bestuurder af maken
                        if (!empty($organizationRelation)) {
                            $this->writeToLog('Annul old manager');
                            $organizationRelation = current($organizationRelation);

                            $organizationRelation->isManager = 0;
                            $organizationRelation->combinedContactPerson = 0;

                            // opslaan wijziging
                            $organizationRelation->update();
                        }

                        // inactief maken portal gebruiker oude bestuurder
                        $portalUser = $oldContactPerson->getRelation()->getPortalUser();

                        // Uitsturen mail naar oude bestuurder
                        if (!empty($portalUser)) {
                            $data = [
                                'relationId' => $relation->object->parent->id,
                                'userId' => $portalUser->id
                            ];
                            $this->writeToLog('Sending old manager confirmation email through API');
                            $result = $this->doPortalApiCall("email", "sendOldManagerConfirmation", $data);

                            // archiveren portal gebruiker
                            $portalUser->delete();
                            $this->writeToLog('Succesfully delete portal users associated with old manager');
                        }

                        // archiveren oude bestuurder
                        $oldContactPerson->getRelation()->archived = 1;
                        $this->writeToLog('Succefully archived relation associated with old manager');

                        // toevoegen log bij relatie met correctie userId
                        $this->log->doLog(
                            $oldContactPerson,
                            'update',
                            $oldContactPerson->getRelation()->id,
                            $changedByObj->userId,
                            $changedByObj->userType
                        );
                        $this->writeToLog('Succesfully logged action');

                        // opslaan wijziging
                        $oldContactPerson->getRelation()->update(false);

                        // controleren of relatie nog portal gebruikers heeft, zo niet,
                        // status transactionele mailings ook aanpassen
                        if (empty($relation->getParent()->getPortalUsersForRelationEnvironment())) {
                            $this->writeToLog('Altering transactional mail setting for old manager');
                            $relation->getParent()->object->transactionalMail = 1;

                            // toevoegen log bij relatie met correcte userId
                            $this->log->doLog(
                                $relation->getParent(),
                                'update',
                                $relation->getParent()->id,
                                $changedByObj->userId,
                                $changedByObj->userType
                            );
                            $this->writeToLog('Succesfully logged action');

                            // opslaan wijziging
                            $relation->getParent()->object->update(false);
                        }
                    }

                    // controleren of er bestanden klaar staan voor nieuwe bestuurde
                    $this->writeToLog('Attempting to retrieve value `mediaId` for change');
                    $changeValue = $change->getChangeValueByProperty('mediaId');
                    if (!empty($changeValue)) {
                        // temp lokatie portal geuploade bestanden
                        $filename = TEMP_MEDIA_LOCATION . $changeValue->newValue;
                        if (file_exists($filename)) {
                            $this->processMedia($filename, $relation, 'Legitimatiebewijs');
                        }
                    }

                    // controleren of er bestanden klaar staan voor nieuwe bestuurde
                    $this->writeToLog('Attempting to retrieve value `mediaChamberOfCommerce` for change');
                    $changeValue = $change->getChangeValueByProperty('mediaChamberOfCommerce');
                    if (!empty($changeValue)) {
                        // temp lokatie portal geuploade bestanden
                        $filename = TEMP_MEDIA_LOCATION . $changeValue->newValue;
                        if (file_exists($filename)) {
                            $this->processMedia($filename, $relation, 'Uitreksel Kamer van Koophandel');
                        }
                    }

                    // controleren of we een telefoonnummer moeten uploaden voor de bestuurder
                    $this->writeToLog('Attempting to retrieve values `number` and `type` for change');
                    $changeValues = $change->getChangeValuesByProperties(['number', 'type']);
                    if (!empty($changeValues)) {
                        $this->writeToLog('Initiating phoneNumber');
                        $phoneNumber = new \instance\MPhoneNumber();

                        $this->writeToLog('Setting values for phonenumber');
                        foreach ($changeValues as $changeValue) {
                            $phoneNumber->{$changeValue->property} = $changeValue->newValue;
                            $this->writeToLog('PhoneNumber property ' . $changeValue->property . ', new value: ' . $changeValue->newValue);
                        }

                        $phoneNumber->relationId = $relation->id;
                        $phoneNumber->add(false);
                        $this->writeToLog('Succesfully added phoneNumber for manager, not logging action yet');

                        // toevoegen log bij relatie met correctie userId
                        $this->log->doLog(
                            $phoneNumber,
                            'add',
                            $relation->id,
                            $changedByObj->userId,
                            $changedByObj->userType
                        );
                        $this->writeToLog('Succesfully logged action');
                    }

                    // controleren of we een emailAddress moeten uploaden voor de bestuurder
                    $this->writeToLog('Attempting to retrieve value `emailAddress` for change');
                    $changeValue = $change->getChangeValueByProperty('emailAddress');
                    if (!empty($changeValue)) {
                        $emailAddress = new \instance\MEmailAddress();
                        $emailAddress->address = $changeValue->newValue;
                        $emailAddress->relationId = $relation->id;
                        $emailAddress->isPrimary = 1;
                        $emailAddress->add(false);
                        $this->writeToLog('EmailAddress addded for manager');

                        // toevoegen log bij relatie met correctie userId
                        $this->log->doLog(
                            $emailAddress,
                            'add',
                            $relation->id,
                            $changedByObj->userId,
                            $changedByObj->userType
                        );
                        $this->writeToLog('Succesfully logged action');
                    }
                }


                /*******************************************
                 * EXTRA PORTAL GEBRUIKER AANMAKEN
                 ********************************************/

                // Voor een extra gebruiker zijn userId en emailAddress beide gezet
                // eerst user koppeling maken, daarna portal user credentials goedzetten
                // en mail versturen

                // controleren of userId bestaat in change values
                $this->writeToLog('Attempting to retrieve value `userId` for change');
                $changeValueUserId = $change->getChangeValueByProperty('userId');
                $this->writeToLog('Attempting to retrieve value `emailAddress` for change');
                $changeValueEmailAddress = $change->getChangeValueByProperty('emailAddress');

                if (!empty($changeValueUserId) && !empty($changeValueEmailAddress)) {
                    $this->writeToLog('`userId` & `emailAddress` found, proceeding with extra portal user actions');
                    $this->writeToLog('Attempting to retrieve value `extraUserType` for change');
                    $changeValueExtraUserType = $change->getChangeValueByProperty('extraUserType');

                    // opslaan portal user id
                    $portalUserId = $changeValueUserId->newValue;
                    $portalUser = new \instance\MPortalUser($portalUserId);
                    $this->writeToLog('PortalUser with ID: ' . $portalUserId . ' initiated');

                    $data = [
                        'userId' => $changeValueUserId->newValue,
                        'relationId' => $model->getRelation()->id
                    ];

                    $this->writeToLog('Adding user relation through API');
                    $result = $this->doPortalApiCall('user_relation','add',$data);
                    if (!isset($result->success) || $result->success != 1) {
                        throw new \Exception('Error processing change in portal');
                    }

                    // toevoegen koppeling tussen portal gebruiker en aangemaakte relatie
                    $portalUserRelation = new MPortalUserRelation();
                    $portalUserRelation->userId = $data['userId'];
                    $portalUserRelation->relationId = $data['relationId'];
                    $portalUserRelation->add();
                    $this->writeToLog('Succesfully added portal user relation');

                    // emailadres toevoegen
                    $emailAddress = new \instance\MEmailAddress();
                    $emailAddress->address = $changeValueEmailAddress->newValue;
                    $emailAddress->isPrimary = 1;
                    $emailAddress->relationId = $model->getRelation()->id;
                    $emailAddress->add(false);
                    $this->writeToLog('Succesfully added emailAddress');

                    // toevoegen koppeling tussen portal gebruiker en contact persoon
                    $portalUserContactPerson = new \instance\MPortalUserContactPerson();
                    $portalUserContactPerson->contactPersonId = $model->id;
                    $portalUserContactPerson->userId = $portalUser->id;
                    $portalUserContactPerson->add();
                    $this->writeToLog('Succesfully added portal user contact person');


                    // toevoegen log bij relatie met correctie userId
                    $this->log->doLog(
                        $emailAddress,
                        'add',
                        $relation->id,
                        $changedByObj->userId,
                        $changedByObj->userType
                    );
                    $this->writeToLog('Succesfully logged action');

                    if ($changeValueExtraUserType->newValue == 'newUser') {
                        $this->writeToLog('Portal user type: newUser');
                        // portal user gegevens aanpassen naar tijdelijke inlog

                        $portalUser->salt = m::app()->generateRandomSalt();
                        $password = h::generatePassword(8);
                        $portalUser->passwordHash = m::app()->hashPassword($password,$portalUser->salt);
                        $this->writeToLog('Random password generated for portal user');
                        $portalUser->emailAddress = $emailAddress->address;

                        $this->writeToLog('Updating portal user through API');
                        $result = $this->doPortalApiCall('user','update',$portalUser->toPostArray());
                        if (!isset($result->success) || $result->success != 1) {
                            throw new \Exception('Error processing change in portal');
                        }

                        // mail versturen vanuit portal
                        $data = [
                            'emailAddress' => $emailAddress->address,
                            'userId' => $portalUserId,
                            'relationId' => $model->getParent()->id,
                            'password' => $password,
                            'emailType' => 'extraUser',
                            'createdById' => $change->createdById
                        ];

                        $this->writeToLog('Sending new user - extra user email through API');
                        $result = $this->doPortalApiCall('email','sendExtraUserEmail', $data);
                        if (!isset($result->success) || $result->success != 1) {
                            throw new \Exception('Error processing change in portal');
                        }
                    }

                    if ($changeValueExtraUserType->newValue == 'existingUser') {
                        // mail versturen vanuit portal
                        $data = [
                            'emailAddress' => $emailAddress->address,
                            'userId' => $portalUserId,
                            'relationId' => $model->getParent()->id,
                            'password' => '',
                            'emailType' => 'extraUserRelationAddedToEnvironment',
                            'createdById' => $change->createdById
                        ];

                        $this->writeToLog('Sending existing user - extra user email through API');
                        $result = $this->doPortalApiCall('email','sendExtraUserEmail', $data);
                        if (!isset($result->success) || $result->success != 1) {
                            throw new \Exception('Error processing change in portal');
                        }

                    }


                }


                /*******************************************
                 * TRANSACTIONELE MAILINGS
                 ********************************************/
                // in de situatie dat contact persoon vanuit de portal op digitaal wordt gezet
                // en deze setting nog niet gelijk is met hoofdrelatie, ook doorvoeren bij hoofdrelatie
                // en alle onderliggende contactpersonen
                $this->writeToLog('Attemping to retrieve value `transactionalMail` for change');
                $changeValue = $change->getChangeValueByProperty('transactionalMail');
                if (!empty($changeValue)) {
                    $parentRelation = $model->getParent();
                    if (!empty($parentRelation)) {
                        $parentObject = $parentRelation->getObject();

                        if (
                            !empty($parentObject) &&
                            $parentObject->transactionalMail != $model->transactionalMail &&
                            (
                                intval($model->transactionalMail) == \instance\MRelation::DIGITAL ||
                                intval($model->transactionalMail) == \instance\MRelation::PER_MAIL_AND_DIGITAL
                            )
                        ) {
                            $this->writeToLog('Transactional mail setting not matching main relation and other contactpersons. Update main relation + all contactpersons');

                            $parentObject->transactionalMail = $model->transactionalMail;
                            // log toevoegen voor parent, waarbij gelogde gebruiker, gebruiker van change is
                            $log = new \instance\CLog();
                            $log->doLog(
                                $parentObject,
                                'update',
                                $parentRelation->id,
                                $changedByObj->userId,
                                $changedByObj->userType
                            );
                            $this->writeToLog('Succesfully logged action');
                            $parentObject->update(false);
                            $this->writeToLog('Succesfully updated parent relation');

                            // verwerken log bijbehorende contactpersonen van hoofdrelatie
                            $this->writeToLog('Start updating transactional mail setting for contactpersons');
                            $model->getParent()->updateContactPersonsTransactionalMail();
                            $this->writeToLog('Finished updating transactional mail setting for contactpersons');
                        }
                    }
                }

                break;

            case 'MPortalUser':
                // wanneer change een portal gebruiker betreft, controleren om welke property het gaat
                $this->writeToLog('Attempting to retrieve value `password` for change');
                $changeValue = $change->getChangeValueByProperty('password');
                if (!empty($changeValue)) {
                    // initieren user
                    $portalUser = new \instance\MPortalUser($change->entityId);

                    // toevoegen van log entry voor alle gekoppelde relaties van portal gebruiker
                    foreach ($portalUser->getRelations() as $relation) {
                        $log = new \instance\MLog();
                        $log->dateTimeCreated = date('Y-m-d H:i:s');
                        $log->entity = $change->entity;
                        $log->action = 'PASSWORD';
                        $log->userId = $change->createdById;
                        $log->userType = 'portal';
                        $log->relationId = $relation->id;
                        $log->oldValue = "";
                        $log->newValue = $changeValue->newValue;
                        $log->add();
                        $this->writeToLog('Succesfully logged password change for relation: ' . $relation->id);
                    }
                }
                break;

            case 'MEmailAddress':
                // additioneel alle overige emailadressen van relatie niet primair maken
                $this->writeToLog('Annul primary setting for, not current model, emailAddresses');
                $emailAddresses = $relation->getActiveEmailAddresses();
                foreach ($emailAddresses as $emailAddress) {
                    if ($emailAddress->id != $model->id) {
                        $emailAddress->isPrimary = 0;
                        $emailAddress->update(false);
                    }
                }

                $changeValue = $change->getChangeValueByProperty('archiveAllOtherEmailAddresses');
                if (!empty($changeValue)) {
                    $this->writeToLog('Archiving all, not current model, emailAddresses');

                    $emailAddresses = $relation->getActiveEmailAddresses();

                    foreach ($emailAddresses as $emailAddress) {
                        if ($emailAddress->id != $model->id) {
                            // alle emailadressen behalve de nieuwe primair archiveren
                            $emailAddress->archived = 1;
                            $emailAddress->update(false);

                            // toevoegen log bij relatie met correctie userId
                            $log = new \instance\MLog();
                            $log->dateTimeCreated = date('Y-m-d H:i:s');
                            $log->entity = $change->entity;
                            $log->action = 'ARCHIVED';
                            $log->userId = 1;
                            $log->userType = 'portal';
                            $log->relationId = $relation->id;
                            $log->oldValue = $emailAddress->address;
                            $log->newValue = "";
                            $log->add();
                            $this->writeToLog('Succesfully logged action');
                        }
                    }
                }
                break;

            case 'MFervour':
                $changeValue = $change->getChangeValueByProperty('fervourTag');
                if (!empty($changeValue)) {
                    $fervourTag = new \instance\MFervourTag();
                    $fervourTag->fervourId = $model->id;
                    $fervourTag->tagId = $changeValue->newValue;
                    $fervourTag->add();
                    $this->writeToLog('Succesfully added tag to new fervour');
                }

                break;

            case 'MRelation':
                // controleren of er een relatie moet worden gearchiveerd
                $changeValue = $change->getChangeValueByProperty('archived');
                if (!empty($changeValue)) {
                    // controleren of
                    $logRelation = new \instance\MRelation($change->entityId);

                    $log = new \instance\MLog();
                    $log->dateTimeCreated = date('Y-m-d H:i:s');
                    $log->entity = $change->entity;
                    $log->action = 'ARCHIVED';
                    $log->userId = $changedByObj->userId;
                    $log->userType = $changedByObj->userType;
                    $log->relationId = $logRelation->id;
                    $log->oldValue = $logRelation->nameSortable;
                    $log->newValue = "";
                    $log->add();
                    $this->writeToLog('Succesfully logged archive action for relation: ' . $logRelation);
                }

                break;

            case 'MWebHook':
                switch ($model->event) {
                    case 'unsubscribe':
                        // ophalen data & emailadres
                        $data = $model->getData();
                        $email = $data->email;

                        // zoeken naar MRelations waar emailadres aan is gekoppeld
                        $relations = m::app()->db->query(
                            "
                                SELECT R.*
                                FROM relation R
                                INNER JOIN email_address EA ON EA.relationId = R.id
                                WHERE EA.address = :email
                                AND EA.archived IS NULL
                            ",
                            [":email" => $email],
                            "\\instance\\MRelation"
                        );

                        // bij gevonden relaties
                        if (!empty($relations)) {
                            // itereren door relaties
                            foreach ($relations as $relation) {
                                // bepalen setting campagne mail
                                $campaignMail = $relation->object->campaignMail;

                                $log = new \instance\MLog();
                                $log->dateTimeCreated = date('Y-m-d H:i:s');
                                $log->entity = $change->entity;
                                $log->action = 'CAMPAIGN_MAILINGS';
                                $log->userId = $changedByObj->userId;
                                $log->userType = $changedByObj->userType;
                                $log->relationId = $relation->id;

                                switch ($campaignMail) {
                                    case \instance\MRelation::NO_MAILINGS:
                                    case \instance\MRelation::PER_MAIL:
                                        // in deze situatie niets doen, immers
                                        // ontvangt de relatie óf niets óf per mail
                                        break;
                                    case \instance\MRelation::DIGITAL:
                                        // wanneer relatie mailings alleen digitaal ontvangt
                                        // aanpassen naar geen mailings
                                        $relation->object->campaignMail = 0;
                                        $relation->object->update(false);

                                        $log->oldValue = t("DIGITAL");
                                        $log->newValue = t("NO_MAILINGS");
                                        $log->add();

                                        break;
                                    case \instance\MRelation::PER_MAIL_AND_DIGITAL:
                                        // bij zowel per post als digitaal ontvangen
                                        // wijzigen naar per post, immers wil relatie
                                        // niets digitaal meer ontvangen
                                        $relation->object->campaignMail = 1;
                                        $relation->object->update(false);

                                        $log->oldValue = t("PER_MAIL_AND_DIGITAL");
                                        $log->newValue = t("PER_MAIL");
                                        $log->add();
                                        break;
                                }
                            }
                        }

                        break;
                    case 'hard_bounce':
                        // ophalen data & emailadres
                        $data = $model->getData();
                        $email = $data->email;

                        // zoeken naar MRelations waar emailadres aan is gekoppeld
                        $emailAddresses = m::app()->db->query(
                            "
                                SELECT *
                                FROM email_address
                                WHERE address = :email
                                AND archived IS NULL
                            ",
                            [":email" => $email],
                            "\\instance\\MEmailAddress"
                        );

                        if (!empty($emailAddresses)) {
                            foreach ($emailAddresses as $emailAddress) {
                                $emailAddress->archived = 1;
                                $emailAddress->update(false);

                                if (!empty($emailAddress->relationId)) {
                                    $log = new \instance\MLog();
                                    $log->dateTimeCreated = date('Y-m-d H:i:s');
                                    $log->entity = 'MEmailAddress';
                                    $log->action = 'ARCHIVED';
                                    $log->userId = $changedByObj->userId;
                                    $log->userType = $changedByObj->userType;
                                    $log->relationId = $emailAddress->relationId;
                                    $log->oldValue = $emailAddress->address;
                                    $log->newValue = "";
                                    $log->add();
                                }
                            }
                        }
                        break;
                }

                break;
				
	        case 'MRegistrationContactPerson':
	        case 'MRegistrationUbo':
				$registration = $model->getRegistration();
		        // verwerken file upload
		        $changeValue = $change->getChangeValueByProperty('fileNameIdFile');
		        if (!empty($changeValue)) {
			        // temp lokatie portal geuploade bestanden
			        $filename = TEMP_MEDIA_LOCATION . $changeValue->newValue;
			        if (file_exists($filename)) {
				        $mediaId = $this->processMediaForRegistration($filename, $registration, $model->idType);
				        if (!empty($mediaId)) {
					        $model->idMediaId = $mediaId;
					        $model->update();
				        }
			        }
		        }
				break;
	
	        case 'MRegistrationRelation':
		        $registration = $model->getRegistration();
		        // verwerken file upload
		        $changeValue = $change->getChangeValueByProperty('fileNameCocFile');
		        if (!empty($changeValue)) {
			        // temp lokatie portal geuploade bestanden
			        $filename = TEMP_MEDIA_LOCATION . $changeValue->newValue;
			        if (file_exists($filename)) {
				        $mediaId = $this->processMediaForRegistration($filename, $registration, 'chamberOfCommerce');
				        if (!empty($mediaId)) {
					        $model->cocMediaId = $mediaId;
					        $model->update();
				        }
			        }
		        }

                $changeValue = $change->getChangeValueByProperty('uboDeclarationFile');
                if (!empty($changeValue)) {
                    // temp lokatie portal geuploade bestanden
                    $filename = TEMP_MEDIA_LOCATION . $changeValue->newValue;
                    if (file_exists($filename)) {
                        $mediaId = $this->processMediaForRegistration($filename, $registration, 'uboDeclarationFile');
                        if (!empty($mediaId)) {
                            $model->uboDeclarationFileMediaId = $mediaId;
                            $model->update();

                            $uboDeclarationRelation = !empty($registration) && !empty($registration->relation) ? $registration->relation : null;
                            if (!empty($uboDeclarationRelation) && $uboDeclarationRelation->type == 'organization') {
                                $uboDeclarationRelation->object->uboDeclarationFileMediaId = $mediaId;
                                $uboDeclarationRelation->object->update();
                            }
                        }
                    }
                }
				break;
                
	        case 'MRegistration':
				// controleren of kopie van inschrijving moet worden verwerkt
		        // verwerken file upload
		        $changeValue = $change->getChangeValueByProperty('subscriptionFormFile');
		        if (!empty($changeValue)) {
			        // temp lokatie portal geuploade bestanden
			        $filename = TEMP_MEDIA_LOCATION . $changeValue->newValue;
			        if (file_exists($filename)) {
				        $mediaId = $this->processMediaForRegistration($filename, $model, 'subscriptionForm');
				        if (!empty($mediaId)) {
					        $model->subscriptionFormMediaId = $mediaId;
					        $model->update();
				        }
			        }
		        }
		        
		        // controleren of een bestaande belangstelling & reservering bestaat
		        $fervour = $model->getFervour();
		        $reservation = $model->getReservation();
		        if (!empty($fervour)) {
		        	// gelijktrekken notitie van belangstelling met inschrijving
		        	$model->note = $fervour->note;
			        $model->update(false);

                    // bijwerken status van belangstelling
		        	if (!empty($fervour->status !== 4)) {
				        $fervour->status = 4;
				        $fervour->update();
			        }
		        }
		        if (!empty($reservation)) {
			        // gelijktrekken notitie van reservering met inschrijving
			        $model->note = $reservation->note;
			        $model->update(false);

                    // bijwerken status van reservering
		            if (!empty($reservation->status !== 4)) {
			            $reservation->status = 4;
			            $reservation->update();
		            }

                    // indien er tags bestaan voor reservering deze overnemen voor inschrijving
                    if (!empty($reservation->tags)) {
                        foreach ($reservation->tags as $tag) {
                            // toevoegen tag aan inschrijving
                            $registrationTag = new \instance\MRegistrationTag();
                            $registrationTag->registrationId = $model->id;
                            $registrationTag->tagId = $tag->id;
                            $registrationTag->add();
                        }
                    }
		        }
		        
	        	break;
        }
    }

    private function processMedia($tmpFilename, $relation, $type) {
        $fileSize = filesize($tmpFilename);
        $extension = pathinfo($tmpFilename, PATHINFO_EXTENSION);
        $filename = $relation->getName() .'-' . $type . '.' . $extension;

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($tmpFilename);

        $this->writeToLog('Creating new MMedia for new uploaded file');
        $media = new MMedia;
        $media->filename = $filename;
        $media->fileSize = $fileSize;
        $media->fileMimeType = $mimeType;
        $media->creatorUserId = 25;
        $media->type = 'relation';
        $media->timestampCreated = time();
        $media->add();
        $this->writeToLog('Succesfully added MMedia with ID: ' . $media->id);

        $mediaFilePath = str_replace("//", "/", $media->getLocalLocation());
        copy($tmpFilename, $mediaFilePath);
        $this->writeToLog('Copying media to new location');

        if (!file_exists($mediaFilePath)) {
            $media->delete();
            throw new Exception('Unable to write to target folder.');
        } else {
            $this->writeToLog('Succesfully copied media to new location');
            // unlink temp file
            unlink($tmpFilename);
            $this->writeToLog('unlinking temp file');
        }

        // toevoegen document
        $document = new \instance\MDocument();
        $document->description = $type . ' - afkomstig van Mijn-IMMO';
        $document->categoryType = 'document';
        $document->categoryId = null;
        $document->dateTimeCreated = date('Y-m-d H:i:s');
        $document->date = date('Y-m-d');
        $document->fileMediaId = $media->id;
        $document->type = 'relation';
        $document->add();
        $this->writeToLog('Succesfully added MDocument voor media with ID: ' . $document->id);

        $relationDocument = new \instance\MRelationDocument();
        $relationDocument->documentId = $document->id;
        $relationDocument->relationId = $relation->id;
        $relationDocument->add();
        $this->writeToLog('Succesfully added MRelationDocument with ID: ' . $relationDocument->id);
    }

    public function processNewRegistrations() {
        $registrations = m::app()->db->query("
                SELECT *
                FROM `portal_user_registration`
                WHERE dateTimeProcessed IS NULL
                AND errorSent IS NULL
            ",
            [],
            '\instance\MPortalUserRegistration'
        );

        $portalUserService = new \instance\CPortalUserService();
        $mailer = new \instance\CMailer();

        foreach ($registrations as $registration) {
            try {
                $relation = new MRelation($registration->relationId);

                // controleren of er een portal block bestaat voor relatie
                if ($relation->isBlockedFromPortal()) {

                    if (!$relation->getPortalBlock()->notificationSent) {
                        $data = [
                            "relationId" => $relation->id,
                            "note" => $registration->comment
                        ];
                        $result = $this->doPortalApiCall("email", "sendNotificationBlockedRelation", $data);
                        if ($result->success) {
                            $relation->getPortalBlock()->notificationSent = 1;
                            $relation->getPortalBlock()->update();
                        }
                    }

                    // doorgaan met volgende registratie, relatie is immers geblokkeerd van portal
                    continue;
                }

                $relationsToCreatePortalUser = $relation->getRelationsToCreatePortalUsers();

                $createPortalUsersResult = $portalUserService->createPortalUsersForRelations($relationsToCreatePortalUser);
                $portalUsers = $createPortalUsersResult['portalUsers'];

                // door aangemaakte portal users itereren
                foreach ($portalUsers as $portalUser) {
                    // zetten van datum registratie voor portal user
                    $portalUser->timestampRegister = strtotime($registration->dateTimeCreated);
                    $portalUser->update();

                    // updaten van portal user met nieuwe timestamp
                    $apiData = $portalUser->toPostArray();

                    $result = $this->doPortalApiCall('user','update', $apiData);
                    if (!isset($result->success) || $result->success != 1) {
                        throw new \Exception('Error processing change in portal (1)');
                    }
                }

                $dateTimeProcessed = date('Y-m-d H:i:s');
                $registration->dateTimeProcessed = $dateTimeProcessed;
                $registration->update();

                // versturen notificatie registratie
                $apiData = [
                    "relationId" => $relation->id,
                    "note" => $registration->comment
                ];

                $result = $this->doPortalApiCall('email','sendRegisterConfirmation', $apiData);
                if (!isset($result->success) || $result->success != 1) {
                    throw new \Exception('Error processing change in portal (1)');
                }

            } catch (\Exception $e) {

                if (!$registration->errorSent) {
                    $message = "Uitvoeren van nieuwe registratie met ID: ".$registration->id." niet gelukt." . PHP_EOL;
                    $message .= $e->getMessage() . PHP_EOL;
                    $message .= $e->getFile() . PHP_EOL;
                    $message .= $e->getLine() . PHP_EOL;
                    $this->writeToLog($message);

                    $registration->errorSent = 1;
                    $registration->update();
                }

                continue;
            }
        }
    }

    public function doPortalApiCall($endpoint, $action, $data = array()) {

        $url = PORTAL_DOMAIN . "/api?key=".PORTAL_API_KEY . '&endpoint=' . $endpoint . '&action=' . $action;
        /*foreach ($data as $key => $value) {
            $url .= '&'.$key.'='.$value;
        }*/
	    
        $ch = curl_init();
		
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $resultJson = curl_exec($ch);

        if (curl_error($ch) != '') {
            throw new \Exception('Curl error: '.curl_error($ch));
        }

        $decodedResult = json_decode($resultJson);
        if (is_null($decodedResult)) {
            throw new \Exception('Error in result: '.$resultJson);
        } else {
            return $decodedResult;
        }
    }

    private function updateLastSyncDateTime() {
        $this->writeToLog('Updating date and time last sync');

        // initieren nieuwe date time
        $dateTime = new \DateTime();

        $parameter = m::app()->db->querySingle(
            "SELECT * FROM parameter WHERE name = 'dateTimeLastSync'",
            [],
            '\instance\MParameter'
        );

        if (empty($parameter)) {
            $parameter = new \instance\MParameter();
            $parameter->name = 'dateTimeLastSync';
            $parameter->value = $dateTime->format('Y-m-d H:i:s');
            $parameter->add();
        } else {
            $parameter->value = $dateTime->format('Y-m-d H:i:s');
            $parameter->update();
        }

        // kan worden uitgebreid met meer models
        $votelists = m::app()->db->query(
            "
                SELECT *
                FROM votelist
            ",
            [],
            '\instance\MVotelist'
        );
		
		if (!empty($votelists)) {
			foreach ($votelists as $votelist) {
				$votelist->dateTimeLastSync = $dateTime->format('Y-m-d H:i:s');
				$votelist->update();
			}
		}

        // eventueel bestaande parameter voor notificatie verwijderen
        $notificationParameter = m::app()->db->querySingle("SELECT * FROM parameter WHERE name = 'portalSyncNotification' LIMIT 0,1", [], '\\instance\\MParameter');
        if (!empty($notificationParameter)) {
            $notificationParameter->delete();
        }

        $this->writeToLog('Date and time last sync updated');
    }

    public function processRelationAssignedDocuments() {
        // ophalen correspondentie settings waar:
        // relation assigned document null is
        // waarbij document naar portal mag
        // EN waar de correspondentie / email is bevestigd.
        $correspondenceSettings = m::app()->db->query(
            "
                SELECT CS.*
                FROM correspondence_setting CS
                LEFT JOIN document D ON D.id = CS.documentId
                WHERE CS.relationAssignedDocumentId IS NULL
                AND CS.toPortal = 1
                AND D.id IS NOT NULL
            ",
            [],
            'instance\MCorrespondenceSetting'
        );

        foreach ($correspondenceSettings as $correspondenceSetting) {
            $this->writeToLog("Processing MCorrespondenceSetting with ID: " . $correspondenceSetting->id);
            try {
                // controleren of setting voor transactionalMail digitaal of per post en digitaal is
                $relation = new \instance\MRelation($correspondenceSetting->relationId);
                $transactionalMail = $relation->object->transactionalMail;
                if (!in_array($transactionalMail, [\instance\MRelation::DIGITAL, \instance\MRelation::PER_MAIL_AND_DIGITAL])) {
                    continue;
                }

                // instantieren templateItem
                $templateItem = new \instance\MTemplateItem($correspondenceSetting->templateItemId);
                if (!empty($templateItem->getNotificationMail())) {
                    // wanneer gekoppelde notificatiemail nog niet is bevestigd, overslaan
	                // fallback voor wanneer notificatie al is verstuurd, maar RAD niet is aangemaakt
                    if (!in_array($templateItem->getNotificationMail()->emailStatus, [1, 2])) {
                        $this->writeToLog("TemplateItem (notification) with ID: " . $templateItem->id . " not yet confirmed.");
                        continue;
                    }
                }

                // controleren of document bestaat
                $document = new \instance\MDocument($correspondenceSetting->documentId);
                // als document niet bestaat, ook geen relation assigned document aanmaken
                if (!file_exists($document->getLocalLocation())) {
                    $this->writeToLog("Local file for MDocument with ID " . $document->id . " does noet exist.");
                    continue;
                }
                
                // extra controle of RAD niet toch al bestaat voor combinatie document en relatie
	            $documentService = new \instance\CDocumentService();
	            $relationAssignedDocument = $documentService->getRelationAssignedDocument($relation->id, $document->id);
	            if (empty($relationAssignedDocument)) {
		            // bepalen dateTime voor relationAssignedDocument
		            $dateTime = new \DateTime();
		            $dateTime->setTimestamp(strtotime($correspondenceSetting->dateTimeScheduled));
		
		            // toevoegen koppeling document en relatie
		            $relationAssignedDocument = new \instance\MRelationAssignedDocument();
		            $relationAssignedDocument->documentId = $document->id;
		            $relationAssignedDocument->relationId = $correspondenceSetting->relationId;
		            $relationAssignedDocument->isRead = 0;
		            $relationAssignedDocument->notify = 1;
		            $relationAssignedDocument->dateTime = $dateTime->format('Y-m-d H:i:s');
		            $relationAssignedDocument->add();
	            }

                $correspondenceSetting->relationAssignedDocumentId = $relationAssignedDocument->id;
                $correspondenceSetting->update();
            } catch(\Exception $e) {
                if ($correspondenceSetting->radErrorSent != 1) {
                    $message = "Aanmaken RelationAssignedDocument voor correspondentie setting met ID: ".$correspondenceSetting->id." niet gelukt." . PHP_EOL;
                    $message .= $e->getMessage() . PHP_EOL;
                    $message .= $e->getFile() . PHP_EOL;
                    $message .= $e->getLine() . PHP_EOL;
                    $this->writeToLog($message);

                    $correspondenceSetting->radErrorSent = 1;
                    $correspondenceSetting->update();
                }
            }
        }
    }

    public function sendNotificationMails() {
        $this->writeToLog('Start processing notification emails');

        // ophalen alle records notificatie template items
        $notificationTemplateItems = m::app()->db->query(
            "
                SELECT TI1.*
                FROM template_item TI1
                INNER JOIN template_item TI2 ON TI2.notificationTemplateItemId = TI1.id
                INNER JOIN correspondence_setting CS ON CS.templateItemId = TI2.id
                WHERE TI1.emailStatus = 1
                AND CS.toPortal = 1
                AND CS.templateItemId IS NOT NULL
                AND CS.documentId IS NOT NULL
                AND CS.relationAssignedDocumentId IS NOT NULL
                AND CS.dateTimeScheduled <= NOW()
                AND TI2.id NOT IN (
                    SELECT TI3.id
                    FROM template_item TI3
                    INNER JOIN fund_template FT ON FT.id = TI3.fundTemplateId
                    INNER JOIN votelist V ON V.id = FT.votelistId
                    WHERE V.published IS NULL
                    AND V.hasCorrespondence = 1
                )
                AND TI2.archived IS NULL
            ",
            [],
            'instance\MTemplateItem'
        );

		if (!empty($notificationTemplateItems)) {
			foreach ($notificationTemplateItems as $notificationTemplateItem) {
				$correspondenceSetting = $notificationTemplateItem->getCorrespondenceSetting();
				
				// controleren of fysieke pdf bestand wel bestaat op portal, zo niet, geen notificatie versturen
				$result = $this->doPortalApiCall("check", "checkExistingPdf", ['relationAssignedDocumentId' => $correspondenceSetting->relationAssignedDocumentId]);
				if (empty($result) || !isset($result->success) || !$result->success) {
					$this->writeToLog($result->message);
					continue;
				}
				
				try {
					$data = [
						"relationId" => $correspondenceSetting->relationId,
						"description" => $notificationTemplateItem->description,
						"body" => $notificationTemplateItem->body,
						"header" => $notificationTemplateItem->header,
						"mailerTemplateId" => $notificationTemplateItem->mailerTemplateId,
						"notifyAccountManager" => $notificationTemplateItem->notifyAccountManager,
						"emailSenderId" => !empty($notificationTemplateItem->template->emailSender) ? $notificationTemplateItem->template->emailSender->id : null
					];
					
					$result = $this->doPortalApiCall("email", "sendNotificationMail", $data);
					
					if (isset($result->success) && $result->success) {
						$notificationTemplateItem->dateSent = date('Y-m-d H:i:s');
						$notificationTemplateItem->emailStatus = 2;
						$notificationTemplateItem->update();
						
						$this->writeToLog('Notification TemplateItem with ID: ' . $notificationTemplateItem->id . ' has been sent succesfully');
					} else {
						$this->writeToLog('Error sending notification TemplateItem with ID: ' . $notificationTemplateItem->id . ': ' . var_export($result, true));
					}
				} catch (\Exception $e) {
					if ($correspondenceSetting->notificationErrorSent != 1) {
						$message = "Versturen notificatiemail voor correspondentie setting met ID: " . $correspondenceSetting->id . " niet gelukt." . PHP_EOL;
						$message .= $e->getMessage() . PHP_EOL;
						$message .= $e->getFile() . PHP_EOL;
						$message .= $e->getLine() . PHP_EOL;
						$this->writeToLog($message);
						
						$correspondenceSetting->notificationErrorSent = 1;
						$correspondenceSetting->update();
					}
				}
			}
		}

        $this->writeToLog('End processing notification emails');
    }

    public function getResponse() {
        return $this->response;
    }

    private function writeToLog($message) {
        file_put_contents(
            $this->logFile,
            date('d-m-Y H:i:s') . ' ' . $message . PHP_EOL,
            FILE_APPEND
        );
    }

    public function handleShutdownError() {
        $this->writeToLog('Start handle shutdown error');

        $lastError = error_get_last();
        $errorString = $lastError;
        if (is_array($lastError)) {
            foreach ($lastError as $key => $message) {
                $errorString .= $message . PHP_EOL;
            }
        }

        $this->writeToLog($errorString);
        $this->writeToLog('End handle shutdown error');
    }

    public function countFiles ($dir) {
        $files = new \FilesystemIterator($dir, \FilesystemIterator::SKIP_DOTS);
        $filter = new \CallbackFilterIterator($files, function($cur, $key, $iter){
            return $cur->isFile();
        });

        $this->writeToLog(iterator_count($filter) . " PDF's to process");
    }
    
    private function processMediaForRegistration($tmpFilename, $registration, $type) {
	    $fileSize = filesize($tmpFilename);
	    $extension = pathinfo($tmpFilename, PATHINFO_EXTENSION);
	    $filename = $registration->registrationCard->relation->name . ' - ' . t(strtoupper(h::toSnakeCase($type))) . '.' . $extension;
	    $finfo = new \finfo(FILEINFO_MIME_TYPE);
	    $mimeType = $finfo->file($tmpFilename);
	
	    $this->writeToLog('Creating new MMedia for new uploaded file');
	    $media = new MMedia;
	    $media->filename = $filename;
	    $media->fileSize = $fileSize;
	    $media->fileMimeType = $mimeType;
	    $media->creatorUserId = 25;
	    $media->type = 'registration';
	    $media->timestampCreated = time();
	    $media->add();
	    $this->writeToLog('Succesfully added MMedia with ID: ' . $media->id);
	
	    $mediaFilePath = str_replace("//", "/", $media->getLocalLocation());
	    copy($tmpFilename, $mediaFilePath);
	    $this->writeToLog('Copying media to new location');
	
	    if (!file_exists($mediaFilePath)) {
		    $media->delete();
		    throw new Exception('Unable to write to target folder.');
	    } else {
		    $this->writeToLog('Succesfully copied media to new location');
		    // unlink temp file
		    unlink($tmpFilename);
		    $this->writeToLog('unlinking temp file');
	    }
	    
	    return $media->id;
    }
	
	private function getMediaExportFiles() {
		$this->writeToLog('Getting list of files which will be exporterd from portal to CRM');
		$result = $this->doPortalApiCall("check", "checkMediaExportFiles", []);
		
		if ($result->success) {
			$this->writeToLog('List of files which will be exporterd from portal to CRM was retrieved succesfully');
			return !empty($result->files) ? $result->files : [];
		} else {
			$this->writeToLog("Wasn't able to get the list of files which will be exporterd from portal to CRM");
			return [];
		}
	}
	
	private function cleanupMediaExportFiles($mediaExportFiles) {
		$this->writeToLog('Start cleaning up exported media in portal environment');
		$filesToDelete = [];
		
		$dir = TEMP_MEDIA_LOCATION;
		if (is_dir($dir)) {
			$files = scandir($dir);
			if (!empty($files)) {
				foreach ($files as $file) {
					// mappen overslaan
					if (in_array($file, ['.', '..'])) { continue; }
					
					// controleren of file size ook acceptabel is, zo niet ook overslaan. Mogelijk iets mis gegaan met downloaden
					$filesize = filesize($dir.$file);
					
					// controleren of gevonden file bestaat in $mediaExportFiles
					if (($key = array_search($file, array_map(function($mediaExportFile) { return $mediaExportFile->filename; }, $mediaExportFiles))) !== false) {
						// controleren of lokale bestandsgrote overeenkomt met die van de portal
						if ($mediaExportFiles[$key]->filesize == $filesize) {
							$filesToDelete[] = $file;
						} else {
							// bestandsgrote komt niet overeen, verwijder?
							//unlink($dir.$file);
						}
					}
				}
			}
		}
		
		if (!empty($filesToDelete)) {
			$data = [
				'mediaExportFiles' => http_build_query($filesToDelete),
			];
			$this->writeToLog('Delete data for cleaning up exported media files in portal environment. These files were transferred succesfully. Data: ' . var_export($data, true));
			$result = $this->doPortalApiCall("delete", "deleteMediaExportFiles", $data);
		}
		$this->writeToLog('End cleaning up exported media in portal environment');
	}

    public function getLockFile() {
        return $this->lockFile;
    }
}