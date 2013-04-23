<?php

namespace Amy\GoogleCheckoutBundle;

use Doctrine\ORM\EntityManager;
use Amy\GoogleCheckoutBundle\Entity\Notification;
use Amy\GoogleCheckoutBundle\Exception;

class Service
{
    private $entityManager;

    private $merchantID, $merchantKey, $serverType, $sslCertificatePath;

    private $listeners = array();

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $currency Like "GBP" or "EUR".
     * @return \GoogleCart
     */
    public function createCart($currency)
    {
        $this->autoloadGoogleClasses();

        return new \GoogleCart(
            $this->merchantID,
            $this->merchantKey,
            $this->serverType,
            $currency
        );
    }

    public function registerListener(NotificationListener $listener)
    {
        $this->listeners[] = $listener;
    }

    public function newNotification(Notification $notification,
        Notification $originalNewOrderNotification = null)
    {
        switch ($notification->getType()) {
            case Notification::TYPE_NEW_ORDER:
                $method = 'onNewOrder';
                break;
            case Notification::TYPE_ORDER_STATE_CHANGE:
                $method = 'onStateChange';
                break;
            case Notification::TYPE_RISK_INFORMATION:
                $method = 'onRiskInformationReceived';
                break;
            case Notification::TYPE_AUTHORIZATION_AMOUNT:
                $method = 'onAuthorization';
                break;
            case Notification::TYPE_CHARGE_AMOUNT:
                $method = 'onSuccessfulCharge';
                break;
            case Notification::TYPE_REFUND_AMOUNT:
                $method = 'onRefund';
                break;
            case Notification::TYPE_CHARGEBACK_AMOUNT:
                $method = 'onChargeback';
                break;
            case Notification::TYPE_CANCELLED_SUBSCRIPTION:
                $method = 'onCancelledSubscription';
                break;
            default:
                throw new Exception('Unexpected type: ' . $notification->getType());
        }

        $this->dispatchToListeners($method, $notification, $originalNewOrderNotification);
    }

    private function dispatchToListeners($method, Notification $notification,
        Notification $originalNewOrderNotification = null)
    {
        foreach ($this->listeners as $listener) {
            /* @var $listener NotificationListener */
            $relevant = $listener->isNotificationRelevantToThisListener($notification, $originalNewOrderNotification);
            if ($relevant) {
                call_user_func(array($listener, $method), $notification, $originalNewOrderNotification);
            }
        }
    }

    /**
     * @param string $serial
     * @return string
     * @throws Exception
     */
    public function getXmlBySerialNumber($serial)
    {
        $this->autoloadGoogleClasses();

        // Request the notification's XML data.
        $request = new \GoogleNotificationHistoryRequest(
            $this->merchantID,
            $this->merchantKey,
            $this->serverType
        );
        $response = $request->SendNotificationHistoryRequest(
            $serial,
            null,
            array(),
            array(),
            null,
            null,
            $this->getSslCertificatePath()
        );
        if (!is_array($response) || $response[0] != 200) {
            throw new Exception('Serial ' . $serial . ' has unexpected history response: ' . print_r($response, true));
        }
        return $response[1];
    }

    public function autoloadGoogleClasses()
    {
        require_once __DIR__ . '/lib/checkout/library/autoload.php';
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    public function getMerchantID()
    {
        return $this->merchantID;
    }

    public function getMerchantKey()
    {
        return $this->merchantKey;
    }

    public function getServerType()
    {
        return $this->serverType;
    }

    public function getSslCertificatePath()
    {
        return $this->sslCertificatePath;
    }

    public function setMerchantID($merchantID)
    {
        $this->merchantID = $merchantID;
    }

    public function setMerchantKey($merchantKey)
    {
        $this->merchantKey = $merchantKey;
    }

    public function setServerType($serverType)
    {
        if ($serverType !== 'sandbox' && $serverType !== 'production') {
            throw new Exception('Unexpected server type: ' . $serverType
                . ' (expected sandbox or production)');
        }
        $this->serverType = $serverType;
    }

    public function setSslCertificatePath($sslCertificatePath)
    {
        $this->sslCertificatePath = $sslCertificatePath;
    }
}
