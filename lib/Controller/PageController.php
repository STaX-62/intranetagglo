<?php

namespace OCA\IntranetAgglo\Controller;

use OCP\IRequest;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Controller;
use OCA\IntranetAgglo\AppInfo\Application;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Http\JSONResponse;
use OCP\Util;
use OCP\IGroupManager;
use OCP\IUserSession;

class PageController extends Controller
{
	private $db;

	public function __construct(IRequest $request,IGroupManager $groupmanager,IUserSession $session)
	{
		parent::__construct(Application::APP_ID,$groupmanager,$session, $request, $user);
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function index()
	{
		return new TemplateResponse(Application::APP_ID, 'index');  // templates/index.php
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */

	public function getUserInfo()
	{
		$user = $this->userSession->getUser();
		$userinfo = [$this->user->getDisplayName(),$this->groupmanager->getUserGroups($user)];
		return new DataResponse($userinfo);
	}
}
