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

    public function getLastPinned()
    {
        /* @var $qb IQueryBuilder */
        $qb = $this->db->getQueryBuilder();

        $qb->select('id')
            ->from($this->getTableName(), 'q')
            ->where('q.pinned = 1');

        $cursor = $qb->execute();
        $row = $cursor->fetchAll(\PDO::FETCH_COLUMN);
        return $row;
    }

    public function categoryArray()
    {
        /* @var $qb IQueryBuilder */
        $qb = $this->db->getQueryBuilder();

        $qb->selectDistinct('category')
            ->from($this->getTableName());

        $cursor = $qb->execute();
        $row = $cursor->fetchAll(\PDO::FETCH_COLUMN);
        return $row;
    }


    /**
     * @return array
     */
    public function findAll($firstresult, string $search, string $category): array
    {
        /* @var $qb IQueryBuilder */
        $qb = $this->db->getQueryBuilder();
        $qb->select('*')
            ->from($this->getTableName(), 'q')
            ->where('q.title LIKE :search')
            ->orWhere('q.subtitle LIKE :search')
            ->orWhere('q.text LIKE :search')
            ->orWhere('q.category = :category')
            ->setParameter('search', '%' . $search . '%')
            ->setParameter('category', $category)
            ->addOrderBy('q.pinned', 'DESC')
            ->addOrderBy('q.time', 'DESC')
            ->setFirstResult($firstresult)
            ->setMaxResults(3);


        $qb2 = $this->db->getQueryBuilder();

        $qb2->selectAlias($qb2->createFunction('COUNT(*)'), 'count')
            ->from($this->getTableName(), 'q')
            ->where('q.title LIKE :search')
            ->orWhere('q.subtitle LIKE :search')
            ->orWhere('q.text LIKE :search')
            ->orWhere('q.category = :category')
            ->setParameter('search', '%' . $search . '%')
            ->setParameter('category', $category);

        $cursor = $qb2->execute();
        $row = $cursor->fetch();
        $cursor->closeCursor();

        return [$this->findEntities($qb), $row['count']];
    }

    /**
     * @return array
     */
    public function findByGroups(int $firstresult, array $groupsArray, string $search, string $category): array
    {
        $groups = '%';
        foreach ($groupsArray as $group) {
            $groups .= $group . '%';
        }

        /* @var $qb IQueryBuilder */
        $qb = $this->db->getQueryBuilder();
        $qb->select('*')
            ->from($this->getTableName(), 'q')
            ->where('q.title LIKE :search')
            ->orWhere('q.subtitle LIKE :search')
            ->orWhere('q.text LIKE :search')
            ->orWhere('q.category = :category')
            ->andWhere("q.groups = ''")
            ->orWhere("q.groups LIKE :groups")
            ->andWhere("q.visible = '1'")
            ->setParameter('groups', $groups)
            ->setParameter('search', '%' . $search . '%')
            ->setParameter('category', $category)
            ->addOrderBy('q.pinned', 'DESC')
            ->addOrderBy('q.time', 'DESC')
            ->setFirstResult($firstresult)
            ->setMaxResults(3);


        $qb2 = $this->db->getQueryBuilder();

        $qb2->selectAlias($qb2->createFunction('COUNT(*)'), 'count')
            ->from($this->getTableName(), 'q')
            ->where('q.title LIKE :search')
            ->orWhere('q.subtitle LIKE :search')
            ->orWhere('q.text LIKE :search')
            ->orWhere('q.category = :category')
            ->andWhere("q.groups = ''")
            ->orWhere("q.groups LIKE :groups")
            ->andWhere("q.visible = '1'")
            ->setParameter('groups', $groups)
            ->setParameter('search', '%' . $search . '%')
            ->setParameter('category', $category);

        $cursor = $qb2->execute();
        $row = $cursor->fetch();
        $cursor->closeCursor();

        return [$this->findEntities($qb), $row['count']];
    }


    /**
     * @return array
     */
    public function dashboard(array $groupsArray): array
    {
        $groups = '%';
        foreach ($groupsArray as $group) {
            $groups .= $group . '%';
        }
        /* @var $qb IQueryBuilder */
        $qb = $this->db->getQueryBuilder();
        $qb->select('*')
            ->from($this->getTableName(), 'q')
            ->andWhere("q.groups = ''")
            ->orWhere("q.groups LIKE :groups")
            ->andWhere("q.visible = '1'")
            ->setParameter('groups', $groups)
            ->addOrderBy('q.pinned', 'DESC')
            ->addOrderBy('q.time', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(7);

        return $this->findEntities($qb);
    }
}
