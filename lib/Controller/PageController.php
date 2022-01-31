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
use OCP\IUser;
use OCP\IGroupManager;
use OCP\IUserSession;

class PageController extends Controller
{
	private $db;

	public function __construct(IRequest $request,IUser $user,IGroupManager $groupmanager,IUserSession $session)
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
		$userinfo = [$this->user->getDisplayName(),$this->groupmanager->getUserGroups($this->session->getUser())];
		return new DataResponse($userinfo);
	}
}
