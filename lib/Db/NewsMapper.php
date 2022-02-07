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
    public function findAll($firstresult, $search): array
    {
        /* @var $qb IQueryBuilder */
        $qb = $this->db->getQueryBuilder();
        $qb->addSelect('*')
            ->addSelect('count(q.id)')
            ->from($this->getTableName(), 'q')
            ->where('q.title LIKE :word')
            ->orWhere('q.subtitle LIKE :word')
            ->orWhere('q.text LIKE :word')
            ->setParameter('word', '%' . $search . '%')
            ->setFirstResult($firstresult)
            ->setMaxResults(3);
        return $this->findEntities($qb);
    }

    /**
     * @return array
     */
    public function getNews(int $firstresult, array $groupsArray, string $search): array
    {
        $groups = '%';
        foreach ($groupsArray as $group) {
            $groups .= $group . '%';
        }

        /* @var $qb IQueryBuilder */
        $qb = $this->db->getQueryBuilder();
        $qb->addSelect('*')
            ->addSelect('count(q.id)')
            ->from($this->getTableName(), 'q')
            ->where('q.title LIKE :word')
            ->orWhere('q.subtitle LIKE :word')
            ->orWhere('q.text LIKE :word')
            ->andWhere("q.groups = ''")
            ->orWhere("q.groups LIKE :groups")
            ->andWhere('q.visible = 1')
            ->setParameter('groups', $groups)
            ->setParameter('word', '%' . $search . '%')
            ->setFirstResult($firstresult)
            ->setMaxResults(3);

        return $this->findEntities($qb);
    }
}
