<?php

namespace OCA\IntranetAgglo\Controller;

use OCA\IntranetAgglo\Controller\NewsController;
use OCP\IRequest;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Controller;
use OCA\IntranetAgglo\AppInfo\Application;
use OCP\AppFramework\Http\DataResponse;
use OCP\IGroupManager;
use OCP\IUserSession;
use OCP\IUser;


class PageController extends Controller
{
	private $db;
	private IUser $user;

	public function __construct(IRequest $request, IGroupManager $groupmanager, IUserSession $session, NewsController $newscontroller)
	{
		parent::__construct(Application::APP_ID, $request);
		$this->groupmanager = $groupmanager;
		$this->session = $session;
		$this->newscontroller = $newscontroller;
	}

	/**
	 * @NoAdminRequired
	 */

	public function searchGroups(string $search): DataResponse
	{
		$groups = $this->groupmanager->search($search, 25);
		$gid = [];
		foreach ($groups as $group) {
			$gid[] = $group->getGID();
		}
		return new DataResponse($gid);
	}

	/**
	 * @NoAdminRequired
	 */

	public function getUserInfo()
	{
		$user = $this->session->getUser();
		$userinfo = [$user->getDisplayName(), $this->groupmanager->getUserGroupIds($user)];
		return new DataResponse($userinfo);
	}


	/**
	 * @NoAdminRequired
	 */

	public function getLocation()
	{
		$isOnSite = false;

		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		if (str_starts_with($ip, '10.'))
			$isOnSite = true;

		return $isOnSite;
	}


	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function index()
	{
		$this->newscontroller->removeNotifications();
		return new TemplateResponse(Application::APP_ID, 'index');  // templates/index.php
	}
}
