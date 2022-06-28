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
    protected $link;
    protected $time;
    protected $expiration;
    protected $visible;
    protected $expiration;
    protected $pinned;

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
            'link' => $this->link,
            'time' => $this->time,
            'expiration' => $this->expiration,
            'visible' => $this->visible,
            'expiration' => $this->expiration,
            'pinned' => $this->pinned
        ];
    }
}
