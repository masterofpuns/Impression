<?php
namespace app\hig;

use \app\h;
use \app\m;

/**
 * MRegistration
 *
 * @property $table string
 * @property $fundId int
 * @property $numParticipations int
 * @property $status string
 * @property $relationId int
 * @property $addressId int
 * @property $bankAccountId int
 * @property $dateTimeCreated int
 */

class MRegistration extends \app\CDBRecord {
	
	protected $table = 'registration';
	protected $type;
	protected $fundId;
	protected $numParticipations;
	protected $status;
	protected $relationId;
	protected $addressId;
	protected $bankAccountId;
	protected $intermediaryRelationId;
	protected $intermediaryCommissionType;
	protected $intermediaryCommissionValue;
	protected $emissionType;
	protected $emissionValue;
	protected $note;
	protected $dateTimeCreated;
	protected $dateTimeValid;
	protected $subscriptionFormMediaId;
	protected $utmCode;
	protected $accountManagerRelationId;

	protected $fields = [
		'fundId' => [
			'description' => 'FUND_ID',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'FUND_ID',
					'placeholder' => 'FUND_ID'
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
		'status' => [
			'description' => 'STATUS',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'STATUS',
					'placeholder' => 'STATUS'
				],
				'order' => 3
			],
			'backend' => [
				'type' => 'text',
				'typeParams' => []
			]
		],
		'relationId' => [
			'description' => 'RELATION_ID',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'RELATION_ID',
					'placeholder' => 'RELATION_ID'
				],
				'order' => 4
			],
			'backend' => [
				'type' => 'int',
				'typeParams' => []
			]
		],
		'addressId' => [
			'description' => 'ADDRESS_ID',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'ADDRESS_ID',
					'placeholder' => 'ADDRESS_ID'
				],
				'order' => 5
			],
			'backend' => [
				'type' => 'int',
				'typeParams' => []
			]
		],
		'bankAccountId' => [
			'description' => 'BANK_ACCOUNT_ID',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'BANK_ACCOUNT_ID',
					'placeholder' => 'BANK_ACCOUNT_ID'
				],
				'order' => 6
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
				'order' => 7
			],
			'backend' => [
				'type' => 'int',
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
	/** @var \app\hig\MRelation $relation */
	private $relation;
	private $contactPersons;
	private $address;
	private $bankAccount;
	private $fund;
	private $participationsValue;
	private $intermediary;
	private $registrationCard;
	private $emissionCost;
	private $tags;
	private $fervour;
	private $reservation;
	private $partaking;
	private $accountManagerRelation;
	
	public function __construct($db_id = null) {
		parent::__construct($db_id);
		
		if (empty($this->emissionType)) {
			$this->emissionType = 'percentage'; // default %
		}
		if (empty($this->emissionValue)) {
			$this->emissionValue = 3; // default %
		}
		
		$this->registrationService = m::app()->serviceManager->get('registrationService');
		$this->emailService = m::app()->serviceManager->get('emailService');
		$this->changeService = m::app()->serviceManager->get('changeService');
	}
	public function addRelated() {}
	public function updateRelated() {}
	
	public function getRelation() {
		if (empty($this->relation) && !empty($this->relationId)) {
			$this->relation = new \app\hig\MRelation($this->relationId);
		}
		return $this->relation;
	}
	public function getAddress() {
		if (empty($this->address) && !empty($this->addressId)) {
			$this->address = new \instance\MAddress($this->addressId);
		}
		return $this->address;
	}
	public function getBankAccount() {
		if (empty($this->bankAccount) && !empty($this->bankAccountId)) {
			$this->bankAccount = new \instance\MBankAccount($this->bankAccountId);
		}
		return $this->bankAccount;
	}
	public function getFund() {
		if (empty($this->fund) && !empty($this->fundId)) {
			$this->fund = new \app\hig\MFund($this->fundId);
		}
		
		return $this->fund;
	}
	public function getParticipationsValue() {
		if (empty($this->participationsValue) && !empty($this->numParticipations)) {
			$this->getFund();
			$this->participationsValue = $this->numParticipations * (!empty($this->fund->currentValue) ? $this->fund->currentValue : $this->fund->value);
		}
		return $this->participationsValue;
	}
	public function getIntermediary() {
		if (empty($this->intermediary) && !empty($this->intermediaryRelationId)) {
			$this->intermediary = new \app\hig\MRelation($this->intermediaryRelationId);
		}
		return $this->intermediary;
	}
	public function getIntermediaryCommission() {
		switch ($this->intermediaryCommissionType) {
			case 'none' :
				return t('NONE');
			case 'percentage' :
				// controleren of huidige gegevens voor frontend of backend zijn
				$value =  substr($this->intermediaryCommissionValue,-3,1) == ',' ?
					$this->intermediaryCommissionValue : number_format($this->intermediaryCommissionValue, 2, ',', '.');
				
				return $value . '%';
			
			case 'fixed' :
				// controleren of huidige gegevens voor frontend of backend zijn
				$value =  substr($this->intermediaryCommissionValue,-3,1) == ',' ?
					$this->intermediaryCommissionValue : number_format($this->intermediaryCommissionValue, 2, ',', '.');
				
				return '€ ' . $value;
			default :
				return t('NONE');
		}
		
		return '';
	}
	public function getIntermediaryCommissionAsCurrency() {        $this->getParticipationsValue();
		
		$changeBack = false;
		
		// Check of huidige staat backend/frontend is
		if (substr($this->intermediaryCommissionValue,-3,1) == ',') {
			$this->convertPostValues();
			$changeBack = true;
		}
		
		switch ($this->intermediaryCommissionType) {
			case 'none' :
				$cost =  '€ ' . '0,00';
				break;
			case 'percentage' :
				$amount = $this->intermediaryCommissionValue / 100 * $this->getParticipationsValue(true);
				$cost = '€ ' . number_format($amount, 2, ',' , '.' );
				break;
			case 'fixed' :
				$cost =  '€ ' . number_format($this->intermediaryCommissionValue, 2, ',' , '.' );
				break;
			default :
				$cost =  '€' . '0,00';
		}
		
		// Backend/frontend staat herstellen
		if ($changeBack)
			$this->convertBackendValues();
		
		return $cost;
	}
	public function getDatatableRow() {
		$this->getParticipationsValue();
		$this->getIntermediary();
		$this->getFund();
		
		$link = m::app()->renderPartial(
			'link/default',
			array(
				'url' => $this->getUrlView(),
				'description' => $this->fund->name
			),
			true
		);
		
		return [
			$link,
			$this->getTypeIcon(),
			$this->numParticipations,
			h::formatCurrency($this->participationsValue),
			!empty($this->intermediary) ? $this->intermediary->nameSortable : 'Onbekend',
			$this->getStatusDescriptionShort(),
			date('d-m-Y', strtotime($this->dateTimeCreated)),
			$this->getActionsInPopover()
		];
	}
	public function getDatatableRowFund() {
		$this->getParticipationsValue();
		$this->getIntermediary();
		
		$selectionCheckbox = m::app()->renderPartial(
			'form/control/checkboxFund',
			[
				'name' => 'registration-selection',
				'data' =>[
					'hook' => 'registration-selector',
					'id' => $this->id,
					'name' => $this->getNameForRelation()
				]
			],
			true
		);
		
		$link = m::app()->renderPartial(
			'link/default',
			array(
				'url' => $this->getUrlView(),
				'description' => $this->getNameForRelation()
			),
			true
		);
		
		return [
			$selectionCheckbox,
			$link,
			$this->getTypeIcon(),
			$this->numParticipations,
			h::formatCurrency($this->participationsValue),
			!empty($this->intermediary) ? $this->intermediary->nameSortable : 'Onbekend',
			$this->getStatusDescriptionShort(),
			date('d-m-Y', strtotime($this->dateTimeCreated)),
			$this->getActionsInPopover()
		];
	}
	public function getActions($inPopover = false){
		$this->getRelation();
		$this->getFund();
		
		$actions = [];
		
		if (m::app()->user->isSecretary) {
			if (
				m::app()->checkPermission('fund-registration-edit') &&
				$this->status !== 'assign-rid'
			) {
				$action = new \stdClass;
				$action->class = 'edit';
				$action->description = t('EDIT');
				$action->icon = 'edit';
				$action->type = 'link';
				$action->url = $this->getUrlEdit();
				$actions[] = $action;
			}
			
			if (
				m::app()->checkPermission('fund-registration-edit') &&
				$this->status == 'assign-rid'
			) {
				$action = new \stdClass;
				$action->class = 'edit';
				$action->description = t('ASSIGN_RID_SHORT');
				$action->icon = 'edit';
				$action->type = 'link';
				$action->url = '#';//$this->getUrlAssignRelationId();
				$actions[] = $action;
			}
		}
		
		return $actions;
	}
	private function getUrlBase() {
		$fund = $this->getFund();
		return $fund->getUrlView() . '/' . 'registration' . '/' . $this->id;
	}
	public function getUrlEdit() {
		return $this->getUrlBase() . '/edit';
	}
	public function getUrlView() {
		return $this->getUrlBase() . '/view';
	}
	public function getRegistrationCard() {
		if (empty($this->registrationCard)) {
			$this->registrationCard = new \stdClass();
			$this->registrationCard->relation = $this->registrationService->getRegistrationRelation($this->id);
			$this->registrationCard->contactPersons = $this->registrationService->getRegistrationContactPersons($this->id);
			$this->registrationCard->address = $this->registrationService->getRegistrationAddress($this->id);
			$this->registrationCard->bankAccount = $this->registrationService->getRegistrationBankAccount($this->id);
			$this->registrationCard->ubos = $this->registrationService->getRegistrationUbos($this->id);
		}
		
		return $this->registrationCard;
	}
	public function getStatusDescription() {
		$status = $this->status;
		
		switch ($this->status) {
			case 'complete':
				$status = 'registration-' . $this->status;
				break;
			case 'declined':
				// bepalen naar welk fonds inschrijving is geexporteerd
				break;
		}
		
		$status = str_replace('-', '_', $status);
		return t(strtoupper($status));
	}
	public function getStatusDescriptionShort() {
		$status = $this->status . '-short';
		$status = str_replace('-', '_', $status);
		return t(strtoupper($status));
	}
	public function getNameForRelation() {
		$relation = $this->getRelation();
		// wanneer er nog geen relatie bestaat, ophalen inschrijvingskaart
		if (empty($relation)) {
			$registrationCard = $this->getRegistrationCard();
			$relation = $registrationCard->relation;
		}
		
		return $relation->name;
	}
	public function getTags() {
		if(empty($this->tags) && !empty($this->id)){
			$this->tags = $this->registrationService->getTagsForRegistration($this);
		}
		
		return $this->tags;
	}
	public function getTypeIcon() {
		switch ($this->type) {
			case 'digital':
				return '<i class="higicon-email"></i>';
				break;
			case 'mail':
				return '<i class="higicon-registration-mail"></i>';
				break;
		}
	}
	/**
	 * Geeft de emissiekosten weer zoals ingevuld in formulier
	 *
	 * @return string
	 */
	/*public function getEmissionCostDescription() {
		$changeBack = false;
		
		// Check of huidige staat backend/frontend is
		if (substr($this->emissionValue,-3,1) == ',') {
			$this->convertPostValues();
			$changeBack = true;
		}
		
		switch ($this->emissionType) {
			case 'none' :
				$cost = t('NONE');
				break;
			case 'percentage' :
				$cost = number_format($this->emissionValue, 2, ',', '.') . '%';
				break;
			case 'fixed' :
				$cost = '€ ' . h::formatCurrency($this->emissionValue);
				break;
			default :
				$cost = t('NONE');
		}
		
		// Backend/frontend staat herstellen
		if ($changeBack)
			$this->convertBackendValues();
		
		return $cost;
		
	}*/
	/**
	 * Totale kosten van participatie (participatie + emissiekosten)
	 *
	 */
	public function getTotalValueAssignedDescription() {
		$cost = $this->getEmissionCostDescriptionCalculated(true);
		$cost+= $this->getParticipationsValue();
		
		return h::formatCurrency($cost);
	}
	/**
	 * Geeft de berekende emissiekosten weer
	 *
	 * @param bool $raw
	 * @return string
	 */
	public function getEmissionCostDescriptionCalculated($raw = false) {
		$this->getParticipationsValue();
		
		$changeBack = false;
		
		// Check of huidige staat backend/frontend is
		if (substr($this->emissionValue,-3,1) == ',') {
			$this->convertPostValues();
			$changeBack = true;
		}
		
		switch ($this->emissionType) {
			case 'none' :
				return 0;
			case 'percentage' :
				$cost = $this->emissionValue / 100 * $this->participationsValue;
				break;
			case 'fixed' :
				$cost =  $this->emissionValue;
				break;
			default :
				$cost = 0;
		}
		
		if ($raw) return $cost;
		
		// Backend/frontend staat herstellen
		if ($changeBack)
			$this->convertBackendValues();
		
		return h::formatCurrency($cost);
	}
	public function getFervour() {
		if (empty($this->fervour) && !empty($this->id)) {
			$this->getFund();
			$this->getRelation();
			
			if (!empty($this->fund) && !empty($this->relation)) {
				$this->fervour = m::app()->db->querySingle("
		                SELECT *
		                FROM fervour
		                WHERE relationId = :relationId
		                AND fundId = :fundId
		                ORDER BY id ASC
		                LIMIT 1
	                ",
					[
						':relationId' => $this->relation->id,
						':fundId' => $this->fund->id
					],
					'\\app\\hig\\MFervour'
				);
			}
		}
		
		return $this->fervour;
	}
	public function getReservation() {
		if (empty($this->reservation) && !empty($this->id)) {
			$this->getFund();
			$this->getRelation();
			
			if (!empty($this->fund) && !empty($this->relation)) {
				$this->reservation = m::app()->db->querySingle("
		                SELECT *
		                FROM reservation
		                WHERE relationId = :relationId
		                AND fundId = :fundId
		                ORDER BY id ASC
		                LIMIT 1
	                ",
					[
						':relationId' => $this->relation->id,
						':fundId' => $this->fund->id
					],
					'\\app\\hig\\MReservation'
				);
			}
		}
		
		return $this->reservation;
	}
	public function getPartaking() {
		if (empty($this->partaking) && !empty($this->id)) {
			$this->getFund();
			$this->getRelation();
			
			if (!empty($this->fund) && !empty($this->relation)) {
				$this->partaking = m::app()->db->querySingle("
		                SELECT *
		                FROM partaking
		                WHERE relationId = :relationId
		                AND fundId = :fundId
		                ORDER BY id ASC
		                LIMIT 1
	                ",
					[
						':relationId' => $this->relation->id,
						':fundId' => $this->fund->id
					],
					'\\app\\hig\\MPartaking'
				);
			}
		}
		
		return $this->partaking;
	}
	
	public function sendEmailRegistrationSignInvitation() {
		$this->emailService->processNotificationMail('registration-sign_invitation', null, $this);
		return $this->emailService->getStatus();
	}
	public function sendEmailRegistrationComplete() {
		$this->emailService->processNotificationMail('registration-complete', null, $this);
		return $this->emailService->getStatus();
	}
	public function sendEmailRegistrationForAccountManager() {
		$this->emailService->processNotificationMail('registration-await_sign_confirmation-account_manager', null, $this);
		return $this->emailService->getStatus();
	}
	public function getAuthorizedEntity() {
		$this->getRegistrationCard();
		$contactPersons = $this->registrationCard->contactPersons;
		
		$authorizedEntity = $this->registrationCard->relation->name;
		if ($this->registrationCard->relation->type != 'organization' && !empty($contactPersons)) {
			$authorizedEntity = "";
			foreach ($contactPersons as $contactPerson) {
				if (!empty($authorizedEntity)) {
					$authorizedEntity .= ' en ';
				}
				$authorizedEntity .= lcfirst($contactPerson->name);
			}
		}
		
		return $authorizedEntity;
	}
	public function getAuthorizedContactPerson($token) {
		$this->getRegistrationCard();
		$contactPersons = $this->registrationCard->contactPersons;
		
		$authorizedContactPerson = null;
		if (!empty($contactPersons)) {
			foreach ($contactPersons as $contactPerson) {
				if (!empty($token) && $token == $contactPerson->signToken) {
					$authorizedContactPerson = $contactPerson;
					break;
				}
			}
		}
		return $authorizedContactPerson;
	}
	public function getSalutation() {
		$this->getRegistrationCard();
		$contactPersons = $this->registrationCard->contactPersons;
		
		$salutation = "";
		if (!empty($contactPersons)) {
			foreach ($contactPersons as $contactPerson) {
				if (!empty($salutation)) {
					$salutation .= ' en ';
				}
				$salutation .= lcfirst($contactPerson->getNameForLetter());
			}
		}
		$salutation = "Geachte " . $salutation;
		
		return $salutation;
	}

	/**
	 * FUNCTIES VOOR AANMAKEN EN VERWERKEN KOPIE INSCHRIJVING
	 */
	public function generateSubscriptionForm() {
		$this->getRegistrationCard();
		$this->getFund();
		$this->getRelation();
		
		$html2Pdf = new \Spipu\Html2Pdf\Html2Pdf("P", "A4", "nl", true, 'UTF-8', [20,50,20,30]);
		$filename = h::toAscii($this->registrationCard->relation->name) . '_Inschrijfformulier_'.date('dmYHis').'.pdf';
		$path = DOC_STORAGE_LOCATION . 'temp/export/';
		$subscriptionForm = $path . $filename;
		$registrationCopyPartial = null;
		$registrationCard = null;
		$signees = [];
		
		switch ($this->registrationCard->relation->type) {
			case 'contactPerson':
				$registrationCopyPartial = m::app()->getCmsPartial('registration-copy_of_registration-contact_person');
				$registrationCopy = m::app()->renderPartial('registration/components/registration-card-contact_person', ['registration' => $this], true, new \app\MEnvironment(2));
				break;
			case 'collective':
				$registrationCopyPartial = m::app()->getCmsPartial('registration-copy_of_registration-collective');
				$registrationCopy = m::app()->renderPartial('registration/components/registration-card-collective', ['registration' => $this], true, new \app\MEnvironment(2));
				break;
			case 'organization':
				$registrationCopyPartial = m::app()->getCmsPartial('registration-copy_of_registration-organization');
				$registrationCopy = m::app()->renderPartial('registration/components/registration-card-organization', ['registration' => $this], true, new \app\MEnvironment(2));
				break;
		}
		
		foreach ($this->registrationCard->contactPersons as $contactPerson) {
			if (!empty($contactPerson->dateTimeSigned)) {
				$entry = date('d-m-Y H:i', strtotime($contactPerson->dateTimeSigned)) . ' uur door ' . $contactPerson->name;
				if (!empty($contactPerson->comments)) {
					$entry .= ": <i>" . $contactPerson->comments . '</i>';
				}
				$signees[] = $entry;
			}
		}
		
		$contentParams = [
			'${numParticipations}' => $this->numParticipations,
			'${participationPlurality}' => ($this->numParticipations > 1 ? 'participaties' : 'participatie'),
			'${participationsValue}' => h::formatCurrency($this->getParticipationsValue()),
			'${participationValue}' => !empty($this->fund->currentValue) ? h::formatCurrency($this->fund->currentValue) : h::formatCurrency($this->fund->value),
			'${emissionCost}' => $this->getEmissionCostDescription(),
			'${registrationCard}' => $registrationCopy,
			'${authorizedEntity}' => $this->getAuthorizedEntity(),
			'${visitingAddressStreet}' => $this->registrationCard->address->street,
			'${visitingAddressHouseNumberFull}' => $this->registrationCard->address->number . $this->registrationCard->address->numberSuffix,
			'${visitingAddressPostalCode}' => $this->registrationCard->address->postalCode,
			'${visitingAddressCity}' => $this->registrationCard->address->city,
			'${fund}' => $this->fund->name,
			'${dateTimePayment}' => !empty($this->fund->dateTimePayment) ? strftime("%e %B %Y", strtotime($this->fund->dateTimePayment)) : "[BETAALDATUM]",
			'${foundationBankAccount}' => !empty($this->fund->foundation) && !empty($this->fund->foundation->bankAccount) ? $this->fund->foundation->bankAccount : "[REKENINGNUMMER VAN STICHTING]",
			'${foundation}' => !empty($this->fund->foundation) ? $this->fund->foundation->description : "[STICHTING]",
			'${curator}' => !empty($this->fund->curator) ? $this->fund->curator->description : "[BEHEERDER]",
			'${signees}' => implode("<br />", $signees)
		];
		$content = str_replace(array_keys($contentParams), array_values($contentParams), $registrationCopyPartial->content);
        $html2Pdf->writeHTML($content);
		$html2Pdf->output($subscriptionForm, 'F');
		
		$letterBackgroundOther = LETTER_BACKGROUND_ROOT . 'letterBackgroundOtherPages.pdf';
		$command = CPDF_EXECUTABLE . ' -stamp-under ' . $letterBackgroundOther . ' ' . $subscriptionForm . ' 1-end -o ' . $subscriptionForm;
		exec($command, $output);
		
		// change value toevoegen aan registration om te verwerken in crm
		$this->processCopyOfRegistration($subscriptionForm);
		
		return $subscriptionForm;
	}
	public function processCopyOfRegistration($path) {
		$pathInfo = pathinfo($path);
		
		// controleren of er al changes bestaan
		$change = $this->changeService->getRegistrationChangeByProperties($this->id, 'MRegistration', 'update');
		// wanneer change nog steeds leeg is, is inschrijving al verwerkt en moeten we een update chagne aanmaken
		if (empty($change)) {
			$change = new \app\hig\MChange();
			$change->entity = 'MRegistration';
			$change->entityId = $this->id;
			$change->type = 'update';
			$change->status = 'no-approval-needed';
			$change->relationId = null;
			$change->add();
		}
		
		$changeValue = $this->changeService->getChangeValueByProperty($change->id, 'subscriptionFormFile');
		$action = empty($changeValue) ? 'add' : 'update';
		if (empty($changeValue)) {
			$changeValue = new \app\hig\MChangeValue();
			$changeValue->changeId = $change->id;
			$changeValue->property = 'subscriptionFormFile';
			$changeValue->oldValue = null;
		}
		// nieuwe waarde zetten voor changeValue, ongeacht of deze al bestaat of het gaat om een nieuwe entry
		// hiermee voorkomen we dubbele entries voor changeValues en eventuele dubbele logging etc.
		$changeValue->newValue = $pathInfo['filename'] . '.' . $pathInfo['extension'];
		$changeValue->$action();
	}

	/**
	 * FUNCTIES VOOR AANMAKEN EN VERWERKEN UBO VERKLARING
	 */
	public function generateUboDeclarationFile() {
		$this->getRegistrationCard();
		$this->getFund();
		$this->getRelation();

		$html2Pdf = new \Spipu\Html2Pdf\Html2Pdf("P", "A4", "nl", true, 'UTF-8', [20,50,20,30]);
		$filename = h::toAscii($this->registrationCard->relation->name) . '_UboVerklaring_'.date('dmYHis').'.pdf';
		$path = DOC_STORAGE_LOCATION . 'temp/export/';
		$uboDeclarationFile = $path . $filename;

		$uboDeclaration = m::app()->getCmsPartial('registration-ubo_declaration');
		$uboSection = m::app()->renderPartial('registration/components/registration-ubo_section', ['registration' => $this], true, new \app\MEnvironment(2));

		$contentParams = [
			'${legalEntity}' => $this->registrationCard->relation->name,
			'${uboSection}' => $uboSection,
			'${legalEntityCity}' => $this->registrationCard->address->city,
			'${currentDate}' => date('d-m-Y H:i'),
		];
		$content = str_replace(array_keys($contentParams), array_values($contentParams), $uboDeclaration->content);
		$html2Pdf->writeHTML($content);
		$html2Pdf->output($uboDeclarationFile, 'F');

		$letterBackgroundOther = LETTER_BACKGROUND_ROOT . 'letterBackgroundOtherPages.pdf';
		$command = CPDF_EXECUTABLE . ' -stamp-under ' . $letterBackgroundOther . ' ' . $uboDeclarationFile . ' 1-end -o ' . $uboDeclarationFile;
		exec($command, $output);

		// change value toevoegen aan registration om te verwerken in crm
		$this->processUboDeclarationFile($uboDeclarationFile);

		return $uboDeclarationFile;
	}
	public function processUboDeclarationFile($path) {
		$this->getRegistrationCard();
		$registrationRelationId = !empty($this->registrationCard) && !empty($this->registrationCard->relation) ? $this->registrationCard->relation->id : null;

		$pathInfo = pathinfo($path);

		// controleren of er al changes bestaan
		$change = $this->changeService->getRegistrationChangeByProperties($registrationRelationId, 'MRegistrationRelation', 'update');
		// wanneer change nog steeds leeg is, is inschrijving al verwerkt en moeten we een update chagne aanmaken
		if (empty($change)) {
			$change = new \app\hig\MChange();
			$change->entity = 'MRegistrationRelation';
			$change->entityId = $registrationRelationId;
			$change->type = 'update';
			$change->status = 'no-approval-needed';
			$change->relationId = null;
			$change->add();
		}

		$changeValue = $this->changeService->getChangeValueByProperty($change->id, 'uboDeclarationFile');
		$action = empty($changeValue) ? 'add' : 'update';
		if (empty($changeValue)) {
			$changeValue = new \app\hig\MChangeValue();
			$changeValue->changeId = $change->id;
			$changeValue->property = 'uboDeclarationFile';
			$changeValue->oldValue = null;
		}
		// nieuwe waarde zetten voor changeValue, ongeacht of deze al bestaat of het gaat om een nieuwe entry
		// hiermee voorkomen we dubbele entries voor changeValues en eventuele dubbele logging etc.
		$changeValue->newValue = $pathInfo['filename'] . '.' . $pathInfo['extension'];
		$changeValue->$action();
	}
	
	public function getEmissionCostDescription() {
		$this->getRelation();

		$emissionCost = "(te vermeerderen met 3% emissiekosten)";
		if (
			!empty($this->emissionType) &&
			!(
				$this->emissionType == 'percentage' &&
				!empty($this->emissionValue) &&
				$this->emissionValue == 3
			)
		) {
			switch ($this->emissionType) {
				case 'fixed':
					// Aldus Rick van Overbeek (16-5-2022): een vast bedrag gaat nooit voorkomen bij een participant
					break;
				case 'percentage':
					$emissionCost = '<s>(te vermeerderen met 3% emissiekosten)</s> (te vermeerderen met '.$this->emissionValue.'% emissiekosten)';
					break;
				case 'none':
					$emissionCost = "<s>(te vermeerderen met 3% emissiekosten)</s>";
					break;
			}
		}
		
		return $emissionCost;
	}

	public function getAccountManagerRelation() {
		if (empty($this->accountManagerRelation) && !is_null($this->accountManagerRelationId)) {
			$this->accountManagerRelation = new \app\hig\MRelation($this->accountManagerRelationId);
		}
		return $this->accountManagerRelation;
	}
}