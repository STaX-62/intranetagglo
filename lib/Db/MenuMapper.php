<?php

namespace OCA\IntranetAgglo\Db;

use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\Entity;
use OCP\AppFramework\Db\QBMapper;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;

class MenuMapper extends QBMapper
{

    public function __construct(IDBConnection $db)
    {
        parent::__construct($db, 'intranetagglomenu', Menu::class);
    }

    /**
     * @param int $id
     * @return Entity|Menu
     * @throws \OCP\AppFramework\Db\MultipleObjectsReturnedException
     * @throws DoesNotExistException
     */
    public function find(int $id): Menu
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
        /* @var $qbSection IQueryBuilder */
        $qbSection = $this->db->getQueryBuilder();

        $qbSection->select('*')
            ->where("q.position LIKE '%-0-0'")
            ->from($this->getTableName(), 'q');

        /* @var $qbMenu IQueryBuilder */
        $qbMenu = $this->db->getQueryBuilder();

        $qbMenu->select('*')
            ->where("q.position LIKE '%-0'")
            ->andWhere("q.position NOT LIKE '%-0-0'")
            ->from($this->getTableName(), 'q');

        /* @var $qb IQueryBuilder */
        $qbSubmenu = $this->db->getQueryBuilder();

        $qbSubmenu->select('*')
            ->where("q.position NOT LIKE '%-0'")
            ->from($this->getTableName(), 'q');

        return [$this->findEntities($qbSection), $this->findEntities($qbMenu), $this->findEntities($qbSubmenu)];
    }


    /**
     * @return array
     */
    public function findByGroups(array $groupsArray): array
    {
        $groups = '%';
        foreach ($groupsArray as $group) {
            $groups .= $group . '%';
        }

        /* @var $qb IQueryBuilder */
        $qbSection = $this->db->getQueryBuilder();
        $qbSection->select('*')
            ->from($this->getTableName(), 'q')
            ->where("q.groups = ''")
            ->orWhere("q.groups LIKE :groups")
            ->andWhere("q.position LIKE '%-0-0'")
            ->setParameter('groups', $groups);

        /* @var $qb IQueryBuilder */
        $qbMenu = $this->db->getQueryBuilder();
        $qbMenu->select('*')
            ->from($this->getTableName(), 'q')
            ->where("q.groups = ''")
            ->orWhere("q.groups LIKE :groups")
            ->andWhere("q.position LIKE '%-0'")
            ->andWhere("q.position NOT LIKE '%-0-0'")
            ->setParameter('groups', $groups);

        /* @var $qb IQueryBuilder */
        $qbSubmenu = $this->db->getQueryBuilder();
        $qbSubmenu->select('*')
            ->from($this->getTableName(), 'q')
            ->where("q.groups = ''")
            ->orWhere("q.groups LIKE :groups")
            ->andWhere("q.position NOT LIKE '%-0'")
            ->setParameter('groups', $groups);

        return [$this->findEntities($qbSection), $this->findEntities($qbMenu), $this->findEntities($qbSubmenu)];
    }


    /**
     * @return array
     */
    public function findByPosition(string $position): array
    {
        /* @var $qb IQueryBuilder */
        $qb = $this->db->getQueryBuilder();
        $qb->select('*')
            ->from($this->getTableName(), 'q')
            ->where("q.position LIKE :position")
            ->setParameter('position', $position);

        return $this->findEntities($qb);
    }
}
