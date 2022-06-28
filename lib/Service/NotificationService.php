<?php

namespace OCA\IntranetAgglo\Service;

use OCA\IntranetAgglo\Db\Notification;

class NotificationService
{
    /** @var Notification */
    private $mapper;

    public function __construct(Notification $mapper)
    {
        $this->mapper = $mapper;
    }

    public function findNotificationByUser($user)
    {
        return $this->mapper->findNotificationByUser($user);
    }
}
