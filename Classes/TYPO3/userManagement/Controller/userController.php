<?php
namespace TYPO3\userManagement\Controller;


class userController extends \TYPO3\Flow\Mvc\Controller\ActionController {

	protected $userFunctions;
	protected $accountRepository;
	protected $partyRepository;

	
	protected function initializeAction() {
		parent::initializeAction();
		if ($this->arguments->hasArgument('profile')) {
			$propertyMappingConfigurationForAccount = $this->arguments->getArgument('profile')->getPropertyMappingConfiguration();
			$propertyMappingConfigurationForAccountParty = $propertyMappingConfigurationForAccount->forProperty('party');
			$propertyMappingConfigurationForAccountPartyName = $propertyMappingConfigurationForAccount->forProperty('party.name');

			foreach (array($propertyMappingConfigurationForAccountParty, $propertyMappingConfigurationForAccountPartyName) as $propertyMappingConfiguration) {
				$propertyMappingConfiguration->setTypeConverterOption(
					'TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter',
					\TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter::CONFIGURATION_MODIFICATION_ALLOWED,
					TRUE
				);
			}
		}
	}

	/*
	Get user details
	 */
	public function profileAction() {
		$this->view->assign('profile', $this->userFunctions->getUser());
	}

	/*
	Update user details action
	 */
	public function updateAction(Account $profile) {

		$this->accountRepository->update($profile);
		$this->partyRepository->update($profile->getParty());

		$this->addFlashMessage('User Details Updated.');

		$referrer = $this->request->getReferringRequest();
		$this->redirect($referrer->getControllerActionName(), $referrer->getControllerName());
	}

	
}

?>