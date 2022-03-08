<?php

namespace OCA\IntranetAgglo\Controller;

use OCA\IntranetAgglo\Controller\ApiController;
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



class PageController extends Controller
{
	private $db;
	private IUser $user;

	public function __construct(IRequest $request, IGroupManager $groupmanager, IUserSession $session, ApiController $api)
	{
		parent::__construct(Application::APP_ID, $request);
		$this->groupmanager = $groupmanager;
		$this->session = $session;
		$this->api = $api;
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
	 * @NoCSRFRequired
	 */
	public function index(int $news = 0)
	{
		if ($news) {
			$this->manager->markNotificationRead($news);
		}

		return new TemplateResponse(Application::APP_ID, 'index');  // templates/index.php
	}
}
