<?php

namespace app\hig;

use app\m;

/**
 * MRegistrationContactPerson
 *
 * @property $table string
 * @property $registrationId int
 * @property $salutation string
 * @property $firstName string
 * @property $lastNamePrefix string
 * @property $lastName string
 * @property $emailAddress string
 * @property $nationality string
 * @property $idType string
 * @property $idMediaId string
 * @property $signToken string
 * @property $dateTimeSigned string
 * @property $dateTimeCreated string
 */

class MRegistrationContactPerson extends \app\CDBRecord {
	
	protected $table = 'registration_contact_person';
	protected $registrationId;
	protected $relationId;
	protected $salutation;
	protected $initials;
	protected $lastNamePrefix;
	protected $lastName;
	protected $emailAddress;
	protected $nationality;
	protected $idType;
	protected $idMediaId;
	protected $idProcessed;
	protected $currentProfession;
	protected $industry;
	protected $originOfResources;
	protected $originOfResourcesComment;
	protected $residentOfUnitedStates;
	protected $otherEmployment;
	protected $isPep;
	protected $isUbo;
	protected $interestRate;
	protected $ownershipRate;
	protected $isPseudoUbo;
	protected $acceptDeclaration;
	protected $comments;
	protected $signToken;
	protected $dateTimeSigned;
	protected $dateTimeCreated;
	
	protected $fields = [
		'registrationId' => [
			'description' => 'REGISTRATION_ID',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'REGISTRATION_ID',
					'placeholder' => 'REGISTRATION_ID'
				],
				'order' => 1
			],
			'backend' => [
				'type' => 'int',
				'typeParams' => []
			]
		],
		'salutation' => [
			'description' => 'SALUTATION',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'SALUTATION',
					'placeholder' => 'SALUTATION'
				],
				'order' => 2
			],
			'backend' => [
				'type' => 'text',
				'typeParams' => []
			]
		],
		'firstName' => [
			'description' => 'FIRST_NAME',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'FIRST_NAME',
					'placeholder' => 'FIRST_NAME'
				],
				'order' => 3
			],
			'backend' => [
				'type' => 'text',
				'typeParams' => []
			]
		],
		'lastNamePrefix' => [
			'description' => 'LAST_NAME_PREFIX',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'LAST_NAME_PREFIX',
					'placeholder' => 'LAST_NAME_PREFIX'
				],
				'order' => 4
			],
			'backend' => [
				'type' => 'text',
				'typeParams' => []
			]
		],
		'lastName' => [
			'description' => 'LAST_NAME',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'LAST_NAME',
					'placeholder' => 'LAST_NAME'
				],
				'order' => 5
			],
			'backend' => [
				'type' => 'text',
				'typeParams' => []
			]
		],
		'emailAddress' => [
			'description' => 'EMAIL_ADDRESS',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'EMAIL_ADDRESS',
					'placeholder' => 'EMAIL_ADDRESS'
				],
				'order' => 6
			],
			'backend' => [
				'type' => 'text',
				'typeParams' => []
			]
		],
		'nationality' => [
			'description' => 'NATIONALITY',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'NATIONALITY',
					'placeholder' => 'NATIONALITY'
				],
				'order' => 7
			],
			'backend' => [
				'type' => 'text',
				'typeParams' => []
			]
		],
		'idType' => [
			'description' => 'ID_TYPE',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'ID_TYPE',
					'placeholder' => 'ID_TYPE'
				],
				'order' => 8
			],
			'backend' => [
				'type' => 'text',
				'typeParams' => []
			]
		],
		'idMediaId' => [
			'description' => 'ID_MEDIA_ID',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'ID_MEDIA_ID',
					'placeholder' => 'ID_MEDIA_ID'
				],
				'order' => 9
			],
			'backend' => [
				'type' => 'text',
				'typeParams' => []
			]
		],
		'signToken' => [
			'description' => 'SIGN_TOKEN',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'SIGN_TOKEN',
					'placeholder' => 'SIGN_TOKEN'
				],
				'order' => 10
			],
			'backend' => [
				'type' => 'text',
				'typeParams' => []
			]
		],
		'dateTimeSigned' => [
			'description' => 'DATE_TIME_SIGNED',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'DATE_TIME_SIGNED',
					'placeholder' => 'DATE_TIME_SIGNED'
				],
				'order' => 11
			],
			'backend' => [
				'type' => 'text',
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
				'order' => 12
			],
			'backend' => [
				'type' => 'text',
				'typeParams' => []
			]
		]
	];
	
	/** @var \app\hig\CRegistrationService $registrationService */
	private $registrationService;
	/** @var \app\hig\CEmailService $emailService */
	private $emailService;
	/** @var \app\hig\CChangeService $changeService */
	private $changeService;
	
	private $phoneNumbers;
	private $name;
	private $registration;
	private $address;
	
	public function __construct($db_id = null) {
		parent::__construct($db_id);
		
		$this->registrationService = m::app()->serviceManager->get('registrationService');
		$this->emailService = m::app()->serviceManager->get('emailService');
		$this->changeService = m::app()->serviceManager->get('changeService');
	}
	
	public function addRelated() {}
	
	public function updateRelated() {}
	
	public function getPhoneNumbers() {
		if (empty($this->phoneNumbers)) {
			$this->phoneNumbers = $this->registrationService->getRegistrationContactPersonPhoneNumbers($this->id);
		}
		
		return $this->phoneNumbers;
	}
	
	public function getName() {
		if (empty($this->name)) {
			$this->name = $this->salutation . ' ';
			$this->name .= $this->initials . ' ';
			if (!empty($this->lastNamePrefix)) {
				$this->name .= $this->lastNamePrefix . ' ';
			}
			$this->name .= $this->lastName;
		}
		return $this->name;
	}
	
	public function getNameForLetter() {
		$fullNameArray = array();
		
		if(!empty($this->salutation)){
			// lidwoord opheffen bij aanhef "De heer"
			$salutation = str_ireplace('de', '', $this->salutation);
			$fullNameArray[] = lcfirst($salutation);
		}
		
		/*
		 * 12-08-2022: Geen initialen toevoegen bij aanhef - MH-420
		 *
		if(!empty($this->initials)){
			// controleren of initialen zijn afgesloten met .
			$lastCharacter = substr($this->initials, -1);
			$initials = $this->initials;
			if ($lastCharacter !== '.') {
				$initials .= '.';
			}
			
			$fullNameArray[] = $initials;
		}
		*/
		
		if(!empty($this->lastNamePrefix)){
			$fullNameArray[] = ucfirst($this->lastNamePrefix);
		}
		
		if(!empty($this->lastName)){
			$fullNameArray[] = ucfirst($this->lastName);
		}
		
		return implode(' ', $fullNameArray);
	}
	
	public function sendEmailRegistrationSigned() {
		$this->emailService->processNotificationMail('registration-signed_by_contact_person', null, $this);
		return $this->emailService->getStatus();
	}
	
	public function getRegistration() {
		if (empty($this->registration) && !empty($this->registrationId)) {
			$this->registration = new \app\hig\MRegistration($this->registrationId);
		}
		return $this->registration;
	}
	
	public function processTmpIdFile($path) {
		$pathInfo = pathinfo($path);
		
		// file verwerking vindt altijd plaats op een al aangemaakte entiteit. 29-08-2022: wijziging tbv synchronisatie van
		// inschrijving zorgt er voor dat alle inschrijvingsentiteiten zullen bestaan in CRM en dus kan file upload
		// worden verwerkt bij al bestaande entiteit
		
		// controleren of er al een update change bestaat voor deze entiteit
		$change = $this->changeService->getRegistrationChangeByProperties($this->id, 'MRegistrationContactPerson', 'update');
		if (empty($change)) {
			$change = new \app\hig\MChange();
			$change->entity = 'MRegistrationContactPerson';
			$change->entityId = $this->id;
			$change->type = 'update';
			$change->status = 'no-approval-needed';
			$change->relationId = !empty($this->relationId) ? $this->relationId : null;
			$change->add();
		}
		
		// controleren of er al een change value bestaat voor file upload
		$changeValue = $this->changeService->getChangeValueByProperty($change->id, 'fileNameIdFile');
		$action = empty($changeValue) ? 'add' : 'update';
		if (empty($changeValue)) {
			$changeValue = new \app\hig\MChangeValue();
			$changeValue->changeId = $change->id;
			$changeValue->property = 'fileNameIdFile';
			$changeValue->oldValue = null;
		}
		// nieuwe waarde zetten voor changeValue, ongeacht of deze al bestaat of het gaat om een nieuwe entry
		// hiermee voorkomen we dubbele entries voor changeValues en eventuele dubbele logging etc.
		$changeValue->newValue = $pathInfo['filename'] . '.' . $pathInfo['extension'];
		$changeValue->$action();
	}
	
	public function getAddress() {
		if (empty($this->address)) {
			$this->address = $this->registrationService->getRegistrationContactPersonAddress($this->id);
		}
		
		return $this->address;
	}
	
	/**
	 * Controleren of entiteit legitimatiebewijs heeft geüpload tijdens registratie
	 *
	 * @return bool
	 */
	public function hasUploadedIdFile() {
		if (empty($this->idMediaId)) {
			// controleren of er changes bestaan voor deze entiteit en kijken of daar een value voor het legitimatiebewijs bij voorkomt
			$change = $this->changeService->getRegistrationChangeByProperties($this->id, 'MRegistrationContactPerson', 'update');
			if (!empty($change)) {
				$changeValue = $this->changeService->getChangeValueByProperty($change->id, 'fileNameIdFile');
				if (!empty($changeValue)) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}
		
		return true;
	}
}