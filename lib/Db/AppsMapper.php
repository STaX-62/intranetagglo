<?php

namespace OCA\IntranetAgglo\Db;

use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\Entity;
use OCP\AppFramework\Db\QBMapper;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;

class AppsMapper extends QBMapper
{

    public function __construct(IDBConnection $db)
    {
        parent::__construct($db, 'intranetaggloapps', Apps::class);
    }

    /**
     * @param int $id
     * @return Entity|Apps
     * @throws \OCP\AppFramework\Db\MultipleObjectsReturnedException
     * @throws DoesNotExistException
     */
    public function find(int $id): Apps
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
    public function findAll(): array
    {
        /* @var $qb IQueryBuilder */
        $qb = $this->db->getQueryBuilder();
        $qb->select('*')
            ->from($this->getTableName());

        return $this->findEntities($qb);
    }
}
