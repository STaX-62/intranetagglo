<?php

namespace OCA\IntranetAgglo\Service;

use Exception;

use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\MultipleObjectsReturnedException;

use OCA\IntranetAgglo\Db\News;
use OCA\IntranetAgglo\Db\NewsMapper;

class NewsService
{
	/** @var NewsMapper */
	private $mapper;

	public function __construct(NewsMapper $mapper)
	{
		$this->mapper = $mapper;
	}



	public function dashboard(array $groups): array
	{
		return $this->mapper->dashboard($groups);
	}

	private function handleException(Exception $e): void
	{
		if (
			$e instanceof DoesNotExistException ||
			$e instanceof MultipleObjectsReturnedException
		) {
			throw $e;
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

	public function getLastPinned()
	{
		return $this->mapper->getLastPinned();
	}

	public function findAll($firstresult, $limit, $search, $categories, $dateFilter): array
	{
		$search = trim($search, " \n\r\t\v");

		if (str_starts_with($search, '#') && is_numeric(substr($search, 1))) {
			$qb = $this->mapper->findAll($firstresult, $limit, '', '', substr($search, 1), $dateFilter['start'], $dateFilter['end']);
		} else {
			$qb = $this->mapper->findAll($firstresult, $limit, $search, $categories, '', $dateFilter['start'], $dateFilter['end']);
		}

		return $qb;
	}

	public function findAlerts($search): array
	{
		$search = trim($search, " \n\r\t\v");
		$qb = $this->mapper->findAlerts($search);

		return $qb;
	}

	public function findAlertsByGroups($search, $groups): array
	{
		$search = trim($search, " \n\r\t\v");
		$qb = $this->mapper->findAlertsByGroup($search, $groups);

		return $qb;
	}

	public function findByGroups(int $firstresult, $limit, array $groups, string $search, string $categories, $dateFilter): array
	{
		$search = trim($search, " \n\r\t\v");

		if (str_starts_with($search, '#') && is_numeric(substr($search, 1))) {
			$qb = $this->mapper->findByGroups($firstresult, $limit, $groups, '', '', substr($search, 1), $dateFilter['start'], $dateFilter['end']);
		} else {
			$qb = $this->mapper->findByGroups($firstresult, $limit, $groups, $search, $categories, '', $dateFilter['start'], $dateFilter['end']);
		}

		return $qb;
	}

	public function create($author, $title, $subtitle, $text, $photo, $category, $groups, $link, $time, $expiration)
	{
		$news = new News();
		$news->setAuthor($author);
		$news->setTitle($title);
		$news->setSubtitle($subtitle);
		$news->setText($text);
		$news->setPhoto(implode(';', $photo));
		$news->setCategory($category);
		$news->setGroups($groups);
		$news->setLink($link);
		$news->setTime($time);
		$expiration = strtotime($expiration);
		if ($expiration != 0) {
			$news->setExpiration($expiration);
		} else {
			$news->setExpiration(0);
		}
		$news->setVisible(0);
		$news->setPinned(0);
		return $this->mapper->insert($news);
	}

	public function update($id, $title, $subtitle, $text, $photo, $category, $groups, $link, $expiration)
	{
		try {
			$news = $this->mapper->find($id);
			$news->setTitle($title);
			$news->setSubtitle($subtitle);
			$news->setText($text);
			$news->setPhoto(implode(';', $photo));
			$news->setCategory($category);
			$news->setGroups($groups);
			$news->setLink($link);
			if ($expiration != 0) {
				$news->setExpiration(strtotime($expiration));
			} else {
				$news->setExpiration(0);
			}
			return $this->mapper->update($news);
		} catch (Exception $e) {
			$this->handleException($e);
		}
	}

	public function publication($id, $visible, $time)
	{
		try {
			$news = $this->mapper->find($id);
			$news->setVisible($visible);
			$news->setTime($time);
			return $this->mapper->update($news);
		} catch (Exception $e) {
			$this->handleException($e);
		}
	}

	public function pinNewsById($id, $pinned)
	{
		try {
			$news = $this->mapper->find($id);
			$news->setPinned($pinned);
			return $this->mapper->update($news);
		} catch (Exception $e) {
			$this->handleException($e);
		}
	}

	public function category()
	{
		try {
			return $this->mapper->categoryArray();
		} catch (Exception $e) {
			$this->handleException($e);
		}
	}

	public function delete($id)
	{
		try {
			$news = $this->mapper->find($id);
			$this->mapper->delete($news);
			return $news;
		} catch (Exception $e) {
			$this->handleException($e);
		}
	}
}
