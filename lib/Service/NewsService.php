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

	public function findAll($firstresult, $search, $categories, $dateFilter): array
	{
		$search = trim($search, " \n\r\t\v");

		if (str_starts_with($search, '#') && is_numeric(substr($search, 1))) {
			$qb = $this->mapper->findAll($firstresult, $search, '', substr($search, 1));
		} else {
			$qb = $this->mapper->findAll($firstresult, $search, $categories, '', $dateFilter['start'], $dateFilter['end']);
		}

		return $qb;
	}

	public function findByGroups(int $firstresult, array $groups, string $search, string $categories, $dateFilter): array
	{
		$search = trim($search, " \n\r\t\v");

		if (str_starts_with($search, '#') && is_numeric(substr($search, 1))) {
			$qb = $this->mapper->findByGroups($firstresult, $groups, $search, '', substr($search, 1));
		} else {
			$qb = $this->mapper->findByGroups($firstresult, $groups, $search, $categories, '', $dateFilter['start'], $dateFilter['end']);
		}

		return $qb;
	}

	public function create($author, $title, $subtitle, $text, $photo, $category, $groups, $link, $time, $visible, $pinned)
	{
		$news = new News();
		$news->setAuthor($author);
		$news->setTitle($title);
		$news->setSubtitle($subtitle);
		$news->setText($text);
		$news->setPhoto($photo);
		$news->setCategory($category);
		$news->setGroups($groups);
		$news->setLink($link);
		$news->setTime($time);
		$news->setVisible($visible);
		$news->setPinned($pinned);
		return $this->mapper->insert($news);
	}

	public function update($id, $title, $subtitle, $text, $photo, $category, $groups, $link)
	{
		try {
			$news = $this->mapper->find($id);
			$news->setTitle($title);
			$news->setSubtitle($subtitle);
			$news->setText($text);
			$news->setPhoto($photo);
			$news->setCategory($category);
			$news->setGroups($groups);
			$news->setLink($link);
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
