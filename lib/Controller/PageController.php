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
use OCP\IUser;
use OCA\User_LDAP\Group_Proxy;

class PageController extends Controller
{
	private $db;
	private IUser $user;
	/** @var Group_Proxy */
	private $groupBackend;

	public function __construct(IRequest $request, IGroupManager $groupmanager, IUserSession $session, Group_Proxy $groupBackend)
	{
		parent::__construct(Application::APP_ID, $request);
		$this->groupmanager = $groupmanager;
		$this->session = $session;
		$this->groupBackend = $groupBackend;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */

	public function getServerGroups()
	{
		return new DataResponse($this->groupBackend->getGroups());
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */

	public function getUserInfo()
	{
		$user = $this->session->getUser();
		$userinfo = [$user->getDisplayName(), $this->groupmanager->getUserGroupIds($user)];
		return new DataResponse($userinfo);
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function index()
	{
		return new TemplateResponse(Application::APP_ID, 'index');  // templates/index.php
	}
}
