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

	public function findAll(): array
	{
		return $this->mapper->findAll();
	}

	public function findByGroups(array $groups)
	{
		$menus = $this->mapper->findAll();
		$hasNeededGroups = true;
		$sortedarray = [];
		$test = [];

		if (count($menus) != 0) {
			for ($idxmenus = 0; $idxmenus < count($menus); $idxmenus++) {
				$menugroups = explode(";", $menus[$idxmenus]->getGroups());
				if (count($menugroups) != 0) {
					$hasNeededGroups = true;
					for ($idxgroups = 0; $idxgroups < count($menugroups); $idxgroups++) {
						if (in_array($menugroups[$idxgroups], $groups) && $hasNeededGroups) {
							$hasNeededGroups = true;
						} else {
							$hasNeededGroups = false;
						}
					}
					$test[] = $hasNeededGroups;
					if ($hasNeededGroups) {
						$sortedarray[] = $menus[$idxmenus];
					}
				} else {
					$test[] = $menus[$idxmenus];
					$sortedarray[] = $menus[$idxmenus];
				}
			}
		}
		return $test;
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
