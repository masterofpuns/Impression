<?php

namespace app\hig;

use \app\h;
use \app\m;
use \app\Exception;

/**
 * MRelation
 *
 * @property $table string
 * @property $type int
 * @property $typeId int
 * @property $archived int
 * @property $search string
 * @property $searchExtended string
 * @property $nameSortable string
 */

class MRelation extends \app\CDBRecord implements \JsonSerializable {

    protected $table = 'relation';
    protected $type;
    protected $typeId;
    protected $archived;
    protected $search;
    protected $searchExtended;
    protected $nameSortable;

    protected $fields = [
        'type' => [
            'description' => 'TYPE',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'TYPE',
                    'placeholder' => 'TYPE'
                ],
                'order' => 1
            ],
            'backend' => [
                'type' => 'int',
                'typeParams' => []
            ]
        ],
        'typeId' => [
            'description' => 'TYPE_ID',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'TYPE_ID',
                    'placeholder' => 'TYPE_ID'
                ],
                'order' => 2
            ],
            'backend' => [
                'type' => 'int',
                'typeParams' => []
            ]
        ],
        'archived' => [
            'description' => 'ARCHIVED',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'ARCHIVED',
                    'placeholder' => 'ARCHIVED'
                ],
                'order' => 3
            ],
            'backend' => [
                'type' => 'int',
                'typeParams' => []
            ]
        ],
        'search' => [
            'description' => 'SEARCH',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'SEARCH',
                    'placeholder' => 'SEARCH'
                ],
                'order' => 4
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ],
        'searchExtended' => [
            'description' => 'SEARCH_EXTENDED',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'SEARCH_EXTENDED',
                    'placeholder' => 'SEARCH_EXTENDED'
                ],
                'order' => 5
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ],
        'nameSortable' => [
            'description' => 'NAME_SORTABLE',
            'frontend' => [
                'type' => 'text-single_line',
                'typeParams' => [
                    'label' =>  'NAME_SORTABLE',
                    'placeholder' => 'NAME_SORTABLE'
                ],
                'order' => 6
            ],
            'backend' => [
                'type' => 'text',
                'typeParams' => []
            ]
        ]
    ];

    use \app\TraitReturnAlias;

    private $parent = null;
    private $addresses;
    private $bankAccounts;
    private $emailAddresses;
    private $activeEmailAddresses;
    private $endedParticipations;
    private $object;
    private $participations;
    private $participationService;
    private $phoneNumbers;
    private $postalAddress;
    private $primaryPhoneNumber;
    private $user = null;
    private $users;
    private $extraUsers;
    private $visitingAddress;
    private $isManager = null;
    private $contactPersons;
    private $intermediaries = null;
    private $relationIntermediaries = null;
    private $category = null;
    private $relationAccountManagers = null;
    private $mostRecentOriginOfResources;
    private $registrations;
    private $isAccountManager;

    /** @var \app\hig\CRelationService */
    private $relationService;
    /** @var \app\hig\CContactPersonService */
    private $contactPersonService = null;
    /** @var \app\hig\COrganizationService */
    private $organizationService = null;
    /** @var \app\hig\CCollectiveService */
    private $collectiveService = null;

    const NO_MAILINGS = 0;
    const PER_MAIL = 1;
    const DIGITAL = 2;
    const PER_MAIL_AND_DIGITAL = 3;

    public function __construct($id = null) {
        $this->relationService = m::app()->serviceManager->get('relationService');
        $this->participationService = m::app()->serviceManager->get('participationService');
        $this->contactPersonService = m::app()->serviceManager->get('contactPersonService');
        $this->organizationService = m::app()->serviceManager->get('organizationService');
        $this->collectiveService = m::app()->serviceManager->get('collectiveService');

        parent::__construct($id);
    }

    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'typeId' => $this->typeId,
            'nameSortable' => $this->nameSortable
        ];
    }

    public function getObject() {

        if (!is_null($this->object)) {
            return $this->object;
        } else if (
            !is_null($this->typeId) &&
            !is_null($this->type)
        ) {
            switch ($this->type) {
                case "contactPerson":
                    $this->object = new MContactPerson($this->typeId);
                    break;
                case "organization":
                    $this->object = new MOrganization($this->typeId);
                    break;
                case "collective":
                    $this->object = new MCollective($this->typeId);
                    break;
                case "intermediary":
                    $this->object = new MIntermediary($this->typeId);
                    break;
                default:
                    throw new Exception("Illegal relation type ".$this->type);
            }

            return $this->object;
        } else {
            return null;
        }

    }

    public function getAddresses() {
        if (is_null($this->addresses)) {
            $this->addresses = $this->relationService->getAddressesForRelation($this->id);
        }

        return $this->addresses;
    }

    public function getPhoneNumbers() {
        if (is_null($this->phoneNumbers)) {
            $this->phoneNumbers = $this->relationService->getPhoneNumbersForRelation($this->id);
        }

        return $this->phoneNumbers;
    }

    public function getPrimaryPhoneNumber() {
        $this->getPhoneNumbers();
        if (!empty($this->phoneNumbers)) {
            return current($this->phoneNumbers);
        }

        return null;
    }

    public function getBankAccounts() {
        if (is_null($this->bankAccounts)) {
            $this->bankAccounts = $this->relationService->getBankAccountsForRelation($this->id);
        }

        return $this->bankAccounts;
    }

    public function getPrimaryBankAccount() {
        $this->getBankAccounts();
        if (!empty($this->bankAccounts)) {
            return current($this->bankAccounts);
        }
    }

    public function getEmailAddresses() {
        if (is_null($this->emailAddresses)) {
            $this->emailAddresses = $this->relationService->getEmailAddressesForRelation($this->id);
        }

        return $this->emailAddresses;
    }

    public function getActiveEmailAddresses() {
        if(is_null($this->activeEmailAddresses)){
            $this->activeEmailAddresses = $this->relationService->getEmailAddressesForRelation($this->id, true);
        }

        return $this->activeEmailAddresses;
    }

    /**
    * @return \app\hig\MEmailAddress
    *
    */
    public function getPrimaryEmailAddress() {
        $emailAddresses = $this->getActiveEmailAddresses();
        if (!empty($emailAddresses))
            return reset($emailAddresses);
    }

    /**
    * Ophalen van postadres van relatie
    *
    * Voor alle contactpersonen wordt het adres van de hoofdrelatie
    * gebruikt, tenzij de optie "prive-adres gebruiken" is geselecteerd
    * bij de contactpersoon.
    *
    * Bij hoofdrelaties met een primair contactpersoon waarbij de optie
    * "prive-adres gebruiken" is geselecteerd wordt het adres van de
    * primaire contactpersoon gebruikt.
    *
    * @param bool $forceOwnAddress Wordt gebruikt in de weergaven van
    * belangstelling, reservering, deelname en participatie om te voorkomen
    * dat het adres van een contactpersoon wordt getoond.
    *
    * @return MAddress|null
    */
    public function getPostalAddress($forceOwnAddress = false) {
        $relationTypeObject = $this->getObject();
        if(is_null($this->postalAddress)) {

            $addresses = $this->getAddresses();
            // altijd laatst toegevoegde primaire adres pakken
            rsort($addresses);

            // Check primary addresses
            if(is_null($this->postalAddress)) {
                foreach ($addresses as $address) {
                    if ($address->isPrimary == 1 && $address->description == 'postal'){
                        $this->postalAddress = $address;
                    }
                }
            }

            // Check postal addresses
            if(is_null($this->postalAddress)) {
                foreach ($addresses as $address) {
                    if ($address->description == 'postal'){
                        $this->postalAddress = $address;
                    }
                }
            }

            if (!empty($relationTypeObject->postalAddressEqualsVisitingAddress)) {
                // check visiting addresses
                if(is_null($this->postalAddress)) {
                    foreach ($addresses as $address) {
                        if ($address->description == 'visiting'){
                            $this->postalAddress = $address;
                            $this->postalAddress->description = 'postal';
                        }
                    }
                }

            }


            // check home addresses
            if(is_null($this->postalAddress)) {
                foreach ($addresses as $address) {
                    if ($address->description == 'home'){
                        $this->postalAddress = $address;
                    }
                }
            }

            if (
                is_null($this->postalAddress) &&
                $this->type == 'contactPerson' &&
                $relationTypeObject->getParent() &&
                !$forceOwnAddress
            ) {
                $parent = $relationTypeObject->getParent();
                $addresses = $parent->getAddresses();
                foreach ($addresses as $address) {
                    if ($address->description == 'postal'){
                        $this->postalAddress = $address;
                    }
                }

                if (!empty($parent->object->postalAddressEqualsVisitingAddress)) {
                    // check visiting addresses
                    if(is_null($this->postalAddress)) {
                        foreach ($addresses as $address) {
                            if ($address->description == 'visiting'){
                                $this->postalAddress = $address;
                                $this->postalAddress->description = 'postal';
                            }
                        }
                    }

                }

            }

        }

        return $this->postalAddress;
    }

    public function getVisitingAddress() {
        $relationTypeObject = $this->getObject();
        if(is_null($this->visitingAddress)) {


            if ($this->getObject()->postalAddressEqualsVisitingAddress) {
                $visitingAddress = $this->getPostalAddress();

                if ($visitingAddress) {
                    // Clonen omdat anders ook het type van het postadres
                    // wordt aangepast
                    $this->visitingAddress = clone $visitingAddress;
                    $this->visitingAddress->description = 'visiting';
                }

                // Verdere executie voorkomen
                return $this->visitingAddress;
            }

            $addresses = $this->getAddresses();

            // 2. check visiting addresses
            if(is_null($this->visitingAddress)) {
                foreach ($addresses as $address) {
                    if ($address->description == 'visiting'){
                        $this->visitingAddress = $address;
                    }
                }
            }

            /**
             * case voor ophalen adres van parent 19-08 opgeheven. Vanaf dit moment is het niet meer wenselijk om
             * het adres van de hoofdrelatie in te vullen bij een contactpersoon indien er bij deze contactpersoon zelf
             * geen adres bekend is. Om de echte waarheid ten alle tijden te hebben (van belang voor het inschrijfproces
             * hebben we deze case moeten opheffen.
             */
        }

        return $this->visitingAddress;
    }

    public function getParticipations(){
        if(is_null($this->participations)){
            $this->participations = [];

            // ophalen fondsen waar relatie in deelneemt of heeft deelgenomen
            $funds = $this->participationService->getFundsForActiveParticipations($this->id);

            // door fondsen itereren
            foreach ($funds as $fund) {
                if (($result = $this->participationService->getCurrentParticipationForParticipantAndFund($this, $fund)) !== false) {
                    $this->participations[$fund->id] = $result;
                }
            }
        }

        return $this->participations;
    }

    public function getEndedParticipations() {
        if (is_null($this->endedParticipations)) {
            $this->endedParticipations = [];

            // ophalen fondsen waar relatie in deelneemt of heeft deelgenomen
            $funds = $this->participationService->getFundsForEndedParticipations($this->id);

            // door fondsen itereren
            foreach ($funds as $fund) {

                if (($result = $this->participationService->getEndedParticipationForParticipantAndFund($this, $fund)) !== false) {
                    $this->endedParticipations[$fund->id] = $result;
                }
            }
        }

        return $this->endedParticipations;
    }

    public function getName() {
        $object = $this->getObject();
        return $object->name;
    }

    public function getNameForLetter() {
        $object = $this->getObject();
        $fullNameArray = array();

        $salutationId = $object->salutationId;
        if(!empty($salutationId)){
            $salutation = new \app\hig\MSalutation($salutationId);
            $fullNameArray[] = $salutation->letterSalutation;
        } else {
            $fullNameArray[] = t('UNKNOWN_SALUTATION');
        }

        if(!empty($object->lastNamePrefix)){
            $fullNameArray[] = ucfirst($object->lastNamePrefix);
        }

        if(!empty($object->lastName)){
            $fullNameArray[] = ucfirst($object->lastName);
        }

        return implode(' ', $fullNameArray);
    }
    
    public function getNameForRegistrationEmail() {
        $object = $this->getObject();
        $fullNameArray = array();
        
        $salutationId = $object->salutationId;
        if(!empty($salutationId)){
            $salutation = new \app\hig\MSalutation($salutationId);
            $fullNameArray[] = $salutation->letterSalutation;
        } else {
            $fullNameArray[] = t('UNKNOWN_SALUTATION');
        }
        
        if(!empty($object->lastNamePrefix)){
            $fullNameArray[] = ucfirst($object->lastNamePrefix);
        }
        
        if(!empty($object->lastName)){
            $fullNameArray[] = ucfirst($object->lastName);
        }
        
        return implode(' ', $fullNameArray);
    }

    public function getUser() {
        if (is_null($this->user)) {
            $this->user = $this->relationService->getUserForRelation($this->id);
        }

        return $this->user;
    }

    public function getUsers() {
        if (is_null($this->users)) {
            $this->users = $this->relationService->getAllUsersForRelation($this->id);
        }

        return $this->users;
    }

    public function getExtraUsers() {
        if (is_null($this->extraUsers)) {
            $this->users = $this->relationService->getExtraUsersForRelation($this->id);
        }

        return $this->users;
    }

    public function getNameSortable($includeName2 = true) {
        $object = $this->getObject();

        switch ($this->type) {
            case 'organization' :
                $name = $object->name;
                if (!empty($object->name2) && $includeName2)
                    $name.= ', ' . $object->name2;
                return $name;
                break;
            case 'intermediary' :
                if (!empty($object->childRelation)) {
                    return $object->childRelation->nameStartingWithLastName;
                } else {
                    $name = $object->name;
                    if (!empty($object->name2) && $includeName2)
                        $name .= ', ' . $object->name2;
                    return $name;
                }
                break;
            case 'contactPerson' :
            case 'collective' :
                return $object->nameStartingWithLastName;
        }
    }

    public function updateIndexFields() {
        $this->nameSortable = $this->getNameSortable(true);

        $this->search = $this->getNameSortable() . ', ' . $this->getName();

        $searchExtended = '';
        foreach ($this->getAddresses() as $address) {
            $searchExtended.= $searchExtended ? ', ' : '';
            $searchExtended.= implode(' ', [
                $address->street,
                $address->number,
                $address->numberSuffix,
                $address->postalCode,
                $address->city,
                $address->country,
            ]);
        }

        foreach ($this->getPhoneNumbers() as $phoneNumber) {
            $searchExtended.= $searchExtended ? ', ' : '';
            $searchExtended.= implode(' ', [
                $phoneNumber->number,
            ]);
        }

        foreach ($this->getBankAccounts() as $bankAccount) {
            $searchExtended.= $searchExtended ? ', ' : '';
            $searchExtended.= implode(' ', [
                $bankAccount->iban,
                $bankAccount->relationNumber,
            ]);
        }

        foreach ($this->getEmailAddresses() as $emailAddress) {
            $searchExtended.= $searchExtended ? ', ' : '';
            $searchExtended.= implode(' ', [
                $emailAddress->address
            ]);
        }

        $this->searchExtended = !empty($searchExtended) ? $searchExtended : null;
    }

    public function setObject($relationTypeObject) {
        if (!empty($relationTypeObject)) {
            $this->object = $relationTypeObject;
        }
    }

    public function getUbos() {
        if($this->type !== 'organization'){
            throw new Exception("Only organizations can have UBO's");
        }

        return $this->getObject()->ubos;
    }

    public function getUrlView() {
        $doc = m::app()->getDocByName('relation-view');
        return $doc->getUrl([$this->id, $this->getAlias()]);
    }

    public function getUrlAddUser() {
        $doc = m::app()->getDocByName('relation-add-user');
        return $doc->getUrl([$this->id]);
    }

    public function getUrlEditCorrespondence() {
        $doc = m::app()->getDocByName('relation-edit-correspondence');
        return $doc->getUrl([$this->id]);
    }

    public function getUrlEditPostalAddress() {
        $doc = m::app()->getDocByName('relation-edit-postal-address');
        return $doc->getUrl([$this->id]);
    }

    public function getUrlEditVisitingAddress() {
        $doc = m::app()->getDocByName('relation-edit-visiting-address');
        return $doc->getUrl([$this->id]);
    }

    public function getUrlAddPhoneNumber() {
        $doc = m::app()->getDocByName('relation-add-phonenumber');
        return $doc->getUrl([$this->id]);
    }

    public function getUrlAddContactPerson() {
        $doc = m::app()->getDocByName('relation-add-contact-person');
        return $doc->getUrl([$this->id]);
    }

    /**
    * Bepalen of relatie manager is
    *
    */
    public function getIsManager() {
        if (is_null($this->isManager)) {
            $this->isManager = false;

            // manager kan alleen een contact persoon zijn
            if ($this->type == 'contactPerson') {
                $parent = $this->getObject()->getParent();
                if (!empty($parent)) {
                    $this->isManager = $this->relationService->checkIsManagerForRelation(
                        $parent->id,
                        $this->id
                    );
                }
            }
        }

        return $this->isManager;
    }

    public function getUrlEditManager() {
        if (!$this->getIsManager()) {
            return null;
        }

        $doc = m::app()->getDocByName('manager-edit');
        return $doc->getUrl([$this->id]);
    }

    public function getUrlBlueCrm() {
        return CRMSERVER . "/relation/".$this->id."/".$this->getAlias();
    }

    /**
    * Alle documenten ophalen voor gegevens fonds
    * Er wordt gekeken of er documentatie is voor fonds, stichting en beheerder
    * Ook wordt gechecked of voor de betreffende documenten een relation_assigned_document bestaat
    * voor deze relatie
    *
    * @param mixed $fundId
    */
    public function getDocumentsForFund($fundId) {
        /** @var \app\hig\CDocumentService */
        $documentService = m::app()->serviceManager->get('documentService');

        return $documentService->getDocumentsForRelationAndFund($this->id, $fundId);
    }

    /**
    * Haalt alle contactpersoon MRelation's op behorende bij MRelation
    *
    * @return \app\hig\MRelation[]
    */
    public function getContactPersons() {
        switch ($this->type) {
            case 'contactPerson':
                $this->contactPersons = $this->contactPersonService->getContactPersonsForContactPerson($this->id);
                break;
            case 'organization':
                $this->contactPersons = $this->organizationService->getContactPersonsForOrganization($this->id);
                break;
            case 'collective':
                $this->contactPersons = $this->collectiveService->getContactPersonsForCollective($this->id);
                break;
        }

        return $this->contactPersons;
    }

    public function getIntermediaries() {
        if (is_null($this->intermediaries)) {
            $this->intermediaries = $this->relationService->getIntermediaries($this->id);
        }

        return $this->intermediaries;
    }

    public function getRelationIntermediaries() {
        if (is_null($this->relationIntermediaries)) {
            $this->relationIntermediaries = $this->relationService->getRelationIntermediaries($this->id);
        }

        return $this->relationIntermediaries;
    }
    
    public function getCategory() {
    	if (empty($this->category)) {
    		$this->category = $this->relationService->getCategoryForRelation($this->id);
	    }
    	
    	return $this->category;
    }
	
	/**
	 * De functie haalt alle account managers op die voor deze relatie zijn ingesteld
	 *
	 * @return \instance\MRelation[]
	 */
	public function getRelationAccountManagers() {
		if (empty($this->relationAccountManagers)) {
			$this->relationAccountManagers = $this->relationService->getAccountManagersForRelation($this->id);
		}
		return $this->relationAccountManagers;
	}
    
    public function getMostRecentOriginOfResources() {
        if (empty($this->mostRecentOriginOfResources)) {
            $this->mostRecentOriginOfResources = $this->relationService->getMostRecentOriginOfResourcesForRelation($this);
        }
        return $this->mostRecentOriginOfResources;
    }
    
    public function getRegistrations() {
        if (empty($this->registrations)) {
            $this->registrations = $this->relationService->getRegistrationsForRelation($this);
        }
        return $this->registrations;
    }
    
    public function getParent() {
        if (
            empty($this->parent) &&
            $this->type == 'contactPerson' &&
            !empty($this->getObject())
        ) {
            $this->parent = $this->getObject()->getParent();
        }
        
        return $this->parent;
    }

    public function getIsAccountManager() {
        if (is_null($this->isAccountManager)) {
            $intermediaryRelation = $this->relationService->getIntermediaryRelationForRelation($this);
            if (!empty($intermediaryRelation)) {
                $this->isAccountManager = $intermediaryRelation->isAccountManager;
            }
        }
        return $this->isAccountManager;
    }
}