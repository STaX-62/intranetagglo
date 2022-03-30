<?php

namespace OCA\IntranetAgglo\Db;

use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\Entity;
use OCP\AppFramework\Db\QBMapper;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;

class MenuMapper extends QBMapper
{

    public function __construct(idBConnection $db)
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
            ->andWhere("q.menuid = 0")
            ->andWhere("q.submenuid = 0")
            ->addOrderBy('q.sectionid', 'ASC')
            ->from($this->getTableName(), 'q');

        /* @var $qbMenu IQueryBuilder */
        $qbMenu = $this->db->getQueryBuilder();

        $qbMenu->select('*')
            ->andWhere("q.menuid > 0")
            ->andWhere("q.submenuid = 0")
            ->addOrderBy('q.menuid', 'ASC')
            ->from($this->getTableName(), 'q');

        /* @var $qb IQueryBuilder */
        $qbSubmenu = $this->db->getQueryBuilder();

        $qbSubmenu->select('*')
            ->andWhere("q.submenuid > 0")
            ->addOrderBy('q.submenuid', 'ASC')
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
            ->andWhere("q.menuid = 0")
            ->andWhere("q.submenuid = 0")
            ->addOrderBy('q.sectionid', 'ASC')
            ->setParameter('groups', $groups);

        /* @var $qb IQueryBuilder */
        $qbMenu = $this->db->getQueryBuilder();
        $qbMenu->select('*')
            ->from($this->getTableName(), 'q')
            ->where("q.groups = ''")
            ->orWhere("q.groups LIKE :groups")
            ->andWhere("q.menuid > 0")
            ->andWhere("q.submenuid = 0")
            ->addOrderBy('q.menuid', 'ASC')
            ->setParameter('groups', $groups);

        /* @var $qb IQueryBuilder */
        $qbSubmenu = $this->db->getQueryBuilder();
        $qbSubmenu->select('*')
            ->from($this->getTableName(), 'q')
            ->where("q.groups = ''")
            ->orWhere("q.groups LIKE :groups")
            ->andWhere("q.submenuid > 0")
            ->addOrderBy('q.submenuid', 'ASC')
            ->setParameter('groups', $groups);

        return [$this->findEntities($qbSection), $this->findEntities($qbMenu), $this->findEntities($qbSubmenu)];
    }

    /**
     * @return array
     */
    public function NewIdSection(): int
    {
        /* @var $qb IQueryBuilder */
        $qb = $this->db->getQueryBuilder();
        $qb->select('q.sectionid')
            ->from($this->getTableName(), 'q')
            ->addOrderBy('q.sectionid', 'DESC')
            ->setMaxResults(1);

        $result = $qb->execute();
        $max = (int)$result->fetchColumn();
        $result->closeCursor();

        return ($max + 1);
    }

    /**
     * @return array
     */
    public function NewIdMenu($section): int
    {
        /* @var $qb IQueryBuilder */
        $qb = $this->db->getQueryBuilder();
        $qb->select('q.menuid')
            ->from($this->getTableName(), 'q')
            ->addOrderBy('q.menuid', 'DESC')
            ->where("q.sectionid = :section")
            ->setParameter('section', $section)
            ->setMaxResults(1);

        $result = $qb->execute();
        $max = (int)$result->fetchColumn();
        $result->closeCursor();

        return ($max + 1);
    }

    /**
     * @return array
     */
    public function NewIdSubmenu($section, $menu): int
    {
        /* @var $qb IQueryBuilder */
        $qb = $this->db->getQueryBuilder();
        $qb->select('q.submenuid')
            ->from($this->getTableName(), 'q')
            ->addOrderBy('q.submenuid', 'DESC')
            ->where("q.sectionid = :section")
            ->andWhere("q.menuid = :menu")
            ->setParameter('section', $section)
            ->setParameter('menu', $menu)
            ->setMaxResults(1);

        $result = $qb->execute();
        $max = (int)$result->fetchColumn();
        $result->closeCursor();

        return ($max + 1);
    }

    /**
     * @return array
     */
    public function findByPosition($section, $menu, $submenu): array
    {
        /* @var $qb IQueryBuilder */
        $qb = $this->db->getQueryBuilder();
        $qb->select('*')
            ->from($this->getTableName(), 'q')
            ->where("q.sectionid = :section")
            ->andWhere("q.menuid = :menu")
            ->andWhere("q.submenuid = :submenu")
            ->setParameter('section', $section)
            ->setParameter('menu', $menu)
            ->setParameter('submenu', $submenu);
        
        return $this->findEntities($qb);
    }
}
