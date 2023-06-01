<?php
namespace app\hig;

use \app\h;
use \app\m;
use \Exception;

class RegistrationController {
	protected $viewBase = 'registration';

	/** @var \app\hig\CFundService $fundService */
	protected $fundService;
	/** @var \app\hig\CRegistrationService $fundService */
	protected $registrationService;
	/** @var \app\hig\CRelationService $relationService */
	protected $relationService;
	/** @var \app\hig\CChangeService $changeService */
	protected $changeService;

	private $result;
	private $logFile;
	
	public function __construct() {
		$this->fundService = m::app()->serviceManager->get('fundService');
		$this->registrationService = m::app()->serviceManager->get('registrationService');
		$this->relationService = m::app()->serviceManager->get('relationService');
		$this->changeService = m::app()->serviceManager->get('changeService');
		$this->result = new \stdClass();
		$this->logFile = LOCAL_PATH . 'logs/registration.log';
	}
	
	public function actionHome(\app\CApp $app, $extraVars = []) {
		$this->handlePermission();
		$extraVars = $this->getExtraVars();
		
		$app->renderView($this->viewBase . '/home/view', $extraVars);
	}
	public function actionLogin(\app\CApp $app, $extraVars = []) {
		$slug = h::getP(0, 'any', null, true);
		$fund = $this->determineFund();
		$this->handlePermission();

		$extraVars = $this->getExtraVars();

		try {
			$login = h::getV('Login', 'array', [], 'POST', false);
			$loginManager = $app->loginManager;
			$messages = [];

			$existingRegistration = $this->getExistingRegistration();

			if ($app->user) {
				if (
					$app->user->isParticipant() ||
					$app->user->isProxy() ||
					$app->user->isAccountManager
				) {
					if (empty($existingRegistration)) {
						// controleren of gebruiker relatie omgevingen heeft waarmee kan worden ingeschreven
						if ($app->user->isAccountManager || !empty($app->user->getAccessibleEnvironmentRelations())) {
							// doorsturen naar volgende pagina
							$app->redirectExternal($extraVars['redirectUrl']);
						} else {
							$messages = ['Er zijn geen tenaamstellingen waarvoor kan worden ingeschreven.'];
						}
					} else {
						if ($app->user->isAccountManager && !empty($app->user->getAccessibleEnvironmentRelations())) {
							// wanneer account manager en er zijn nog meer relatie omgeving om voor in te schrijven, doorsturen
							// naar relatie selectie pagina
							$app->redirectToDoc('registration-identification-ascription', [$slug]);
						}
						$messages = ['Er bestaat al een inschrijving voor deze tenaamstelling.'];
					}
				}

			} else if (!empty($login)) {
				// verwerken login
				$identification = trim($login['identification']);
				$password = trim($login['password']);
				if (!filter_var($identification, FILTER_VALIDATE_EMAIL)) {
					$messages = ['U heeft geen geldig e-mailadres opgegeven.'];
				} else {
					$loginManager->identification = $identification;
					$loginManager->password = $password;

					if ($loginManager->login()) {
						$this->resetSession();
						// redirecten
						if (empty($this->getExistingRegistration())) {
							// doorsturen naar volgende pagina
							if ($app->user->isAccountManager || !empty($app->user->getAccessibleEnvironmentRelations())) {
								// doorsturen naar volgende pagina
								$app->redirectExternal($extraVars['redirectUrl']);
							} else {
								$messages = ['Er zijn geen tenaamstellingen waarvoor kan worden ingeschreven.'];
							}

						} else {
							$loginManager->logout();
							$messages = ['Er bestaat al een inschrijving voor deze tenaamstelling.'];
						}
					}
				}
			}

			// ophalen messages
			$messages = array_merge($messages, $loginManager->messages);
		} catch (\Exception $e) {
			$messages = [DEBUG_MODE ? $e->getMessage() : 'Er ging iets mis, probeer het nogmaals.'];
		}
		
		$extraVars['messages'] = $messages;
		$app->renderView($this->viewBase . '/login/view', $extraVars);
	}
	public function actionNewUser(\app\CApp $app, $extraVars = []) {
		$this->handlePermission();
		$this->handlePost();
		$extraVars = $this->getExtraVars();
		
		$app->renderView($this->viewBase . '/new/home/view', $extraVars);
	}
	public function actionOnBehalf(\app\CApp $app, $extraVars = []) {
		$this->handlePermission();
		$extraVars = $this->getExtraVars();
		
		$app->renderView($this->viewBase . '/new/onbehalf/view', $extraVars);
	}
	
	// flow: portal gebruiker
	public function actionAscription(\app\CApp $app, $extraVars = []) {
		$this->handlePermission();
		$this->handlePost();
		$extraVars = $this->getExtraVars();
		
		$app->renderView($this->viewBase . '/existing/ascription/view', $extraVars);
	}
	public function actionData(\app\CApp $app, $extraVars = []) {
		$this->handlePermission();
		$this->handlePost();
		$extraVars = $this->getExtraVars();
		
		// controleren of gekozen relatie bevoegdheid heeft om in te schrijven
		$relationEnvironment = m::app()->user->getRelationEnvironmentForRelation(m::app()->user->getSelectedRelation()->id);
		if ($relationEnvironment->roleId == 4) {
			$fund = $this->determineFund();
			$relation = m::app()->user->getSelectedRelation();
			m::app()->user->unsetSelectedRelation();
			m::app()->renderView(
				'registration/message',
				[
					'fund' => $fund,
					'message' => 'Helaas heeft u niet de juiste bevoegdheid om '.$relation->getName().' in te schrijven voor dit fonds.',
					'prevUrl' => $extraVars['prevUrl']
				]
			);
			
		}
		
		$app->renderView($this->viewBase . '/existing/data/view', $extraVars);
	}
	
	public function actionUbo(\app\CApp $app, $extraVars = []) {
		$this->handlePermission();
        $this->handlePost();
        $extraVars = $this->getExtraVars();
		
		$app->renderView($this->viewBase . '/existing/ubo/view', $extraVars);
	}
	public function actionExtraUboNew(\app\CApp $app, $extraVars = []) {
		$this->handlePermission();
		$this->handlePost();
		$extraVars = $this->getExtraVars();
		
		$app->renderView($this->viewBase . '/existing/ubo/extra/view', $extraVars);
	}
	public function actionExtraUboEdit(\app\CApp $app, $extraVars = []) {
		$this->handlePermission();
		$this->handlePost();
		$extraVars = $this->getExtraVars();
		
		$app->renderView($this->viewBase . '/existing/ubo/extra/view', $extraVars);
	}
	public function actionExtraUboDelete(\app\CApp $app, $extraVars = []) {
		$result = ['success' => true, 'message' => ''];
		
		try {
			$uboIdx = h::getV('uboIdx', 'int', -1, 'POST');
			if (!empty($_SESSION['Registration']['Ubo']) && !empty($_SESSION['Registration']['Ubo'][$uboIdx])) {
				unset($_SESSION['Registration']['Ubo'][$uboIdx]);
			}
		} catch (\Exception $e) {
			$result = ['success' => false, 'message' => (DEBUG_MODE ? $e->getMessage() : 'Er ging iets mis.')];
		}
		
		$app->renderJSON($result);
	}
	public function actionUboDeclaration(\app\CApp $app, $extraVars = []) {
		$this->handlePermission();
		$this->handlePost();
		$extraVars = $this->getExtraVars();
		
		$app->renderView($this->viewBase . '/existing/ubo/declaration/view', $extraVars);
	}
	
	public function actionBankAccount(\app\CApp $app, $extraVars = []) {
		$this->handlePermission();
		$this->handlePost();
		$extraVars = $this->getExtraVars();
		
		$app->renderView($this->viewBase . '/existing/bankaccount/view', $extraVars);
	}
	public function actionParticipation(\app\CApp $app, $extraVars = []) {
		$this->handlePermission();
		$this->handlePost();
		$extraVars = $this->getExtraVars();
		
		$app->renderView($this->viewBase . '/existing/participation/view', $extraVars);
	}
	public function actionCheck(\app\CApp $app, $extraVars = []) {
		$this->handlePermission();
		$this->handlePost();
		$extraVars = $this->getExtraVars();
		
		$app->renderView($this->viewBase . '/existing/check/view', $extraVars);
	}
	public function actionProcess(\app\CApp $app, $extraVars = []) {
		$slug = h::getP(0, 'any', null, true);
		
		$this->handlePermission();
		$this->handlePost();
		$this->processRegistration();
		$this->resetSession();
		
		m::app()->redirectToDoc('registration-complete', [$slug]);
	}
	public function actionComplete(\app\CApp $app) {
		$this->handlePermission();
		$extraVars = $this->getExtraVars();
		
		$app->renderView($this->viewBase . '/existing/complete/view', $extraVars);
	}
	
	// flow: niet-portal gebruiker - natuurlijk persoon
	public function actionPersonOnBehalf(\app\CApp $app, $extraVars = []) {
		$this->handlePermission();
		$this->handlePost();
		$extraVars = $this->getExtraVars();
		
		$app->renderView($this->viewBase . '/new/person/onbehalf/view', $extraVars);
	}
	public function actionPersonAscription(\app\CApp $app, $extraVars = []) {
		$this->handlePermission();
		$this->handlePost();
		$extraVars = $this->getExtraVars();
		
		$app->renderView($this->viewBase . '/new/person/ascription/view', $extraVars);
	}
	public function actionPersonExtraNew(\app\CApp $app, $extraVars = []) {
		$this->handlePermission();
		$this->handlePost();
		$extraVars = $this->getExtraVars();
		
		$app->renderView($this->viewBase . '/new/person/extra/view', $extraVars);
	}
	public function actionPersonExtraEdit(\app\CApp $app, $extraVars = []) {
		$this->handlePermission();
		$this->handlePost();
		$extraVars = $this->getExtraVars();
		
		$app->renderView($this->viewBase . '/new/person/extra/view', $extraVars);
	}
	public function actionPersonExtraDelete(\app\CApp $app, $extraVars = []) {
		$result = ['success' => true, 'message' => ''];
		
		try {
			$contactPersonIdx = h::getV('contactPersonIdx', 'int', -1, 'POST');
			if (!empty($_SESSION['Registration']['ContactPerson']) && !empty($_SESSION['Registration']['ContactPerson'][$contactPersonIdx])) {
				unset($_SESSION['Registration']['ContactPerson'][$contactPersonIdx]);
			}
		} catch (\Exception $e) {
			$result = ['success' => false, 'message' => (DEBUG_MODE ? $e->getMessage() : 'Er ging iets mis.')];
		}
		
		$app->renderJSON($result);
	}
	public function actionPersonBankAccount(\app\CApp $app, $extraVars = []) {
		$this->handlePermission();
		$this->handlePost();
		$extraVars = $this->getExtraVars();
		
		$app->renderView($this->viewBase . '/new/person/bankaccount/view', $extraVars);
	}
	public function actionPersonParticipation(\app\CApp $app, $extraVars = []) {
		$this->handlePermission();
		$this->handlePost();
		$extraVars = $this->getExtraVars();
		
		$app->renderView($this->viewBase . '/new/person/participation/view', $extraVars);
	}
	public function actionPersonCheck(\app\CApp $app, $extraVars = []) {
		$this->handlePermission();
		$this->handlePost();
		$extraVars = $this->getExtraVars();
		
		$app->renderView($this->viewBase . '/new/person/check/view', $extraVars);
	}
	public function actionPersonProcess(\app\CApp $app, $extraVars = []) {
		$slug = h::getP(0, 'any', null, true);
		
		$this->handlePermission();
		$this->handlePost();
		$this->processRegistration();
		$this->resetSession();
		
		m::app()->redirectToDoc('registration-person-complete', [$slug]);
	}
	public function actionPersonComplete(\app\CApp $app) {
		$this->handlePermission();
		$extraVars = $this->getExtraVars();
		
		$app->renderView($this->viewBase . '/new/person/complete/view', $extraVars);
	}
	
	// flow: niet-portal gebruiker - rechtspersoon
	public function actionLegalEntityAscription(\app\CApp $app, $extraVars = []) {
		$this->handlePermission();
		$this->handlePost();
		$extraVars = $this->getExtraVars();
		
		$app->renderView($this->viewBase . '/new/legalentity/ascription/view', $extraVars);
	}
	public function actionLegalEntityExtraNew(\app\CApp $app, $extraVars = []) {
		$this->handlePermission();
		$this->handlePost();
		$extraVars = $this->getExtraVars();
		
		$app->renderView($this->viewBase . '/new/legalentity/extra/view', $extraVars);
	}
	public function actionLegalEntityExtraEdit(\app\CApp $app, $extraVars = []) {
		$this->handlePermission();
		$this->handlePost();
		$extraVars = $this->getExtraVars();
		
		$app->renderView($this->viewBase . '/new/legalentity/extra/view', $extraVars);
	}
	public function actionLegalEntityExtraDelete(\app\CApp $app, $extraVars = []) {
		$result = ['success' => true, 'message' => ''];
		
		try {
			$contactPersonIdx = h::getV('contactPersonIdx', 'int', -1, 'POST');
			if (!empty($_SESSION['Registration']['ContactPerson']) && !empty($_SESSION['Registration']['ContactPerson'][$contactPersonIdx])) {
				unset($_SESSION['Registration']['ContactPerson'][$contactPersonIdx]);
			}
		} catch (\Exception $e) {
			$result = ['success' => false, 'message' => (DEBUG_MODE ? $e->getMessage() : 'Er ging iets mis.')];
		}
		
		$app->renderJSON($result);
	}
	public function actionLegalEntityUbo(\app\CApp $app, $extraVars = []) {
		$this->handlePermission();
		$this->handlePost();
		$extraVars = $this->getExtraVars();
		
		$app->renderView($this->viewBase . '/new/legalentity/ubo/view', $extraVars);
	}
	public function actionLegalEntityExtraUboNew(\app\CApp $app, $extraVars = []) {
		$this->handlePermission();
		$this->handlePost();
		$extraVars = $this->getExtraVars();
		
		$app->renderView($this->viewBase . '/new/legalentity/ubo/extra/view', $extraVars);
	}
	public function actionLegalEntityExtraUboEdit(\app\CApp $app, $extraVars = []) {
		$this->handlePermission();
		$this->handlePost();
		$extraVars = $this->getExtraVars();
		
		$app->renderView($this->viewBase . '/new/legalentity/ubo/extra/view', $extraVars);
	}
	public function actionLegalEntityExtraUboDelete(\app\CApp $app, $extraVars = []) {
		$result = ['success' => true, 'message' => ''];
		
		try {
			$uboIdx = h::getV('uboIdx', 'int', -1, 'POST');
			if (!empty($_SESSION['Registration']['Ubo']) && !empty($_SESSION['Registration']['Ubo'][$uboIdx])) {
				unset($_SESSION['Registration']['Ubo'][$uboIdx]);
			}
		} catch (\Exception $e) {
			$result = ['success' => false, 'message' => (DEBUG_MODE ? $e->getMessage() : 'Er ging iets mis.')];
		}
		
		$app->renderJSON($result);
	}
	public function actionLegalEntityUboDeclaration(\app\CApp $app, $extraVars = []) {
		$this->handlePermission();
		$this->handlePost();
		$extraVars = $this->getExtraVars();
		
		$app->renderView($this->viewBase . '/new/legalentity/ubo/declaration/view', $extraVars);
	}
	
	public function actionLegalEntityBankAccount(\app\CApp $app, $extraVars = []) {
		$this->handlePermission();
		$this->handlePost();
		$extraVars = $this->getExtraVars();
		
		$app->renderView($this->viewBase . '/new/legalentity/bankaccount/view', $extraVars);
	}
	public function actionLegalEntityParticipation(\app\CApp $app, $extraVars = []) {
		$this->handlePermission();
		$this->handlePost();
		$extraVars = $this->getExtraVars();
		
		$app->renderView($this->viewBase . '/new/legalentity/participation/view', $extraVars);
	}
	public function actionLegalEntityCheck(\app\CApp $app, $extraVars = []) {
		$this->handlePermission();
		$this->handlePost();
		$extraVars = $this->getExtraVars();
		
		$app->renderView($this->viewBase . '/new/legalentity/check/view', $extraVars);
	}
	public function actionLegalEntityProcess(\app\CApp $app, $extraVars = []) {
		$slug = h::getP(0, 'any', null, true);
		
		$this->handlePermission();
		$this->handlePost();
		$this->processRegistration();
		$this->resetSession();
		
		m::app()->redirectToDoc('registration-legalentity-complete', [$slug]);
	}
	public function actionLegalEntityComplete(\app\CApp $app) {
		$this->handlePermission();
		$extraVars = $this->getExtraVars();
		
		$app->renderView($this->viewBase . '/new/legalentity/complete/view', $extraVars);
	}

	// flow: account manager
	public function actionAccountManagerLogin(\app\CApp $app) {
		$slug = h::getP(0, 'any', null, true);
		$fund = $this->determineFund();

		// bepalen redirect url, immers moeten we hier redirecten
		$redirect = h::getV('redirect', 'any', null, 'GET');


	}
	
	// flow: verklaren en ondertekenen
	public function actionDeclaration(\app\CApp $app) {
		$_SESSION['step'] = null;
		$this->handlePermission();
		$extraVars = $this->getExtraVars();
		
		$app->renderView($this->viewBase . '/declaration/view', $extraVars);
	}
	public function actionSign(\app\CApp $app) {
		$this->handlePermission();
		$extraVars = $this->getExtraVars();
		
		// controleren of ondertekening is afgerond
		$this->processDeclaration();
		$app->renderView($this->viewBase . '/complete/view', $extraVars);
	}
	
	// aanvullende functies
	public function actionValidateEmailAddress(\app\CApp $app) {
		$emailAddress = h::getV('emailAddress', 'any', null, 'POST', true);
		if (!empty($emailAddress)) {
			/** @var \app\hig\CUserService $userService */
			$userService = $app->serviceManager->get('userService');
			// controleren of e-mailadres al bestaat bij een portal gebruiker
			$user = $userService->findUser('emailAddress', $emailAddress);
			if (!empty($user)) {
				$app->renderJSON("Dit e-mailadres is al in gebruik voor Mijn-IMMO. Gebruik een ander e-mailadres.");
			} else {
				$app->renderJSON("true");
			}
		} else {
			$app->renderJSON("Er is geen e-mailadres opgegeven.");
		}
	}
	
	private function handlePermission() {
		$slug = h::getP(0, 'any', null, true);
		$fund = $this->determineFund();
		$this->setSessionParams();

		$currentTime = time();

		$fundRegistrationSlug = $_SESSION['fundRegistrationSlug'];

		// controleren of er een ingelogde gebruiker is, deze account manager is en er een niet account manager url wordt benaderd
		if (!empty(m::app()->user) && $fundRegistrationSlug->type !== 'accountManager' && m::app()->user->isAccountManager) {
			m::app()->loginManager->logout();
			$this->resetSession();
			m::app()->redirectToDoc('registration-home', [$slug]);
		}

		// stappen controleren
		$last = isset($_SESSION['step']) ? $_SESSION['step'] : '';
		$_SESSION['step'] = m::app()->doc->name;
		// wanneer laatste stap afronding is en vervolgstap is niet home, naar home sturen
		// immers moet inschrijving gewoon vanaf start worden ingevuld
		if (stripos($last, 'complete') !== false && $_SESSION['step'] !== 'registration-home') {
			$this->resetSession();
			m::app()->redirectToDoc('registration-home', [$slug]);
		}
		
		// wanneer user is gezet, inschrijving al bestaat, user geen accountmanager is en doc name niet voorkomt in opgegeven array
		// user doorsturen naar login
		if (
			!empty(m::app()->user) &&
			!empty($this->getExistingRegistration()) &&
			!m::app()->user->getIsAccountManager() &&
			!in_array(
				m::app()->doc->name,
				[
					'registration-home',
					'registration-login',
					'registration-process',
					'registration-complete',
					'registration-person-process',
					'registration-person-complete',
					'registration-legalentity-process',
					'registration-legalentity-complete',
					'registration-declaration',
					'registration-sign'
				]
			)
		) {
			m::app()->redirectToDoc('registration-login', [$slug]);
		}

		// wanneer datum oprichting van fonds bestaat, kan niet meer worden ingeschreven
		if (
			!in_array($fund->id, [285]) &&
			!empty($fund->dateFoundation)
		) {
			$partial = m::app()->getCmsPartial('registration-registration_period_passed');
			m::app()->renderView(
				'registration/message',
				[
					'fund' => $fund,
					'message' => !empty($partial) ? $partial->content : 'Dit fonds is reeds opgericht. U kunt zich niet meer inschrijven.',
				]
			);
			die;
		}

		// type van inschrijvingsurl controleren
		switch ($fundRegistrationSlug->type) {
			case 'default':
				// bepalen of huidige datum valt binnen inschrijfperiode
				if ($currentTime < strtotime($fund->dateTimeRegistrationStart)) {
					// te vroeg
					$partial = m::app()->getCmsPartial('registration-registration_period_not_active_yet');
					m::app()->renderView(
						'registration/message',
						[
							'fund' => $fund,
							'message' => !empty($partial) ? $partial->content : 'Helaas is de inschrijfperiode nog niet actief, probeer het op een later moment nog eens.',
						]
					);
					die;
				} else if (strtotime($fund->dateTimeRegistrationEnd) < $currentTime) {
					// te laat
					$partial = m::app()->getCmsPartial('registration-registration_period_passed');
					m::app()->renderView(
						'registration/message',
						[
							'fund' => $fund,
							'message' => !empty($partial) ? $partial->content : 'Helaas is de inschrijfperiode verstreken. U kunt zich niet meer inschrijven voor dit fonds.',
						]
					);
					die;
				}
				break;
			case 'permanent':
			case 'intermediary':
				break;
			case 'accountManager':
				/**
				 * bij url voor accountmanagers is eerste stap om te controleren welke pagina wordt benaderd, indien pagina
				 * niet de inlog, verklaring of ondertekenverwerk pagina betreft, dan acties uitvoeren. Genoemde pagina's
				 * kunnen zonder bevoegdheden worden benaderd, aangezien inschrijving door iedereen moet kunnen worden ondertekend
				 * die een ondertekenlink heeft ontvangen.
				 */
				if (
					!in_array(
						m::app()->doc->name,
						[
							'registration-login',
							'registration-declaration',
							'registration-sign'
						]
					)
				) {
					if (empty(m::app()->user)) {
						m::app()->redirectToDoc('registration-login', [$slug]);
					} else {
						// controleren of huidige portal gebruiker account manager rol heeft, zo niet mag deze de huidige url niet benaderen
						$roles = m::app()->user->getRoles();
						if (!array_key_exists('portal-user-account_manager', $roles)) {
							$partial = null;

							m::app()->renderView(
								'registration/message',
								[
									'fund' => $fund,
									'message' => !empty($partial) ? $partial->content : 'Deze inschrijvingslink behoeft speciale bevoegdheden welke u niet geniet.',
								]
							);
							die;
						}
					}
				}
				break;
		}
		
		// permissie obv doc
		switch (m::app()->doc->name) {
			case 'registration-home':
				if (!empty(m::app()->user) && m::app()->user->getIsAccountManager()) {
					m::app()->redirectToDoc('registration-identification-ascription', [$slug]);
				}

				break;
			case 'registration-login':
				break;
			
			case 'registration-new_user':
				$this->resetSession();
				if (m::app()->user && !m::app()->user->isAccountManager) {
					m::app()->loginManager->logout();
					m::app()->redirectToDoc(m::app()->doc->name, [$slug]);
				}
				break;
			
			// bestaand
			case 'registration-identification-ascription':
				// wanneer user niet gezet, doorsturen naar home
				if (!m::app()->user) {
					m::app()->redirectToDoc('registration-home', [$slug]);
				}
				// wanneer actieve en benaderbare omgevingsrelatie maar 1 is, direct zetten relatie en doorsturen naar data
				if (count(m::app()->user->getAccessibleEnvironmentRelations()) == 1 && !m::app()->user->getIsAccountManager()) {
					$relation = current(m::app()->user->getAccessibleEnvironmentRelations());
					m::app()->user->setSelectedRelation($relation);
					m::app()->redirectToDoc('registration-identification-data', [$slug]);
				}
				
				break;
			case 'registration-identification-data':
			case 'registration-identification-ubo':
			case 'registration-identification-extra_ubo-new':
			case 'registration-identification-extra_ubo-edit':
			case 'registration-identification-extra_ubo-delete':
			case 'registration-identification-ubo-declaration':
			case 'registration-identification-bank_account':
			case 'registration-participation':
			case 'registration-check':
			case 'registration-process':
				if (!m::app()->user) { m::app()->redirectToDoc('registration-home', [$slug]); }
				if (empty(m::app()->user->getSelectedRelation())) {
					m::app()->redirectToDoc('registration-identification-ascription', [$slug]);
				}
				break;
			case 'registration-complete':
				if (!m::app()->user) { m::app()->redirectToDoc('registration-home', [$slug]); }
				break;
			
			// nieuw: flow natuurlijk persoon
			case 'registration-person-identification-on_behalf':
			case 'registration-person-identification-ascription':
			case 'registration-person-identification-extra_person-new':
			case 'registration-person-identification-extra_person-edit':
			case 'registration-person-identification-bank_account':
			case 'registration-person-participation':
			case 'registration-person-check':
			case 'registration-person-process':
			case 'registration-person-complete':
				if (m::app()->user && !m::app()->user->isAccountManager) {
					m::app()->redirectToDoc('registration-home', [$slug]);
				}
				
				// ook controleren of sessie bestaat en of relatie type overeenkomt, zo niet, redirecten naar type keuze en sessie resetten
				if (
					isset($_SESSION['Registration']) &&
					isset($_SESSION['Registration']['Relation']) &&
					isset($_SESSION['Registration']['Relation']['type']) &&
					!in_array($_SESSION['Registration']['Relation']['type'], ['contactPerson', 'collective'])
				) {
					$this->resetSession();
					$doc = m::app()->doc->name == 'registration-person-identification-on_behalf' ? 'registration-person-identification-on_behalf' : 'registration-new_user';
					m::app()->redirectToDoc($doc, [$slug]);
				}
				break;
			
			// nieuw: flow rechtspersoon
			case 'registration-legalentity-identification-ascription':
			case 'registration-legalentity-identification-extra_manager-new':
			case 'registration-legalentity-identification-extra_manager-edit':
			case 'registration-legalentity-identification-ubo':
			case 'registration-legalentity-identification-extra_ubo-new':
			case 'registration-legalentity-identification-extra_ubo-edit':
			case 'registration-legalentity-identification-ubo-declaration':
			case 'registration-legalentity-identification-bank_account':
			case 'registration-legalentity-participation':
			case 'registration-legalentity-check':
			case 'registration-legalentity-process':
			case 'registration-legalentity-complete':
				if (m::app()->user && !m::app()->user->isAccountManager) {
					m::app()->redirectToDoc('registration-home', [$slug]);
				}
				
				// ook controleren of sessie bestaat en of relatie type overeenkomt, zo niet, redirecten naar type keuze en sessie resetten
				if (
					isset($_SESSION['Registration']) &&
					isset($_SESSION['Registration']['Relation']) &&
					isset($_SESSION['Registration']['Relation']['type']) &&
					$_SESSION['Registration']['Relation']['type'] != 'organization'
				) {
					$this->resetSession();
					$doc = m::app()->doc->name == 'registration-legalentity-identification-ascription' ? 'registration-legalentity-identification-ascription' : 'registration-new_user';
					m::app()->redirectToDoc($doc, [$slug]);
				}
				break;
			
			case 'registration-declaration':
			case 'registration-sign':
				// controleren of opgegeven token hoort bij inschrijving
				$token = h::getV('token', 'any', null, 'GET', true);
				
				$registration = $this->registrationService->getRegistrationBySignToken($token);
				if (empty($registration)) { m::app()->redirectToDoc('registration-home', [$slug]); }
				
				$signed = $this->registrationService->checkRegistrationSignedByToken($registration, $token);
				if (!empty($signed)) {
					$partial = m::app()->getCmsPartial('registration-already-signed');
					m::app()->renderView(
						'registration/message',
						[
							'fund' => $fund,
							'message' => !empty($partial) ? $partial->content : 'Reeds heeft u deze inschrijving ondertekend. Spoedig ontvangt u van ons bericht.',
						]
					);
					die;
				}
				break;
		}
	}
	private function handlePost() {
        $params = h::getV('Registration', 'array', [], 'POST', false);
		if ($this->isEmptySession()) {
			$_SESSION['Registration'] = [
				'Relation' => [],
				'ContactPerson' => [],
				'PostalAddress' => [],
				'BankAccount' => [],
				'Participation' => [],
				'Ubo' => [],
				'EmissionCost' => "(te vermeerderen met 3% emissiekosten)",
			];
		}

		// default emissiekosten zijn altijd 3%
		$emissionType = null;
		$emissionValue = null;
		// bepaling intermediair en eventuele opgegeven emissiekosten
		if (!empty($_SESSION['RegistrationEmissionType'])) {
			$emissionType = $_SESSION['RegistrationEmissionType'];
			if (!empty($_SESSION['RegistrationEmissionValue'])) {
				$emissionValue = $_SESSION['RegistrationEmissionValue'];
			}
		} elseif (!empty($_SESSION['RegistrationIntermediaryRelationId'])) {
			$intermediaryRelation = new \app\hig\MRelation($_SESSION['RegistrationIntermediaryRelationId']);
			$emissionType = $intermediaryRelation->object->defaultEmissionType;
			$emissionValue = $intermediaryRelation->object->defaultEmissionValue;
		}
		
		// bij een ingelogde gebruiker relatie gegevens altijd ophalen obv geselecteerde relatie
		if (!empty(m::app()->user) && !empty(m::app()->user->getSelectedRelation())) {
			$relationEnvironment = m::app()->user->getRelationEnvironmentForRelation(m::app()->user->getSelectedRelation()->id);
			if ($relationEnvironment->roleId != 4) {
				$relation = m::app()->user->getSelectedRelation();
				$name = $relation->getObject()->name;

				if (
					!empty($relation) &&
					!empty($relation->getObject()) &&
					!empty($relation->getObject()->emissionType) &&
					empty($emissionType)
				) {
					$emissionType = $relation->getObject()->emissionType;
					$emissionValue = $relation->getObject()->emissionValue;
				}
				
				// bepaling contactpersonen, bij particulier is relatie dat zelf
				$contactPersonRelations = [];
				$uboRelations = [];
				switch ($relation->type) {
					case 'contactPerson':
						$contactPersonRelations = [$relation];
						
						// bepaling naam
						$name = "";
						$properties = ['salutation', 'initials', 'lastNamePrefix', 'lastName'];
						foreach ($properties as $property) {
							$name .= (isset($_SESSION['Registration']['ContactPerson'][$relation->getObject()->id][$property]) ? $_SESSION['Registration']['ContactPerson'][$relation->getObject()->id][$property] : $relation->getObject()->$property);
							$name .= " ";
						}
						break;
					case 'collective':
						$contactPersonRelations = $relation->getObject()->getSortedContactPersons();
						break;
					case 'organization':
						$contactPersonRelations = $relation->getObject()->getManagerRelations();
						$uboRelations = $relation->getObject()->getUboRelations();
						break;
				}
				
				// opmaken relation object voor sessie
				if (empty($_SESSION['Registration']['Relation'])) {
					$_SESSION['Registration']['Relation'] = [];
				}
				$_SESSION['Registration']['Relation']['id'] = $relation->id;
				$_SESSION['Registration']['Relation']['type'] = $relation->type;
				$_SESSION['Registration']['Relation']['name'] = $name;
				$_SESSION['Registration']['Relation']['correspondenceType'] = $relation->getObject()->transactionalMail;

				if ($relation->type == 'organization') {
					$_SESSION['Registration']['Relation']['legalEntityActivities'] = isset($params['Relation']['legalEntityActivities']) ? $params['Relation']['legalEntityActivities'] : (isset($_SESSION['Registration']['Relation']['legalEntityActivities']) ? $_SESSION['Registration']['Relation']['legalEntityActivities'] : $relation->getObject()->legalEntityActivities);
					$_SESSION['Registration']['Relation']['originOfResources'] = isset($params['Relation']['originOfResources']) ? $params['Relation']['originOfResources'] : (isset($_SESSION['Registration']['Relation']['originOfResources']) ? $_SESSION['Registration']['Relation']['originOfResources'] : $relation->getMostRecentOriginOfResources());
				}
				
				// door alle contactpersonen itereren
				if (!empty($contactPersonRelations)) {
					foreach ($contactPersonRelations as $contactPersonRelationId => $contactPersonRelation) {
						// ophalen post waarden, indien die bestaan
						$postParamsContactPerson = isset($params['ContactPerson']) && isset($params['ContactPerson'][$contactPersonRelation->getObject()->id]) ? $params['ContactPerson'][$contactPersonRelation->getObject()->id] : null;
						$sessionParamsContactPerson  = isset($_SESSION['Registration']['ContactPerson'][$contactPersonRelation->getObject()->id]) ? $_SESSION['Registration']['ContactPerson'][$contactPersonRelation->getObject()->id] : null;
						
						// instantieren sessie object
						if (empty($_SESSION['Registration']['ContactPerson'][$contactPersonRelation->getObject()->id])) {
							$_SESSION['Registration']['ContactPerson'][$contactPersonRelation->getObject()->id] = [];
						}
						
						// bepaling emailadress
						// emailadres wordt handmatig ingevuld wanneer deze niet bestaat voor relatie of user horende bij relatie
						$emailAddress =
							!empty($contactPersonRelation->getPrimaryEmailAddress()) ? $contactPersonRelation->getPrimaryEmailAddress()->address :
								(
									isset($postParamsContactPerson['emailAddressForInvitation']) ? $postParamsContactPerson['emailAddressForInvitation'] :
										(
											isset($sessionParamsContactPerson['emailAddressForInvitation']) ? $sessionParamsContactPerson['emailAddressForInvitation'] : ""
										)
								);
						
						$_SESSION['Registration']['ContactPerson'][$contactPersonRelation->getObject()->id]['salutation'] = $contactPersonRelation->getObject()->gender == 'male' ? 'De heer' : 'Mevrouw';
						$_SESSION['Registration']['ContactPerson'][$contactPersonRelation->getObject()->id]['initials'] = $this->processInitials($contactPersonRelation->getObject()->initials);
						$_SESSION['Registration']['ContactPerson'][$contactPersonRelation->getObject()->id]['lastNamePrefix'] = $contactPersonRelation->getObject()->lastNamePrefix;
						$_SESSION['Registration']['ContactPerson'][$contactPersonRelation->getObject()->id]['lastName'] = $contactPersonRelation->getObject()->lastName;
						$_SESSION['Registration']['ContactPerson'][$contactPersonRelation->getObject()->id]['idType'] = $contactPersonRelation->getObject()->idType;
						$_SESSION['Registration']['ContactPerson'][$contactPersonRelation->getObject()->id]['emailAddress'] = $emailAddress;
						$_SESSION['Registration']['ContactPerson'][$contactPersonRelation->getObject()->id]['name'] = $contactPersonRelation->getObject()->name;
						
						// bepaling telefoonnummers
						$phoneNumbers = [];
						foreach ($contactPersonRelation->getPhoneNumbers() as $phoneNumber) {
							$phoneNumbers[$phoneNumber->id] = [
								'id' => $phoneNumber->id,
								'type' => $phoneNumber->type,
								'number' => $phoneNumber->number,
								'isPrimary' => $phoneNumber->isPrimary
							];
						}
						$_SESSION['Registration']['ContactPerson'][$contactPersonRelation->getObject()->id]['phoneNumbers'] = $phoneNumbers;
						
						// bepaling nationaliteit
						$_SESSION['Registration']['ContactPerson'][$contactPersonRelation->getObject()->id]['nationality'] = isset($postParamsContactPerson['nationality']) ? $postParamsContactPerson['nationality'] : (isset($sessionParamsContactPerson['nationality']) ? $sessionParamsContactPerson['nationality'] : $contactPersonRelation->getObject()->nationality);
						$_SESSION['Registration']['ContactPerson'][$contactPersonRelation->getObject()->id]['residentOfUnitedStates'] = isset($postParamsContactPerson['residentOfUnitedStates']) ? $postParamsContactPerson['residentOfUnitedStates'] : (isset($sessionParamsContactPerson['residentOfUnitedStates']) ? $sessionParamsContactPerson['residentOfUnitedStates'] : $contactPersonRelation->getObject()->residentOfUnitedStates);
						$_SESSION['Registration']['ContactPerson'][$contactPersonRelation->getObject()->id]['isPep'] = isset($postParamsContactPerson['isPep']) ? $postParamsContactPerson['isPep'] : (isset($sessionParamsContactPerson['isPep']) ? $sessionParamsContactPerson['isPep'] : $contactPersonRelation->getObject()->isPep);
						
						switch ($_SESSION['Registration']['Relation']['type']) {
							case 'contactPerson':
								$_SESSION['Registration']['ContactPerson'][$contactPersonRelation->getObject()->id]['currentProfession'] = isset($postParamsContactPerson['currentProfession']) ? $postParamsContactPerson['currentProfession'] : (isset($sessionParamsContactPerson['currentProfession']) ? $sessionParamsContactPerson['currentProfession'] : $contactPersonRelation->getObject()->currentProfession);
								$_SESSION['Registration']['ContactPerson'][$contactPersonRelation->getObject()->id]['industry'] = isset($postParamsContactPerson['industry']) ? $postParamsContactPerson['industry'] : (isset($sessionParamsContactPerson['industry']) ? $sessionParamsContactPerson['industry'] : $contactPersonRelation->getObject()->industry);
								$_SESSION['Registration']['ContactPerson'][$contactPersonRelation->getObject()->id]['originOfResources'] = isset($postParamsContactPerson['originOfResources']) ? $postParamsContactPerson['originOfResources'] : (isset($sessionParamsContactPerson['originOfResources']) ? $sessionParamsContactPerson['originOfResources'] : $contactPersonRelation->getMostRecentOriginOfResources());
								break;
							case 'collective':
								$_SESSION['Registration']['ContactPerson'][$contactPersonRelation->getObject()->id]['currentProfession'] = isset($postParamsContactPerson['currentProfession']) ? $postParamsContactPerson['currentProfession'] : (isset($sessionParamsContactPerson['currentProfession']) ? $sessionParamsContactPerson['currentProfession'] : $contactPersonRelation->getObject()->currentProfession);
								$_SESSION['Registration']['ContactPerson'][$contactPersonRelation->getObject()->id]['industry'] = isset($postParamsContactPerson['industry']) ? $postParamsContactPerson['industry'] : (isset($sessionParamsContactPerson['industry']) ? $sessionParamsContactPerson['industry'] : $contactPersonRelation->getObject()->industry);
								$_SESSION['Registration']['ContactPerson'][$contactPersonRelation->getObject()->id]['originOfResources'] = isset($postParamsContactPerson['originOfResources']) ? $postParamsContactPerson['originOfResources'] : (isset($sessionParamsContactPerson['originOfResources']) ? $sessionParamsContactPerson['originOfResources'] : $contactPersonRelation->getMostRecentOriginOfResources());
								$_SESSION['Registration']['ContactPerson'][$contactPersonRelation->getObject()->id]['emailAddressForInvitation'] = isset($postParamsContactPerson['emailAddressForInvitation']) ? $postParamsContactPerson['emailAddressForInvitation'] : (isset($sessionParamsContactPerson['emailAddressForInvitation']) ? $sessionParamsContactPerson['emailAddressForInvitation'] : "");
								break;
							case 'organization':
								$otherEmployment = "";
								// controleren of otherEmployment bestaat in post
								if (isset($postParamsContactPerson['otherEmployment'])) {
									$otherEmployment = $postParamsContactPerson['otherEmployment'];
								// controleren of otherEmployment bestaat in sessie
								} else if (isset($sessionParamsContactPerson['otherEmployment'])) {
									$otherEmployment = $sessionParamsContactPerson['otherEmployment'];
								// ophalen otherEmployment bij entiteit
								} else {
									$otherEmployment = $contactPersonRelation->getObject()->otherEmployment;
								}
								$_SESSION['Registration']['ContactPerson'][$contactPersonRelation->getObject()->id]['otherEmployment'] = $otherEmployment;
								
								$_SESSION['Registration']['ContactPerson'][$contactPersonRelation->getObject()->id]['isUbo'] = isset($postParamsContactPerson['isUbo']) ? $postParamsContactPerson['isUbo'] : (isset($sessionParamsContactPerson['isUbo']) ? $sessionParamsContactPerson['isUbo'] : $contactPersonRelation->getObject()->isUbo);
								$_SESSION['Registration']['ContactPerson'][$contactPersonRelation->getObject()->id]['interestRate'] = isset($postParamsContactPerson['interestRate']) ? $postParamsContactPerson['interestRate'] : (isset($sessionParamsContactPerson['interestRate']) ? $sessionParamsContactPerson['interestRate'] : $contactPersonRelation->getObject()->interestRate);
								$_SESSION['Registration']['ContactPerson'][$contactPersonRelation->getObject()->id]['ownershipRate'] = isset($postParamsContactPerson['ownershipRate']) ? $postParamsContactPerson['ownershipRate'] : (isset($sessionParamsContactPerson['ownershipRate']) ? $sessionParamsContactPerson['ownershipRate'] : $contactPersonRelation->getObject()->ownershipRate);
								
								$_SESSION['Registration']['ContactPerson'][$contactPersonRelation->getObject()->id]['isPseudoUbo'] = isset($postParamsContactPerson['isPseudoUbo']) ? $postParamsContactPerson['isPseudoUbo'] : (isset($sessionParamsContactPerson['isPseudoUbo']) ? $sessionParamsContactPerson['isPseudoUbo'] : $contactPersonRelation->getObject()->isPseudoUbo);
								// wanneer contactpersoon ubo is, kan deze geen pseudo ubo zijn
								if ($_SESSION['Registration']['ContactPerson'][$contactPersonRelation->getObject()->id]['isUbo'] || !empty($_SESSION['Registration']['Ubo'])) {
									$_SESSION['Registration']['ContactPerson'][$contactPersonRelation->getObject()->id]['isPseudoUbo'] = false;
								}
						}
						
						/**
						 * Altijd toevoegen van adres bij bestuurder van rechtspersoon. Indien deze géén UBO is
						 * wordt het adres toch niet opgeslagen in processRegistration(). Hiermee voorkomen we dat
						 * default het adres veld (indien bestuurder wordt aangemerkt als UBO) alsnog leeg is.
						 */
						$postParamsContactPersonPseudoUbo = isset($params['ContactPersonPseudoUbo']) && isset($params['ContactPersonPseudoUbo'][$contactPersonRelation->getObject()->id]) ? $params['ContactPersonPseudoUbo'][$contactPersonRelation->getObject()->id] : null;
						
						$_SESSION['Registration']['ContactPerson'][$contactPersonRelation->getObject()->id]['street'] = isset($postParamsContactPerson['street']) && isset($postParamsContactPerson['isUbo']) && $postParamsContactPerson['isUbo'] ? $postParamsContactPerson['street'] : (isset($postParamsContactPersonPseudoUbo['street']) ? $postParamsContactPersonPseudoUbo['street'] : (!empty($sessionParamsContactPerson['street']) ? $sessionParamsContactPerson['street'] : (!empty($contactPersonRelation->visitingAddress) ? $contactPersonRelation->visitingAddress->street : null)));
						$_SESSION['Registration']['ContactPerson'][$contactPersonRelation->getObject()->id]['number'] = isset($postParamsContactPerson['number']) && isset($postParamsContactPerson['isUbo']) && $postParamsContactPerson['isUbo'] ? $postParamsContactPerson['number'] : (isset($postParamsContactPersonPseudoUbo['number']) ? $postParamsContactPersonPseudoUbo['number'] : (!empty($sessionParamsContactPerson['number']) ? $sessionParamsContactPerson['number'] : (!empty($contactPersonRelation->visitingAddress) ? $contactPersonRelation->visitingAddress->number : null)));
						$_SESSION['Registration']['ContactPerson'][$contactPersonRelation->getObject()->id]['numberSuffix'] = isset($postParamsContactPerson['numberSuffix']) && isset($postParamsContactPerson['isUbo']) && $postParamsContactPerson['isUbo'] ? $postParamsContactPerson['numberSuffix'] : (isset($postParamsContactPersonPseudoUbo['numberSuffix']) ? $postParamsContactPersonPseudoUbo['numberSuffix'] : (!empty($sessionParamsContactPerson['numberSuffix']) ? $sessionParamsContactPerson['numberSuffix'] : (!empty($contactPersonRelation->visitingAddress) ? $contactPersonRelation->visitingAddress->numberSuffix : null)));
						$_SESSION['Registration']['ContactPerson'][$contactPersonRelation->getObject()->id]['postalCode'] = isset($postParamsContactPerson['postalCode']) && isset($postParamsContactPerson['isUbo']) && $postParamsContactPerson['isUbo'] ? $postParamsContactPerson['postalCode'] : (isset($postParamsContactPersonPseudoUbo['postalCode']) ? $postParamsContactPersonPseudoUbo['postalCode'] : (!empty($sessionParamsContactPerson['postalCode']) ? $sessionParamsContactPerson['postalCode'] : (!empty($contactPersonRelation->visitingAddress) ? $contactPersonRelation->visitingAddress->postalCode : null)));
						$_SESSION['Registration']['ContactPerson'][$contactPersonRelation->getObject()->id]['city'] = isset($postParamsContactPerson['city']) && isset($postParamsContactPerson['isUbo']) && $postParamsContactPerson['isUbo'] ? $postParamsContactPerson['city'] : (isset($postParamsContactPersonPseudoUbo['city']) ? $postParamsContactPersonPseudoUbo['city'] : (!empty($sessionParamsContactPerson['city']) ? $sessionParamsContactPerson['city'] : (!empty($contactPersonRelation->visitingAddress) ? $contactPersonRelation->visitingAddress->city : null)));
						$_SESSION['Registration']['ContactPerson'][$contactPersonRelation->getObject()->id]['country'] = isset($postParamsContactPerson['country']) && isset($postParamsContactPerson['isUbo']) && $postParamsContactPerson['isUbo'] ? $postParamsContactPerson['country'] : (isset($postParamsContactPersonPseudoUbo['country']) ? $postParamsContactPersonPseudoUbo['country'] : (!empty($sessionParamsContactPerson['country']) ? $sessionParamsContactPerson['country'] : (!empty($contactPersonRelation->visitingAddress) ? $contactPersonRelation->visitingAddress->country : null)));
					}
				}
				
				// controleren of ubo relaties zijn gezet
				if (!empty($uboRelations)) {
					foreach ($uboRelations as $uboRelationId => $uboRelation) {
                        $uboRelationObject = $uboRelation->getObject();
                        if (!isset($_SESSION['Registration']['Ubo'][$uboRelationObject->id])) {
                            $visitingAddress = $uboRelation->getVisitingAddress();

                            $_SESSION['Registration']['Ubo'][$uboRelation->getObject()->id] = [
                                'relationId' => $uboRelation->id,
                                'name' => $uboRelationObject->getName(),
                                'salutation' => $uboRelationObject->gender == 'male' ? 'De heer' : 'Mevrouw',
                                'initials' => $this->processInitials($uboRelationObject->initials),
                                'lastNamePrefix' => $uboRelationObject->lastNamePrefix,
                                'lastName' => $uboRelationObject->lastName,
                                'interestRate' => $uboRelationObject->getInterestRate(),
                                'ownershipRate' => $uboRelationObject->getOwnershipRate(),
                                'street' => !is_null($visitingAddress) ? $visitingAddress->street : '',
                                'number' => !is_null($visitingAddress) ? $visitingAddress->number : '',
                                'numberSuffix' => !is_null($visitingAddress) ? $visitingAddress->numberSuffix : '',
                                'postalCode' => !is_null($visitingAddress) ? $visitingAddress->postalCode : '',
                                'city' => !is_null($visitingAddress) ? $visitingAddress->city : '',
                                'country' => !is_null($visitingAddress) ? $visitingAddress->country : '',
                                'edit' => false,
                                'delete' => false
                            ];
                        }
					}
				}
				
				// bepaling adres
				$postalAddress = [];
				if (!empty($relation->getPostalAddress())) {
					$postalAddress = [
						'id' => $relation->getPostalAddress()->id,
						'street' => $relation->getPostalAddress()->street,
						'number' => $relation->getPostalAddress()->number,
						'numberSuffix' => $relation->getPostalAddress()->numberSuffix,
						'postalCode' => $relation->getPostalAddress()->postalCode,
						'city' => $relation->getPostalAddress()->city,
						'country' => $relation->getPostalAddress()->country,
					];
				}
				$_SESSION['Registration']['PostalAddress'] = $postalAddress;
				
				// bepaling rekeningnummer
				if (isset($_SESSION['Registration']['BankAccount']) && !empty($_SESSION['Registration']['BankAccount']['id'])) {
					$bankAccount = new \app\hig\MBankAccount($_SESSION['Registration']['BankAccount']['id']);
					$_SESSION['Registration']['BankAccount']['iban'] = $bankAccount->iban;
					$_SESSION['Registration']['BankAccount']['ascription'] = $bankAccount->ascription;
				}
			}
		}
		
		// bij niet ingelogde gebruiker relatie gegevens goed verwerken
		if (empty(m::app()->user) || (m::app()->user->isAccountManager && empty(m::app()->user->getSelectedRelation()))) {
			if (isset($params['Relation']) && isset($params['Relation']['type'])) {
				$_SESSION['Registration']['Relation']['type'] = $params['Relation']['type'];
			}
			
			// fallback
			if (
				isset($_SESSION['Registration']['ContactPerson']) &&
				count($_SESSION['Registration']['ContactPerson']) == 1 &&
				$_SESSION['Registration']['Relation']['type'] == 'collective'
			) {
				$_SESSION['Registration']['Relation']['type'] = 'contactPerson';
			}
			
			if (
				(
					(isset($params['ContactPerson']) && array_key_first($params['ContactPerson']) > 0) ||
					(isset($_SESSION['Registration']['ContactPerson']) && count($_SESSION['Registration']['ContactPerson']) > 1)
				) &&
				$_SESSION['Registration']['Relation']['type'] == 'contactPerson'
			) {
				$_SESSION['Registration']['Relation']['type'] = 'collective';
			}
			
			if (isset($params['ContactPerson'])) {

				foreach ($params['ContactPerson'] as $key => $contactPersonParams) {

					$contactPersonName = "";
					if (!empty($contactPersonParams['salutation'])) {
						$contactPersonName .= $contactPersonParams['salutation'] . ' ';
					}
					if (!empty($contactPersonParams['initials'])) {
						$contactPersonName .= $this->processInitials($contactPersonParams['initials']) . ' ';
					}
					if (!empty($contactPersonParams['lastNamePrefix'])) {
						$contactPersonName .= $contactPersonParams['lastNamePrefix'] . ' ';
					}
					if (!empty($contactPersonParams['lastName'])) {
						$contactPersonName .= $contactPersonParams['lastName'];
					}
					
					if (!isset($_SESSION['Registration']['ContactPerson'][$key])) {
						$_SESSION['Registration']['ContactPerson'][$key] = [];
					}
					
					$_SESSION['Registration']['ContactPerson'][$key]['salutation'] = isset($contactPersonParams['salutation']) ? trim($contactPersonParams['salutation']) : (isset($_SESSION['Registration']['ContactPerson'][$key]['salutation']) ? trim($_SESSION['Registration']['ContactPerson'][$key]['salutation']) : "");
					$_SESSION['Registration']['ContactPerson'][$key]['initials'] = isset($contactPersonParams['initials']) ? trim($this->processInitials($contactPersonParams['initials'])) : (isset($_SESSION['Registration']['ContactPerson'][$key]['initials']) ? trim($_SESSION['Registration']['ContactPerson'][$key]['initials']) : "");
					$_SESSION['Registration']['ContactPerson'][$key]['lastNamePrefix'] = isset($contactPersonParams['lastNamePrefix']) ? trim($contactPersonParams['lastNamePrefix']) : (isset($_SESSION['Registration']['ContactPerson'][$key]['lastNamePrefix']) ? trim($_SESSION['Registration']['ContactPerson'][$key]['lastNamePrefix']) : "");
					$_SESSION['Registration']['ContactPerson'][$key]['lastName'] = isset($contactPersonParams['lastName']) ? trim($contactPersonParams['lastName']) : (isset($_SESSION['Registration']['ContactPerson'][$key]['lastName']) ? trim($_SESSION['Registration']['ContactPerson'][$key]['lastName']) : "");
					$_SESSION['Registration']['ContactPerson'][$key]['nationality'] = isset($contactPersonParams['nationality']) ? trim($contactPersonParams['nationality']) : (isset($_SESSION['Registration']['ContactPerson'][$key]['nationality']) ? trim($_SESSION['Registration']['ContactPerson'][$key]['nationality']) : "");
					$_SESSION['Registration']['ContactPerson'][$key]['idType'] = isset($contactPersonParams['idType']) ? trim($contactPersonParams['idType']) : (isset($_SESSION['Registration']['ContactPerson'][$key]['idType']) ? trim($_SESSION['Registration']['ContactPerson'][$key]['idType']) : "");
					$_SESSION['Registration']['ContactPerson'][$key]['phoneNumbers'] = isset($contactPersonParams['phoneNumbers']) ? $contactPersonParams['phoneNumbers'] : (isset($_SESSION['Registration']['ContactPerson'][$key]['phoneNumbers']) ? $_SESSION['Registration']['ContactPerson'][$key]['phoneNumbers'] : "");
					$_SESSION['Registration']['ContactPerson'][$key]['emailAddress'] = isset($contactPersonParams['emailAddress']) ? trim($contactPersonParams['emailAddress']) : (isset($_SESSION['Registration']['ContactPerson'][$key]['emailAddress']) ? trim($_SESSION['Registration']['ContactPerson'][$key]['emailAddress']) : "");
					$_SESSION['Registration']['ContactPerson'][$key]['currentProfession'] = isset($contactPersonParams['currentProfession']) ? trim($contactPersonParams['currentProfession']) : (isset($_SESSION['Registration']['ContactPerson'][$key]['currentProfession']) ? trim($_SESSION['Registration']['ContactPerson'][$key]['currentProfession']) : "");
					$_SESSION['Registration']['ContactPerson'][$key]['otherEmployment'] = isset($contactPersonParams['otherEmployment']) ? trim($contactPersonParams['otherEmployment']) : (isset($_SESSION['Registration']['ContactPerson'][$key]['otherEmployment']) ? trim($_SESSION['Registration']['ContactPerson'][$key]['otherEmployment']) : "");
					$_SESSION['Registration']['ContactPerson'][$key]['industry'] = isset($contactPersonParams['industry']) ? trim($contactPersonParams['industry']) : (isset($_SESSION['Registration']['ContactPerson'][$key]['industry']) ? trim($_SESSION['Registration']['ContactPerson'][$key]['industry']) : "");
					$_SESSION['Registration']['ContactPerson'][$key]['originOfResources'] = isset($contactPersonParams['originOfResources']) ? trim($contactPersonParams['originOfResources']) : (isset($_SESSION['Registration']['ContactPerson'][$key]['originOfResources']) ? trim($_SESSION['Registration']['ContactPerson'][$key]['originOfResources']) : "");
					$_SESSION['Registration']['ContactPerson'][$key]['isPep'] = isset($contactPersonParams['isPep']) ? (int) $contactPersonParams['isPep'] : (isset($_SESSION['Registration']['ContactPerson'][$key]['isPep']) ? (int) $_SESSION['Registration']['ContactPerson'][$key]['isPep'] : "");
					$_SESSION['Registration']['ContactPerson'][$key]['name'] = !empty($contactPersonName) ? trim($contactPersonName) : (isset($_SESSION['Registration']['ContactPerson'][$key]['name']) ? trim($_SESSION['Registration']['ContactPerson'][$key]['name']) : "");
					
					$_SESSION['Registration']['ContactPerson'][$key]['residentOfUnitedStates'] = isset($contactPersonParams['residentOfUnitedStates']) && !is_null($contactPersonParams['residentOfUnitedStates']) ?
						(int) $contactPersonParams['residentOfUnitedStates'] : (
							isset($_SESSION['Registration']['ContactPerson'][$key]['residentOfUnitedStates']) && !is_null($_SESSION['Registration']['ContactPerson'][$key]['residentOfUnitedStates']) ?
								(int) $_SESSION['Registration']['ContactPerson'][$key]['residentOfUnitedStates'] : null
						);
					
					if (isset($_SESSION['Registration']['Relation']['type'])) {
						switch ($_SESSION['Registration']['Relation']['type']) {
							case 'contactPerson':
								// indien relatie particulier betreft; herkomst van middelen ook zetten bij Relation[] obv ingevulde
								// gegevens bij Contactperson[]. Voor Collectief geldt dit niet. Organistie wordt direct ingevuld via
								// Relation[]
								$_SESSION['Registration']['Relation']['originOfResources'] = trim($_SESSION['Registration']['ContactPerson'][$key]['originOfResources']);
								break;
							case 'organization':
								$_SESSION['Registration']['ContactPerson'][$key]['isUbo'] = isset($contactPersonParams['isUbo']) ? $contactPersonParams['isUbo'] : (isset($_SESSION['Registration']['ContactPerson'][$key]['isUbo']) ? $_SESSION['Registration']['ContactPerson'][$key]['isUbo'] : false);
								$_SESSION['Registration']['ContactPerson'][$key]['interestRate'] = isset($contactPersonParams['interestRate']) ? $contactPersonParams['interestRate'] : (isset($_SESSION['Registration']['ContactPerson'][$key]['interestRate']) ? $_SESSION['Registration']['ContactPerson'][$key]['interestRate'] : null);
								$_SESSION['Registration']['ContactPerson'][$key]['ownershipRate'] = isset($contactPersonParams['ownershipRate']) ? $contactPersonParams['ownershipRate'] : (isset($_SESSION['Registration']['ContactPerson'][$key]['ownershipRate']) ? $_SESSION['Registration']['ContactPerson'][$key]['ownershipRate'] : null);
								
								$_SESSION['Registration']['ContactPerson'][$key]['isPseudoUbo'] = isset($contactPersonParams['isPseudoUbo']) ? $contactPersonParams['isPseudoUbo']  : (isset($_SESSION['Registration']['ContactPerson'][$key]['isPseudoUbo']) ? $_SESSION['Registration']['ContactPerson'][$key]['isPseudoUbo'] : false);
								
								// eventueel toevoegen adres indien deze bij bij Bestuurder als UBO is toegevoegd
								if (
									(isset($_SESSION['Registration']['ContactPerson'][$key]['isUbo']) && $_SESSION['Registration']['ContactPerson'][$key]['isUbo'])
								) {
									$_SESSION['Registration']['ContactPerson'][$key]['street'] = isset($contactPersonParams['street']) ? trim($contactPersonParams['street']) : (isset($_SESSION['Registration']['ContactPerson'][$key]['street']) ? trim($_SESSION['Registration']['ContactPerson'][$key]['street']) : null);
									$_SESSION['Registration']['ContactPerson'][$key]['number'] = isset($contactPersonParams['number']) ? trim($contactPersonParams['number']) : (isset($_SESSION['Registration']['ContactPerson'][$key]['number']) ? trim($_SESSION['Registration']['ContactPerson'][$key]['number']) : null);
									$_SESSION['Registration']['ContactPerson'][$key]['numberSuffix'] = isset($contactPersonParams['numberSuffix']) ? trim($contactPersonParams['numberSuffix']) : (isset($_SESSION['Registration']['ContactPerson'][$key]['numberSuffix']) ? trim($_SESSION['Registration']['ContactPerson'][$key]['numberSuffix']) : null);
									$_SESSION['Registration']['ContactPerson'][$key]['postalCode'] = isset($contactPersonParams['postalCode']) ? trim($contactPersonParams['postalCode']) : (isset($_SESSION['Registration']['ContactPerson'][$key]['postalCode']) ? trim($_SESSION['Registration']['ContactPerson'][$key]['postalCode']) : null);
									$_SESSION['Registration']['ContactPerson'][$key]['city'] = isset($contactPersonParams['city']) ? trim($contactPersonParams['city']) : (isset($_SESSION['Registration']['ContactPerson'][$key]['city']) ? trim($_SESSION['Registration']['ContactPerson'][$key]['city']) : null);
									$_SESSION['Registration']['ContactPerson'][$key]['country'] = isset($contactPersonParams['country']) ? trim($contactPersonParams['country']) : (isset($_SESSION['Registration']['ContactPerson'][$key]['country']) ? trim($_SESSION['Registration']['ContactPerson'][$key]['country']) : null);
								}

                                // eventueel toevoegen adres indien deze als pseudo-UBO is toegevoegd
                                if (
                                    (isset($_SESSION['Registration']['ContactPerson'][$key]['isPseudoUbo']) && $_SESSION['Registration']['ContactPerson'][$key]['isPseudoUbo'])
                                ) {
                                    $postParamsContactPersonPseudoUbo = isset($params['ContactPersonPseudoUbo']) && isset($params['ContactPersonPseudoUbo'][$key]) ? $params['ContactPersonPseudoUbo'][$key] : null;

                                    $_SESSION['Registration']['ContactPerson'][$key]['street'] = isset($postParamsContactPersonPseudoUbo['street']) ? trim($postParamsContactPersonPseudoUbo['street']) : (isset($_SESSION['Registration']['ContactPerson'][$key]['street']) ? trim($_SESSION['Registration']['ContactPerson'][$key]['street']) : null);
                                    $_SESSION['Registration']['ContactPerson'][$key]['number'] = isset($postParamsContactPersonPseudoUbo['number']) ? trim($postParamsContactPersonPseudoUbo['number']) : (isset($_SESSION['Registration']['ContactPerson'][$key]['number']) ? trim($_SESSION['Registration']['ContactPerson'][$key]['number']) : null);
                                    $_SESSION['Registration']['ContactPerson'][$key]['numberSuffix'] = isset($postParamsContactPersonPseudoUbo['numberSuffix']) ? trim($postParamsContactPersonPseudoUbo['numberSuffix']) : (isset($_SESSION['Registration']['ContactPerson'][$key]['numberSuffix']) ? trim($_SESSION['Registration']['ContactPerson'][$key]['numberSuffix']) : null);
                                    $_SESSION['Registration']['ContactPerson'][$key]['postalCode'] = isset($postParamsContactPersonPseudoUbo['postalCode']) ? trim($postParamsContactPersonPseudoUbo['postalCode']) : (isset($_SESSION['Registration']['ContactPerson'][$key]['postalCode']) ? trim($_SESSION['Registration']['ContactPerson'][$key]['postalCode']) : null);
                                    $_SESSION['Registration']['ContactPerson'][$key]['city'] = isset($postParamsContactPersonPseudoUbo['city']) ? trim($postParamsContactPersonPseudoUbo['city']) : (isset($_SESSION['Registration']['ContactPerson'][$key]['city']) ? trim($_SESSION['Registration']['ContactPerson'][$key]['city']) : null);
                                    $_SESSION['Registration']['ContactPerson'][$key]['country'] = isset($postParamsContactPersonPseudoUbo['country']) ? trim($postParamsContactPersonPseudoUbo['country']) : (isset($_SESSION['Registration']['ContactPerson'][$key]['country']) ? trim($_SESSION['Registration']['ContactPerson'][$key]['country']) : null);
                                }

								break;
							case 'collective':
								$_SESSION['Registration']['Relation']['originOfResources'] = null;
								
								// eventueel adres opslaan wanneer deze is aangemaakt voor nieuwe contactpersoon in collectief, NOOIT eerste contactpersoon
								if ($key > 0) {
									$_SESSION['Registration']['ContactPerson'][$key]['addressEqualsMainRelationAddress'] = isset($contactPersonParams['addressEqualsMainRelationAddress']) ? $contactPersonParams['addressEqualsMainRelationAddress'] : (isset($_SESSION['Registration']['ContactPerson'][$key]['addressEqualsMainRelationAddress']) ? $_SESSION['Registration']['ContactPerson'][$key]['addressEqualsMainRelationAddress'] : false);
									if (
										isset($_SESSION['Registration']['ContactPerson'][$key]['addressEqualsMainRelationAddress']) &&
										!$_SESSION['Registration']['ContactPerson'][$key]['addressEqualsMainRelationAddress']
									) {
										$_SESSION['Registration']['ContactPerson'][$key]['street'] = isset($contactPersonParams['street']) ? trim($contactPersonParams['street']) : (isset($_SESSION['Registration']['ContactPerson'][$key]['street']) ? trim($_SESSION['Registration']['ContactPerson'][$key]['street']) : null);
										$_SESSION['Registration']['ContactPerson'][$key]['number'] = isset($contactPersonParams['number']) ? trim($contactPersonParams['number']) : (isset($_SESSION['Registration']['ContactPerson'][$key]['number']) ? trim($_SESSION['Registration']['ContactPerson'][$key]['number']) : null);
										$_SESSION['Registration']['ContactPerson'][$key]['numberSuffix'] = isset($contactPersonParams['numberSuffix']) ? trim($contactPersonParams['numberSuffix']) : (isset($_SESSION['Registration']['ContactPerson'][$key]['numberSuffix']) ? trim($_SESSION['Registration']['ContactPerson'][$key]['numberSuffix']) : null);
										$_SESSION['Registration']['ContactPerson'][$key]['postalCode'] = isset($contactPersonParams['postalCode']) ? trim($contactPersonParams['postalCode']) : (isset($_SESSION['Registration']['ContactPerson'][$key]['postalCode']) ? trim($_SESSION['Registration']['ContactPerson'][$key]['postalCode']) : null);
										$_SESSION['Registration']['ContactPerson'][$key]['city'] = isset($contactPersonParams['city']) ? trim($contactPersonParams['city']) : (isset($_SESSION['Registration']['ContactPerson'][$key]['city']) ? trim($_SESSION['Registration']['ContactPerson'][$key]['city']) : null);
										$_SESSION['Registration']['ContactPerson'][$key]['country'] = isset($contactPersonParams['country']) ? trim($contactPersonParams['country']) : (isset($_SESSION['Registration']['ContactPerson'][$key]['country']) ? trim($_SESSION['Registration']['ContactPerson'][$key]['country']) : null);
									} else {
										unset($_SESSION['Registration']['ContactPerson'][$key]['street']);
										unset($_SESSION['Registration']['ContactPerson'][$key]['number']);
										unset($_SESSION['Registration']['ContactPerson'][$key]['numberSuffix']);
										unset($_SESSION['Registration']['ContactPerson'][$key]['postalCode']);
										unset($_SESSION['Registration']['ContactPerson'][$key]['city']);
										unset($_SESSION['Registration']['ContactPerson'][$key]['country']);
									}
								}
								break;
						}
					}
					
					// @TODO: file upload voor legitimatiebewijs afhandelen
					if (
						!empty($_FILES) &&
						isset($_FILES['Registration']) &&
						isset($_FILES['Registration']['tmp_name']) &&
						isset($_FILES['Registration']['tmp_name']['ContactPerson']) &&
						isset($_FILES['Registration']['tmp_name']['ContactPerson'][$key]) &&
						!empty($_FILES['Registration']['tmp_name']['ContactPerson'][$key]['idFile'])
					) {
						// controleren of er momenteel een file bestaat in sessie
						if (!empty($_SESSION['Registration']['ContactPerson'][$key]['idFile'])) {
							unlink($_SESSION['Registration']['ContactPerson'][$key]['idFile']['path']);
						}
						
						$pathInfo = pathinfo($_FILES['Registration']['name']['ContactPerson'][$key]['idFile']);
						$filename = $pathInfo['filename'] . '.' . $pathInfo['extension'];
						
						$tmpPathInfo = pathinfo($_FILES['Registration']['tmp_name']['ContactPerson'][$key]['idFile']);
						$filenameExplorer = time() . '_' . session_id() . '_' . $tmpPathInfo['filename'] . '.' . $pathInfo['extension'];
						
						$exportLocation = DOC_STORAGE_LOCATION . 'temp/export/';
						$path = $exportLocation . $filenameExplorer;
						
						if (copy($_FILES['Registration']['tmp_name']['ContactPerson'][$key]['idFile'], $path)) {
							$_SESSION['Registration']['ContactPerson'][$key]['idFile'] = [
								'filename' => $filename,
								'path' => $path
							];
						}
					}
				}
				
				if (
					isset($_SESSION['Registration']['Relation']['type']) &&
					$_SESSION['Registration']['Relation']['type'] != 'organization'
				) {
					$name = '';
					foreach ($_SESSION['Registration']['ContactPerson'] as $contactPersonParams) {
						if (isset($contactPersonParams['name'])) {
							if (!empty($name)) {
								$name .= ' en ';
							}
							$name .= $contactPersonParams['name'];
						}
					}
					$_SESSION['Registration']['Relation']['name'] = trim($name);
				}
			}
			
			if (isset($params['Relation'])) {
				if (
					isset($params['Relation']['name']) &&
					isset($_SESSION['Registration']['Relation']['type']) &&
					$_SESSION['Registration']['Relation']['type'] == 'organization'
				) {
					$_SESSION['Registration']['Relation']['name'] = trim($params['Relation']['name']);
				}
				
				if (isset($params['Relation']['cocNumber'])) {
					$_SESSION['Registration']['Relation']['cocNumber'] = $params['Relation']['cocNumber'];
				}
				
				if (isset($params['Relation']['originOfResources'])) {
					$_SESSION['Registration']['Relation']['originOfResources'] = trim($params['Relation']['originOfResources']);
				}
				
				if (isset($params['Relation']['legalEntityActivities'])) {
					$_SESSION['Registration']['Relation']['legalEntityActivities'] = trim($params['Relation']['legalEntityActivities']);
				}
				
				if (
					!empty($_FILES) &&
					isset($_FILES['Registration']) &&
					isset($_FILES['Registration']['tmp_name']) &&
					isset($_FILES['Registration']['tmp_name']['Relation']) &&
					!empty($_FILES['Registration']['tmp_name']['Relation']['cocFile'])
				) {
					// controleren of er momenteel een file bestaat in sessie
					if (!empty($_SESSION['Registration']['Relation']['cocFile'])) {
						unlink($_SESSION['Registration']['Relation']['cocFile']['path']);
					}
					
					$pathInfo = pathinfo($_FILES['Registration']['name']['Relation']['cocFile']);
					$filename = $pathInfo['filename'] . '.' . $pathInfo['extension'];
					
					$tmpPathInfo = pathinfo($_FILES['Registration']['tmp_name']['Relation']['cocFile']);
					$filenameExplorer = time() . '_' . session_id() . '_' . $tmpPathInfo['filename'] . '.' . $pathInfo['extension'];
					
					$exportLocation = DOC_STORAGE_LOCATION . 'temp/export/';
					$path = $exportLocation . $filenameExplorer;
					
					if (copy($_FILES['Registration']['tmp_name']['Relation']['cocFile'], $path)) {
						$_SESSION['Registration']['Relation']['cocFile'] = [
							'filename' => $filename,
							'path' => $path
						];
					}
				}
				
				if (isset($params['Relation']['correspondenceType'])) {
					$_SESSION['Registration']['Relation']['correspondenceType'] = $params['Relation']['correspondenceType'];
				}
				
				if (isset($params['Relation']['acceptDeclarationUbo'])) {
					$_SESSION['Registration']['Relation']['acceptDeclarationUbo'] = $params['Relation']['acceptDeclarationUbo'] == 'on' ? true : false;
				}
			}
		}

		if (!empty($emissionType)) {
			switch ($emissionType) {
				case 'fixed':
					// Aldus Rick van Overbeek (16-5-2022): een vast bedrag gaat nooit voorkomen bij een participant
					break;
				case 'percentage':
					$_SESSION['Registration']['EmissionCost'] = '<div class="emission-multiple"><s>(te vermeerderen met 3% emissiekosten)</s><span id="registration-emission_cost-custom">(te vermeerderen met ' . $emissionValue . '% emissiekosten)</span></div>';
					break;
				case 'none':
					$_SESSION['Registration']['EmissionCost'] = "<s>(te vermeerderen met 3% emissiekosten)</s>";
					break;
			}
		} else {
			$_SESSION['Registration']['EmissionCost'] = "(te vermeerderen met 3% emissiekosten)";
		}
		
		// UBO verwerking kan zowel bij bestaande als nieuwe gebruiker zijn
        if (isset($params['Ubo'])) {
			foreach ($params['Ubo'] as $key => $uboParams) {
				$uboName = "";
				if (!empty($uboParams['salutation'])) {
					$uboName .= $uboParams['salutation'] . ' ';
				}
				if (!empty($uboParams['initials'])) {
					$uboName .= $this->processInitials($uboParams['initials']) . ' ';
				}
				if (!empty($uboParams['lastNamePrefix'])) {
					$uboName .= $uboParams['lastNamePrefix'] . ' ';
				}
				if (!empty($uboParams['lastName'])) {
					$uboName .= $uboParams['lastName'];
				}
				
				if (!isset($_SESSION['Registration']['Ubo'][$key])) {
					$_SESSION['Registration']['Ubo'][$key] = [
						'edit' => true,
						'delete' => true
					];
				}
				
				$_SESSION['Registration']['Ubo'][$key]['salutation'] = isset($uboParams['salutation']) ? $uboParams['salutation'] : (isset($_SESSION['Registration']['Ubo'][$key]['salutation']) ? $_SESSION['Registration']['Ubo'][$key]['salutation'] : "");
				$_SESSION['Registration']['Ubo'][$key]['initials'] = isset($uboParams['initials']) ? $this->processInitials($uboParams['initials']) : (isset($_SESSION['Registration']['Ubo'][$key]['initials']) ? $_SESSION['Registration']['Ubo'][$key]['initials'] : "");
				$_SESSION['Registration']['Ubo'][$key]['lastNamePrefix'] = isset($uboParams['lastNamePrefix']) ? $uboParams['lastNamePrefix'] : (isset($_SESSION['Registration']['Ubo'][$key]['lastNamePrefix']) ? $_SESSION['Registration']['Ubo'][$key]['lastNamePrefix'] : "");
				$_SESSION['Registration']['Ubo'][$key]['lastName'] = isset($uboParams['lastName']) ? $uboParams['lastName'] : (isset($_SESSION['Registration']['Ubo'][$key]['lastName']) ? $_SESSION['Registration']['Ubo'][$key]['lastName'] : "");
				$_SESSION['Registration']['Ubo'][$key]['street'] = isset($uboParams['street']) ? $uboParams['street'] : (isset($_SESSION['Registration']['Ubo'][$key]['street']) ? $_SESSION['Registration']['Ubo'][$key]['street'] : "");
				$_SESSION['Registration']['Ubo'][$key]['number'] = isset($uboParams['number']) ? $uboParams['number'] : (isset($_SESSION['Registration']['Ubo'][$key]['number']) ? $_SESSION['Registration']['Ubo'][$key]['number'] : "");
				$_SESSION['Registration']['Ubo'][$key]['numberSuffix'] = isset($uboParams['numberSuffix']) ? $uboParams['numberSuffix'] : (isset($_SESSION['Registration']['Ubo'][$key]['numberSuffix']) ? $_SESSION['Registration']['Ubo'][$key]['numberSuffix'] : "");
				$_SESSION['Registration']['Ubo'][$key]['postalCode'] = isset($uboParams['postalCode']) ? $uboParams['postalCode'] : (isset($_SESSION['Registration']['Ubo'][$key]['postalCode']) ? $_SESSION['Registration']['Ubo'][$key]['postalCode'] : "");
				$_SESSION['Registration']['Ubo'][$key]['city'] = isset($uboParams['city']) ? $uboParams['city'] : (isset($_SESSION['Registration']['Ubo'][$key]['city']) ? $_SESSION['Registration']['Ubo'][$key]['city'] : "");
				$_SESSION['Registration']['Ubo'][$key]['country'] = isset($uboParams['country']) ? $uboParams['country'] : (isset($_SESSION['Registration']['Ubo'][$key]['country']) ? $_SESSION['Registration']['Ubo'][$key]['country'] : "");
				$_SESSION['Registration']['Ubo'][$key]['nationality'] = isset($uboParams['nationality']) ? $uboParams['nationality'] : (isset($_SESSION['Registration']['Ubo'][$key]['nationality']) ? $_SESSION['Registration']['Ubo'][$key]['nationality'] : "");
				$_SESSION['Registration']['Ubo'][$key]['idType'] = isset($uboParams['idType']) ? $uboParams['idType'] : (isset($_SESSION['Registration']['Ubo'][$key]['idType']) ? $_SESSION['Registration']['Ubo'][$key]['idType'] : "");
				$_SESSION['Registration']['Ubo'][$key]['isPep'] = isset($uboParams['isPep']) ? (int)$uboParams['isPep'] : (isset($_SESSION['Registration']['Ubo'][$key]['isPep']) ? $_SESSION['Registration']['Ubo'][$key]['isPep'] : "");
				$_SESSION['Registration']['Ubo'][$key]['residentOfUnitedStates'] = isset($uboParams['residentOfUnitedStates']) ? (int)$uboParams['residentOfUnitedStates'] : (isset($_SESSION['Registration']['Ubo'][$key]['residentOfUnitedStates']) ? (int)$_SESSION['Registration']['Ubo'][$key]['residentOfUnitedStates'] : "");
				$_SESSION['Registration']['Ubo'][$key]['name'] = !empty($uboName) ? $uboName : (isset($_SESSION['Registration']['Ubo'][$key]['name']) ? $_SESSION['Registration']['Ubo'][$key]['name'] : "");
				
				$_SESSION['Registration']['Ubo'][$key]['interestRate'] = isset($uboParams['interestRate']) ? $uboParams['interestRate'] : (isset($_SESSION['Registration']['Ubo'][$key]['interestRate']) ? $_SESSION['Registration']['Ubo'][$key]['interestRate'] : "");
				$_SESSION['Registration']['Ubo'][$key]['ownershipRate'] = isset($uboParams['ownershipRate']) ? $uboParams['ownershipRate'] : (isset($_SESSION['Registration']['Ubo'][$key]['ownershipRate']) ? $_SESSION['Registration']['Ubo'][$key]['ownershipRate'] : "");
				
				// @TODO: file upload voor legitimatiebewijs afhandelen
				if (
					!empty($_FILES) &&
					isset($_FILES['Registration']) &&
					isset($_FILES['Registration']['tmp_name']) &&
					isset($_FILES['Registration']['tmp_name']['Ubo']) &&
					isset($_FILES['Registration']['tmp_name']['Ubo'][$key]) &&
					!empty($_FILES['Registration']['tmp_name']['Ubo'][$key]['idFile'])
				) {
					// controleren of er momenteel een file bestaat in sessie
					if (!empty($_SESSION['Registration']['Ubo'][$key]['idFile'])) {
						unlink($_SESSION['Registration']['Ubo'][$key]['idFile']['path']);
					}
					
					$pathInfo = pathinfo($_FILES['Registration']['name']['Ubo'][$key]['idFile']);
					$filename = $pathInfo['filename'] . '.' . $pathInfo['extension'];
					
					$tmpPathInfo = pathinfo($_FILES['Registration']['tmp_name']['Ubo'][$key]['idFile']);
					$filenameExplorer = time() . '_' . session_id() . '_' . $tmpPathInfo['filename'] . '.' . $pathInfo['extension'];
					
					$exportLocation = DOC_STORAGE_LOCATION . 'temp/export/';
					$path = $exportLocation . $filenameExplorer;
					
					if (copy($_FILES['Registration']['tmp_name']['Ubo'][$key]['idFile'], $path)) {
						$_SESSION['Registration']['Ubo'][$key]['idFile'] = [
							'filename' => $filename,
							'path' => $path
						];
					}
				}
			}
		}

        $dummyHasUbo = h::getV('dummyHasUbo','alphanum',null,'POST');

        if (!is_null($dummyHasUbo) && $dummyHasUbo == '0') {

            // gebruiker heeft expliciet aangegeven dat er geen additionele UBO's zijn dus sessie opschonen
            $_SESSION['Registration']['Ubo'] = [];
        }


		// altijd mergen van parameters
		if (isset($params['PostalAddress'])) {
			$_SESSION['Registration']['PostalAddress'] = array_merge($_SESSION['Registration']['PostalAddress'], $params['PostalAddress']);
		}
		if (isset($params['BankAccount'])) {
			$_SESSION['Registration']['BankAccount'] = array_merge($_SESSION['Registration']['BankAccount'], $params['BankAccount']);

			// eventueel handmatig ingevoerde gegevens voor bankrekening opschonen
			$_SESSION['Registration']['BankAccount']['iban'] = trim($_SESSION['Registration']['BankAccount']['iban']);
			$_SESSION['Registration']['BankAccount']['ascription'] = trim($_SESSION['Registration']['BankAccount']['ascription']);
		}
		if (isset($params['Participation'])) {
			$_SESSION['Registration']['Participation'] = array_merge($_SESSION['Registration']['Participation'], $params['Participation']);
		}

	}
	private function getExtraVars() {
		$slug = h::getP(0, 'any', null, true);
		$fund = $this->determineFund();
		$extraVars = [];
		
		$extraVars['registration'] = isset($_SESSION['Registration']) ? $_SESSION['Registration'] : [];
		$extraVars['fund'] = $fund;
		$extraVars['slug'] = $slug;
		if (m::app()->user && !empty(m::app()->user->getSelectedRelation())) {
			$extraVars['relation'] = m::app()->user->getSelectedRelation();
		}

		$extraVars['accessibleEnvironmentRelationsForUser'] = $this->getActiveAccessibleEnvironmentRelations();
		
		switch (m::app()->doc->name) {
			case 'registration-home':
				$extraVars['urlNewUser'] = m::app()->getDocByName('registration-new_user')->getUrl([$slug]);
				$extraVars['urlExistingUser'] = m::app()->getDocByName('registration-login')->getUrl([$slug]);
				break;
			case 'registration-login':
				$extraVars['homeUrl'] = m::app()->getDocByName('registration-home')->getUrl([$slug]);
				$extraVars['redirectUrl'] = m::app()->getDocByName('registration-identification-ascription')->getUrl([$slug]);
				break;
			case 'registration-new_user':
				$extraVars['homeUrl'] = m::app()->getDocByName('registration-home')->getUrl([$slug]);
				$extraVars['urlLegalEntity'] = m::app()->getDocByName('registration-legalentity-identification-ascription')->getUrl([$slug]);
				
				$extraVars['urlPerson'] = m::app()->getDocByName('registration-person-identification-on_behalf')->getUrl([$slug]);
				if (m::app()->user && m::app()->user->isAccountManager) {
					$extraVars['urlPerson'] = m::app()->getDocByName('registration-person-identification-ascription')->getUrl([$slug]);
				}
				break;


			case 'registration-identification-ascription':
				// uitloggen en terug naar home?
				$extraVars['prevUrl'] = m::app()->getDocByName('registration-home')->getUrl([$slug]);
				$extraVars['nextUrl'] = m::app()->getDocByName('registration-identification-data')->getUrl([$slug]);
				break;
			case 'registration-identification-data':
				$prevUrl = m::app()->getDocByName('registration-identification-ascription')->getUrl([$slug]);
				if (count(m::app()->user->getEnvironmentRelations()) == 1) {
					$prevUrl = m::app()->getDocByName('registration-home')->getUrl([$slug]);
				}

				$extraVars['prevUrl'] = $prevUrl;

				$selectedRelation = m::app()->user->getSelectedRelation();
				switch ($selectedRelation->type) {
					case 'contactPerson':
					case 'collective':
						$extraVars['nextUrl'] = m::app()->getDocByName('registration-identification-bank_account')->getUrl([$slug]);
						break;
					case 'organization':
						$extraVars['nextUrl'] = m::app()->getDocByName('registration-identification-ubo')->getUrl([$slug]);
						break;
				}

				break;
			case 'registration-identification-ubo':
				// uitloggen en terug naar home?
				$extraVars['prevUrl'] = m::app()->getDocByName('registration-identification-data')->getUrl([$slug]);
				$extraVars['nextUrl'] = m::app()->getDocByName('registration-identification-ubo-declaration')->getUrl([$slug]);
				
				$extraVars['extraUboNewUrl'] = m::app()->getDocByName('registration-identification-extra_ubo-new')->getUrl([$slug]);
				$extraVars['extraUboEditUrl'] = m::app()->getDocByName('registration-identification-extra_ubo-edit')->getUrl([$slug]);
				$extraVars['extraUboDeleteUrl'] = m::app()->getDocByName('registration-identification-extra_ubo-delete')->getUrl([$slug]);
				
				$extraVars['uboIdx'] = 0; // altijd 0
				
				// bepaling of er bestuurders Ubo zijn
				$hasManagerAsUbo = false;
				foreach ($_SESSION['Registration']['ContactPerson'] as $contactPersonIdx => $contactPersonParams) {
					if (isset($contactPersonParams['isUbo']) && $contactPersonParams['isUbo']) {
						$hasManagerAsUbo = true;
						break;
					}
				}
				$extraVars['hasManagerAsUbo'] = $hasManagerAsUbo;
				$extraVars['hasAdditionalUbos'] = !empty($_SESSION['Registration']['Ubo']) ? true : false;
				
				break;
			case 'registration-identification-extra_ubo-new':
				$extraVars['prevUrl'] = m::app()->getDocByName('registration-identification-ubo')->getUrl([$slug]);;
				$extraVars['nextUrl'] = m::app()->getDocByName('registration-identification-ubo')->getUrl([$slug]);
				
				$extraVars['uboIdx'] = count($_SESSION['Registration']['Ubo']);
				break;
			case 'registration-identification-extra_ubo-edit':
				$extraVars['prevUrl'] = m::app()->getDocByName('registration-identification-ubo')->getUrl([$slug]);
				$extraVars['nextUrl'] = m::app()->getDocByName('registration-identification-ubo')->getUrl([$slug]);
				
				$extraVars['uboIdx'] = $_POST['idxEditExtraUbo'];
				
				break;
			case 'registration-identification-ubo-declaration':
				$extraVars['prevUrl'] = m::app()->getDocByName('registration-identification-ubo')->getUrl([$slug]);
				$extraVars['nextUrl'] = m::app()->getDocByName('registration-identification-bank_account')->getUrl([$slug]);
				
				break;
			case 'registration-identification-bank_account':
				$selectedRelation = m::app()->user->getSelectedRelation();
				switch ($selectedRelation->type) {
					case 'contactPerson':
					case 'collective':
						$extraVars['prevUrl'] = m::app()->getDocByName('registration-identification-data')->getUrl([$slug]);
						break;
					case 'organization':
						$extraVars['prevUrl'] = m::app()->getDocByName('registration-identification-ubo')->getUrl([$slug]);
						break;
				}
				$extraVars['nextUrl'] = m::app()->getDocByName('registration-participation')->getUrl([$slug]);
				break;
			case 'registration-participation':
				$extraVars['prevUrl'] = m::app()->getDocByName('registration-identification-bank_account')->getUrl([$slug]);
				$extraVars['nextUrl'] = m::app()->getDocByName('registration-check')->getUrl([$slug]);
				break;
			case 'registration-check':
				$extraVars['prevUrl'] = m::app()->getDocByName('registration-participation')->getUrl([$slug]);
				$extraVars['nextUrl'] = m::app()->getDocByName('registration-process')->getUrl([$slug]);
				break;
			case 'registration-complete':
				$content = m::app()->getCmsPartial('registration-thank_you-message')->content;
				if (m::app()->user && m::app()->user->isAccountManager) {
					$content = $this->getContentForAccountManager();
				}
				$extraVars['content'] = $content;
				break;
			
			case 'registration-person-identification-on_behalf':
				// uitloggen en terug naar home?
				$extraVars['prevUrl'] = m::app()->getDocByName('registration-new_user')->getUrl([$slug]);
				$extraVars['nextUrl'] = m::app()->getDocByName('registration-person-identification-ascription')->getUrl([$slug]);
				break;
			case 'registration-person-identification-ascription':
				// uitloggen en terug naar home?
				$extraVars['prevUrl'] = m::app()->getDocByName('registration-person-identification-on_behalf')->getUrl([$slug]);
				if (m::app()->user && m::app()->user->isAccountManager) {
					$extraVars['prevUrl'] = m::app()->getDocByName('registration-new_user')->getUrl([$slug]);
				}
				
				$extraVars['nextUrl'] = m::app()->getDocByName('registration-person-identification-bank_account')->getUrl([$slug]);
				
				$extraVars['extraPersonNewUrl'] = m::app()->getDocByName('registration-person-identification-extra_person-new')->getUrl([$slug]);
				$extraVars['extraPersonEditUrl'] = m::app()->getDocByName('registration-person-identification-extra_person-edit')->getUrl([$slug]);
				$extraVars['extraPersonDeleteUrl'] = m::app()->getDocByName('registration-person-identification-extra_person-delete')->getUrl([$slug]);
				
				$extraVars['contactPersonIdx'] = 0; // altijd 0
				$extraVars['phoneNumberIdx'] = 0; // altijd 0
				break;
			case 'registration-person-identification-extra_person-new':
				$extraVars['prevUrl'] = m::app()->getDocByName('registration-person-identification-ascription')->getUrl([$slug]);
				$extraVars['nextUrl'] = m::app()->getDocByName('registration-person-identification-ascription')->getUrl([$slug]);
				
				$contactPersonIdx = count($_SESSION['Registration']['ContactPerson']);
				$extraVars['contactPersonIdx'] = $contactPersonIdx;
				$extraVars['phoneNumberIdx'] = 0;
				break;
			case 'registration-person-identification-extra_person-edit':
				$extraVars['prevUrl'] = m::app()->getDocByName('registration-person-identification-ascription')->getUrl([$slug]);
				$extraVars['nextUrl'] = m::app()->getDocByName('registration-person-identification-ascription')->getUrl([$slug]);
				
				$extraVars['contactPersonIdx'] = $_POST['idxEditExtraPerson'];
				
				break;
			case 'registration-person-identification-bank_account':
				$extraVars['prevUrl'] = m::app()->getDocByName('registration-person-identification-ascription')->getUrl([$slug]);
				$extraVars['nextUrl'] = m::app()->getDocByName('registration-person-participation')->getUrl([$slug]);
				break;
			case 'registration-person-participation':
				$extraVars['prevUrl'] = m::app()->getDocByName('registration-person-identification-bank_account')->getUrl([$slug]);
				$extraVars['nextUrl'] = m::app()->getDocByName('registration-person-check')->getUrl([$slug]);
				break;
			case 'registration-person-check':
				$extraVars['prevUrl'] = m::app()->getDocByName('registration-person-participation')->getUrl([$slug]);;
				$extraVars['nextUrl'] = m::app()->getDocByName('registration-person-process')->getUrl([$slug]);
				break;
			case 'registration-person-complete':
				$content = m::app()->getCmsPartial('registration-thank_you-message')->content;
				if (m::app()->user && m::app()->user->isAccountManager) {
					$content = $this->getContentForAccountManager();
				}
				$extraVars['content'] = $content;
				break;
			
			case 'registration-legalentity-identification-ascription':
				// uitloggen en terug naar home?
				$extraVars['prevUrl'] = m::app()->getDocByName('registration-new_user')->getUrl([$slug]);
				$extraVars['nextUrl'] = m::app()->getDocByName('registration-legalentity-identification-ubo')->getUrl([$slug]);
				
				$extraVars['extraManagerNewUrl'] = m::app()->getDocByName('registration-legalentity-identification-extra_manager-new')->getUrl([$slug]);
				$extraVars['extraManagerEditUrl'] = m::app()->getDocByName('registration-legalentity-identification-extra_manager-edit')->getUrl([$slug]);
				$extraVars['extraManagerDeleteUrl'] = m::app()->getDocByName('registration-legalentity-identification-extra_manager-delete')->getUrl([$slug]);
				
				$extraVars['contactPersonIdx'] = 0; // altijd 0
				$extraVars['phoneNumberIdx'] = 0; // altijd 0
				break;
			case 'registration-legalentity-identification-extra_manager-new':
				$extraVars['prevUrl'] = m::app()->getDocByName('registration-legalentity-identification-ascription')->getUrl([$slug]);;
				$extraVars['nextUrl'] = m::app()->getDocByName('registration-legalentity-identification-ascription')->getUrl([$slug]);
				
				$extraVars['contactPersonIdx'] = count($_SESSION['Registration']['ContactPerson']);
				$extraVars['phoneNumberIdx'] = 0;
				break;
			case 'registration-legalentity-identification-extra_manager-edit':
				$extraVars['prevUrl'] = m::app()->getDocByName('registration-legalentity-identification-ascription')->getUrl([$slug]);
				$extraVars['nextUrl'] = m::app()->getDocByName('registration-legalentity-identification-ascription')->getUrl([$slug]);
				
				$extraVars['contactPersonIdx'] = $_POST['idxEditExtraManager'];
				
				break;
			case 'registration-legalentity-identification-ubo':
				// uitloggen en terug naar home?
				$extraVars['prevUrl'] = m::app()->getDocByName('registration-legalentity-identification-ascription')->getUrl([$slug]);
				$extraVars['nextUrl'] = m::app()->getDocByName('registration-legalentity-identification-ubo-declaration')->getUrl([$slug]);
				
				$extraVars['extraUboNewUrl'] = m::app()->getDocByName('registration-legalentity-identification-extra_ubo-new')->getUrl([$slug]);
				$extraVars['extraUboEditUrl'] = m::app()->getDocByName('registration-legalentity-identification-extra_ubo-edit')->getUrl([$slug]);
				$extraVars['extraUboDeleteUrl'] = m::app()->getDocByName('registration-legalentity-identification-extra_ubo-delete')->getUrl([$slug]);
				
				$extraVars['uboIdx'] = 0; // altijd 0
				
				// bepaling of er bestuurders Ubo zijn
				$hasManagerAsUbo = false;
				foreach ($_SESSION['Registration']['ContactPerson'] as $contactPersonIdx => $contactPersonParams) {
					if (isset($contactPersonParams['isUbo']) && $contactPersonParams['isUbo']) {
						$hasManagerAsUbo = true;
						break;
					}
				}
				$extraVars['hasManagerAsUbo'] = $hasManagerAsUbo;
				$extraVars['hasAdditionalUbos'] = !empty($_SESSION['Registration']['Ubo']) ? true : false;
				
				break;
			case 'registration-legalentity-identification-extra_ubo-new':
				$extraVars['prevUrl'] = m::app()->getDocByName('registration-legalentity-identification-ubo')->getUrl([$slug]);;
				$extraVars['nextUrl'] = m::app()->getDocByName('registration-legalentity-identification-ubo')->getUrl([$slug]);
				
				$extraVars['uboIdx'] = count($_SESSION['Registration']['Ubo']);
				break;
			case 'registration-legalentity-identification-extra_ubo-edit':
				$extraVars['prevUrl'] = m::app()->getDocByName('registration-legalentity-identification-ubo')->getUrl([$slug]);
				$extraVars['nextUrl'] = m::app()->getDocByName('registration-legalentity-identification-ubo')->getUrl([$slug]);
				
				$extraVars['uboIdx'] = $_POST['idxEditExtraUbo'];
				
				break;
			case 'registration-legalentity-identification-ubo-declaration':
				$extraVars['prevUrl'] = m::app()->getDocByName('registration-legalentity-identification-ubo')->getUrl([$slug]);
				$extraVars['nextUrl'] = m::app()->getDocByName('registration-legalentity-identification-bank_account')->getUrl([$slug]);
				
				break;
			
			case 'registration-legalentity-identification-bank_account':
				$extraVars['prevUrl'] = m::app()->getDocByName('registration-legalentity-identification-ubo-declaration')->getUrl([$slug]);
				$extraVars['nextUrl'] = m::app()->getDocByName('registration-legalentity-participation')->getUrl([$slug]);
				break;
			case 'registration-legalentity-participation':
				$extraVars['prevUrl'] = m::app()->getDocByName('registration-legalentity-identification-bank_account')->getUrl([$slug]);
				$extraVars['nextUrl'] = m::app()->getDocByName('registration-legalentity-check')->getUrl([$slug]);
				break;
			case 'registration-legalentity-check':
				$extraVars['prevUrl'] = m::app()->getDocByName('registration-legalentity-participation')->getUrl([$slug]);
				$extraVars['nextUrl'] = m::app()->getDocByName('registration-legalentity-process')->getUrl([$slug]);
				break;
			case 'registration-legalentity-complete':
				$content = m::app()->getCmsPartial('registration-thank_you-message')->content;
				if (m::app()->user && m::app()->user->isAccountManager) {
					$content = $this->getContentForAccountManager();
				}
				$extraVars['content'] = $content;
				break;
			
			case 'registration-declaration':
				$token = h::getV('token', 'any', null, 'GET', true);
				$registration = $this->registrationService->getRegistrationBySignToken($token);
				$content = $registration->fund->getDeclarationForRegistration($registration, $token);
				
				$extraVars['content'] = $content;
				$extraVars['nextUrl'] = m::app()->getDocByName('registration-sign')->getUrl([$slug]) . '?token='.$token;
				break;
			case 'registration-sign':
				break;
		}

		$extraVars['result'] = $this->result;
		
		return $extraVars;
	}
	private function resetSession() {
		unset($_SESSION['Registration']);
		unset($_SESSION['fundRegistrationSlug']);
		unset($_SESSION['RegistrationIntermediaryRelationId']);
		unset($_SESSION['RegistrationEmissionType']);
		unset($_SESSION['RegistrationEmissionValue']);

		// wanneer sessie wordt gereset, voor ingelogde gebruiker ook gekozen relatie resetten om eventueel opnieuw vullen
		// van sessie te voorkomen
		if (!empty(m::app()->user)) {
			m::app()->user->unsetSelectedRelation();
		}
	}
	private function isEmptySession() {
		if (
			empty($_SESSION['Registration']) ||
			(
				empty($_SESSION['Registration']['Relation']) &&
				empty($_SESSION['Registration']['ContactPerson']) &&
				empty($_SESSION['Registration']['PostalAddress']) &&
				empty($_SESSION['Registration']['BankAccount']) &&
				empty($_SESSION['Registration']['Participation']) &&
				empty($_SESSION['Registration']['Ubo'])
			)
		) {
			return true;
		}
		
		return false;
	}
	private function processRegistration() {
		$this->result->success = true;
		$this->result->message = "";

		// uitvoeren acties voor verwerking inschrijving
		try {
			m::app()->db->startTransaction();
			
			$fund = $this->determineFund();
			
			if (!empty($_SESSION['Registration'])) {
				$relation = null;
				$bankAccountId = null;
				$addressId = null;
				$numParticipations = 0;
				
				if (!empty(m::app()->user) && !empty(m::app()->user->getSelectedRelation())) {
					$relation = m::app()->user->getSelectedRelation();
					$addressId = $relation->getPostalAddress()->id;
				}
				if (!empty($_SESSION['Registration']) && isset($_SESSION['Registration']['BankAccount']) && !empty($_SESSION['Registration']['BankAccount']['id'])) {
					$bankAccountId = $_SESSION['Registration']['BankAccount']['id'];
				}
				if (!empty($_SESSION['Registration']) && isset($_SESSION['Registration']['Participation']) && !empty($_SESSION['Registration']['Participation']['amount'])) {
					$numParticipations = $_SESSION['Registration']['Participation']['amount'];
				}
				
				// aanmaken inschrijving
				$registration = new \app\hig\MRegistration();
				$registration->relationId = !empty($relation) ? $relation->id : null;
				$registration->type = 'digital';
				$registration->fundId = $fund->id;
				$registration->status = 'waiting-for-signing';
				$registration->bankAccountId = $bankAccountId;
				$registration->addressId = $addressId;
				$registration->numParticipations = $numParticipations;

				$intermediaryRelation = null;
				if (!empty($_SESSION['RegistrationIntermediaryRelationId'])) {
					$intermediaryRelation = new \app\hig\MRelation($_SESSION['RegistrationIntermediaryRelationId']);
					$registration->intermediaryRelationId = $intermediaryRelation->id;
				}
				
				// default emissiekosten zijn altijd 3%
				$defaultEmissionType = 'percentage';
				$defaultEmissionValue = 3;
				// bepaling emissiekosten op basis van parameters in sessie
				if (!empty($_SESSION['RegistrationEmissionType'])) {
					$defaultEmissionType = $_SESSION['RegistrationEmissionType'];
					if (!empty($_SESSION['RegistrationEmissionValue'])) {
						$defaultEmissionValue = $_SESSION['RegistrationEmissionValue'];
					}
				} elseif (!empty($intermediaryRelation)) {
					$registration->intermediaryCommissionType = $intermediaryRelation->object->defaultCommissionType;
					$registration->intermediaryCommissionValue = $intermediaryRelation->object->defaultCommissionValue;
					$defaultEmissionType = $intermediaryRelation->object->defaultEmissionType;
					$defaultEmissionValue = $intermediaryRelation->object->defaultEmissionValue;
				} elseif (!empty($relation) && !empty($relation->object)) {
					$defaultEmissionType = $relation->object->emissionType;
					$defaultEmissionValue = $relation->object->emissionValue;
				}

				if (!empty($defaultEmissionType)) {
					$registration->emissionType = $defaultEmissionType;
				}
				if (!empty($defaultEmissionValue)) {
					$registration->emissionValue = $defaultEmissionValue;
				}

				// bepalen of UTM code bestaat voor inschrijving
				if (!empty($_SESSION['RegistrationUtmCode'])) {
					$registration->utmCode = $_SESSION['RegistrationUtmCode'];
				}

				// controleren of accountmanager registratie invult
				if (!empty(m::app()->user) && m::app()->user->isAccountManager && !empty(m::app()->user->accountManagerRelation)) {
					$registration->accountManagerRelationId = m::app()->user->accountManagerRelation->id;
				}

				$registration->add();
				
				foreach ($_SESSION['Registration'] as $category => $params) {
					$entity = '\\app\hig\\MRegistration' . h::toCamelCase($category);
					if ($category == 'PostalAddress') { $entity = '\\app\\hig\\MRegistrationAddress'; }
					
					
					switch ($category) {
						case 'ContactPerson':
							$registrationContactPersons = [];
							foreach ($params as $contactPersonIdx => $contactPersonParams) {
								$registrationContactPerson = new \app\hig\MRegistrationContactPerson();
								$registrationContactPerson->registrationId = $registration->id;
								if (!empty($contactPersonParams)) {
									foreach ($contactPersonParams as $param => $value) {
										if ($param == 'phoneNumbers' || $param == 'name') { continue; }
										
										if (property_exists($registrationContactPerson, $param)) {
											$registrationContactPerson->$param = !empty($value) ? $value : (is_int($value) ? $value : null);
										}
									}
								}
								$registrationContactPerson->signToken = m::app()->generateRandomSalt();
								$registrationContactPerson->add();
								
								if (!empty($contactPersonParams['idFile']) && isset($contactPersonParams['idFile']['path'])) {
									$registrationContactPersons[$registrationContactPerson->id] = $contactPersonParams['idFile']['path'];
								}
								
								if (isset($contactPersonParams['phoneNumbers'])) {
									foreach ($contactPersonParams['phoneNumbers'] as $phoneNumberParams) {
										$registrationContactPersonPhoneNumber = new \app\hig\MRegistrationContactPersonPhoneNumber();
										$registrationContactPersonPhoneNumber->registrationContactPersonId = $registrationContactPerson->id;
										$registrationContactPersonPhoneNumber->type = $phoneNumberParams['type'];
										$registrationContactPersonPhoneNumber->number = $phoneNumberParams['number'];
										$registrationContactPersonPhoneNumber->add();
									}
								}
								
								if (isset($contactPersonParams['addressEqualsMainRelationAddress']) && !$contactPersonParams['addressEqualsMainRelationAddress']) {
									$properties = ['street','numberSuffix','number','postalCode','city','country'];
									
									$registrationContactPersonAddress = new \app\hig\MRegistrationContactPersonAddress();
									$registrationContactPersonAddress->registrationId = $registration->id;
									$registrationContactPersonAddress->registrationContactPersonId = $registrationContactPerson->id;
									foreach ($properties as $property) {
										if (!isset($contactPersonParams[$property])) { continue; }
										if (property_exists($registrationContactPersonAddress, $property)) {
											$registrationContactPersonAddress->$property = $contactPersonParams[$property];
										}
									}
									$registrationContactPersonAddress->add();
								}
								
								// controleren of we adres moeten opslaan voor de contactpersoon
								if (
									(isset($contactPersonParams['isUbo']) && $contactPersonParams['isUbo']) ||
									(isset($contactPersonParams['isPseudoUbo']) && $contactPersonParams['isPseudoUbo'])
								) {
									$properties = ['street','numberSuffix','number','postalCode','city','country'];
									
									// controleren of adres bestaat voor contactpersoon
									$registrationContactPersonAddress = $this->registrationService->getRegistrationContactPersonsAddress($registrationContactPerson->id, $contactPersonParams, $properties);
									$action = 'update';
									if (empty($registrationContactPersonAddress)) {
										$registrationContactPersonAddress = new \app\hig\MRegistrationContactPersonAddress();
										$registrationContactPersonAddress->registrationId = $registration->id;
										$registrationContactPersonAddress->registrationContactPersonId = $registrationContactPerson->id;
										$action = 'add';
									}
									
									foreach ($properties as $property) {
										if (!isset($contactPersonParams[$property])) { continue; }
										if (property_exists($registrationContactPersonAddress, $property)) {
											$registrationContactPersonAddress->$property = $contactPersonParams[$property];
										}
									}
									$registrationContactPersonAddress->$action();
								}
								
								// aanvullende acties voor bestaande relatie / contactpersoon
								if (!empty($relation)) {
									// ophalen contactpersonen van relatie
									$contactPersons = [$relation->getObject()->id => $relation]; // bij particulier
									switch ($relation->type) {
										case 'collective':
											$contactPersons = [];
											foreach ($relation->getObject()->getSortedContactPersons() as $contactPersonRelation) {
												$contactPersons[$contactPersonRelation->getObject()->id] = $contactPersonRelation;
											}
											break;
										case 'organization':
											$contactPersons = [];
											foreach ($relation->getObject()->getManagerRelations() as $contactPersonRelation) {
												$contactPersons[$contactPersonRelation->getObject()->id] = $contactPersonRelation;
											}
											break;
									}
									
									// bepalen of contactpersoon hoort bij een bestaande relatie
									if (!empty($contactPersons[$contactPersonIdx])) {
										$contactPersonRelation = $contactPersons[$contactPersonIdx];
										$registrationContactPerson->relationId = $contactPersonRelation->id;
										$registrationContactPerson->update();
										
										// controleren of e-mailadres bestaat voor deze relatie
										if (
											empty($contactPersonRelation->getPrimaryEmailAddress()) &&
											(
												(!empty($contactPersonRelation->user) && is_int($contactPersonRelation->user->emailAddress)) ||
												empty($contactPersonRelation->user)
											) &&
											isset($contactPersonParams['emailAddressForInvitation'])
										) {
											// bij geen bestaand e-mailadres voor relatie, toevoegen als change
											$emailAddress = new \app\hig\MEmailAddress();
											$emailAddress->relationId = $contactPersonRelation->id;
											$emailAddress->address = $contactPersonParams['emailAddressForInvitation'];
											$emailAddress->isPrimary = 1;
											$emailAddress->sendEmail = 1;
											$emailAddress->add();
										}
										
										// bijwerken van aanvullende gegevens voor bestaande relatie
										$properties = ['nationality', 'currentProfession','industry','residentOfUnitedStates','otherEmployment','isPep','isUbo','isPseudoUbo','interestRate','ownershipRate'];
										foreach ($properties as $property) {
											if (!isset($contactPersonParams[$property])) { continue; }
											
											switch ($property) {
												default:
													if (property_exists($contactPersonRelation->object, $property)) {
														$contactPersonRelation->object->$property = $contactPersonParams[$property];
													}
													break;
												case 'otherEmployment':
												case 'isUbo':
												case 'isPseudoUbo':
												case 'interestRate':
												case 'ownershipRate':
													/** @var \app\hig\CContactPersonService $contactPersonService */
													$contactPersonService = m::app()->serviceManager->get('contactPersonService');
													$organizationRelation = $contactPersonService->getOrganizationRelationForContactPerson($contactPersonRelation->id);
													if (!empty($organizationRelation)) {
														$organizationRelation->$property = $contactPersonParams[$property];
														$organizationRelation->update();
													}
													break;
											}
										}
										$contactPersonRelation->object->update();
										$contactPersonRelation->update();
										
										// wanneer adres voor inschrijving niet leeg is, deze ook toevoegen bij de relatie
										if (!empty($registrationContactPersonAddress)) {
											$addAddress = true;
											
											$addresses = $this->relationService->getAddressesForRelation($contactPersonRelation->id);
											if (!empty($addresses)) {
												foreach ($addresses as $address) {
													/**
													 * Adressen worden hier op alle properties gematcht. Indien een adres
													 * exact match, het bestaande adres updaten met de nieuwe waarheid.
													 * De nieuwe waarheid kan eventueel een verschil zijn in hoofdletters of andere
													 * kleine zaken. Uiteraard maken we dan ook geen nieuw adres aan.
													 *
													 * Matchen op basis van trim(), strtolower() en str_replace(" ", "")
													 */
													if (
														strtolower(str_replace(" ", "", trim($address->street))) == strtolower(str_replace(" ", "", trim($registrationContactPersonAddress->street))) &&
														strtolower(str_replace(" ", "", trim($address->number))) == strtolower(str_replace(" ", "", trim($registrationContactPersonAddress->number))) &&
														strtolower(str_replace(" ", "", trim($address->numberSuffix))) == strtolower(str_replace(" ", "", trim($registrationContactPersonAddress->numberSuffix))) &&
														strtolower(str_replace(" ", "", trim($address->postalCode))) == strtolower(str_replace(" ", "", trim($registrationContactPersonAddress->postalCode))) &&
														strtolower(str_replace(" ", "", trim($address->city))) == strtolower(str_replace(" ", "", trim($registrationContactPersonAddress->city))) &&
														strtolower(str_replace(" ", "", trim($address->country))) == strtolower(str_replace(" ", "", trim($registrationContactPersonAddress->country)))
													) {
														$addAddress = false;
														
														$address->street = $registrationContactPersonAddress->street;
														$address->number = $registrationContactPersonAddress->number;
														$address->numberSuffix = $registrationContactPersonAddress->numberSuffix;
														$address->postalCode = $registrationContactPersonAddress->postalCode;
														$address->city = $registrationContactPersonAddress->city;
														$address->country = $registrationContactPersonAddress->country;
														$address->update();
														break;
													}
												}
											}
											
											if ($addAddress) {
												$changeManager = new \app\hig\CChangeManager();
												
												$contactPersonRelationAddress = new \app\hig\MAddress();
												$contactPersonRelationAddress->street = $registrationContactPersonAddress->street;
												$contactPersonRelationAddress->number = $registrationContactPersonAddress->number;
												$contactPersonRelationAddress->numberSuffix = $registrationContactPersonAddress->numberSuffix;
												$contactPersonRelationAddress->postalCode = $registrationContactPersonAddress->postalCode;
												$contactPersonRelationAddress->city = $registrationContactPersonAddress->city;
												$contactPersonRelationAddress->country = $registrationContactPersonAddress->country;
												$contactPersonRelationAddress->add(false);
												
												// toevoegen change voor contactpersoon NIET hoofdrelatie
												$vars = $contactPersonRelationAddress->getDBvars();
												array_unshift($vars,"id");
												$changeManager->setModel($contactPersonRelationAddress);
												$changeManager->setRelation($contactPersonRelation);
												$changeManager->addChange('add', $vars);
												
												$contactPersonRelationRelationAddress = new \app\hig\MRelationAddress();
												$contactPersonRelationRelationAddress->relationId = $contactPersonRelation->id;
												$contactPersonRelationRelationAddress->addressId = $contactPersonRelationAddress->id;
												$contactPersonRelationRelationAddress->isPrimary = 1;
												$contactPersonRelationRelationAddress->description = 'visiting';
												$contactPersonRelationRelationAddress->add(false);
												
												$vars = $contactPersonRelationRelationAddress->getDBvars();
												array_unshift($vars,"id");
												$changeManager->setModel($contactPersonRelationRelationAddress);
												$changeManager->setRelation($contactPersonRelation);
												$changeManager->addChange('add', $vars);
											}
										}
									}
								}
							}
							break;
						case 'Relation':
							$cocFile = !empty($params['cocFile']) && isset($params['cocFile']['path']) ? $params['cocFile']['path'] : null;
							
							$registrationRelation = new $entity();
							$registrationRelation->registrationId = $registration->id;
							foreach ($params as $param => $value) {
								if ($param == 'correspondenceType') { $param = 'transactionalMail'; }
								if (property_exists($registrationRelation, $param)) {
									$registrationRelation->$param = !empty($value) ? $value : null;
								}
							}
							$registrationRelation->add();
							
							
							// aanvullende acties voor bestaande relatie / contactpersoon
							if (!empty($relation) && $relation->type == 'organization' && !empty($relation->getObject())) {
								// bijwerken van aanvullende gegevens voor bestaande relatie
								$properties = ['legalEntityActivities'];
								foreach ($properties as $property) {
									if (!isset($params[$property])) { continue; }
									
									switch ($property) {
										default:
											if (property_exists($relation, $property)) {
												$relation->$property = $params[$property];
											}
											break;
										case 'legalEntityActivities':
											$relation->getObject()->$property = $params[$property];
											$relation->getObject()->update();
											break;
									}
								}
								$relation->update();
							}
							break;
						case 'PostalAddress':
						case 'BankAccount':
							$model = new $entity();
							$model->registrationId = $registration->id;
							foreach ($params as $param => $value) {
								if (property_exists($model, $param)) {
									$model->$param = !empty($value) ? $value : null;
								}
							}
							$model->add();
							
							// wanneer type bankrekening is, relatie bestaat en er geen ID voor het bankrekeningnummer is gezet
							if ($category == 'BankAccount' && !empty($registration->relation) && empty($registration->bankAccountId)) {
								$newBankAccount = new \app\hig\MBankAccount();
								$newBankAccount->relationId = $registration->relation->id;
								$newBankAccount->iban = $model->iban;
								$newBankAccount->ascription = $model->ascription;
								$newBankAccount->active = 1;
								$newBankAccount->add(false);
								
								$changeManager = new \app\hig\CChangeManager();
								
								// toevoegen change voor contactpersoon NIET hoofdrelatie
								$vars = $newBankAccount->getDBvars();
								array_unshift($vars,"id");
								$changeManager->setModel($newBankAccount);
								$changeManager->setRelation($registration->relation);
								$changeManager->addChange('add', $vars);
								
								// ook toevoegen id van registratie aan zojuist toegevoegde change, zodat we bij sync het daadwerkelijke
								// ID van het nieuwe bankrekeningnummer kunnen toevoegen
								$change = $this->changeService->getChangeByProperties($newBankAccount->id, 'MBankAccount', $registration->relation->id, 'add');
								if (!empty($change)) {
									$change->status = 'no-approval-needed';
									$change->update();
									
									// change moet hier eigenlijk ALTIJD bestaan, immers zojuist toegevoegd
									$changeValue = new \app\hig\MChangeValue();
									$changeValue->property = 'registrationId';
									$changeValue->newValue = $registration->id;
									$changeValue->changeId = $change->id;
									$changeValue->add();
								}
							}
							
							break;
						case 'Ubo':
							$registrationUbos = [];
							foreach ($params as $uboIdx => $uboParams) {
								if (!empty($uboParams)) {
									$registrationUbo = new \app\hig\MRegistrationUbo();
									$registrationUbo->registrationId = $registration->id;
									// vullen van model obv params bekend in sessie
									foreach ($uboParams as $param => $value) {
										if ($param == 'name') { continue; }
										
										if (property_exists($registrationUbo, $param)) {
											$registrationUbo->$param = !empty($value) ? $value : (is_int($value) ? $value : null);
										}
									}
									
                                    // Als het goed is zijn deze gegevens altijd gevuld, maar voor het geval dit niet is,
                                    // lege adres/naam waarden doorsturen zodat er geen errors worden gegenereerd
                                    $registrationUbo->relationId = empty($registrationUbo->relationId) ? null : $registrationUbo->relationId;
                                    $registrationUbo->salutation = empty($registrationUbo->salutation) ? '' : $registrationUbo->salutation;
                                    $registrationUbo->initials = empty($registrationUbo->initials) ? '' : $registrationUbo->initials;
                                    $registrationUbo->lastName = empty($registrationUbo->lastName) ? '' : $registrationUbo->lastName;
                                    $registrationUbo->nationality = empty($registrationUbo->nationality) ? '' : $registrationUbo->nationality;
                                    $registrationUbo->street = empty($registrationUbo->street) ? '' : $registrationUbo->street;
                                    $registrationUbo->number = empty($registrationUbo->number) ? '' : $registrationUbo->number;
                                    $registrationUbo->postalCode = empty($registrationUbo->postalCode) ? '' : $registrationUbo->postalCode;
                                    $registrationUbo->city = empty($registrationUbo->city) ? '' : $registrationUbo->city;
                                    $registrationUbo->country = empty($registrationUbo->country) ? '' : $registrationUbo->country;
                                    $registrationUbo->residentOfUnitedStates = !empty($registrationUbo->residentOfUnitedStates) ? $registrationUbo->residentOfUnitedStates : (is_int($registrationUbo->residentOfUnitedStates) ? $registrationUbo->residentOfUnitedStates : null);
                                    $registrationUbo->isPep = !empty($registrationUbo->isPep) ? $registrationUbo->isPep : (is_int($registrationUbo->isPep) ? $registrationUbo->isPep : null);
                                    $registrationUbo->interestRate = empty($registrationUbo->interestRate) ? '' : $registrationUbo->interestRate;
                                    $registrationUbo->ownershipRate = empty($registrationUbo->ownershipRate) ? '' : $registrationUbo->ownershipRate;
									$registrationUbo->add();
									
									// naast het toevoegen van MRegistrationUbo controleren we ook of deze entiteit niet al bestaat bij de relatie
									if (!empty($relation)) {
										// ophalen bestaande UBO's van relatie, hoofdrelatie kan alleen rechtspersoon zijn. Voor andere typen bestaan geen UBO's
										$contactPersons = [];
										foreach ($relation->getObject()->getUboRelations() as $contactPersonRelation) {
											$contactPersons[$contactPersonRelation->getObject()->id] = $contactPersonRelation;
										}
										
										// bepalen of contactpersoon hoort bij een bestaande relatie
										if (!empty($contactPersons[$uboIdx])) {
											$contactPersonRelation = $contactPersons[$uboIdx];
											$registrationUbo->relationId = $contactPersonRelation->id;
											$registrationUbo->update();
											
											// bijwerken van aanvullende gegevens voor bestaande relatie
											$properties = ['residentOfUnitedStates','interestRate','ownershipRate'];
											foreach ($properties as $property) {
												if (!isset($uboParams[$property])) { continue; }
												
												switch ($property) {
													default:
														if (property_exists($contactPersonRelation->object, $property)) {
															$contactPersonRelation->object->$property = $uboParams[$property];
														}
														break;
													case 'interestRate':
													case 'ownershipRate':
														/** @var \app\hig\CContactPersonService $contactPersonService */
														$contactPersonService = m::app()->serviceManager->get('contactPersonService');
														$organizationRelation = $contactPersonService->getOrganizationRelationForContactPerson($contactPersonRelation->id);
														if (!empty($organizationRelation)) {
															$organizationRelation->$property = $uboParams[$property];
															$organizationRelation->update();
														}
														break;
												}
											}
											$contactPersonRelation->object->update();
											$contactPersonRelation->update();
											
											// controleren of we een adres moeten toevoegen / bijwerken
											$properties = ['street','numberSuffix','number','postalCode','city','country'];
											$uboRelationAddress = $this->relationService->getSpecificRelationAddressForRelation($contactPersonRelation->id, $uboParams, $properties);
											if (empty($uboRelationAddress)) {
												$changeManager = new \app\hig\CChangeManager();
												
												// wanneer er geen relation address bestaat voor deze specifieke combinatie aan gegevens, toevoegen
												$uboAddress = new \app\hig\MAddress();
												foreach ($properties as $property) {
													if (!isset($uboParams[$property])) { continue; }
													if (property_exists($uboAddress, $property)) {
														$uboAddress->$property = $uboParams[$property];
													}
												}
												$uboAddress->add(false);
												
												// toevoegen change voor contactpersoon NIET hoofdrelatie
												$vars = $uboAddress->getDBvars();
												array_unshift($vars,"id");
												$changeManager->setModel($uboAddress);
												$changeManager->setRelation($contactPersonRelation);
												$changeManager->addChange('add', $vars);
												
												$uboRelationAddress = new \app\hig\MRelationAddress();
												$uboRelationAddress->relationId = $contactPersonRelation->id;
												$uboRelationAddress->addressId = $uboAddress->id;
												$uboRelationAddress->isPrimary = 1;
												$uboRelationAddress->description = 'visiting';
												$uboRelationAddress->add(false);
												
												$vars = $uboRelationAddress->getDBvars();
												array_unshift($vars,"id");
												$changeManager->setModel($uboRelationAddress);
												$changeManager->setRelation($contactPersonRelation);
												$changeManager->addChange('add', $vars);
											}
										}
									}
								}

								if (!empty($uboParams['idFile']) && isset($uboParams['idFile']['path'])) {
									$registrationUbos[$registrationUbo->id] = $uboParams['idFile']['path'];
								}
							}
							break;
					}
				}
			}

			m::app()->db->commitTransaction();
		} catch (\Exception $e) {
			m::app()->db->rollbackTransaction();
			$this->result->success = false;
			$this->writeExceptionToLog('Er ging iets mis bij het opslaan van de gegevens voor deze inschrijving', $e);
		}

		// wanneer verwerken gegevens succesvol is verlopen, uitvoeren versturen e-mails en aanvullende acties

		if ($this->result->success) {
			// versturen onderteken e-mails naar alle contactpersonen
			try {
				$registration->sendEmailRegistrationSignInvitation();
			} catch (\Exception $e) {
				$this->writeExceptionToLog('Er ging iets mis bij het uitsturen van de uitnodiging de inschrijving te ondertekenen', $e);
			}

			// versturen e-mail naar account manager indien deze inschrijving heeft gedaan voor relatie
			if (m::app()->user && m::app()->user->isAccountManager) {
				try {
					$registration->sendEmailRegistrationForAccountManager();
				} catch (\Exception $e) {
					$this->writeExceptionToLog('Er ging iets mis bij het uitsturen van "wacht op ondertekening" voor de accountmanager', $e);
				}
			}
			
			if (!empty($registrationRelation) && !empty($cocFile)) {
				$registrationRelation->processTmpCocFile($cocFile);
			}
			if (!empty($registrationContactPersons)) {
				foreach ($registrationContactPersons as $registrationContactPersonId => $idFile) {
					$registrationContactPerson = new \app\hig\MRegistrationContactPerson($registrationContactPersonId);
					$registrationContactPerson->processTmpIdFile($idFile);
				}
			}
			if (!empty($registrationUbos)) {
				foreach ($registrationUbos as $registrationUboId => $idFile) {
					$registrationUbo = new \app\hig\MRegistrationUbo($registrationUboId);
					$registrationUbo->processTmpIdFile($idFile);
				}
			}

			// opslaan registrationId in sessie om bij complete pagina informatie over inschrijving te kunnen tonen?
			$_SESSION['registrationId'] = $registration->id;
			
			$this->resetSession();
		}
	}
	private function processDeclaration() {
		$this->result->success = true;
		$this->result->message = "";

		// uitvoeren acties voor verwerken ondertekening inschrijving
		try {
			m::app()->db->startTransaction();
			$token = h::getV('token', 'any', null, 'GET', true);
			
			// opslaan relationId
			$relationId = !empty(m::app()->user) && !empty(m::app()->user->getSelectedRelation()) ? m::app()->user->getSelectedRelation()->id : null;
			
			/** @var \app\hig\MRegistration $registration */
			$registration = $this->registrationService->getRegistrationBySignToken($token);
			$contactPerson = $this->registrationService->getContactPersonForRegistrationByToken($registration, $token);
			if (!empty($contactPerson) && !empty($registration)) {
				$declaration = h::getV('Declaration', 'array', [], 'POST', false);
				
				$contactPerson->acceptDeclaration = isset($declaration['acceptDeclaration']) ? true : false; // false zou hier niet moeten kunnen voorkomen
				$contactPerson->comments = isset($declaration['comments']) ? trim($declaration['comments']) : "";
				$contactPerson->dateTimeSigned = date('Y-m-d H:i:s');
				$contactPerson->update();
			}
			
			// controleren of inschrijving door iedereen is ondertekend
			$signedByAllContactPersons = true;
			foreach ($registration->registrationCard->contactPersons as $registrationContactPerson) {
				if (empty($registrationContactPerson->dateTimeSigned)) {
					$signedByAllContactPersons = false;
				}
			}
			m::app()->db->commitTransaction();
		} catch (\Exception $e) {
			m::app()->db->rollbackTransaction();
			$this->result->success = false;
			$this->writeToLog('Er ging iets mis bij het ondertekenen van de inschrijving.', $_SESSION, $e);
		}

		if ($this->result->success) {
			// versturen e-mail dat er is ondertekend naar contactpersoon, alleen in het geval er meer dan 1 contactpersoon is voor inschrijving
			if (!empty($contactPerson) && !empty($registration) && !empty($registration->registrationCard) && count($registration->registrationCard->contactPersons) > 1) {
				try {
					$contactPerson->sendEmailRegistrationSigned();
				} catch (\Exception $e) {
					$this->writeExceptionToLog('Er ging iets mis bij het uitsturen van de ondertekenbevestiging voor contactpersoon ' . $contactPerson->getName(), $e);
				}
			}

			// indien inschrijving is ondertekend door alle contactpersonen, uitsturen kopie inschrijving en inschrijfbevestiging
			// ook maken we hier de ubo verklaring op indien het gaat om een rechtspersoon
			if ($signedByAllContactPersons) {
				$registration->status = !empty($registration->getRelation()) ? 'waiting-for-review' : 'assign-rid';
				$registration->update();

				try {
					if (!empty($registration->registrationCard) && !empty($registration->registrationCard->relation) && $registration->registrationCard->relation->type == 'organization') {
						$hasUbo = false;
						if (!empty($registration->registrationCard->contactPersons))
						{
							foreach ($registration->registrationCard->contactPersons as $contactPerson)
							{
								if ($contactPerson->isUbo || $contactPerson->isPseudoUbo)
								{
									$hasUbo = true;
									break;
								}
							}
						}
						elseif (!empty($registration->registrationCard->ubos)) {
							$hasUbo = true;
						}

						if ($hasUbo)
						{
							$registration->generateUboDeclarationFile();
						}
					}
					$registration->sendEmailRegistrationComplete();
				} catch (\Exception $e) {
					$this->writeExceptionToLog('Er ging iets mis bij het uitsturen van de ontvangstbevestiging voor deze inschrijving', $e);
				}
			}

			$this->resetSession();
		}
	}
	
	private function determineFund() {
		$slug = h::getP(0, 'any', null, true);
		// controleren of slug bestaat
		$fundRegistrationSlug = $this->fundService->getFundRegistrationSlugBySlug($slug);
		if (empty($fundRegistrationSlug)) { throw new \Exception("No fund was found for this slug"); }
		// model opslaan in sessie om later te gebruiken
		$_SESSION['fundRegistrationSlug'] = $fundRegistrationSlug;
		// fund ophalen
		$fund = $fundRegistrationSlug->fund;
		// controleren of er moet worden gekeken naar start en eind datum inschrijving.
		if (empty($fund)) { throw new \Exception("No fund was found for this slug"); }

		return $fund;
	}
	private function getExistingRegistration() {
		// controleren of user ingelogde user bestaat
		$existingRegistration = null;
		if (!empty(m::app()->user)) {
			// ophalen geselecteerde relatie omgeving
			$selectedRelation = m::app()->user->getSelectedRelation();
			if (!empty($selectedRelation)) {
				// bepalen of er al een inschrijving bestaat voor deze relatie en fonds
				$fund = $this->determineFund();
				$existingRegistration = $this->registrationService->getExistingRegistrationForRelation($selectedRelation, $fund);
			}
		}
		
		return $existingRegistration;
	}

	private function processInitials($initials) {
		// eventueel al toegevoegde . weghalen
		$initials = str_replace(".", "", $initials);
		$initialsArr = str_split($initials, 1);
		return implode('.', $initialsArr) . ".";
	}
	public function getResult() {
		return $this->result;
	}
	private function writeExceptionToLog($message, \Exception $exception) {
		$lines = [
			date('Y-m-d H:i:s') . ": " . $message . PHP_EOL,
			date('Y-m-d H:i:s') . ": " . $exception->getMessage() . PHP_EOL,
			date('Y-m-d H:i:s') . ": " . $exception->getTraceAsString() . PHP_EOL,
			date('Y-m-d H:i:s') . ": " . var_export($_SESSION, true) . PHP_EOL,
		];

		foreach ($lines as $line) {
			file_put_contents($this->logFile, $line, FILE_APPEND);
		}
	}
	private function getActiveAccessibleEnvironmentRelations() {
		$accessibleEnvironmentRelation = [];
		if (!empty(m::app()->user)) {
			$fund = $this->determineFund();

			$environmentRelations = m::app()->user->getAccessibleEnvironmentRelations();
			foreach ($environmentRelations as $environmentRelation) {
				$existingRegistration = $this->registrationService->getExistingRegistrationForRelation($environmentRelation, $fund);
				if (empty($existingRegistration)) {
					$accessibleEnvironmentRelation[$environmentRelation->id] = $environmentRelation;
				}
			}
		}

		return $accessibleEnvironmentRelation;
	}
	private function setSessionParams() {
		$fundRegistrationSlug = $_SESSION['fundRegistrationSlug'];
		$currentTime = time();

		$intermediary = $fundRegistrationSlug->intermediary;
		if (!empty($intermediary)) {
			// opslaan van intermediair in sessie
			$_SESSION['RegistrationIntermediaryRelationId'] = $intermediary->id;
		}
		// opslaan emissie type en emissie waarde indien deze bestaat bij slug
		if (!empty($fundRegistrationSlug->emissionType)) {
			$_SESSION['RegistrationEmissionType'] = $fundRegistrationSlug->emissionType;
		}
		if (!empty($fundRegistrationSlug->emissionValue)) {
			$_SESSION['RegistrationEmissionValue'] = $fundRegistrationSlug->emissionValue;
		}
		if (!empty($fundRegistrationSlug->utmCode)) {
			$_SESSION['RegistrationUtmCode'] = $fundRegistrationSlug->utmCode;
		}
	}
	private function getContentForAccountManager() {
		$content = m::app()->getCmsPartial('registration-thank_you-message-account_manager')->content;

		// controleren of inschrijving bestaat
		$registration = !empty($_SESSION['registrationId']) ? new \app\hig\MRegistration($_SESSION['registrationId']) : null;
		if (!empty($registration)) {
			$registrationCard = $registration->registrationCard;
			$contentParams = [
				'{fund}' => $registration->fund->name,
				'{relation}' => $registrationCard->relation->name
			];

			// vervangen merge velden
			$content = str_replace(array_keys($contentParams), array_values($contentParams), $content);
		}

		return $content;
	}
}