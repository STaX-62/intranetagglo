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
    public function findAll($firstresult, string $search, string $categories, string $searchid, string $startDate, string $endDate): array
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
            ->andWhere('LOWER(q.title) LIKE LOWER(:search)')
            ->orWhere('LOWER(q.subtitle) LIKE LOWER(:search)')
            ->orWhere('LOWER(q.text) LIKE LOWER(:search)')
            ->setParameter('search', '%' . $search . '%');
        if ($categories != '') {
            $index = 0;
            foreach ($categoryArray  as $category) {
                if ($index == 0) {
                    $qb2->andWhere('q.category = :category' . $index)
                        ->setParameter('category' . $index, $category);
                } else {
                    $qb2->orWhere('q.category = :category' . $index)
                        ->setParameter('category' . $index, $category);
                }
                $index++;
            }
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

        $qb2->orWhere('q.id = :searchid')
            ->setParameter('searchid', $searchid)
            ->addOrderBy('q.pinned', 'DESC')
            ->addOrderBy('q.time', 'DESC')
            ->setFirstResult($firstresult)
            ->setMaxResults(3);


        $qb3 = $this->db->getQueryBuilder();

        $qb3->selectAlias($qb3->createFunction('COUNT(*)'), 'count')
            ->from($this->getTableName(), 'q')
            ->where("q.expiration = 0")
            ->andWhere('LOWER(q.title) LIKE LOWER(:search)')
            ->orWhere('LOWER(q.subtitle) LIKE LOWER(:search)')
            ->orWhere('LOWER(q.text) LIKE LOWER(:search)')
            ->setParameter('search', '%' . $search . '%');
        if ($categories != '') {
            $index = 0;
            foreach ($categoryArray as $category) {
                if ($index == 0) {
                    $qb3->andWhere('q.category = :category' . $index)
                        ->setParameter('category' . $index, $category);
                } else {
                    $qb3->orWhere('q.category = :category' . $index)
                        ->setParameter('category' . $index, $category);
                }
                $index++;
            }
        }

        $qb3->orWhere('q.id = :searchid')
            ->setParameter('searchid', $searchid);


        $cursor = $qb3->execute();
        $row = $cursor->fetch();
        $cursor->closeCursor();



        return [$this->findEntities($qb2), $row['count'], $time];
    }

    /**
     * @return array
     */
    public function findAlerts($firstresult, string $search, string $searchid, string $startDate, string $endDate): array
    {
        $qb = $this->db->getQueryBuilder();
        $qb->select('*')
            ->from($this->getTableName(), 'q')
            ->where("q.expiration != 0")
            ->andWhere("q.expiration <= :tomorrow")
            ->setParameter('tomorrow', date("Y-m-d 00:00", strtotime('tomorrow')))
            ->andWhere('LOWER(q.title) LIKE LOWER(:search)')
            ->orWhere('LOWER(q.subtitle) LIKE LOWER(:search)')
            ->orWhere('LOWER(q.text) LIKE LOWER(:search)')
            ->setParameter('search', '%' . $search . '%');
        if ($startDate == "") {
            $qb->andWhere('q.time >= :startdate')
                ->setParameter('startdate', $time['time']);
        } else {
            $qb->andWhere('q.time >= :startdate')
                ->setParameter('startdate', strtotime($startDate));
        }

        if ($endDate == "") {
            $qb->andWhere('q.time <= :enddate')
                ->setParameter('enddate', time());
        } else {
            $qb->andWhere('q.time <= :enddate')
                ->setParameter('enddate', strtotime($endDate) + (60 * 60 * 24));
        }

        $qb->orWhere('q.id = :searchid')
            ->setParameter('searchid', $searchid)
            ->addOrderBy('q.pinned', 'DESC')
            ->addOrderBy('q.time', 'DESC')
            ->setFirstResult($firstresult)
            ->setMaxResults(3);


        $qb2 = $this->db->getQueryBuilder();

        $qb2->selectAlias($qb2->createFunction('COUNT(*)'), 'count')
            ->from($this->getTableName(), 'q')
            ->where("q.expiration != 0")
            ->andWhere("q.expiration <= :tomorrow")
            ->setParameter('tomorrow', date("Y-m-d 00:00", strtotime('tomorrow')))
            ->andWhere('LOWER(q.title) LIKE LOWER(:search)')
            ->orWhere('LOWER(q.subtitle) LIKE LOWER(:search)')
            ->orWhere('LOWER(q.text) LIKE LOWER(:search)')
            ->setParameter('search', '%' . $search . '%');

        $qb2->orWhere('q.id = :searchid')
            ->setParameter('searchid', $searchid);


        $cursor = $qb2->execute();
        $row = $cursor->fetch();
        $cursor->closeCursor();



        return [$this->findEntities($qb), $row['count']];
    }

    /**
     * @return array
     */
    public function findByGroups(int $firstresult, array $groupsArray, string $search, string $categories, string $searchid, string $startDate, string $endDate): array
    {
        $groups = '%';
        foreach ($groupsArray as $group) {
            $groups .= $group . '%';
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
            ->andWhere('LOWER(q.title) LIKE LOWER(:search)')
            ->orWhere('LOWER(q.subtitle) LIKE LOWER(:search)')
            ->orWhere('LOWER(q.text) LIKE LOWER(:search)')
            ->setParameter('search', '%' . $search . '%');
        if ($categories != '') {
            $index = 0;
            foreach ($categoryArray  as $category) {
                if ($index == 0) {
                    $qb2->andWhere('q.category = :category' . $index)
                        ->setParameter('category' . $index, $category);
                } else {
                    $qb2->orWhere('q.category = :category' . $index)
                        ->setParameter('category' . $index, $category);
                }
                $index++;
            }
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
        $qb2->orWhere('q.id = :searchid')
            ->andWhere("q.groups = ''")
            ->orWhere("q.groups LIKE :groups")
            ->andWhere("q.visible = '1'")
            ->setParameter('groups', $groups)

            ->setParameter('searchid', $searchid)

            ->addOrderBy('q.pinned', 'DESC')
            ->addOrderBy('q.time', 'DESC')
            ->setFirstResult($firstresult)
            ->setMaxResults(3);


        $qb3 = $this->db->getQueryBuilder();

        $qb3->selectAlias($qb3->createFunction('COUNT(*)'), 'count')
            ->from($this->getTableName(), 'q')
            ->where("q.expiration = 0")
            ->andWhere('LOWER(q.title) LIKE LOWER(:search)')
            ->orWhere('LOWER(q.subtitle) LIKE LOWER(:search)')
            ->orWhere('LOWER(q.text) LIKE LOWER(:search)')
            ->setParameter('search', '%' . $search . '%');
        if ($categories != '') {
            $index = 0;
            foreach ($categoryArray  as $category) {
                if ($index == 0) {
                    $qb3->andWhere('q.category = :category' . $index)
                        ->setParameter('category' . $index, $category);
                } else {
                    $qb3->orWhere('q.category = :category' . $index)
                        ->setParameter('category' . $index, $category);
                }
                $index++;
            }
        }
        $qb3->orWhere('q.id = :searchid')
            ->andWhere("q.groups = ''")
            ->orWhere("q.groups LIKE :groups")
            ->andWhere("q.visible = '1'")
            ->setParameter('groups', $groups)

            ->setParameter('searchid', $searchid);

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
        $groups = '%';
        foreach ($groupsArray as $group) {
            $groups .= $group . '%';
        }
        /* @var $qb IQueryBuilder */
        $qb = $this->db->getQueryBuilder();
        $qb->select('*')
            ->from($this->getTableName(), 'q')
            ->where("q.expiration = 0")
            ->andWhere("q.groups = ''")
            ->orWhere("q.groups LIKE :groups")
            ->andWhere("q.visible = '1'")
            ->setParameter('groups', $groups)
            ->addOrderBy('q.time', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(7);

        return $this->findEntities($qb);
    }
}
