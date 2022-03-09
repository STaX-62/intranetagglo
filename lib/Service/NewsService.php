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

	public function findByGroups(int $firstresult, array $groups, string $search): array
	{
		return $this->mapper->findByGroups($firstresult, $groups, $search);
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

	public function findAll($firstresult, $search): array
	{
		return $this->mapper->findAll($firstresult, $search);
	}

	public function create($author, $title, $subtitle, $text, $photo, $category, $groups, $time, $visible)
	{
		$news = new News();
		$news->setAuthor($author);
		$news->setTitle($title);
		$news->setSubtitle($subtitle);
		$news->setText($text);
		$news->setPhoto($photo);
		$news->setCategory($category);
		$news->setGroups($groups);
		$news->setTime($time);
		$news->setVisible($visible);
		return $this->mapper->insert($news);
	}

	public function update($id, $author, $title, $subtitle, $text, $photo, $category, $groups, $time, $visible)
	{
		try {
			$news = $this->mapper->find($id);
			$news->setAuthor($author);
			$news->setTitle($title);
			$news->setSubtitle($subtitle);
			$news->setText($text);
			$news->setPhoto($photo);
			$news->setCategory($category);
			$news->setGroups($groups);
			$news->setTime($time);
			$news->setVisible($visible);
			return $this->mapper->update($news);
		} catch (Exception $e) {
			$this->handleException($e);
		}
	}

	public function publication($id, $visible)
	{
		try {
			$news = $this->mapper->find($id);
			$news->setVisible($visible);
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
