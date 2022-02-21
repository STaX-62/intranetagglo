<?php

namespace OCA\IntranetAgglo\Service;

use Exception;

use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\MultipleObjectsReturnedException;

use OCA\IntranetAgglo\Db\Menu;
use OCA\IntranetAgglo\Db\MenuMapper;

class MenuService
{
	/** @var MenuMapper */
	private $mapper;

	public function __construct(MenuMapper $mapper)
	{
		$this->mapper = $mapper;
	}

	public function findAll(string $search): array
	{
		return $this->mapper->findAll($search);
	}

	public function findByGroups(array $groups): array
	{
		return $this->mapper->findByGroups($groups);
	}

	private function handleException(Exception $e): void
	{
		if (
			$e instanceof DoesNotExistException ||
			$e instanceof MultipleObjectsReturnedException
		) {
			throw new menuNotFound($e->getMessage());
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

	public function create($title, $icon,  $link, $groups, $position)
	{
		$menu = new Menu();
		$menu->setTitle($title);
		$menu->setIcon($icon);
		$menu->setLink($link);
		$menu->setGroups($groups);
		$menu->setPosition($position);
		return $this->mapper->insert($menu);
	}

	public function update($id, $title, $icon, $link, $groups, $position)
	{
		try {
			$menu = $this->mapper->find($id);
			$menu->setTitle($title);
			$menu->setIcon($icon);
			$menu->setLink($link);
			$menu->setGroups($groups);
			$menu->setPosition($position);
			return $this->mapper->update($menu);
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
			$menu = $this->mapper->find($id);
			$this->mapper->delete($menu);
			return $menu;
		} catch (Exception $e) {
			$this->handleException($e);
		}
	}
}
