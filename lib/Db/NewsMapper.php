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
            ->where('category != ""')
            ->andWhere("q.expiration != 0")
            ->from($this->getTableName());

        $cursor = $qb->execute();
        $row = $cursor->fetchAll(\PDO::FETCH_COLUMN);
        return $row;
    }


    /**
     * @return array
     */
    public function findAll($firstresult, $limit, string $search, string $categories, string $searchid, string $startDate, string $endDate): array
    {
        $categoryArray = explode(';', $categories);

        $qb = $this->db->getQueryBuilder();
        $qb->select('time')
            ->from($this->getTableName(), 'q')
            ->where("q.expiration = 0")
            ->addOrderBy('q.time', 'ASC')
            ->setMaxResults(1);

        $cursor = $qb->execute();
        $time = $cursor->fetch();
        $cursor->closeCursor();

        $qb2 = $this->db->getQueryBuilder();
        $qb2->select('*')
            ->from($this->getTableName(), 'q')
            ->where("q.expiration = 0")
            ->andWhere('(LOWER(q.title) LIKE LOWER(:search) OR LOWER(q.subtitle) LIKE LOWER(:search) OR LOWER(q.text) LIKE LOWER(:search))')
            ->setParameter('search', '%' . $search . '%');
        if ($categories != '') {
            $qb2->andWhere('q.category IN (:categories)')
                ->setParameter('categories', implode(",", $categoryArray));
        }
        if ($startDate == "") {
            $qb2->andWhere('q.time >= :startdate')
                ->setParameter('startdate', $time['time']);
        } else {
            $qb2->andWhere('q.time >= :startdate')
                ->setParameter('startdate', strtotime($startDate));
        }

        if ($endDate == "") {
            $qb2->andWhere('q.time <= :enddate')
                ->setParameter('enddate', time());
        } else {
            $qb2->andWhere('q.time <= :enddate')
                ->setParameter('enddate', strtotime($endDate) + (60 * 60 * 24));
        }
        if ($searchid != '') {
            $qb2->andWhere('q.id = :searchid')
                ->setParameter('searchid', $searchid);
        }
        $qb2->addOrderBy('q.pinned', 'DESC')
            ->addOrderBy('q.time', 'DESC')
            ->setFirstResult($firstresult)
            ->setMaxResults($limit);


        $qb3 = $this->db->getQueryBuilder();

        $qb3->selectAlias($qb3->createFunction('COUNT(*)'), 'count')
            ->from($this->getTableName(), 'q')
            ->where("q.expiration = 0")
            ->andWhere('(LOWER(q.title) LIKE LOWER(:search) OR LOWER(q.subtitle) LIKE LOWER(:search) OR LOWER(q.text) LIKE LOWER(:search))')
            ->setParameter('search', '%' . $search . '%');

        if ($categories != '') {
            $qb3->andWhere('q.category IN (:categories)')
                ->setParameter('categories', implode(',', $categoryArray));
        }

        if ($searchid != '') {
            $qb3->orWhere('q.id = :searchid')
                ->setParameter('searchid', $searchid);
        }


        $cursor = $qb3->execute();
        $row = $cursor->fetch();
        $cursor->closeCursor();



        return [$this->findEntities($qb2), $row['count'], $time];
    }
    /**
     * @return array
     */
    public function findAlerts(string $search): array
    {
        $qb = $this->db->getQueryBuilder();
        $qb->select('*')
            ->from($this->getTableName(), 'q')
            ->where("q.expiration != 0")
            ->andWhere("q.expiration >= :today")
            ->andWhere('(LOWER(q.title) LIKE LOWER(:search) OR LOWER(q.subtitle) LIKE LOWER(:search) OR LOWER(q.text) LIKE LOWER(:search))')
            ->setParameter('search', '%' . $search . '%')
            ->setParameter('today', strtotime('today'));

        $qb->addOrderBy('q.time', 'DESC');
        return $this->findEntities($qb);
    }

    /**
     * @return array
     */
    public function findByGroups(int $firstresult, $limit, array $groupsArray, string $search, string $categories, string $searchid, string $startDate, string $endDate): array
    {
        $groups = '';
        foreach ($groupsArray as $group) {
            $groups .= ' OR q.groups LIKE "%' . $group . '%" ';
        }
        $categoryArray = explode(';', $categories);

        $qb = $this->db->getQueryBuilder();
        $qb->select('time')
            ->from($this->getTableName(), 'q')
            ->where("q.expiration = 0")
            ->addOrderBy('q.time', 'ASC')
            ->setMaxResults(1);

        $cursor = $qb->execute();
        $time = $cursor->fetch();
        $cursor->closeCursor();

        $qb2 = $this->db->getQueryBuilder();
        $qb2->select('*')
            ->from($this->getTableName(), 'q')
            ->where("q.expiration = 0")
            ->andWhere('(LOWER(q.title) LIKE LOWER(:search) OR LOWER(q.subtitle) LIKE LOWER(:search) OR LOWER(q.text) LIKE LOWER(:search))')
            ->setParameter('search', '%' . $search . '%');
        if ($categories != '') {
            $qb2->andWhere('q.category IN (:categories)')
                ->setParameter('categories', implode(",", $categoryArray));
        }
        if ($startDate == "") {
            $qb2->andWhere('q.time >= :startdate')
                ->setParameter('startdate', $time['time']);
        } else {
            $qb2->andWhere('q.time >= :startdate')
                ->setParameter('startdate', strtotime($startDate));
        }

        if ($endDate == "") {
            $qb2->andWhere('q.time <= :enddate')
                ->setParameter('enddate', time());
        } else {
            $qb2->andWhere('q.time <= :enddate')
                ->setParameter('enddate', strtotime($endDate));
        }
        if ($searchid != '') {
            $qb2->andWhere('q.id = :searchid')
                ->setParameter('searchid', $searchid);
        }
        $qb2->andWhere("(q.groups = ''"  . $groups . ")")
            ->andWhere("q.visible = '1'")
            ->addOrderBy('q.pinned', 'DESC')
            ->addOrderBy('q.time', 'DESC')
            ->setFirstResult($firstresult)
            ->setMaxResults($limit);


        $qb3 = $this->db->getQueryBuilder();

        $qb3->selectAlias($qb3->createFunction('COUNT(*)'), 'count')
            ->from($this->getTableName(), 'q')
            ->where("q.expiration = 0")
            ->andWhere('(LOWER(q.title) LIKE LOWER(:search) OR LOWER(q.subtitle) LIKE LOWER(:search) OR LOWER(q.text) LIKE LOWER(:search))')
            ->setParameter('search', '%' . $search . '%');
        if ($categories != '') {
            $qb3->andWhere('q.category IN (:categories)')
                ->setParameter('categories', implode(",", $categoryArray));
        }
        if ($searchid != '') {
            $qb3->andWhere('q.id = :searchid')
                ->setParameter('searchid', $searchid);
        }
        $qb3->andWhere("(q.groups = ''"  . $groups . ")")
            ->andWhere("q.visible = '1'");

        $cursor = $qb3->execute();
        $row = $cursor->fetch();
        $cursor->closeCursor();



        return [$this->findEntities($qb2), $row['count'], $time];
    }


    /**
     * @return array
     */
    public function dashboard(array $groupsArray): array
    {
        $groups = '';
        foreach ($groupsArray as $group) {
            $groups .= ' OR q.groups LIKE "%' . $group . '%" ';
        }
        /* @var $qb IQueryBuilder */
        $qb = $this->db->getQueryBuilder();
        $qb->select('*')
            ->from($this->getTableName(), 'q')
            ->where("(q.expiration = 0 OR q.expiration >= :today)")
            ->andWhere("(q.groups = ''"  . $groups . ")")
            ->andWhere("q.visible = '1'")
            ->setParameter('groups', $groups)
            ->setParameter('today', strtotime('today'))
            ->addOrderBy('q.time', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(7);

        return $this->findEntities($qb);
    }
}
