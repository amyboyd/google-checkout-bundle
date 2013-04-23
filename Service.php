<?php

namespace Amy\GoogleCheckoutBundle;

use Doctrine\ORM\EntityManager;
use Amy\GoogleCheckoutBundle\Entity\Notification;
use Amy\GoogleCheckoutBundle\Exception;

class Service
{
    private $entityManager;

    private $listeners = array();

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        
        // @todo - remove after testing completed.
        $sampleListener = new Test\SampleListener();
        $this->registerListener($sampleListener);
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
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }
}
