<?php

namespace OCA\IntranetAgglo\Db;

use OCP\AppFramework\Db\QBMapper;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;

class Notification extends QBMapper
{

    public function __construct(IDBConnection $db)
    {
        parent::__construct($db, 'notifications');
    }

    public function findNotificationByUser(int $user)
    {
        /* @var $qb IQueryBuilder */
        $qb3 = $this->db->getQueryBuilder();

        $qb3->selectAlias($qb3->createFunction('COUNT(*)'), 'count')
            ->from($this->getTableName(), 'q')
            ->where('q.user = :user')
            ->andWhere('q.app = :app')
            ->setParameter('user', $user)
            ->setParameter('app', 'intranetagglo');

        $cursor = $qb3->execute();
        $row = $cursor->fetch();
        $cursor->closeCursor();

        return $row;
    }
}
