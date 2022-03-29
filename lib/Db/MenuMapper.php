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
            ->from($this->getTableName(), 'q');

        /* @var $qbMenu IQueryBuilder */
        $qbMenu = $this->db->getQueryBuilder();

        $qbMenu->select('*')
            ->andWhere("q.menuid > 0")
            ->andWhere("q.submenuid = 0")
            ->from($this->getTableName(), 'q');

        /* @var $qb IQueryBuilder */
        $qbSubmenu = $this->db->getQueryBuilder();

        $qbSubmenu->select('*')
            ->andWhere("q.submenuid > 0")
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
            ->setParameter('groups', $groups);

        /* @var $qb IQueryBuilder */
        $qbMenu = $this->db->getQueryBuilder();
        $qbMenu->select('*')
            ->from($this->getTableName(), 'q')
            ->where("q.groups = ''")
            ->orWhere("q.groups LIKE :groups")
            ->andWhere("q.menuid > 0")
            ->andWhere("q.submenuid = 0")
            ->setParameter('groups', $groups);

        /* @var $qb IQueryBuilder */
        $qbSubmenu = $this->db->getQueryBuilder();
        $qbSubmenu->select('*')
            ->from($this->getTableName(), 'q')
            ->where("q.groups = ''")
            ->orWhere("q.groups LIKE :groups")
            ->andWhere("q.submenuid > 0")
            ->setParameter('groups', $groups);

        return [$this->findEntities($qbSection), $this->findEntities($qbMenu), $this->findEntities($qbSubmenu)];
    }


    /**
     * @return array
     */
    public function findBySection(int $sectionA, int $sectionB): array
    {
        /* @var $qb IQueryBuilder */
        $qb = $this->db->getQueryBuilder();
        $qb->select('*')
            ->from($this->getTableName(), 'q')
            ->where("q.sectionid BETWEEN :sectionA AND :sectionB")
            ->addOrderBy('q.sectionid', 'ASC')
            ->setParameter('sectionA', $sectionA)
            ->setParameter('sectionB', $sectionB);

        return $this->findEntities($qb);
    }

    /**
     * @return array
     */
    public function findByMenu(int $section, int $menuA, int $menuB): array
    {
        /* @var $qb IQueryBuilder */
        $qb = $this->db->getQueryBuilder();
        $qb->select('*')
            ->from($this->getTableName(), 'q')
            ->where("q.sectionid = :section")
            ->andWhere("q.menuid BETWEEN :menuA AND :menuB")
            ->addOrderBy('q.menuid', 'ASC')
            ->setParameter('section', $section)
            ->setParameter('menuA', $menuA)
            ->setParameter('menuB', $menuB);

        return $this->findEntities($qb);
    }

    /**
     * @return array
     */
    public function findBySubmenu(int $section, int $menu, int $submenuA, int $submenuB): array
    {
        /* @var $qb IQueryBuilder */
        $qb = $this->db->getQueryBuilder();
        $qb->select('*')
            ->from($this->getTableName(), 'q')
            ->where("q.sectionid = :section")
            ->andWhere("q.menuid = :menu")
            ->andWhere("q.submenuid BETWEEN :submenuA AND :submenuB")
            ->addOrderBy('q.submenuid', 'ASC')
            ->setParameter('section', $section)
            ->setParameter('menu', $menu)
            ->setParameter('submenuA', $submenuA)
            ->setParameter('submenuB', $submenuB);

        return $this->findEntities($qb);
    }
}
