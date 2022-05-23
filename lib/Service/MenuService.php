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

	public function findByGroups(array $groups): array
	{
		return $this->mapper->findByGroups($groups);
	}

	public function findByPosition(int $section, int $menu, int $submenu): array
	{
		return $this->mapper->findByPosition($section, $menu, $submenu);
	}

	public function NewIdSection()
	{
		return $this->mapper->NewIdSection();
	}
	public function NewIdMenu($section)
	{
		return $this->mapper->NewIdMenu($section);
	}
	public function NewIdSubmenu($section, $menu)
	{
		return $this->mapper->NewIdSubmenu($section, $menu);
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

	public function create($title, $icon,  $link, $groups, $sectionid, $menuid, $submenuid)
	{
		$menu = new Menu();
		$menu->setTitle($title);
		$menu->setIcon($icon);
		$menu->setLink($link);
		$menu->setGroups($groups);
		$menu->setSectionid($sectionid);
		$menu->setMenuid($menuid);
		$menu->setSubmenuid($submenuid);
		return $this->mapper->insert($menu);
	}

	public function update($id, $title, $icon, $link, $groups)
	{
		try {
			$menu = $this->mapper->find($id);
			$menu->setTitle($title);
			$menu->setIcon($icon);
			$menu->setLink($link);
			$menu->setGroups($groups);
			return $this->mapper->update($menu);
		} catch (Exception $e) {
			$this->handleException($e);
		}
	}

	public function updateOrder($id, $sectionid, $menuid, $submenuid)
	{
		try {
			$menu = $this->mapper->find($id);
			$menu->setSectionid($sectionid);
			if ($menuid != 0) {
				$menu->setMenuid($menuid);
			}
			if ($submenuid != 0) {
				$menu->setSubmenuid($submenuid);
			}
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
