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
use OCP\GroupInterface;

class PageController extends Controller
{
	private $db;
	private IUser $user;
	/** @var GroupInterface */
	private $group;

	public function __construct(IRequest $request, IGroupManager $groupmanager, IUserSession $session)
	{
		parent::__construct(Application::APP_ID, $request);
		$this->groupmanager = $groupmanager;
		$this->session = $session;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */

	public static function getServerGroups()
	{
		$group = new GroupInterface;
		return new DataResponse($group->getGroups());
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
