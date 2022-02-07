<?php

namespace OCA\IntranetAgglo\Db;

use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\Entity;
use OCP\AppFramework\Db\QBMapper;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;

class NewsMapper extends QBMapper
{

    public function __construct(IDBConnection $db)
    {
        parent::__construct($db, 'intranetagglonews', News::class);
    }

    /**
     * @param int $id
     * @return Entity|News
     * @throws \OCP\AppFramework\Db\MultipleObjectsReturnedException
     * @throws DoesNotExistException
     */
    public function find(int $id): News
    {
        /* @var $qb IQueryBuilder */
        $qb = $this->db->getQueryBuilder();

        $qb->select('*')
            ->from($this->getTableName())
            ->where(
                $qb->expr()->eq('id', $qb->createNamedParameter($id))
            );

        return $this->findEntity($qb);
    }

    /**
     * @return array
     */
    public function findAll($firstresult): array
    {
        /* @var $qb IQueryBuilder */
        $qb = $this->db->getQueryBuilder();
        $qb->select('*')
            ->from($this->getTableName())
            ->setFirstResult($firstresult)
            ->setMaxResults(3);

        return $this->findEntities($qb);
    }

    /**
     * @return array
     */
    public function getNews(int $firstresult, array $groupsArray): array
    {
        $groups = '%';
        foreach ($groupsArray as $group) {
            $groups += $group . '%';
        }

        /* @var $qb IQueryBuilder */
        $qb = $this->db->getQueryBuilder();
        $qb->select('*')
            ->from($this->getTableName(), 'q')
            ->setFirstResult($firstresult)
            ->setMaxResults(3)
            ->where("q.groups = ''")
            ->orWhere("q.groups LIKE :groups")
            ->setParameter('groups', $groups);

        return $this->findEntities($qb);
    }

    /**
     * @return array
     */
    public function getNumberOfNews(): int
    {
        /* @var $qb IQueryBuilder */
        $qb = $this->db->getQueryBuilder();
        $qb->select($qb->createFunction('COUNT(*)'))
            ->from($this->getTableName(), 'i')
            ->where('i.visible = 1');

        return $this->findEntities($qb);
    }

    /**
     * @return array
     */
    public function getNewsBySearch(int $firstresult, array $groupsArray, string $search): array
    {
        $groups = '%';
        foreach ($groupsArray as $group) {
            $groups += $group . '%';
        }

        /* @var $qb IQueryBuilder */
        $qb = $this->db->getQueryBuilder();
        $qb->select('*')
            ->from($this->getTableName(), 'q')
            ->where('q.title LIKE :word')
            ->orWhere('q.subtitle LIKE :word')
            ->orWhere('q.text LIKE :word')
            ->andWhere("q.groups = ''")
            ->orWhere("q.groups LIKE :groups")
            ->setParameter('groups', $groups)
            ->setParameter('word', '%' . $search . '%')
            ->setFirstResult($firstresult)
            ->setMaxResults(3);

        return $this->findEntities($qb);
    }
}
