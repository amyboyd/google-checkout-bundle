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
     * See https://developers.google.com/checkout/developer/Google_Checkout_XML_API_Notification_API
     * @ORM\Column(name="xml", type="text")
     */
    private $xml;

    /**
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(name="type", type="integer")
     */
    private $type;

    // A <new-order-notification>
    const TYPE_NEW_ORDER = 1;
    // A <order-state-change-notification>
    const TYPE_ORDER_STATE_CHANGE = 2;
    // A <risk-information-notification>
    const TYPE_RISK_INFORMATION = 3;
    // A <authorization-amount-notification> - the charge amount has been auth'ed.
    const TYPE_AUTHORIZATION_AMOUNT = 4;
    // A <charge-amount-notification> - the order has actually been charged.
    const TYPE_CHARGE_AMOUNT = 5;
    // A <refund-amount-notification>
    const TYPE_REFUND_AMOUNT = 6;
    // A <chargeback-amount-notification>
    const TYPE_CHARGEBACK_AMOUNT = 7;
    // A <cancelled-subscription-notification>
    const TYPE_CANCELLED_SUBSCRIPTION = 8;

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

    public function getType()
    {
        return $this->type;
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

    public function setType($type)
    {
        $this->type = $type;
    }
}
