<?php
namespace TYPO3\userManagement\View;


class userFunctions {

	protected $accountRepository;
	protected $partyRepository;
	protected $accountFactory;

	/*
	 This function is for add new user
	 */
	public function addNewUser($username, $password, $repeatPassword, $fName, $lName, $userEmail, $roles, $authenticationProvider = 'DefaultProvider') {
		$profile = $this->accountRepository->findByAccountIdentifierAndAuthenticationProviderName($username, $authenticationProvider);
		if ($profile instanceof \TYPO3\Flow\Security\Account) {
		
			return FALSE;
		}

		$user = new \TYPO3\Party\Domain\Model\Person;
		$name = new \TYPO3\Party\Domain\Model\PersonName('', $fName, '', $lName, '', $username);
		$user->setName($name);
		
		$email = new \TYPO3\Party\Domain\Model\ElectronicAddress($userEmail);
		$type->setType($email);

		$this->partyRepository->add($user);

		$profile = $this->accountFactory->createAccountWithPassword($username, $password, $repeatPassword, explode(',', $roles), $authenticationProvider);
		$profile->setParty($user);
		$this->accountRepository->add($profile);

		return $profile;
	}

	/*
	This function is for delete a user
	 */
	public function deleteUser($identifier, $authenticationProvider = 'DefaultProvider') {
		$profile = $this->accountRepository->findByAccountIdentifierAndAuthenticationProviderName($identifier, $authenticationProvider);
		if ($profile instanceof \TYPO3\Flow\Security\Account) {
			$party = $profile->getParty();

			$this->partyRepository->remove($party);
			$this->accountRepository->remove($profile);

			return TRUE;
		} else {
			return FALSE;
		}
	}

	
}

?>