<?php

namespace OCA\UserDeletion\Controller;

use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\JSONResponse;
use OCP\IL10N;
use OCP\IRequest;
use OCP\IUserManager;
use OCP\IUserSession;

class PersonalController extends Controller {

	/** @var IUserSession $userSession */
	private $userSession;

	/** @var IUserManager $userManager */
	private $userManager;

	/** @var IL10N $l10n */
	private $l10n;

	public function __construct($appName, IRequest $request, IUserSession $userSession, IUserManager $userManager, IL10N $l10n) {
		parent::__construct($appName, $request);
		$this->userSession = $userSession;
		$this->userManager = $userManager;
		$this->l10n = $l10n;
	}

	/**
	 * @NoAdminRequired
	 * @param string $id
	 * @return JSONResponse
	 */
	public function delete($id) {
		$userId = $this->userSession->getUser()->getUID();
		$user = $this->userManager->get($id);

		if ($userId === $id && $user) {
			if ($user->delete()) {
				$this->userSession->logout();
				return new JSONResponse([
					'status' => 'success',
					'message' => (string)$this->l10n->t("Account deleted"),
				]);
			}
			return new JSONResponse([
				'status' => 'error',
				'message' => (string)$this->l10n->t("Error while trying to delete the user"),
			]);
		}
		return new JSONResponse([
			'status' => 'error',
			'message' => (string)$this->l10n->t("Can't delete an account that't not yours"),
		]);
	}
}