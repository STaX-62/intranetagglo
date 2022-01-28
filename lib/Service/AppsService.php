<?php

namespace OCA\SimpleApp\Service;

use Exception;

use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\MultipleObjectsReturnedException;

use OCA\SimpleApp\Db\Apps;
use OCA\SimpleApp\Db\AppsMapper;

class AppsService
{
	/** @var AppsMapper */
	private $mapper;

	public function __construct(AppsMapper $mapper)
	{
		$this->mapper = $mapper;
	}

	public function findAll(): array
	{
		return $this->mapper->findAll();
	}

	private function handleException(Exception $e): void
	{
		if (
			$e instanceof DoesNotExistException ||
			$e instanceof MultipleObjectsReturnedException
		) {
			throw new appsNotFound($e->getMessage());
		} else {
			throw $e;
		}
	}

	public function find($id)
	{
		try {
			return $this->mapper->find($id);
		} catch (Exception $e) {
			$this->handleException($e);
		}
	}

	public function create($title, $icon,  $link, $groups)
	{
		$apps = new Apps();
		$apps->setTitle($title);
		$apps->setIcon($icon);
		$apps->setLink($link);
		$apps->setGroups($groups);
		return $this->mapper->insert($apps);
	}

	public function update($id, $title, $icon, $link, $groups)
	{
		try {
			$apps = $this->mapper->find($id);
			$apps->setTitle($title);
			$apps->setIcon($icon);
			$apps->setLink($link);
			$apps->setGroups($groups);
			return $this->mapper->update($apps);
		} catch (Exception $e) {
			$this->handleException($e);
		}
	}
	/**
	 * @NoAdminRequired
	 */
	public function delete($id)
	{
		try {
			$apps = $this->mapper->find($id);
			$this->mapper->delete($apps);
			return $apps;
		} catch (Exception $e) {
			$this->handleException($e);
		}
	}
}
