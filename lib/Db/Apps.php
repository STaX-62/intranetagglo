<?php

namespace OCA\SimpleApp\Db;

use JsonSerializable;

use OCP\AppFramework\Db\Entity;

class Apps extends Entity implements JsonSerializable
{

    protected $title;
    protected $icon;
    protected $link;
    protected $groups;

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'icon' => $this->icon,
            'link' => $this->link,
            'groups' => $this->groups
        ];
    }
}
