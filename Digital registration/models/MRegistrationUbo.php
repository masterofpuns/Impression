<?php
namespace app\hig;

use \app\m;
use \app\h;

/**
 * MRegistrationUbo
 *
 * @property $table string
 * @property $registrationId int
 * @property $salutation string
 * @property $initials string
 * @property $lastNamePrefix string
 * @property $lastName string
 * @property $nationality string
 * @property $idType string
 * @property $idMediaId string
 * @property $street string
 * @property $number string
 * @property $numberSuffix string
 * @property $postalCode string
 * @property $city string
 * @property $country string
 * @property $interestRate int
 * @property $ownershipRate int
 * @property $dateTimeCreated string
 */

class MRegistrationUbo extends \app\CDBRecord {
	
	protected $table = 'registration_ubo';
	protected $registrationId;
	protected $relationId;
	protected $salutation;
	protected $initials;
	protected $lastNamePrefix;
	protected $lastName;
	protected $nationality;
	protected $idType;
	protected $idMediaId;
	protected $idProcessed;
	protected $street;
	protected $number;
	protected $numberSuffix;
	protected $postalCode;
	protected $city;
	protected $country;
	protected $residentOfUnitedStates;
	protected $isPep;
	protected $interestRate;
	protected $ownershipRate;
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
		'initials' => [
			'description' => 'INITIALS',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'INITIALS',
					'placeholder' => 'INITIALS'
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
		'nationality' => [
			'description' => 'NATIONALITY',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'NATIONALITY',
					'placeholder' => 'NATIONALITY'
				],
				'order' => 6
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
				'order' => 7
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
				'order' => 8
			],
			'backend' => [
				'type' => 'text',
				'typeParams' => []
			]
		],
		'street' => [
			'description' => 'STREET',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'STREET',
					'placeholder' => 'STREET'
				],
				'order' => 9
			],
			'backend' => [
				'type' => 'text',
				'typeParams' => []
			]
		],
		'number' => [
			'description' => 'NUMBER',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'NUMBER',
					'placeholder' => 'NUMBER'
				],
				'order' => 10
			],
			'backend' => [
				'type' => 'text',
				'typeParams' => []
			]
		],
		'numberSuffix' => [
			'description' => 'NUMBER_SUFFIX',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'NUMBER_SUFFIX',
					'placeholder' => 'NUMBER_SUFFIX'
				],
				'order' => 11
			],
			'backend' => [
				'type' => 'text',
				'typeParams' => []
			]
		],
		'postalCode' => [
			'description' => 'POSTAL_CODE',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'POSTAL_CODE',
					'placeholder' => 'POSTAL_CODE'
				],
				'order' => 12
			],
			'backend' => [
				'type' => 'text',
				'typeParams' => []
			]
		],
		'city' => [
			'description' => 'CITY',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'CITY',
					'placeholder' => 'CITY'
				],
				'order' => 13
			],
			'backend' => [
				'type' => 'text',
				'typeParams' => []
			]
		],
		'country' => [
			'description' => 'COUNTRY',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'COUNTRY',
					'placeholder' => 'COUNTRY'
				],
				'order' => 14
			],
			'backend' => [
				'type' => 'text',
				'typeParams' => []
			]
		],
		'interestRate' => [
			'description' => 'INTEREST_RATE',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'INTEREST_RATE',
					'placeholder' => 'INTEREST_RATE'
				],
				'order' => 15
			],
			'backend' => [
				'type' => 'int',
				'typeParams' => []
			]
		],
		'ownershipRate' => [
			'description' => 'OWNERSHIP_RATE',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'OWNERSHIP_RATE',
					'placeholder' => 'OWNERSHIP_RATE'
				],
				'order' => 16
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
				'order' => 17
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
		
		if(!empty($this->initials)){
			// controleren of initialen zijn afgesloten met .
			$lastCharacter = substr($this->initials, -1);
			$initials = $this->initials;
			if ($lastCharacter !== '.') {
				$initials .= '.';
			}
			
			$fullNameArray[] = $initials;
		}
		
		if(!empty($this->lastNamePrefix)){
			$fullNameArray[] = ucfirst($this->lastNamePrefix);
		}
		
		if(!empty($this->lastName)){
			$fullNameArray[] = ucfirst($this->lastName);
		}
		
		return implode(' ', $fullNameArray);
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
		$change = $this->changeService->getRegistrationChangeByProperties($this->id, 'MRegistrationUbo', 'update');
		if (empty($change)) {
			$change = new \app\hig\MChange();
			$change->entity = 'MRegistrationUbo';
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
			$this->address = new \app\hig\MAddress();
			$this->address->street = $this->street;
			$this->address->number = $this->number;
			$this->address->numberSuffix = $this->numberSuffix;
			$this->address->postalCode = $this->postalCode;
			$this->address->city = $this->city;
			$this->address->country = $this->country;
		}
		
		return $this->address;
	}
}