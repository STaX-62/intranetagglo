<?php

namespace OCA\IntranetAgglo\Db;

use JsonSerializable;

use OCP\AppFramework\Db\Entity;

class News extends Entity implements JsonSerializable
{

    protected $author;
    protected $title;
    protected $subtitle;
    protected $text;
    protected $photo;
    protected $category;
    protected $groups;
    protected $visible;

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'author' => $this->author,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'text' => $this->text,
            'photo' => $this->photo,
            'category' => $this->category,
            'groups' => $this->groups,
            // 'time' => $this->time,
            'visible' => $this->visible
        ];
    }
}
