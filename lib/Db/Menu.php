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
    protected $sectionid;
    protected $menuid;
    protected $submenuid;

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'icon' => $this->icon,
            'link' => $this->link,
            'groups' => $this->groups,
            'sectionid' => $this->sectionid,
            'menuid' => $this->menuid,
            'submenuid' => $this->submenuid
        ];
    }
}
