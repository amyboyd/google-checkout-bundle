<?php

namespace Amy\GoogleCheckoutBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * @ORM\Table(name="google_checkout_notif")
 * @ORM\Entity
 */
class Notification
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * A long integer, like 543332236569.
     * @ORM\Column(name="order_number", type="string", length=255)
     */
    private $orderNumber;

    /**
     * Looks like 543332236569-00010-2 (where 543332236569 is the order
     * number).
     * @ORM\Column(name="serial", type="string", length=255)
     */
    private $serial;

    /**
     * @ORM\Column(name="xml", type="text")
     */
    private $xml;

    /**
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    public function __construct()
    {
        $this->date = new DateTime();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    public function getSerial()
    {
        return $this->serial;
    }

    public function getXml()
    {
        return $this->xml;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setSerial($serial)
    {
        $this->serial = $serial;

        $parts = explode('-', $serial, 2);
        $this->orderNumber = $parts[0];
    }

    public function setXml($xml)
    {
        $this->xml = $xml;
    }
}
