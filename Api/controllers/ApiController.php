<?php
namespace app\hig;

use \app\h;
use \app\m;

class ApiController {
    protected $action;
    protected $endpoint;
    protected $key;
    protected $redirect;

    /** @var \app\hig\CEmailService */
    protected $emailService;
    /** @var \app\hig\CUserService */
    protected $userService;

    public function __construct() {
        $this->key = PORTAL_API_KEY;
        $this->emailService = m::app()->serviceManager->get('emailService');
        $this->userService = m::app()->serviceManager->get('userService');
    }

    /**
    * Wijzigen van User, extern ge?nitieerd
    * @param $app \app\CApp
    * @return json
    */
    public function actionProcess($app) {
        $endpoint = h::getV("endpoint", "any", "", "GET", false);
        $apiKey = h::getV("key", "any", "", "GET", false);
        $action = h::getV("action", "any", "", "GET", false);
        $redirect = h::getV("redirect", "any", "", "GET", false);

        if (empty($endpoint)) {
            throw new \Exception("No endpoint set");
        }
        if (empty($apiKey) || $apiKey !== PORTAL_API_KEY) {
            throw new \Exception("No correct api key found");
        }

        $this->action = $action;
        $this->endpoint = $endpoint;
        $this->redirect = $redirect;

        switch ($this->endpoint) {
            case 'email':
                $this->processEmail($app);
                break;
            case 'relation':
                $this->processRelation($app);
                break;
            case 'user':
                $this->processUser($app);
                break;
            case 'user_registration':
                $this->processUserRegistration($app);
                break;
            case 'user_relation':
                $this->processUserRelation($app);
                break;
            case 'user_relation_environment':
                $this->processUserRelationEnvironment($app);
                break;
            case 'user_role':
                $this->processUserRole($app);
                break;
            case 'token':
                $this->processLoginToken($app);
                break;
            case 'document':
                $this->processDocument($app);
                break;
            case 'change':
                $this->processChange($app);
                break;
            case 'changeValue':
                $this->processChangeValue($app);
                break;
            case 'webhooks':
                $this->processWebhooks($app);
                break;
	        case 'connectivity':
	        	$this->processConnectivity($app);
	        	break;
            case 'check':
                $this->processCheck($app);
                break;
            case 'registration':
                $this->processRegistration($app);
                break;
            case 'registration_relation':
                $this->processRegistrationRelation($app);
                break;
            case 'registration_contact_person':
                $this->processRegistrationContactPerson($app);
                break;
            case 'registration_contact_person_phone_number':
                $this->processRegistrationContactPersonPhoneNumber($app);
                break;
            case 'registration_contact_person_address':
                $this->processRegistrationContactPersonAddress($app);
                break;
            case 'registration_bank_account':
                $this->processRegistrationBankAccount($app);
                break;
            case 'registration_address':
                $this->processRegistrationAddress($app);
                break;
            case 'registration_ubo':
                $this->processRegistrationUbo($app);
                break;
            case 'delete':
                $this->processDelete($app);
                break;
        }
    }

    /**
    * Email endpoint afhandelen
    *
    * @param $app \app\CApp
    */

    private function processEmail($app) {

        $result = [
            'success' => 1,
            'message' => ''
        ];

        // default zetten van mailerTemplateId
        $this->emailService->setMailerTemplateId(EMAIL_TEMPLATE_ID_DEFAULT);

        switch ($this->action) {
            case 'sendExtraUserEmail':
                $emailAddress = h::getV("emailAddress", "email", "", "POST");
                $userId = h::getV("userId", "int", -1, "POST");
                $createdById = h::getV("createdById", "int", -1, "POST");
                $relationId = h::getV("relationId", "int", -1, "POST");
                $password = h::getV("password", "any", "", "POST");
                $emailType = h::getV("emailType", "any", "", "POST");

                $user = new \app\MUser($userId);
                $relation = new \app\hig\MRelation($relationId);

                $user->setSelectedRelation($relation);

                $changeByUser = new \app\MUser($createdById);

                $additionalParams = [
                    '{password}' => $password,
                    '{url}' => DOMAIN,
                ];

                $this->emailService->setUser($changeByUser);
                $this->emailService->setRelation($relation);

                // verwerken email
                $this->emailService->processNotificationMail(
                    $emailType,
                    null,
                    $user,
                    null,
                    $additionalParams
                );
                break;

            case 'sendSyncNotification':
                // verwerken email
                $this->emailService->processNotificationMail(
                    'sync'
                );
                break;

            case 'sendNotificationBlockedRelation':
                // verwerken email
                $note = h::getV("note", "any", "", "POST");
                $relationId = h::getV("relationId", "int", -1, "POST");
                $relation = new \app\hig\MRelation($relationId);

                $this->emailService->setRelation($relation);

                $this->emailService->processNotificationMail(
                    'registration-blocked-relation',
                    null,
                    null,
                    null,
                    ["{note}" => $note]
                );
                break;

            case 'sendNotificationAdulthood':
                $relationId = h::getV("relationId", "int", -1, "POST");
                $relation = new \app\hig\MRelation($relationId);

                $this->emailService->setRelation($relation);

                // verwerken email
                $this->emailService->processNotificationMail(
                    'adulthood'
                );
                break;

            case 'sendOldManagerConfirmation':
                $relationId = h::getV("relationId", "any", "", "POST");
                $userId = h::getV("userId", "any", "", "POST");

                $relation = new \app\hig\MRelation($relationId);
                // zetten van relatie voor users bij relatie (= fallback voor niet kunnne ophalen selectedRelation)
                foreach ($relation->getUsers() as $user) {
                    $user->setSelectedRelation($relation);
                }

                $user = new \app\MUser($userId);

                // verwerken email
                $this->emailService->setUser($user);
                $this->emailService->setRelation($relation);
                $this->emailService->processNotificationMail(
                    'old-manager-confirmation'
                );

                // archiveren oude bestuurder
                $user->delete();

                break;
            case 'sendBankAccountConfirmation':
                $relationId = h::getV("relationId", "any", "", "POST");
                $iban = h::getV("iban", "any", "", "POST");
                $bic = h::getV("bic", "any", "", "POST");
                $relationNumber = h::getV("relationNumber", "any", "", "POST");
                $ascription = h::getV("ascription", "any", "", "POST");

                // zetten van relatie
                $relation = new \app\hig\MRelation($relationId);
                // zetten van relatie voor users bij relatie (= fallback voor niet kunnne ophalen selectedRelation)
                foreach ($relation->getUsers() as $user) {
                    $user->setSelectedRelation($relation);
                }

                $bankAccount = new \app\hig\MBankAccount();
                $bankAccount->iban = $iban;
                $bankAccount->bic = $bic;
                $bankAccount->relationNumber = $relationNumber;
                $bankAccount->ascription = $ascription;

                // verwerken email
                $this->emailService->setRelation($relation);
                $this->emailService->processNotificationMail(
                    'bankAccount-confirmation',
                    null,
                    $bankAccount
                );
                break;

            case 'sendNotificationMail':
                $relationId = h::getV("relationId", "any", "", "POST");
                $mailerTemplateId = h::getV("mailerTemplateId", "int", -1, "POST");
                $emailSenderId = h::getV("emailSenderId", "int", -1, "POST");
                $notifyAccountManager = h::getV("notifyAccountManager", "int", 0, "POST", false);
                $relation = new \app\hig\MRelation($relationId);

                // zetten van relatie voor users bij relatie (= fallback voor niet kunnne ophalen selectedRelation)
                foreach ($relation->getUsers() as $user) {
                    $user->setSelectedRelation($relation);
                }

                $description = h::getV("description", "any", "", "POST");
                $body = h::getV("body", "any", "", "POST");
                $header = h::getV("header", "any", "", "POST");

                $params = [
                    'description' => $description,
                    'body' => $body,
                    'header' => $header,
	                'notifyAccountManager' => $notifyAccountManager,
                ];

                // verwerken email
                $this->emailService->setRelation($relation);
                $this->emailService->setMailerTemplateId($mailerTemplateId);
    
                $emailSender = !empty($emailSenderId) && $emailSenderId != -1 ? new \app\hig\MEmailSender($emailSenderId) : null;
                if (!empty($emailSender)) {
                    $this->emailService->setEmailSender($emailSender);
                }

                $this->emailService->processNotificationMail(
                    'notification',
                    null,
                    null,
                    null,
                    $params
                );
                break;

            case 'sendRegisterConfirmation':
                $note = h::getV("note", "any", "", "POST");
                $relationId = h::getV("relationId", "any", "", "POST");
                $relation = new \app\hig\MRelation($relationId);

                $this->emailService->setRelation($relation);
                $this->emailService->processNotificationMail(
                    'register',
                    null,
                    null,
                    null,
                    ["{note}" => $note]
                );
                break;
        }

        $app->renderJSON($result);


    }

    /**
    * Relation endpoint afhandelen
    *
    * @param $app \app\CApp
    */
    private function processRelation($app) {
        $relationId = h::getV("relationId", "int", -1, "POST");
        $relation = null;

        switch ($this->action) {
            case 'checkRelationExists':
                /** @var \app\hig\CRelationService */
                $relationService = $app->serviceManager->get('relationService');

                $relationNameSortable = h::getV("relationNameSortable", "any", "", "POST");

                $relation = $relationService->findRelationByIdAndNameSortable($relationId, $relationNameSortable);
                break;
        }

        $app->renderJSON([
            'success' => 1,
            'message' => '',
            'relation' => $relation
        ]);
    }

    /**
    * User endpoint afhandelen
    *
    * @param $app \app\CApp
    */

    private function processUser($app) {
        $postUser = h::getV("User", "array", [], "POST");

        // instantieren user
        $user = new \app\MUser();
        if(!empty($postUser['id'])){
            $user = new \app\MUser($postUser['id']);
        }

        switch ($this->action) {
            case 'add':
            case 'update':
            case 'delete':
                $user->fillFromArray($postUser);

                // timestamps vullen obv status
                switch ($user->status) {
                    case 'inactive':
                        $user->timestampInactive = date('U');
                        break;
                    case 'extra-user-created':
                    case 'correspondence-created':
                        if (is_null($user->timestampCorrespondenceCreated)) {
                            $user->timestampCorrespondenceCreated = date('U');
                        }
                        break;
                    case 'login-changed':
                        if (is_null($user->timestampLoginChanged)) {
                            $user->timestampLoginChanged = date('U');
                        }
                        break;
                    case 'active':
                        $user->timestampActive = date('U');
                        break;
                }

                $user->{$this->action}();
                break;
            case 'updateSendNotification':

                if (isset($postUser['relations'])) {
                    $relations = json_decode($postUser['relations']);
                    foreach($relations as $relationId => $values) {
                        $userRelationEnvironment = $this->userService->findUserRelationEnvironment($user->id, $relationId);
                        $userRelationEnvironment->sendNotification = $values->sendNotification;
                        $userRelationEnvironment->update();
                    }
                }

                break;
            case 'get':
                // aanvullende acties?
                break;
        }

        $result = [
            'success' => 1,
            'message' => '',
            'userId' => $user->id,
            'user' => [
                'status' => $user->status
            ]
        ];


        $app->renderJSON($result);
    }

    /**
    * User Registration endpoint afhandelen
    *
    * @param $app \app\CApp
    */
    private function processUserRegistration($app) {
        $userRegistrationId = h::getV("userRegistrationId", "int", -1, "POST");
        $dateTimeProcessed = h::getV("dateTimeProcessed", "any", "", "POST");
        $errorSent = h::getV("errorSent", "any", "null", "POST");

        switch ($this->action) {
            case 'update':
                $userRegistration = new \app\hig\MUserRegistration($userRegistrationId);
                $userRegistration->dateTimeProcessed = empty($dateTimeProcessed) ? null : $dateTimeProcessed;
                $userRegistration->errorSent = empty($errorSent) ? null : $errorSent;
                $userRegistration->update();
            break;
        }

        $app->renderJSON([
            'success' => 1,
            'message' => ''
        ]);
    }

    /**
    * User Relation endpoint afhandelen
    *
    * @param $app \app\CApp
    */
    private function processUserRelation($app) {
        $userId = h::getV("userId", "int", -1, "POST");
        $relationId = h::getV("relationId", "int", -1, "POST");
        $userRelationId = h::getV("userRelationId", "int", -1, "POST");

        $userRelation = null;

        switch ($this->action) {
            case 'add':
                $userRelation = new \app\hig\MUserRelation();
                $userRelation->userId = $userId;
                $userRelation->relationId = $relationId;
                $userRelation->add();
            break;
            case 'update':
                $userRelation = new \app\hig\MUserRelation($userRelationId);
                $userRelation->userId = $userId;
                $userRelation->relationId = $relationId;
                $userRelation->update();
            break;
            case 'delete':
                $userRelation = new \app\hig\MUserRelation($userRelationId);
                $userRelation->delete();
            break;
            case 'checkUserExists':
                /** @var \app\hig\CUserService */
                $userService = $app->serviceManager->get('userService');

                $userRelation = $userService->findUserRelationForRelationId($relationId);
            break;
        }

        $app->renderJSON([
            'success' => 1,
            'message' => '',
            'userRelation' => $userRelation
        ]);
    }

    /**
    * User Relation Environment endpoint afhandelen
    *
    * @param $app \app\CApp
    */
    private function processUserRelationEnvironment($app) {
        $userRelationEnvironmentId = h::getV("userRelationEnvironmentId", "int", -1, "POST");
        $userId = h::getV("userId", "int", 0, "POST");
        $relationId = h::getV("relationId", "int", 0, "POST");
        $roleId = h::getV("roleId", "int", 0, "POST");
        $emailSend = h::getV("emailSend", "int", 0, "POST");
        $sendNotification = h::getV("sendNotification", "int", 0, "POST");
        $active = h::getV("active", "int", 0, "POST");

        /** @var \app\hig\CUserService */
        $userService = $app->serviceManager->get('userService');

        $userRelationEnvironment = null;

        switch ($this->action) {
            case 'add':
                $userRelationEnvironment = new \app\hig\MUserRelationEnvironment();
                $userRelationEnvironment->userId = $userId;
                $userRelationEnvironment->relationId = $relationId;
                $userRelationEnvironment->roleId = $roleId;
                $userRelationEnvironment->emailSend = empty($emailSend) ? null : $emailSend;
                $userRelationEnvironment->sendNotification = empty($sendNotification) ? null : $sendNotification;
                $userRelationEnvironment->active = is_null($active) ? 0 : $active;
                $userRelationEnvironment->add();
            break;
            case 'update':
                $userRelationEnvironment = new \app\hig\MUserRelationEnvironment($userRelationEnvironmentId);
                $userRelationEnvironment->userId = $userId;
                $userRelationEnvironment->relationId = $relationId;
                $userRelationEnvironment->roleId = $roleId;
                $userRelationEnvironment->emailSend = empty($emailSend) ? null : $emailSend;
                $userRelationEnvironment->sendNotification = empty($sendNotification) ? null : $sendNotification;
                $userRelationEnvironment->active = is_null($active) ? 0 : $active;
                $userRelationEnvironment->update();
            break;
            case 'checkUserRelationEnvironmentExists':
                $userRelationEnvironment = $userService->findUserRelationEnvironment($userId, $relationId);
            break;
            case 'delete':
                $userService->deleteUserFromRelationEnvironment($userId,$relationId);
            break;
        }

        $app->renderJSON([
            'success' => 1,
            'message' => '',
            'userRelationEnvironment' => $userRelationEnvironment
        ]);
    }

    /**
    * User Role endpoint afhandelen
    *
    * @param $app \app\CApp
    */
    private function processUserRole($app) {
        $userId = h::getV("userId", "int", -1, "POST");
        $roleId = h::getV("roleId", "int", -1, "POST");

        switch ($this->action) {
            case 'add':
                $userRole = new \app\MUserRole();
                $userRole->userId = $userId;
                $userRole->roleId = $roleId;
                $userRole->add();
            break;
        }

        $app->renderJSON([
            'success' => 1,
            'message' => ''
        ]);
    }

    private function processLoginToken($app) {
        $userId = h::getV("userId", "int", -1, "POST");
        $crmUserId = h::getV("crmUserId", "int", -1, "POST");

        $user = new \app\MUser($userId);
        $user->generateLoginToken();
        $user->loginTokenCrmUserId = $crmUserId;
        $user->loginTokenDateTimeCrmUserLogin = date('Y-m-d H:i:s');
        $user->save();

        $result = [
            'success' => 1,
            'message' => '',
            'data' => [
                'loginToken' => $user->loginToken,
                'dateTimeValid' => $user->dateTimeValidLoginToken,
            ]
        ];

        $app->renderJSON($result);
    }

    private function processDocument($app) {
        $result = [
            'success' => 1,
            'message' => ''
        ];

        switch ($this->action) {
            case 'delete':
                $documentId = h::getV("documentId", 'int', -1, "POST");
                try {
                    $document = new \app\hig\MDocument($documentId);
                    $document->delete(false);
                } catch (\Exception $e) {
                    $result['success'] = 0;
                    $result['message'] = 'We waren niet in staat de gekoppelde PDF te verwijderen. Probeer het nogmaals';
                }

                break;
        }

        $app->renderJSON($result);
    }

    /**
    * Functie voor het verwerken van changes (initieel alleen per datum wijzigingen
    * voor adressen) vanuit het CRM
    *
    * In result terug geven van change Id
    *
    * @param mixed $app
    */
    private function processChange($app) {
        $result = [
            'success' => 1,
            'message' => '',
            'changeId' => null
        ];

        switch ($this->action) {
            case 'add':
                $entityId = h::getV("entityId", 'int', null, "POST");
                $entity = h::getV("entity", 'any', null, "POST");
                $type = h::getV("type", 'any', null, "POST");
                $status = h::getV("status", 'any', null, "POST");
                $relationId = h::getV("relationId", 'int', -1, "POST");
                $dateTimeScheduled = h::getV("dateTimeScheduled", 'any', null, "POST");
                $createdById = h::getV("createdById", 'any', null, "POST");

                $change = new \app\hig\MChange();
                $change->entity = $entity;
                $change->entityId = $entityId;
                $change->type = $type;
                $change->status = $status;
                $change->relationId = $relationId;
                $change->createdById = null;
                $change->dateTimeScheduled = $dateTimeScheduled;
                $change->add();

                $changeValue = new \app\hig\MChangeValue();
                $changeValue->changeId = $change->id;
                $changeValue->property = 'loggedInCrmUserId';
                $changeValue->oldValue = null;
                $changeValue->newValue = $createdById;
                $changeValue->add();

                $result['changeId'] = $change->id;

                break;

            case 'delete':
                $changeId = h::getV("changeId", 'int', null, "POST");
                $change = new \app\hig\MChange($changeId);
                $change->delete();

                break;
        }

        $app->renderJSON($result);
    }

    /**
    * Functie voor het verwerken van change values (initieel alleen per datum wijzigingen
    * voor adressen) vanuit het CRM
    *
    * In result terug geven van changeValue Id
    *
    * @param mixed $app
    */
    private function processChangeValue($app) {
        $result = [
            'success' => 1,
            'message' => '',
            'changeValueId' => null
        ];

        switch ($this->action) {
            case 'add':
                $changeId = h::getV("changeId", 'int', null, "POST");
                $property = h::getV("property", 'any', null, "POST");
                $oldValue = h::getV("oldValue", 'any', null, "POST");
                $newValue = h::getV("newValue", 'any', null, "POST");

                $changeValue = new \app\hig\MChangeValue();
                $changeValue->changeId = $changeId;
                $changeValue->property = $property;
                $changeValue->oldValue = $oldValue;
                $changeValue->newValue = $newValue;
                $changeValue->add();

                $result['changeValueId'] = $changeValue->id;

                break;
        }

        $app->renderJSON($result);
    }

    private function processWebhooks($app) {

        $json = file_get_contents('php://input');
        $post = json_decode($json);

        $eventId = isset($post->id) ? $post->id : null;
        $campaignId = isset($post->Camp_id) ? $post->Camp_id : null;
        $campaignName = isset($post->{"campaign name"}) ? $post->{"campaign name"} : null;
        $email = isset($post->email) ? $post->email : null;
        $event = isset($post->Event) ? $post->Event : null;
        $tag = isset($post->Tag) ? $post->Tag : null;
        $listIds = isset($post->list_id) ? $post->list_id : null;
        $dateSent = isset($post->date_sent) ? $post->date_sent : null;
        $dateEvent = isset($post->date_event) ? $post->date_event : null;

        $result = [
            'success' => 1,
            'message' => ""
        ];

        $url = DOMAIN . '?endpoint=' . $this->endpoint . '&action=' . $this->action;

        // initieren data object
        $data = [
            'eventId' => $eventId,
            'campaignId' => $campaignId,
            'campaignName' => $campaignName,
            'email' => $email,
            'event' => $event,
            'tag' => $tag,
            'listIds' => $listIds,
            'dateSent' => $dateSent,
            'dateEvent' => $dateEvent
        ];

        try {
            // opslaan change
            $change = new \app\hig\MChange();
            $change->entity = 'MWebHook';
            $change->entityId = null;
            $change->type = 'add';
            $change->status = 'no-approval-needed';
            $change->relationId = null;
            $change->createdById = 2; // mailing gebruiker
            $change->add();

            // opslaan change values
            $changeValue = new \app\hig\MChangeValue();
            $changeValue->changeId = $change->id;
            $changeValue->property = 'data';
            $changeValue->newValue = json_encode($data);
            $changeValue->add();

            $changeValue = new \app\hig\MChangeValue();
            $changeValue->changeId = $change->id;
            $changeValue->property = 'url';
            $changeValue->newValue = $url;
            $changeValue->add();

            $changeValue = new \app\hig\MChangeValue();
            $changeValue->changeId = $change->id;
            $changeValue->property = 'event';
            $changeValue->newValue = $event;
            $changeValue->add();

        } catch (\Exception $e) {
            $result['success'] = 0;
            $result['message'] = 'Er ging iets mis bij het opslaan van de Webhook change.';
        }


        $app->renderJSON($result);
    }
    
    private function processConnectivity($app) {
	    $result = [
		    'success' => 1,
		    'message' => 'Reachable',
	    ];
	
	
	    $app->renderJSON($result);
    }
    
    private function processCheck($app) {
        $result = [
            'success' => 1,
            'message' => ''
        ];
        
        switch ($this->action) {
            case 'checkExistingPdf':
                $relationAssignedDocumentId = h::getV("relationAssignedDocumentId", 'int', -1, "POST");
                try {
                    $relationAssignedDocument = new \app\hig\MRelationAssignedDocument($relationAssignedDocumentId);
                } catch (\Exception $e) {
                    $result['success'] = 0;
                    $result['message'] = 'MRelationAssignedDocument met ID '.$relationAssignedDocumentId.' bestaat niet.';
                }
    
                if (
                    !empty($relationAssignedDocument) &&
                    !empty($relationAssignedDocument->document) &&
                    !file_exists($relationAssignedDocument->document->getLocalLocation())
                ) {
                    $result['success'] = 0;
                    $result['message'] = 'PDF bestand voor MRelationAssignedDocument met ID '.$relationAssignedDocumentId.' bestaat (nog) niet.';
                }
            
                break;
            case 'checkMediaExportFiles':
                $mediaExportFiles = [];
                
                // ophalen alle bestanden die klaar staan in export map om te worden gedownload
                $dir = DOC_STORAGE_LOCATION . 'temp/export/';
                if (is_dir($dir)) {
                    $files = scandir($dir);
                    if (!empty($files)) {
                        foreach ($files as $file) {
                            if (in_array($file, ['.', '..'])) { continue; }
                            $mediaExportFiles[] = [
                                'filename' => $file,
                                'filesize' => filesize($dir.$file)
                            ];
                        }
                    }
                    $result['files'] = $mediaExportFiles;
                } else {
                    $result['success'] = 0;
                    $result['message'] = $dir . ' is geen bekende map. Kon actie niet uitvoeren';
                }
                break;
        }
    
        $app->renderJSON($result);
    }
    
    /**
     * Endpoint voor afhandelen van het verwijderen van entiteiten
     *
     * @param \app\Capp $app
     */
    private function processDelete($app) {
        $result = [
            'success' => 1,
            'message' => ''
        ];
        
        switch ($this->action) {
            case 'deleteMediaExportFiles':
                $str = h::getV("mediaExportFiles", 'any', null, "POST");
                if (!empty($str)) {
                    parse_str($str, $files);
                    $dir = DOC_STORAGE_LOCATION . 'temp/export/';
                    foreach ($files as $file) {
                        if (file_exists($dir . $file)) {
                            unlink($dir . $file);
                        }
                    }
                } else {
                    $result['success'] = 0;
                    $result['message'] = 'Geen opgegeven bestanden gevonden.';
                }
                break;
        }
    
        $app->renderJSON($result);
    }
    

    /**
     * Registration endpoint afhandelen
     *
     * @param $app \app\CApp
     */

    private function processRegistration($app) {
        $postRegistration = h::getV("Registration", "array", [], "POST");

        // instantieren registration
        $registration = new \app\hig\MRegistration();
        if(!empty($postRegistration['id'])){
            $registration = new \app\hig\MRegistration($postRegistration['id']);
        }

        switch ($this->action) {
            case 'add':
            case 'update':
            case 'delete':
                $registration->fillFromArray($postRegistration);

                // addRelated, updatedRelated en deleteRelated uitschakelen zodat er geen aanvullende acties/changes worden aangemaakt
                $relatedFunctionBoolean = $this->action .'Related';
                $registration->$relatedFunctionBoolean = false;

                $registration->{$this->action}(false);
                break;
        }

        $result = [
            'success' => 1,
            'message' => '',
            'registrationId' => $registration->id
        ];

        $app->renderJSON($result);
    }

    /**
     * Registration Relation endpoint afhandelen
     *
     * @param $app \app\CApp
     */

    private function processRegistrationRelation($app) {
        $postRegistrationRelation = h::getV("RegistrationRelation", "array", [], "POST");

        // instantieren registration relation
        $registrationRelation = new \app\hig\MRegistrationRelation();
        if(!empty($postRegistrationRelation['id'])){
            $registrationRelation = new \app\hig\MRegistrationRelation($postRegistrationRelation['id']);
        }

        switch ($this->action) {
            case 'add':
            case 'update':
            case 'delete':
                $registrationRelation->fillFromArray($postRegistrationRelation);

                // addRelated, updatedRelated en deleteRelated uitschakelen zodat er geen aanvullende acties/changes worden aangemaakt
                $relatedFunctionBoolean = $this->action .'Related';
                $registrationRelation->$relatedFunctionBoolean = false;

                $registrationRelation->{$this->action}(false);
                break;
        }

        $result = [
            'success' => 1,
            'message' => '',
            'registrationRelationId' => $registrationRelation->id
        ];

        $app->renderJSON($result);
    }
    
    /**
     * Registration ContactPerson endpoint afhandelen
     *
     * @param $app \app\CApp
     */

    private function processRegistrationContactPerson($app) {
        $postRegistrationContactPerson = h::getV("RegistrationContactPerson", "array", [], "POST");

        // instantieren registration phone number
        $registrationContactPerson = new \app\hig\MRegistrationContactPerson();
        if(!empty($postRegistrationContactPerson['id'])){
            $registrationContactPerson = new \app\hig\MRegistrationContactPerson($postRegistrationContactPerson['id']);
        }

        switch ($this->action) {
            case 'add':
            case 'update':
            case 'delete':
                $registrationContactPerson->fillFromArray($postRegistrationContactPerson);

                // addRelated, updatedRelated en deleteRelated uitschakelen zodat er geen aanvullende acties/changes worden aangemaakt
                $relatedFunctionBoolean = $this->action .'Related';
                $registrationContactPerson->$relatedFunctionBoolean = false;

                $registrationContactPerson->{$this->action}(false);
                break;
        }

        $result = [
            'success' => 1,
            'message' => '',
            'registrationContactPersonId' => $registrationContactPerson->id
        ];

        $app->renderJSON($result);
    }
        
    /**
     * Registration ContactPersonPhoneNumber endpoint afhandelen
     *
     * @param $app \app\CApp
     */

    private function processRegistrationContactPersonPhoneNumber($app) {
        $postRegistrationContactPersonPhoneNumber = h::getV("RegistrationContactPersonPhoneNumber", "array", [], "POST");

        // instantieren registration phone number
        $registrationContactPersonPhoneNumber = new \app\hig\MRegistrationContactPersonPhoneNumber();
        if(!empty($postRegistrationContactPersonPhoneNumber['id'])){
            $registrationContactPersonPhoneNumber = new \app\hig\MRegistrationContactPersonPhoneNumber($postRegistrationContactPersonPhoneNumber['id']);
        }

        switch ($this->action) {
            case 'add':
            case 'update':
            case 'delete':
                $registrationContactPersonPhoneNumber->fillFromArray($postRegistrationContactPersonPhoneNumber);

                // addRelated, updatedRelated en deleteRelated uitschakelen zodat er geen aanvullende acties/changes worden aangemaakt
                $relatedFunctionBoolean = $this->action .'Related';
                $registrationContactPersonPhoneNumber->$relatedFunctionBoolean = false;

                $registrationContactPersonPhoneNumber->{$this->action}(false);
                break;
        }

        $result = [
            'success' => 1,
            'message' => '',
            'registrationContactPersonPhoneNumberId' => $registrationContactPersonPhoneNumber->id
        ];

        $app->renderJSON($result);
    }
    
    /**
     * Registration ContactPersonAddress endpoint afhandelen
     *
     * @param $app \app\CApp
     */
    
    private function processRegistrationContactPersonAddress($app) {
        $postRegistrationContactPersonAddress = h::getV("RegistrationContactPersonAddress", "array", [], "POST");
        
        // instantieren registration phone number
        $registrationContactPersonAddress = new \app\hig\MRegistrationContactPersonAddress();
        if(!empty($postRegistrationContactPersonAddress['id'])){
            $registrationContactPersonAddress = new \app\hig\MRegistrationContactPersonAddress($postRegistrationContactPersonAddress['id']);
        }
        
        switch ($this->action) {
            case 'add':
            case 'update':
            case 'delete':
                $registrationContactPersonAddress->fillFromArray($postRegistrationContactPersonAddress);
                
                // addRelated, updatedRelated en deleteRelated uitschakelen zodat er geen aanvullende acties/changes worden aangemaakt
                $relatedFunctionBoolean = $this->action .'Related';
                $registrationContactPersonAddress->$relatedFunctionBoolean = false;
        
                $registrationContactPersonAddress->{$this->action}(false);
                break;
        }
        
        $result = [
            'success' => 1,
            'message' => '',
            'registrationContactPersonAddressId' => $registrationContactPersonAddress->id
        ];
        
        $app->renderJSON($result);
    }
    
    /**
     * Registration BankAccount endpoint afhandelen
     *
     * @param $app \app\CApp
     */

    private function processRegistrationBankAccount($app) {
        $postRegistrationBankAccount = h::getV("RegistrationBankAccount", "array", [], "POST");

        // instantieren registration bank account
        $registrationBankAccount = new \app\hig\MRegistrationBankAccount();
        if(!empty($postRegistrationBankAccount['id'])){
            $registrationBankAccount = new \app\hig\MRegistrationBankAccount($postRegistrationBankAccount['id']);
        }

        switch ($this->action) {
            case 'add':
            case 'update':
            case 'delete':
                $registrationBankAccount->fillFromArray($postRegistrationBankAccount);

                // addRelated, updatedRelated en deleteRelated uitschakelen zodat er geen aanvullende acties/changes worden aangemaakt
                $relatedFunctionBoolean = $this->action .'Related';
                $registrationBankAccount->$relatedFunctionBoolean = false;

                $registrationBankAccount->{$this->action}(false);
                break;
        }

        $result = [
            'success' => 1,
            'message' => '',
            'registrationBankAccountId' => $registrationBankAccount->id
        ];

        $app->renderJSON($result);
    }
    
    /**
     * Registration Address endpoint afhandelen
     *
     * @param $app \app\CApp
     */

    private function processRegistrationAddress($app) {
        $postRegistrationAddress = h::getV("RegistrationAddress", "array", [], "POST");

        // instantieren registration bank account
        $registrationAddress = new \app\hig\MRegistrationAddress();
        if(!empty($postRegistrationAddress['id'])){
            $registrationAddress = new \app\hig\MRegistrationAddress($postRegistrationAddress['id']);
        }

        switch ($this->action) {
            case 'add':
            case 'update':
            case 'delete':
                $registrationAddress->fillFromArray($postRegistrationAddress);

                // addRelated, updatedRelated en deleteRelated uitschakelen zodat er geen aanvullende acties/changes worden aangemaakt
                $relatedFunctionBoolean = $this->action .'Related';
                $registrationAddress->$relatedFunctionBoolean = false;

                $registrationAddress->{$this->action}(false);
                break;
        }

        $result = [
            'success' => 1,
            'message' => '',
            'registrationAddressId' => $registrationAddress->id
        ];

        $app->renderJSON($result);
    }
    
    /**
     * Registration ContactPerson endpoint afhandelen
     *
     * @param $app \app\CApp
     */
    
    private function processRegistrationUbo($app) {
        $postRegistrationUbo = h::getV("RegistrationUbo", "array", [], "POST");
        
        // instantieren registration phone number
        $registrationUbo = new \app\hig\MRegistrationUbo();
        if(!empty($postRegistrationUbo['id'])){
            $registrationUbo = new \app\hig\MRegistrationUbo($postRegistrationUbo['id']);
        }
        
        switch ($this->action) {
            case 'add':
            case 'update':
            case 'delete':
            $registrationUbo->fillFromArray($postRegistrationUbo);
                
                // addRelated, updatedRelated en deleteRelated uitschakelen zodat er geen aanvullende acties/changes worden aangemaakt
                $relatedFunctionBoolean = $this->action .'Related';
                $registrationUbo->$relatedFunctionBoolean = false;
    
                $registrationUbo->{$this->action}(false);
                break;
        }
        
        $result = [
            'success' => 1,
            'message' => '',
            'registrationUboId' => $registrationUbo->id
        ];
        
        $app->renderJSON($result);
    }
}
