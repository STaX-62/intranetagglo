<?php

namespace OCA\SimpleApp\Controller;

use OCP\IRequest;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Controller;
use OCA\SimpleApp\AppInfo\Application;
use OCP\Util;

class PageController extends Controller
{
	private $db;

	public function __construct(IRequest $request)
	{
		parent::__construct(Application::APP_ID, $request);
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
