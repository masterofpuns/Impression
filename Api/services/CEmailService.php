<?php
namespace app\hig;

use \app\m;
use \app\h;


class CEmailService {
    protected $mailer;
    /** @var \app\MUser */
    protected $user;
    /** @var \app\hig\MRelation */
    protected $relation;
    protected $status;
    protected $type;
    protected $oldEntity;
    protected $entity;
    protected $dateTimeScheduled;
    protected $additionalParams;
    protected $recipients;
    protected $ccRecipients;
    // mailerTemplateId wordt gezet vanuit het CRM
    protected $mailerTemplateId;
    protected $emailSender;

    public function __construct() {
        $this->mailer = new \app\CMailer();

        // default zetten van mailerTemplateId
        $this->mailerTemplateId = EMAIL_TEMPLATE_ID_DEFAULT;
    }

    public function setUser($user) {
        if (!empty($user)) {
            $this->user = $user;
        }
    }

    public function setRelation($relation) {
        if (!empty($relation)) {
            $this->relation = $relation;
        }
    }

    public function setMailerTemplateId($id) {
        if (!empty($id)) {
            $this->mailerTemplateId = $id;
        }
    }
    
    public function getEmailSender() {
        if (empty($this->emailSender)) {
            // default afzender is altijd Maartje
            $this->emailSender = new \app\hig\MEmailSender(DEFAULT_EMAIL_SENDER_ID);
        }
        return $this->emailSender;
    }
    
    public function setEmailSender(\app\hig\MEmailSender $emailSender) {
        if (!empty($emailSender)) {
            $this->emailSender = $emailSender;
        }
    }
    
    private function getUser() {
        if (empty($this->user) && !empty(m::app()->user)) {
            $this->user = m::app()->user;
        }

        return $this->user;
    }

    private function getRelation() {
        if (
            is_null($this->relation) &&
            !empty(m::app()->user)
        ) {
            $this->relation = m::app()->user->getSelectedRelation();
        }
    }

    private function getRecipients() {
        $this->recipients = [];

        // controleren of er een ingelogde gebruiker is
        if (!empty(m::app()->user)) {
            $this->recipients = [m::app()->user];
        // controleren of er een handmatige user is gezet
        } elseif (!empty($this->user)) {
            $this->recipients = [$this->user];
        // zo niet, mail sturen aan alle participanten
        } else {
	        $relationObject = $this->relation->getObject();
	        
            // op basis van relatie type controleren welke onderliggende relaties we
            // nodig hebben
            switch ($this->relation->type) {
                case 'contactPerson':
                    if (!empty($relationObject->getContactPersonsProxy())) {
                        foreach ($relationObject->getContactPersonsProxy() as $contactPersonProxy) {
                            $user = $contactPersonProxy->getUser();
                            if (
                                !empty($user) &&
                                !$user->archived &&
                                $user->status !== 'inactive' &&
                                $user->status !== 'login-changed' &&
                                $user->status !== 'correspondence-created' &&
                                $user->sendNotificationForRelation($this->relation->id) &&
                                !$user->isSuperAdmin() &&
                                $user->isParticipant()
                            ) {
                                $this->recipients[] = $user;
                            } else {
                                $this->recipients[] = $contactPersonProxy;
                            }
                        }
                    } else {
                        $user = $this->relation->getUser();
                        if (
                            !empty($user) &&
                            !$user->archived &&
                            $user->status !== 'inactive' &&
                            $user->status !== 'login-changed' &&
                            $user->status !== 'correspondence-created' &&
                            $user->sendNotificationForRelation($this->relation->id) &&
                            !$user->isSuperAdmin() &&
                            $user->isParticipant()
                        ) {
                            $this->recipients[] = $user;
                        } else {
                            // wanneer er nog geen user voor de relatie bestaat
                            // relatie model toevoegen, zodat mail naar primaire emailadres
                            // gestuurd kan worden
                            $this->recipients[] = $this->relation;
                        }
                    }
                    break;
                case 'organization':
                    foreach ($relationObject->getManagerRelations() as $managerRelation) {
                        // controleren of relation een user heeft
                        $user = $managerRelation->getUser();
                        if (
                            !empty($user) &&
                            !$user->archived &&
                            $user->status !== 'inactive' &&
                            $user->status !== 'login-changed' &&
                            $user->status !== 'correspondence-created' &&
                            $user->sendNotificationForRelation($this->relation->id) &&
                            !$user->isSuperAdmin() &&
                            $user->isParticipant()
                        ) {
                            $this->recipients[] = $user;
                        } else {
                            // wanneer er nog geen user voor de relatie bestaat
                            // relatie model toevoegen, zodat mail naar primaire emailadres
                            // gestuurd kan worden
                            $this->recipients[] = $managerRelation;
                        }
                    }
                    break;
                case 'collective':
                    foreach ($relationObject->getSortedContactPersons() as $sortedContactPersonRelation) {
                        // controleren of relation een user heeft
                        $user = $sortedContactPersonRelation->getUser();
                        if (
                            !empty($user) &&
                            !$user->archived &&
                            $user->status !== 'inactive' &&
                            $user->status !== 'login-changed' &&
                            $user->status !== 'correspondence-created' &&
                            $user->sendNotificationForRelation($this->relation->id) &&
                            !$user->isSuperAdmin() &&
                            $user->isParticipant()
                        ) {
                            $this->recipients[] = $user;
                        } else {
                            // wanneer er nog geen user voor de relatie bestaat
                            // relatie model toevoegen, zodat mail naar primaire emailadres
                            // gestuurd kan worden
                            $this->recipients[] = $sortedContactPersonRelation;
                        }
                    }
                    break;
            }
        }

        return $this->recipients;
    }

    private function getCCRecipients() {
        $this->ccRecipients = [];

        // bepalen CC ontvangers
        /** @var \app\MUser $user */
        foreach ($this->relation->getUsers() as $user) {
            /**
             * Overslaan van $user indien
             * - deze gelijk is aan huidig ingelogde gebruiker (indien ingelogd)
             * - deze gelijk is aan specifiek gezette user in service
             * - deze is gearchiveerd
             * - de status gelijk is aan [inactive, login-changed, correspondence-created]
             * - er geen notificatie naar user mag worden gestuurd
             * - deze super admin is
             * - deze participant is
             * - deze account manager is (default niet opnemen in cc recipients, indien nodig wordt dit expliciet aangegeven)
             */
            if (
                (!empty(m::app()->user) && $user->id === m::app()->user->id) ||
                (!empty($this->user) && $user->id === $this->user->id) ||
                $user->archived ||
                in_array($user->status, ['inactive', 'login-changed', 'correspondence-created']) ||
                !$user->sendNotificationForRelation($this->relation->id) ||
                $user->isSuperAdmin() ||
                $user->isParticipant() ||
                $user->isAccountManager
            ) {
                continue;
            }
            
            // controleren of emailadres relationId is, deze overslaan, gebruiker heeft dan immers registratie nog niet afgerond
            if (is_numeric($user->emailAddress)) { continue; }
            $this->ccRecipients[] = $user->emailAddress;
        }

        /**
         * Controleren of account managers alsnog moeten worden opgenomen als CC ontvanger, dit is het geval indien:
         * - email een notificatie is
         * - de parameter notifyAccountManager is aangezet bij het aanmaken van de correspondentie
         * - er account managers staan ingesteld voor de relatie (omgeving) waarvoor notificatie wordt verstuurd
         */
        if (
        	$this->type == 'notification' &&
	        $this->additionalParams['notifyAccountManager'] &&
	        !empty($this->relation->relationAccountManagers)
        ) {
        	foreach ($this->relation->relationAccountManagers as $relationAccountManager) {
		        $emailAddress = $relationAccountManager->primaryEmailAddress;
		        if (empty($emailAddress)) { $emailAddress = current($relationAccountManager->emailAddresses); }
		        if (empty($emailAddress)) { continue; }
        		
		        $this->ccRecipients[] = $emailAddress->address;
	        }
        }

        return $this->ccRecipients;
    }

    public function processNotificationMail(
        $type,
        $oldEntity = null,
        $entity = null,
        $dateTimeScheduled = null,
        $additionalParams = [],
        $attachments = null
    ) {
        // bepalen hoofdrelatie
        $this->getRelation();

        // opslaan
        $this->type = $type;
        $this->oldEntity = $oldEntity;
        $this->entity = $entity;
        $this->dateTimeScheduled = $dateTimeScheduled;
        $this->additionalParams = $additionalParams;
        $this->attachments = $attachments;

        switch ($type) {
            // PARTICIPANT GERELATEERDE MAILS
            case 'postalAddress':
            case 'visitingAddress':
                $this->prepareMailAddress($type);
                break;
            case 'manager':
                $this->prepareMailManager();
                break;
            case 'old-manager-confirmation':
                $this->prepareMailOldManagerConfirmation();
                break;
            case 'emailAddress':
                $this->prepareMailEmailAddress();
                break;
            case 'bankAccount':
                $this->prepareMailBankAccount();
                break;
            case 'bankAccount-confirmation':
                $this->prepareMailBankAccountConfirmation();
                break;
            case 'correspondence':
                $this->prepareMailCorrespondence();
                break;
            case 'vote':
                $this->prepareMailVote();
                break;
            case 'contact':
                $this->prepareMailContact();
                break;
            case 'fervour':
                $this->prepareMailFervour();
                break;
            case 'phoneNumber-add':
                $this->prepareMailPhoneNumberAdd();
                break;
            case 'phoneNumber-change':
                $this->prepareMailPhoneNumberChange();
                break;
            case 'phoneNumber-delete':
                $this->prepareMailPhoneNumberDelete();
                break;
            case 'notification':
                return $this->prepareMailNotification();
                break;
            case 'revokeChange':
                return $this->prepareMailRevokeChange();
                break;

            // USER GERELATEERDE MAILS
            case 'emailAddress':
                $this->prepareMailEmailAddress();
                break;
            case 'name':
                $this->prepareMailName();
                break;
            case 'register':
                $this->prepareMailRegister();
                break;
            case 'register-complete':
                $this->prepareMailRegisterComplete();
                break;
            case 'extraUser':
                $this->prepareMailExtraUser();
                break;
            case 'extraUserRelationAddedToEnvironment':
                $this->prepareMailExtraUserRelationAddedToEnvironment();
                break;
            case 'password':
                $this->prepareMailPassword();
                break;
            case 'recover-password':
                $this->prepareMailRecoverPassword();
                break;
            case 'password-expiration':
                $this->prepareMailPasswordExpiration();
                break;

            // ALGEMENE MAILS
            case 'sync':
                $this->prepareMailSync();
                break;
            case 'adulthood':
                $this->prepareMailAdulthood();
                break;

            // REGISTRATION
            case 'registration-blocked-relation':
                $this->prepareMailRegistrationBlockedRelation();
                break;
            case 'registration-sign_invitation':
                $this->prepareMailRegistrationSignInvitation();
                break;
            case 'registration-signed_by_contact_person':
                $this->prepareMailRegistrationSignedByContactPerson();
                break;
            case 'registration-await_sign_confirmation-account_manager':
                $this->prepareMailRegistrationAwaitSignConfirmationAccountManager();
                break;
            case 'registration-complete':
                $this->prepareMailRegistrationComplete();
                break;
        }
    }

    private function prepareMailAddress($type) {
        // bepalen adres type
        if ($type == 'postalAddress') {
            $addressType = 'POSTAL_ADDRESS';
        }
        if ($type == 'visitingAddress') {
            $addressType = 'VISITING_ADDRESS';
        }

        // zetten van parameters voor content
        $contentParams = [
            "{addressType}" => strtolower(t($addressType)),
            "{address}" => $this->entity
        ];

        // zetten van parameteres voor onderwerp
        $subjectParams = [
            "{addressType}" => strtolower(t($addressType))
        ];

        // sturen mail participant
        $this->processMails(
            'email-change-address-per-date',
            $this->getRecipients(),
            $contentParams,
            $subjectParams,
            $this->getCCRecipients()
        );


        $correspondenceType = '';
        switch ($this->relation->getObject()->transactionalMail) {
            case '1': $correspondenceType = 'PER_MAIL'; break;
            case '2': $correspondenceType = 'DIGITAL'; break;
            case '3': $correspondenceType = 'PER_MAIL_AND_DIGITAL';
        }

        // aanvullen content parameters
        $contentParams['{oldAddress}'] = $this->oldEntity;
        $contentParams['{correspondenceType}'] = t($correspondenceType);

        // versturen mail HIG
        $this->processMails(
            'email-change-address-per-date-hig',
            [
                EMAIL_ADDRESS_HIG_NOTIFY,
                EMAIL_ADDRESS_HIG_ADMINISTRATION
            ],
            $contentParams
        );
    }

    private function prepareMailPassword() {
        // versturen mail gebruiker
        $this->processMails(
            'email-change-password',
            [$this->user]
        );
    }

    private function prepareMailName() {
        // zetten van parameters voor content
        $contentParams = [
            "{name}" => $this->entity,
        ];

        // versturen mail gebruiker
        $this->processMails(
            'email-change-name',
            [$this->user],
            $contentParams
        );
    }

    private function prepareMailEmailAddress() {
        // zetten van parameters voor content
        $contentParams = [
            "{newEmailAddress}" => $this->entity->emailAddress,
        ];

        // versturen mail gebruiker
        $this->processMails(
            'email-change-emailaddress-old',
            [$this->oldEntity],
            $contentParams
        );

        // versturen mail gebruiker
        $this->processMails(
            'email-change-emailaddress-new',
            [$this->entity],
            $contentParams
        );
    }

    private function prepareMailBankAccount() {
        // zetten van parameters voor content
        $contentParams = [
            "{iban}" => $this->entity->iban,
            "{bic}" => $this->entity->bic,
            "{relationNumber}" => $this->entity->relationNumber,
            "{ascription}" => $this->entity->ascription,
        ];

        // versturen mail participant
        $this->processMails(
            'email-change-bankaccount-authorizing',
            $this->getRecipients(),
            $contentParams,
            [],
            $this->getCCRecipients()
        );

        // zetten van parameters voor content
        $contentParams = [
            '{postalAddress}' => $this->relation->getPostalAddress(),
            '{oldBankAccount}' => $this->oldEntity,
            "{ibanOld}" => $this->oldEntity->iban,
            "{bicOld}" => $this->oldEntity->bic,
            "{relationNumberOld}" => $this->oldEntity->relationNumber,
            "{ascriptionOld}" => $this->oldEntity->ascription,
            "{iban}" => $this->entity->iban,
            "{bic}" => $this->entity->bic,
            "{relationNumber}" => $this->entity->relationNumber,
            "{ascription}" => $this->entity->ascription,
        ];

        // itereren door additionele parameters en zetten voor content parameters
        foreach ($this->additionalParams as $finds => $replace) {
            $contentParams[$finds] = $replace;
        }

        // versturen mail HIG
        $this->processMails(
            'email-change-bankaccount-hig',
            [EMAIL_ADDRESS_HIG_NOTIFY],
            $contentParams
        );
    }

    private function prepareMailBankAccountConfirmation() {
        // zetten van parameters voor content
        $contentParams = [
            "{iban}" => $this->entity->iban,
            "{bic}" => $this->entity->bic,
            "{relationNumber}" => $this->entity->relationNumber,
            "{ascription}" => $this->entity->ascription,
        ];

        // versturen mail participant
        // deze case heeft geen CC recipients, omdat mail vanuit CRM via API
        // wordt verstuurd. Op deze manier is er geen ingelogde gebruiker
        $this->processMails(
            'email-change-bankaccount-accepted',
            $this->getRecipients(),
            $contentParams
        );
    }

    private function prepareMailManager() {
        // zetten van parameters voor content
        $contentParams = [
            "{manager}" => $this->entity->getName()
        ];

        // itereren door additionele parameters en zetten voor content
        foreach ($this->additionalParams as $finds => $replace) {
            $contentParams[$finds] = $replace;
        }

        // versturen mail participant
        $this->processMails(
            'email-change-manager-authorizing',
            $this->getRecipients(),
            $contentParams,
            [],
            $this->getCCRecipients()
        );

        // zetten van parameters voor content
        $contentParams = [
            '{oldManager}' => $this->oldEntity,
            '{newManager}' => $this->entity,
        ];

        // zetten en itereren door additionele parameters voor onderwerp
        $subjectParams = [];
        foreach ($this->additionalParams as $finds => $replace) {
            $subjectParams[$finds] = $replace;
        }
    
        $attachments = [];
        if (!empty($this->attachments)) {
            foreach ($this->attachments as $filename => $filepath) {
                $attachments[] = $filepath;
            }
        }

        // versturen mail HIG
        $this->processMails(
            'email-change-manager-hig',
            [EMAIL_ADDRESS_HIG_NOTIFY],
            $contentParams,
            $subjectParams,
            [],
            $attachments
        );
    }

    private function prepareMailOldManagerConfirmation() {
        // versturen mail participant
        $this->processMails(
            'email-change-manager-old',
            [$this->user]
        );
    }

    private function prepareMailCorrespondence() {
        // bepalen te versturen partial
        if ($this->entity->transactionalMail == 1) {
            $partial = 'email-change-correspondence-mail';
        }
        if ($this->entity->transactionalMail == 2) {
            $partial = 'email-change-correspondence-digital';
        }

        // versturen mail participant
        $this->processMails(
            $partial,
            $this->getRecipients(),
            [],
            [],
            $this->getCCRecipients()
        );

        // bepalen oud correspondentietype
        $oldCorrespondenceType = '';
        switch ($this->oldEntity->transactionalMail) {
            case '1': $oldCorrespondenceType = 'PER_MAIL'; break;
            case '2': $oldCorrespondenceType = 'DIGITAL'; break;
            case '3': $oldCorrespondenceType = 'PER_MAIL_AND_DIGITAL';
        }

        // bepalen nieuw correspondentietype
        $newCorrespondenceType = '';
        switch ($this->entity->transactionalMail) {
            case '1': $newCorrespondenceType = 'PER_MAIL'; break;
            case '2': $newCorrespondenceType = 'DIGITAL'; break;
            case '3': $newCorrespondenceType = 'PER_MAIL_AND_DIGITAL';
        }

        // zetten van parameters voor content
        $contentParams = [
            '{postalAddress}' => $this->relation->getPostalAddress(),
            '{oldCorrespondenceType}' => t($oldCorrespondenceType),
            '{newCorrespondenceType}' => t($newCorrespondenceType),
        ];

        // versturen mail HIG
        $this->processMails(
            'email-change-correspondence-hig',
            [EMAIL_ADDRESS_HIG_NOTIFY],
            $contentParams
        );
    }

    private function prepareMailPhoneNumberAdd() {
        // zetten van parameters voor content
        $contentParams = [
            "{newPhoneNumberType}" => t(strtoupper($this->entity->type)),
            "{phoneNumber}" => $this->entity->number,
        ];

        // versturen mail participant
        $this->processMails(
            'email-add-phonenumber',
            $this->getRecipients(),
            $contentParams,
            [],
            $this->getCCRecipients()
        );
    }

    private function prepareMailPhoneNumberChange() {
        // zetten van parameters voor content
        $contentParams = [
            "{oldPhoneNumberType}" => t(strtoupper($this->oldEntity->type)),
            "{newPhoneNumberType}" => t(strtoupper($this->entity->type)),
            "{phoneNumber}" => $this->entity->number,
        ];

        // versturen mail participant
        $this->processMails(
            'email-change-phonenumber',
            $this->getRecipients(),
            $contentParams,
            [],
            $this->getCCRecipients()
        );
    }

    private function prepareMailPhoneNumberDelete() {
        // zetten van parameters voor content
        $contentParams = [
            "{oldPhoneNumber}" => $this->oldEntity->number,
            "{oldPhoneNumberType}" => t(strtoupper($this->oldEntity->type)),
        ];

        // versturen mail participant
        $this->processMails(
            'email-delete-phonenumber',
            $this->getRecipients(),
            $contentParams,
            [],
            $this->getCCRecipients()
        );
    }

    private function prepareMailRegister() {
        // zetten van parameters voor content
        $contentParams = [];

        // itereren door additionele parameters voor content
        foreach ($this->additionalParams as $finds => $replace) {
            $contentParams[$finds] = $replace;
        }

        // versturen mail HIG
        $this->processMails(
            'email-register',
            [EMAIL_ADDRESS_HIG_NOTIFY],
            $contentParams
        );
    }

    private function prepareMailRegisterComplete() {
        $portalUserRelation = $this->getPortalUserRelation();

        $contentParams = [
            "{emailAddress}" => $this->entity->emailAddress,
            "{portalUserRelation}" => $portalUserRelation->getName()
        ];

        // versturen mail gebruiker
        $this->processMails(
            'email-register-confirmation',
            [$this->user],
            $contentParams
        );

        // versturen mail HIG
        $this->processMails(
            'email-register-confirmation-hig',
            [EMAIL_ADDRESS_HIG_NOTIFY],
            $contentParams
        );
    }

    private function prepareMailExtraUser() {
        // zetten van parameters voor content
        $contentParams = [
            "{user}" => ucfirst($this->entity->getName())
        ];

        // ophalen ontvangers en filteren van user uit resultaten
        $recipients = $this->getRecipients();
        unset($recipients[$this->entity->id]);
        
        // ophalen cc ontvangers en filteren van user uit resultaten
        $ccRecipients = $this->getCCRecipients();
	    unset($ccRecipients[$this->entity->id]);

        // versturen mail participant
        $this->processMails(
            'email-extra-user-add',
            $recipients,
            $contentParams,
            [],
            $ccRecipients
        );

        // aanvullen content parameters
        $contentParams["{emailAddress}"] = $this->entity->emailAddress;

        // itereren door additionele parameters voor content
        foreach ($this->additionalParams as $finds => $replace) {
            $contentParams[$finds] = $replace;
        }

        // versturen mail extra gebruiker
        $this->processMails(
            'email-extra-user-credentials',
            [$this->entity],
            $contentParams
        );
    }

    private function prepareMailExtraUserRelationAddedToEnvironment() {
        // zetten van parameters voor content
        $contentParams = [
            "{user}" => ucfirst($this->entity->getName())
        ];

        // ophalen recipients
        $recipients = $this->getRecipients();
        // filteren van user uit recipients
        unset($recipients[$this->entity->id]);

        // versturen mail participant
        $this->processMails(
            'email-extra-user-add',
            $recipients,
            $contentParams,
            [],
            $this->getCCRecipients()
        );

        // aanvullen content parameters
        $contentParams["{emailAddress}"] = $this->entity->emailAddress;

        // itereren door additionele parameters voor content
        foreach ($this->additionalParams as $finds => $replace) {
            $contentParams[$finds] = $replace;
        }

        // versturen mail extra gebruiker
        $this->processMails(
            'email-extra-user-new-environment',
            [$this->entity],
            $contentParams
        );
    }

    private function prepareMailFervour() {
        $contentParams = [
            "{fund}" => $this->entity,
            "{postalAddress}" => $this->relation->getPostalAddress(),
        ];

        // itereren door additionele parameters en zetten voor content
        foreach ($this->additionalParams as $finds => $replace) {
            $contentParams[$finds] = $replace;
        }

        // zetten van parameters voor onderwerp
        $subjectParams = [
            '{fund}' => $this->entity,
        ];

        // versturen mail HIG en kopie participant
        $this->processMails(
            'email-fervour-add',
            [EMAIL_ADDRESS_HIG_INFO],
            $contentParams,
            $subjectParams,
            $this->getRecipients()
        );
    }

    private function prepareMailVote() {
        $votelist = new \app\hig\MVotelist($this->entity->votelistId);
        $dateTimeMeeting = new \DateTime($votelist->dateTimeMeeting);
        // converteren naar Coordinated Universal Time
	    $dateTimeMeeting->setTimezone(new \DateTimeZone('UTC'));
	    // converteren naar Coordinated Universal Time
        $dateTimeNow = new \DateTime();
        $dateTimeNow->setTimezone(new \DateTimeZone('UTC'));

        // bepalen van partial obv stem type
        $partial = '';
        $attachments = [];
        switch ($this->entity->type) {
            case 'self':
                $partial = 'email-vote-present';

                $icalData = [
                    'BEGIN:VCALENDAR',
                    'METHOD:REQUEST',
                    'VERSION:2.0',
                    'PRODID:-//hacksw/handcal//NONSGML v1.0//EN',
                    'CALSCALE:GREGORIAN',
                    'BEGIN:VEVENT',
                    'UID:' . $votelist->id . uniqid(),
                    'DTSTAMP:' . $dateTimeNow->format("Ymd\THis\Z"),
                    'DTSTART:' . $dateTimeMeeting->format("Ymd\THis\Z"),
                    'DTEND:'  . $dateTimeMeeting->format("Ymd\THis\Z"),
                    'ORGANIZER;CN='.EMAIL_SENDER_NAME.':mailto:'.EMAIL_SENDER_ADDRESS,
                    'ATTACHT;ENCODING=BASE64',
                    'LOCATION:' . addslashes($votelist->locationMeeting),
                    'DESCRIPTION:' . addslashes($votelist->name),
	                'SUMMARY:' . addslashes($votelist->name) . ' - ' . addslashes($votelist->getFund()->name),
                    'END:VEVENT',
                    'END:VCALENDAR'
                ];
    
                // aanmaken ical bestand
                $filename = h::toAscii($votelist->name).".ics";
                $path = DOC_STORAGE_LOCATION . 'temp/ical/';
                if (!is_dir($path)) { mkdir($path, 0755); }
                $meeting = $path . $filename;
    
                $fh = fopen($meeting, "w+");
                fwrite($fh, implode("\r\n",$icalData));
                fclose($fh);
    
                $attachments = [$meeting];
                
                /*
                $attachments = [
                    "meeting.ics" => [
                        "file" => chunk_split(base64_encode(implode("\r\n",$icalData))),
                        "headers" => [
                            "Content-Type" => "text/calendar; charset=utf8",
                            "Content-Disposition" => "attachment; filename=".h::toAscii($votelist->name).".ics",
                        ]
                    ]
                ];
                */

                break;
            case 'proxy':
                switch ($this->entity->proxyType) {
                    case 'hig':
                        $partial = 'email-vote-proxy-hig';
                        break;
                    case 'person':
                        $partial = 'email-vote-proxy-person';
                        break;
                    case 'custom':
                        $partial = 'email-vote-proxy-custom';
                        break;
                }
                break;
            case 'not':
                $partial = 'email-vote-absent';
                break;
        }

        // ophalen steminstructie
        /** @var \app\hig\CVotelistService */
        $votelistService = m::app()->serviceManager->get('votelistService');
        $voteInstruction = '';
        $count = 1;
        foreach ($this->entity->getAnswers() as $answer) {
            $question = new \app\hig\MVotelistQuestion($answer->votelistQuestionId);
            $description = $votelistService->getMergeFieldsForDescription($votelist, $question->descriptionPortal);
            $voteInstruction .= $count . '. ';
            $voteInstruction .= $description;

            $voteInstruction .= '<br>';

            $answer = new \app\hig\MVotelistQuestionAnswer($answer->votelistQuestionAnswerId);
            $voteInstruction .= '<i>Uw reactie: ';
            $voteInstruction .= strtolower($answer->description);
            $voteInstruction .= '</i>';
            $voteInstruction .= '<br>';
            $voteInstruction .= '<br>';

            $count++;
        }

        // zettem van parameters voor content
        $contentParams = [
            "{votelist}" => $votelist->name,
            "{fund}" => $votelist->getFund()->name,
            "{thankYouPresent}" => $votelistService->getMergeFieldsForDescription($votelist, $votelist->thankYouPresent),
            "{vote}" => $voteInstruction,
            "{proxy}" => $this->entity->proxyName
        ];

        // zettem van parameters voor onderwerp
        $subjectParams = [
            "{fund}" => $votelist->getFund()->name,
            "{votelist}" => $votelist->name,
        ];

        // versturen mail participant
        $this->processMails(
            $partial,
            $this->getRecipients(),
            $contentParams,
            $subjectParams,
            $this->getCCRecipients(),
            $attachments
        );
    
        // na versturen ical verwijderen uit map om vervuiling te voorkomen
        if (!empty($meeting) && file_exists($meeting)) {
            unlink($meeting);
        }
    }

    private function prepareMailContact() {
        $portalUserRelation = $this->getPortalUserRelation();

        $subjectParams = [
            "{portalUserRelation}" => $portalUserRelation->getName()
        ];

        $contentParams = [
            "{portalUserRelationSalutation}" => $portalUserRelation->getNameForLetter(),
            "{portalUserRelation}" => $portalUserRelation->getName()
        ];

        // zetten van parameters voor content
        foreach ($this->additionalParams as $finds => $replace) {
            $contentParams[$finds] = $replace;
        }

        //$recipients = array_merge($this->getRecipients(), $this->getCCRecipients());

        // versturen mail HIG en kopie participant
        $this->processMails(
            'email-contact-form',
            [$this->user],
            $contentParams,
            $subjectParams,
            [EMAIL_ADDRESS_HIG_NOTIFY]
        );
    }

    private function prepareMailSync() {
        switch (ENVIRONMENT) {
            case 'live':
                $environment = 'Live omgeving';
                break;
            case 'release':
                $environment = 'Test omgeving';
                break;
            case 'develop':
                $environment = 'Lokale test omgeving';
                break;
        }

        $contentParams = [
            "{environment}" => $environment
        ];

        // versturen mail HIG
        $this->processMails(
            'email-sync-notification',
            [EMAIL_ADDRESS_HIG_NOTIFY],
            $contentParams
        );
    }

    private function prepareMailAdulthood() {
        // versturen mail HIG en kopie participant
        $this->processMails(
            'email-adulthood-notification',
            [EMAIL_ADDRESS_HIG_NOTIFY]
        );
    }

    private function prepareMailRegistrationBlockedRelation() {
        $contentParams = [];

        // itereren door additionele parameters voor content
        foreach ($this->additionalParams as $finds => $replace) {
            $contentParams[$finds] = $replace;
        }

        // versturen mail HIG en kopie participant
        $this->processMails(
            'email-registration-blocked-relation-notification',
            [EMAIL_ADDRESS_HIG_NOTIFY],
            $contentParams
        );
    }

    private function prepareMailRevokeChange() {
        // zetten van parameters voor content
        $contentParams = [
            "{entity}" => $this->entity,
            "{oldEntity}" => $this->oldEntity
        ];

        foreach ($this->additionalParams as $finds => $replace) {
            $contentParams[$finds] = $replace;
        }

        $recipients = array_merge([EMAIL_ADDRESS_HIG_NOTIFY], $this->getCCRecipients());

        // versturen mail naar participant �n HIG
        $this->processMails(
            'email-revoke-change',
            $this->getRecipients(),
            $contentParams,
            [],
            $recipients
        );
    }

    private function prepareMailRecoverPassword() {
        // zetten van parameters voor content
        $contentParams = [
            "{resetUrl}" =>
                '<a href="'.$this->user->getUrlRecoverPassword().'" target="_blank">'.$this->user->getUrlRecoverPassword().'</a>',
        ];

        // versturen mail naar participant ?n HIG
        $this->processMails(
            'email-recover-password',
            [$this->user],
            $contentParams
        );
    }

    private function prepareMailPasswordExpiration() {
        // zetten van parameters voor content
        $contentParams = [
            "{resetUrl}" => $this->user->getUrlRecoverPassword(),
        ];

        // versturen mail naar participant ?n HIG
        $this->processMails(
            'email-password-expiration',
            [$this->user],
            $contentParams
        );
    }

    private function processMails(
        $partial,
        $recipients,
        $contentParams = [],
        $subjectParams = [],
        $ccRecipients = [],
        $attachments = [],
        $replyTo = null
    ) {
        // ophalen cms partial
        $cmsPartial = m::app()->getCmsPartial($partial);

        // ophalen relatie
        $this->getRelation();
        
        // ophalen email afzender
        $this->getEmailSender();

        // opslaan content
        $content = "
            <style>
            td {
                vertical-align: top;
                color: #4e4e4e;
            }
            th {
                vertical-align: top;
                color: #4e4e4e;
            }
            .button {
                border-radius: 2px;
                padding: 0px 10px;
                border: 1px solid #4e4e4e;
            }
            .button a {
                border: solid #FFF 1px;
                display: inline-block;
                border-radius: 2px;
                font-family: 'Lato','Arial',Helvetica,sans-serif;
                font-size: 11px;
                color: #444;
                padding: 10px;
                text-decoration: none;
            }
            .email_notification-grey {
                margin: 15px;
            }
            </style>
            <br>
        ";
        $content .= $cmsPartial->content;

        // opslaan onderwerp
        $subject = $cmsPartial->subject;

        // standaard mergevelden voor content en/of onderwerp vervangen door
        // betreffende waarde (wanneer deze bestaan)
        $params = [];
        preg_match_all("/{+(.*?)}/", $cmsPartial->content, $matches, PREG_PATTERN_ORDER);
        if (!empty($matches[1])) {
            foreach ($matches[1] as $match) {
                switch ($match) {
                    case 'RID':
                        $params['{RID}'] = (empty($this->relation) ? "" : $this->relation->id);
                        break;
                    case 'relation':
                        $params['{relation}'] = (empty($this->relation) ? "" : $this->relation->getName());
                        break;
                    case 'link':
                        $params['{'.$match.'}'] = (empty($this->relation) ? "" : $this->relation->getUrlBlueCrm());
                        break;
                    case 'funds':
                        // bepalen fondsen
                        $funds = [];
                        if (!empty($this->relation)) {
                            foreach ($this->relation->getParticipations() as $fundId => $participation) {
                                $funds[] = new \app\hig\MFund($fundId);
                            }
                        }
                        $params['{'.$match.'}'] = (empty($funds) ? "" : implode("<br>", $funds));
                        break;
                    case 'dateScheduled':
                        $params['{'.$match.'}'] = (empty($this->dateTimeScheduled) ? "" : $this->dateTimeScheduled->format('d-m-Y'));
                        break;
                    case 'button':
                        $params['{'.$match.'}'] =
                            '<table width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td>
                                        <table cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td class="button">
                                                    <a target="_blank" href="'.DOMAIN.'">Ga naar mijn-IMMO</a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>';
                        break;
                    case 'intermediaries':
                        // bepalen welke intermediairs bestaan voor relatie
                        $intermediaries = [];
                        if (!empty($this->relation)) {
                            // itereren door intermediair relaties
                            foreach ($this->relation->getRelationIntermediaries() as $relationId => $relation) {
                                // opslaan naam van intermediair
                                $intermediary = $relation->getName();
                                // ophalen relatie object
                                $relationObject = $this->relation->getObject();
                                if (
                                    isset($relationObject->intermediaryRelationId) &&
                                    $relation->id === $relationObject->intermediaryRelationId
                                ) {
                                    // is primair
                                    $intermediary .= " (primair)";
                                }

                                $intermediaries[] = $intermediary;
                            }
                        }

                        $params['{'.$match.'}'] = (empty($intermediaries) ? "" : implode("<br>", $intermediaries));
                        break;
                }
            }
        }

        $contentParams = array_merge($params, $contentParams);
        $subjectParams = array_merge($params, $subjectParams);


        // mergevelden voor content vervangen door betreffende waarde
        foreach ($contentParams as $param => $value) {
            // vervangen param met waarde
            $content = str_replace($param, $value, $content);

            // vervangen param small met waarde
            $paramSmall = str_replace('}', '_small}', $param);
            $content = str_replace($paramSmall, lcfirst($value), $content);
        }

        // mergevelden voor onderwerp vervangen door betreffende waarde
        foreach ($subjectParams as $param => $value) {
            if ($param == '{link}' || $param == '{funds}') { continue; }

            // vervangen param met waarde
            $subject = str_replace($param, $value, $subject);

            // vervangen param small met waarde
            $paramSmall = str_replace('}', '_small}', $param);
            $subject = str_replace($paramSmall, lcfirst($value), $subject);
        }

        // voorbereiden template mail
        $params = [];
        $params["SUBJECT"] = $subject;
        $params["IMMO_BTN"] = DOMAIN;

        $params["HEADER"] = "";
        if (!empty($this->relation)) {
            $relationId = $this->relation->id;
            $relationName = $this->relation->getName();
            $params["HEADER"] = "Betreffende: ".$relationName." (".$relationId.")";
        }

        $bccRecipients = [];

        // controleren waar mail naar toe gestuur moet worden
        foreach ($recipients as $recipient) {
            // opslaan van content in nieuwe parameter voor replace, content moet ongewijzigd blijven
            $body = $content;

            // default array voor e-mailadressen
            $emailAddresses = [];

            if (is_string($recipient)) { // mail naar HIG

                // zetten body
                $params["BODY"] = $content;
                // leegmaken salutation
                $params["HEADER"] = "";
                // zetten e-mailadres
                $emailAddresses = [$recipient];
                // zetten templateId
                $this->mailerTemplateId = EMAIL_TEMPLATE_ID_DEFAULT_NO_BTN;

            } else { // mail naar participant (ontvangers participant)

                // zetten van body obv salutation van recipient
                $body = str_replace("{salutation}", $recipient->getNameForLetter(), $body);
                if (stripos($body, "{salutation_small}")) {
                    $body = str_replace("{salutation_small}", lcfirst($recipient->getNameForLetter()), $body);
                }

                $params["BODY"] = $body;
                // zetten BCC ontvangers
                $bccRecipients[] = EMAIL_ADDRESS_HIG_MAILINGS;

                // bepalen e-mailadres voor recipient obv model
                $className = (new \ReflectionClass($recipient))->getShortName();

                // controleren welk model de recipient is om te bepalen waar
                // we het e-mailadres vandaan moeten halen
                switch ($className) {
                    case 'MRelation':
                        foreach ($recipient->getActiveEmailAddresses() as $emailAddress) {
                            $emailAddresses[] = $emailAddress->address;
                        }
                        break;

                    case 'MUser':
                        $emailAddresses = [$recipient->emailAddress];
                        break;
                }
            }

            if (!empty($emailAddresses)) {
                $result = \app\CMailer::sendTemplateMail(
                    implode(",", $emailAddresses),
                    $this->mailerTemplateId,
                    $params,
                    $attachments,
                    $replyTo,
                    $ccRecipients,
                    $bccRecipients,
	                !empty($this->relation) ? $this->relation->id : null,
                    false,
                    $this->emailSender
                );
                $this->setStatus($result);
            } else {
                $this->setStatus(false);
            }
        }
    }

    private function setStatus($status) {
        $this->status = $status;
    }

    public function getStatus() {
        return $this->status;
    }


    /**
    * Onderstaande functie is een uitzondering op het bovenstaande verstuurmechanisme
    * Notificatiemails worden namelijk geïnitieerd vanuit het BlueCRM. Content voor body
    * wordt vanuit het CRM meegestuurd ipv dat deze uit partials wordt gehaald
    *
    */
    private function prepareMailNotification() {
        $content = $this->additionalParams['body'];
        $params = array(
            "SUBJECT" => $this->additionalParams['description'],
            "BODY" => $content,
            "IMMO_BTN" => DOMAIN,
            "HEADER" => $this->additionalParams['header'],
        );

        // ophalen ontvangers
        $recipients = $this->getRecipients();
        $ccRecipients = $this->getCCRecipients();
        
        $this->getRelation();
        // ophalen email afzender
        $this->getEmailSender();

        if (!empty($recipients)) {
            $emailAddresses = [];
            $pushNotificationTokens = [];
            foreach ($recipients as $recipient) {
                // bepalen e-mailadres
                $className = (new \ReflectionClass($recipient))->getShortName();
                switch ($className) {
                    case 'MRelation':
                        // @todo: niet alle active emailadressen, maar primaire emailadres
                        if (!empty($recipient->getPrimaryEmailAddress())) {
                            $emailAddresses[] = $recipient->getPrimaryEmailAddress()->address;
                        }
                        break;

                    case 'MUser':
                        $emailAddresses[] = $recipient->emailAddress;

                        $recipientPushNotificationTokens = $recipient->getPushNotificationTokens();
                        if (!empty($recipientPushNotificationTokens)) {
                            $unreadDocuments = $recipient->getTotalNumberOfUnreadDocuments();
                            foreach ($recipientPushNotificationTokens as $userMobileDeviceId => $recipientPushNotificationToken) {
                                $pushNotificationObj = new \stdClass;
                                $pushNotificationObj->pushNotificationToken = $recipientPushNotificationToken;
                                $pushNotificationObj->unreadDocuments = $unreadDocuments;
                                $pushNotificationObj->gotoUrl = '/mijn-berichten';
                                $pushNotificationObj->userId = $recipient->id;
                                $pushNotificationObj->userMobileDeviceId = $userMobileDeviceId;
                                $pushNotificationTokens[] = $pushNotificationObj;
                            }
                        }
                        break;
                }
            }

            if (!empty($emailAddresses)) {
                $result = \app\CMailer::sendTemplateMail(
                    implode(",", $emailAddresses),
                    $this->mailerTemplateId,
                    $params,
                    [],
                    [],
                    $ccRecipients,
                    [EMAIL_ADDRESS_HIG_MAILINGS],
	                !empty($this->relation) ? $this->relation->id : null,
                    true,
                    $this->emailSender
                );
                $this->setStatus($result);

                if (!empty($pushNotificationTokens)) {
                    $this->sendPushNotificationToTokens($this->additionalParams['description'],'Er staat een bericht voor u klaar.',$pushNotificationTokens);
                }
            } else {
                $this->setStatus(false);
            }
        }
    }

    /**
    * Functie voor ophalen van relatie die hoort bij ingelogde gebruiker
    * in combinatie met de relatie omgeving. Aan 1 gebruiker kunnen meerdere
    * relaties zijn gekoppeld (participant / extra gebruiker), echter kan de
    * combinatie relatie / gebruiker maar 1 keer voorkomen.
    *
    */
    private function getPortalUserRelation() {
        $this->getRelation();
        $this->getUser();

        // by default is de ingestelde relatie de portalUserRelation
        $portalUserRelation = $this->relation;

        // gedetailleerde bepaling van portal user relation
        $relations = [$this->relation->id => $this->relation];
        $contactPersons = $this->relation->getContactPersons();
        if (!empty($contactPersons)) {
            foreach ($contactPersons as $contactPerson) {
                // ophalen contactPersonen van hoofdrelatie
                $relations[$contactPerson->id] = $contactPerson;
            }
        }

        // ophalen relaties gekoppeld aan portal gebruiker
        $portalUserRelations = $this->user->getRelations();

        // zoeken naar overeenkomsten
        $matches = array_intersect_key($relations, $portalUserRelations);
        if (!empty($matches)) {
            // altijd 1 resultaat teruggeven
            $portalUserRelation = current($matches);
        } else {
            // wanneer er geen matches zijn gevonden controleren of gekoppelde relatie aan user account manager is
            // in een normale situatie zou er maar 1 relatie moeten / kunnen zijn voor de user. Voor account managers worden nooit
            // extra relaties aangemaakt / extra portal gebruikers aangemaakt
            $possibleAccountManagerRelation = current($portalUserRelations);
            if ($possibleAccountManagerRelation->getIsAccountManager()) {
                $portalUserRelation = $possibleAccountManagerRelation;
            }
        }

        return $portalUserRelation;
    }

    /**
     * Stuur push notifications naar een array van tokens
     * $pushNotificationObjects is een array van objecten
     * [
     *  {
     *      pushNotificationToken: Expo push notificatie token van mobile device
     *      unreadDocuments: aantal ongelezen berichten (voor badge bij app icoon)
     *      gotoUrl: url waar de app naartoe moet navigeren als push notificatie wordt aangeklikt
     *  }
     * ]
     * @param string $title
     * @param string $body
     * @param array $pushNotificationObjects
     */
    private function sendPushNotificationToTokens($title,$body,$pushNotificationObjects) {
        $objectsArray = array_chunk($pushNotificationObjects, 100);

        $objectsData = [];

        foreach ($objectsArray as $objectArray) {
            try {

                $postFields = [];
                foreach ($objectArray as $pushNotificationObject) {

                    $postField = [
                        'to' => $pushNotificationObject->pushNotificationToken,
                        'title' => $title,
                        'body' => $body,
                        'badge' => $pushNotificationObject->unreadDocuments,    // iOS
                        'data' => [
                            'gotoUrl' => $pushNotificationObject->gotoUrl,
                            'badge' => $pushNotificationObject->unreadDocuments // Android
                        ]
                    ];
                    $postFields[] = $postField;

                    $objectData = new \stdClass;
                    $objectData->userId = $pushNotificationObject->userId;
                    $objectData->userMobileDeviceId = $pushNotificationObject->userMobileDeviceId;
                    $objectData->pushNotificationToken = $pushNotificationObject->pushNotificationToken;
                    $objectData->body = json_encode($postField);

                    $objectsData[sha1($pushNotificationObject->pushNotificationToken)] = $objectData;
                }

                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => EXPO_PUSH_NOTIFICATION_URL,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => json_encode($postFields),
                    CURLOPT_HTTPHEADER => array(
                        'Accept: application/json',
                        'Accept-Encoding: gzip, deflate',
                        'Content-Type: application/json'
                    ),
                ));

                $response = curl_exec($curl);
                $responseObj = json_decode($response);

                if (!empty(curl_error($curl)) || !isset($responseObj->data)) {
                    // Errors negeren maar wel loggen
                    $error = new \stdClass();
                    $error->datetime = date("Y-m-d H:i:s");
                    $error->user_id = -1;
                    $error->errorMessage = 'Curl error: '.curl_error($curl);
                    $error->postFields = $postFields;
                    file_put_contents("logs/push_notification_errors.log",json_encode($error).PHP_EOL,FILE_APPEND);
                } else {
                    for ($d=0; $d<count($responseObj->data); $d++) {
                        if (isset($postFields[$d]['to'])) {
                            $pushNotificationToken = $postFields[$d]['to'];

                            if (isset($objectsData[sha1($pushNotificationToken)])) {
                                $objectData = $objectsData[sha1($pushNotificationToken)];

                                $umdpn = new \app\hig\MUserMobileDevicePushNotification();
                                $umdpn->userId = $objectData->userId;
                                $umdpn->userMobileDeviceId = $objectData->userMobileDeviceId;
                                $umdpn->pushNotificationToken = $pushNotificationToken;
                                $umdpn->receipt = $responseObj->data[$d]->id;
                                $umdpn->body = $objectData->body;
                                $umdpn->status = $responseObj->data[$d]->status;
                                $umdpn->add();

                            }
                        }
                    }
                }

                curl_close($curl);
            } catch (\Exception $e) {
                // Errors negeren maar wel loggen
                $error = new \stdClass();
                $error->datetime = date("Y-m-d H:i:s");
                $error->user_id = -1;
                $error->errorMessage = $e->getMessage() . ' in ' . $e->getFile() . ' on line '.$e->getLine();
                $error->postFields = $postFields;
                file_put_contents("logs/push_notification_errors.log",json_encode($error).PHP_EOL,FILE_APPEND);

            }
        }
    }

    /**
     * @param \app\MUser $user
     */
    public function updateNumUnreadDocumentsForPortalUser($user, $userMobileDeviceId = null) {
        $userPushNotificationTokens = $user->getPushNotificationTokens($userMobileDeviceId);
        if (!empty($userPushNotificationTokens)) {
            $unreadDocuments = $user->getTotalNumberOfUnreadDocuments();
            foreach ($userPushNotificationTokens as $userMobileDeviceId => $userPushNotificationToken) {
                $pushNotificationObj = new \stdClass;
                $pushNotificationObj->pushNotificationToken = $userPushNotificationToken;
                $pushNotificationObj->unreadDocuments = $unreadDocuments;
                $pushNotificationObj->userId = $user->id;
                $pushNotificationObj->userMobileDeviceId = $userMobileDeviceId;

                $pushNotificationTokens[] = $pushNotificationObj;
            }

            $this->updatePushNotificationTokensBadgeCount($pushNotificationTokens);
        }

    }

    /**
     * Update notificatie badge in de app voor een array van $pushNotificationObjects
     * $pushNotificationObjects is een array van objecten
     * [
     *  {
     *      pushNotificationToken: Expo push notificatie token van mobile device
     *      unreadDocuments: aantal ongelezen berichten (voor badge bij app icoon)
     *  }
     * ]
     * @param array $pushNotificationObjects
     */
    private function updatePushNotificationTokensBadgeCount($pushNotificationObjects) {
        $objectsArray = array_chunk($pushNotificationObjects, 100);

        $objectsData = [];

        foreach ($objectsArray as $objectArray) {
            try {

                $postFields = [];
                foreach ($objectArray as $pushNotificationObject) {

                    $postField = [
                        'to' => $pushNotificationObject->pushNotificationToken,
                        'badge' => $pushNotificationObject->unreadDocuments,    // iOS
                        'data' => [
                            'badge' => $pushNotificationObject->unreadDocuments // Android
                        ]
                    ];
                    $postFields[] = $postField;

                    $objectData = new \stdClass;
                    $objectData->userId = $pushNotificationObject->userId;
                    $objectData->userMobileDeviceId = $pushNotificationObject->userMobileDeviceId;
                    $objectData->pushNotificationToken = $pushNotificationObject->pushNotificationToken;
                    $objectData->body = json_encode($postField);

                    $objectsData[sha1($pushNotificationObject->pushNotificationToken)] = $objectData;

                }

                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => EXPO_PUSH_NOTIFICATION_URL,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => json_encode($postFields),
                    CURLOPT_HTTPHEADER => array(
                        'Accept: application/json',
                        'Accept-Encoding: gzip, deflate',
                        'Content-Type: application/json'
                    ),
                ));

                $response = curl_exec($curl);
                $responseObj = json_decode($response);

                if (!empty(curl_error($curl)) || !isset($responseObj->data)) {
                    // Errors negeren maar wel loggen
                    $error = new \stdClass();
                    $error->datetime = date("Y-m-d H:i:s");
                    $error->user_id = -1;
                    $error->errorMessage = 'Curl error: '.curl_error($curl);
                    $error->postFields = $postFields;
                    file_put_contents("logs/push_notification_errors.log",json_encode($error).PHP_EOL,FILE_APPEND);
                } else {
                    for ($d=0; $d<count($responseObj->data); $d++) {

                        if (isset($postFields[$d]['to'])) {
                            $pushNotificationToken = $postFields[$d]['to'];

                            if (isset($objectsData[sha1($pushNotificationToken)])) {
                                $objectData = $objectsData[sha1($pushNotificationToken)];

                                $umdpn = new \app\hig\MUserMobileDevicePushNotification();
                                $umdpn->userId = $objectData->userId;
                                $umdpn->userMobileDeviceId = $objectData->userMobileDeviceId;
                                $umdpn->pushNotificationToken = $pushNotificationToken;
                                $umdpn->receipt = $responseObj->data[$d]->id;
                                $umdpn->body = $objectData->body;
                                $umdpn->status = $responseObj->data[$d]->status;
                                $umdpn->add();

                            }
                        }
                    }
                }

                curl_close($curl);
            } catch (\Exception $e) {
                // Errors negeren maar wel loggen
                $error = new \stdClass();
                $error->datetime = date("Y-m-d H:i:s");
                $error->user_id = -1;
                $error->errorMessage = $e->getMessage() . ' in ' . $e->getFile() . ' on line '.$e->getLine();
                $error->postFields = $postFields;
                file_put_contents("logs/push_notification_errors.log",json_encode($error).PHP_EOL,FILE_APPEND);

            }
        }
    }


    // mails voor inschrijvingen
    
    private function prepareMailRegistrationSignInvitation() {
        // ophalen email afzender
        $this->getEmailSender();
        
        $registration = $this->entity;
        $contactPersons = $registration->registrationCard->contactPersons;
        $partial = m::app()->getCmsPartial('registration-email-sign_invitation');

        // controleren of account manager is gezet voor inschrijving
        $accountManagerRelation = null;
        $intermediaryRelation = null;
        if (!empty($registration->accountManagerRelation)) {
            $partial = m::app()->getCmsPartial('registration-email-sign_invitation-on_behalf-account_manager');
            $accountManagerRelation = $registration->accountManagerRelation;
            $intermediaryRelation = $accountManagerRelation->parent;
        }

        $doc = m::app()->getDocByName('registration-declaration');
        $mailerTemplateId = 94;
        
        // adhv sessie opgeslagen fundRegistrationSlugId de slug bepalen die voor url moet worden gebruikt
        if (empty($_SESSION['fundRegistrationSlug'])) {
            throw new \Exception("No MFundRegistrationSlug id was found in session");
        }
        $fundRegistratonSlug = $_SESSION['fundRegistrationSlug'];
        
        foreach ($contactPersons as $contactPerson) {
            $content = $partial->content;
            $contentParams = [
                '{salutation}' => 'Geachte ' . $contactPerson->getNameForLetter(),
                '{fund}' => $registration->fund->name,
                '{urlSignInvitation}' => DOMAIN . $doc->getUrl([$fundRegistratonSlug->slug]) . '?token='.$contactPerson->signToken,
                '{accountManager}' => !empty($accountManagerRelation) ? $accountManagerRelation->getName() : "",
                '{intermediary}' => !empty($intermediaryRelation) ? $intermediaryRelation->getName() : "",
            ];
            $content = str_replace(array_keys($contentParams), array_values($contentParams), $content);
    
            $subject = $partial->subject;
            $subjectParams = [
                '{fund}' => $registration->fund->name,
            ];
            $subject = str_replace(array_keys($subjectParams), array_values($subjectParams), $subject);
            
            $params = array(
                "SUBJECT" => $subject,
                "BODY" => $content,
                "IMMO_BTN" => DOMAIN,
                "HEADER" => "Betreffende: " . $registration->getAuthorizedEntity() . (!empty($registration->getRelation()) ? ' ('.$registration->relation->id.')' : ''),
            );
    
            $result = \app\CMailer::sendTemplateMail(
                $contactPerson->emailAddress,
                $mailerTemplateId,
                $params,
                [],
                [],
                [],
                [EMAIL_ADDRESS_HIG_MAILINGS],
                null,
                false,
                $this->emailSender
            );
            $this->setStatus($result);
        }
    }
    
    private function prepareMailRegistrationSignedByContactPerson() {
        // ophalen email afzender
        $this->getEmailSender();
        
        $contactPerson = $this->entity;
        $registration = $contactPerson->registration;
        $partial = m::app()->getCmsPartial('registration-email-signed_by_contact_person');
        $mailerTemplateId = 94;
        
        $authorizedEntityPlural = 'ingeschreven personen';
        $registrationCard = $registration->getRegistrationCard();
        if (!empty($registrationCard) && !empty($registrationCard->relation)) {
            switch ($registrationCard->relation->type) {
                case 'contactPerson':
                    $authorizedEntityPlural = 'ingeschreven personen';
                    break;
                case 'organization':
                    $authorizedEntityPlural = 'ingeschreven bestuurders';
                    break;
                case 'collective':
                    $authorizedEntityPlural = 'ingeschreven personen';
                    break;
            }
        }
        
    
        $content = $partial->content;
        $contentParams = [
            '{salutation}' => 'Geachte ' . $contactPerson->getNameForLetter(),
            '{fund}' => $registration->fund->name,
            '{authorizedEntityPlural}' => $authorizedEntityPlural,
        ];
        $content = str_replace(array_keys($contentParams), array_values($contentParams), $content);
    
        $subject = $partial->subject;
        $subjectParams = [
            '{fund}' => $registration->fund->name,
        ];
        $subject = str_replace(array_keys($subjectParams), array_values($subjectParams), $subject);
    
        $params = array(
            "SUBJECT" => $subject,
            "BODY" => $content,
            "IMMO_BTN" => DOMAIN,
            "HEADER" => "Betreffende: " . $registration->getAuthorizedEntity() . (!empty($registration->getRelation()) ? ' ('.$registration->relation->id.')' : ''),
        );
    
        $result = \app\CMailer::sendTemplateMail(
            $contactPerson->emailAddress,
            $mailerTemplateId,
            $params,
            [],
            [],
            [],
            [EMAIL_ADDRESS_HIG_MAILINGS],
            null,
            false,
            $this->emailSender
        );
        $this->setStatus($result);
    }
    
    private function prepareMailRegistrationComplete() {
        // ophalen email afzender
        $this->getEmailSender();
        
        /** @var \app\hig\MRegistration $registration */
        $registration = $this->entity;
        $contactPersons = $registration->registrationCard->contactPersons;
    
        $mailerTemplateId = 94;
        $emailPartial = m::app()->getCmsPartial('registration-acknowledgement_of_receipt');

        // controleren of account manager is gezet voor inschrijving
        $accountManagerRelation = null;
        $intermediaryRelation = null;
        if (!empty($registration->accountManagerRelation)) {
            $emailPartial = m::app()->getCmsPartial('registration-acknowledgement_of_receipt-account_manager');
            $accountManagerRelation = $registration->accountManagerRelation;
            $intermediaryRelation = $accountManagerRelation->parent;
        }
        
        // params voor email
        $contentParams = [
            '{fund}' => $registration->fund->name,
            '{salutation}' => $registration->getSalutation(),
            '{accountManager}' => !empty($accountManagerRelation) ? $accountManagerRelation->getName() : "",
            '{intermediary}' => !empty($intermediaryRelation) ? $intermediaryRelation->getName() : ""
        ];
        $subjectParams = [
            '{fund}' => $registration->fund->name
        ];
        
        $content = str_replace(array_keys($contentParams), array_values($contentParams), $emailPartial->content);
        $subject = str_replace(array_keys($subjectParams), array_values($subjectParams), $emailPartial->subject);

    
        $emailParams = array(
            "SUBJECT" => $subject,
            "BODY" => $content,
            "HEADER" => "Betreffende: " . $registration->getAuthorizedEntity() . (!empty($registration->getRelation()) ? ' ('.$registration->relation->id.')' : ''),
        );
    
    
        // params voor bijlage inschrijving kopie
        $subscriptionForm = $registration->generateSubscriptionForm();
        
        $emailAddresses = [];
        foreach ($contactPersons as $contactPerson) {
            $emailAddresses[] = $contactPerson->emailAddress;
        }

        // controleren of account manager is gezet voor inschrijving
        $ccRecipients = [];
        if (!empty($registration->accountManagerRelation) && !empty($registration->accountManagerRelation->user)) {
            $ccRecipients[] = $registration->accountManagerRelation->user->emailAddress;
        }
        
        $bccRecipients = [EMAIL_ADDRESS_HIG_MAILINGS];
        if (ENVIRONMENT === 'live') {
            $bccRecipients = [EMAIL_ADDRESS_HIG_MAILINGS, EMAIL_ADDRESS_HIG_INFO];
        }
    
        $result = \app\CMailer::sendTemplateMail(
            implode(',', $emailAddresses),
            $mailerTemplateId,
            $emailParams,
            [$subscriptionForm],
            [],
            $ccRecipients,
            $bccRecipients,
            null,
            false,
            $this->emailSender
        );
        $this->setStatus($result);
    }

    private function prepareMailRegistrationAwaitSignConfirmationAccountManager() {
        // ophalen email afzender
        $this->getEmailSender();

        // inschrijving ophalen
        $registration = $this->entity;
        $registrationCard = $registration->registrationCard;

        /** bepalen account manager @var \app\hig\MRelation $accountManagerRelation */
        $accountManagerRelation = $registration->accountManagerRelation;
        $emailAddressAccountManager = !empty($accountManagerRelation->getPrimaryEmailAddress()) ? $accountManagerRelation->getPrimaryEmailAddress()->address : "";

        // bepalen params voor e-mail
        $partial = m::app()->getCmsPartial('registration-email-await_sign_confirmation-account_manager');
        $content = $partial->content;
        $mailerTemplateId = 94;
        $subject = $partial->subject;
        $contentParams = [
            '{salutation}' => $accountManagerRelation->getNameForRegistrationEmail(),
            '{fund}' => $registration->fund->name,
            '{relation}' => $registrationCard->relation->name
        ];

        // vervangen merge velden
        $content = str_replace(array_keys($contentParams), array_values($contentParams), $content);
        $subject = str_replace(array_keys($contentParams), array_values($contentParams), $subject);

        // bepalen params voor daadwerkelijke e-mail
        $params = array(
            "SUBJECT" => $subject,
            "BODY" => $content,
            "IMMO_BTN" => DOMAIN,
            "HEADER" => "Betreffende: " . $registration->getAuthorizedEntity() . (!empty($registration->getRelation()) ? ' ('.$registration->relation->id.')' : ''),
        );

        $result = \app\CMailer::sendTemplateMail(
            $emailAddressAccountManager,
            $mailerTemplateId,
            $params,
            [],
            [],
            [],
            [EMAIL_ADDRESS_HIG_MAILINGS],
            null,
            false,
            $this->emailSender
        );
        $this->setStatus($result);
    }
}
