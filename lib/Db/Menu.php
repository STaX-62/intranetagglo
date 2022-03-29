<?php

namespace OCA\IntranetAgglo\Db;

use JsonSerializable;

use OCP\AppFramework\Db\Entity;

class Menu extends Entity implements JsonSerializable
{

    protected $title;
    protected $icon;
    protected $link;
    protected $groups;
    protected $sectionId;
    protected $menuId;
    protected $submenuId;

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'icon' => $this->icon,
            'link' => $this->link,
            'groups' => $this->groups,
            'sectionId' => $this->sectionId,
            'menuId' => $this->menuId,
            'submenuId' => $this->submenuId
        ];
    }
}
