<?php
namespace app\hig;

use \app\m;

/**
 * MRegistrationRelation
 *
 * @property $table string
 * @property $registrationId int
 * @property $numParticipations int
 * @property $name string
 * @property $type string
 * @property $transactionalMail int
 * @property $cocNumber int
 * @property $cocMediaId int
 * @property $dateTimeCreated int
 */

class MRegistrationRelation extends \app\CDBRecord {
	
	protected $table = 'registration_relation';
	protected $registrationId;
	protected $name;
	protected $type;
	protected $transactionalMail;
	protected $cocNumber;
	protected $cocMediaId;
	protected $originOfResources;
	protected $originOfResourcesComment;
	protected $legalEntityActivities;
	protected $acceptDeclarationUbo;
	protected $uboDeclarationFileMediaId;
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
		'numParticipations' => [
			'description' => 'NUM_PARTICIPATIONS',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'NUM_PARTICIPATIONS',
					'placeholder' => 'NUM_PARTICIPATIONS'
				],
				'order' => 2
			],
			'backend' => [
				'type' => 'int',
				'typeParams' => []
			]
		],
		'name' => [
			'description' => 'NAME',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'NAME',
					'placeholder' => 'NAME'
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
		'transactionalMail' => [
			'description' => 'TRANSACTIONAL_MAIL',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'TRANSACTIONAL_MAIL',
					'placeholder' => 'TRANSACTIONAL_MAIL'
				],
				'order' => 5
			],
			'backend' => [
				'type' => 'int',
				'typeParams' => []
			]
		],
		'cocNumber' => [
			'description' => 'COC_NUMBER',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'COC_NUMBER',
					'placeholder' => 'COC_NUMBER'
				],
				'order' => 6
			],
			'backend' => [
				'type' => 'int',
				'typeParams' => []
			]
		],
		'cocMediaId' => [
			'description' => 'COC_MEDIA_ID',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'COC_MEDIA_ID',
					'placeholder' => 'COC_MEDIA_ID'
				],
				'order' => 7
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
				'order' => 8
			],
			'backend' => [
				'type' => 'int',
				'typeParams' => []
			]
		]
	];
	
	/** @var \app\hig\CChangeService $changeService */
	private $changeService;
	
	public function __construct($db_id = null) {
		parent::__construct($db_id);
		
		$this->changeService = m::app()->serviceManager->get('changeService');
	}
	
	public function addRelated() {}
	
	public function processTmpCocFile($path) {
		$pathInfo = pathinfo($path);
		
		// file verwerking vindt altijd plaats op een al aangemaakte entiteit. 29-08-2022: wijziging tbv synchronisatie van
		// inschrijving zorgt er voor dat alle inschrijvingsentiteiten zullen bestaan in CRM en dus kan file upload
		// worden verwerkt bij al bestaande entiteit
		
		// controleren of er al een update change bestaat voor deze entiteit
		$change = $this->changeService->getRegistrationChangeByProperties($this->id, 'MRegistrationRelation', 'update');
		if (empty($change)) {
			$change = new \app\hig\MChange();
			$change->entity = 'MRegistrationRelation';
			$change->entityId = $this->id;
			$change->type = 'update';
			$change->status = 'no-approval-needed';
			$change->relationId = !empty($this->relationId) ? $this->relationId : null;
			$change->add();
		}
		
		// controleren of er al een change value bestaat voor file upload
		$changeValue = $this->changeService->getChangeValueByProperty($change->id, 'fileNameCocFile');
		$action = empty($changeValue) ? 'add' : 'update';
		if (empty($changeValue)) {
			$changeValue = new \app\hig\MChangeValue();
			$changeValue->changeId = $change->id;
			$changeValue->property = 'fileNameCocFile';
			$changeValue->oldValue = null;
		}
		// nieuwe waarde zetten voor changeValue, ongeacht of deze al bestaat of het gaat om een nieuwe entry
		// hiermee voorkomen we dubbele entries voor changeValues en eventuele dubbele logging etc.
		$changeValue->newValue = $pathInfo['filename'] . '.' . $pathInfo['extension'];
		$changeValue->$action();
	}
}