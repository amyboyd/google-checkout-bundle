<?php

namespace Amy\GoogleCheckoutBundle;

use Doctrine\ORM\EntityManager;

class Service
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function newNotification($notification, $originalNewOrderNotification)
    {
        // @todo
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }
}
