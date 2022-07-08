<?php

namespace OCA\IntranetAgglo\Controller;

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
	public function __construct(IRequest $request, IGroupManager $groupmanager, IUserSession $session)
	{
		parent::__construct(Application::APP_ID, $request);
		$this->groupmanager = $groupmanager;
		$this->session = $session;
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
		$userinfo = [$user->getDisplayName(), $this->groupmanager->getUserGroupIds($user), $user->getLastLogin()];
		return new DataResponse($userinfo);
	}

	/**
	 * @NoAdminRequired
	 */

	public function userIsAdmin()
	{
		$user = $this->session->getUser();
		return new DataResponse($this->groupmanager->isInGroup($user->getUID(), 'intranet-admin') || $this->groupmanager->isInGroup($user->getUID(), 'admin'));
	}



	/**
	 * @NoAdminRequired
	 */

	public function getLocation()
	{
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		return str_starts_with($ip, '192.') || str_starts_with($ip, '10.');
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
