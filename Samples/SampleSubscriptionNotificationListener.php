<?php

namespace Amy\GoogleCheckoutBundle\Samples;

use Amy\GoogleCheckoutBundle\Entity\Notification;
use Amy\GoogleCheckoutBundle\NotificationListener;

/**
 * NotificationListener's methods have documentation to help you implement a
 * listener.
 */
class SampleSubscriptionNotificationListener extends NotificationListener
{
    /** @var EntityManager */
    private $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function isNotificationRelevantToThisListener(Notification $notification, Notification $originalNewOrderNotification = null)
    {
        $privateData = $this->getPrivateData($originalNewOrderNotification);
        return ($privateData->type === 'subscription');
    }

    public function onNewOrder(Notification $notification, Notification $originalNewOrderNotification = null)
    {
        $privateData = $this->getPrivateData($notification);

        $user = $this->em
            ->getRepository('User')
            ->find((int) $privateData->user);
        /* @var $user \Oddboys\UserBundle\Entity\User */

        $price = $notification->getXmlAsSimpleXMLElement()
            ->{'shopping-cart'}
            ->{'items'}
            ->{'item'}
            ->{'unit-price'};
        $user->setHasActiveSubscription(true);
        $user->setBillingAmount($price);
        $user->setLastBilledAt(new \DateTime());
        $this->em->persist($user);
        $this->em->flush();
    }

    public function onSuccessfulCharge(Notification $notification, Notification $originalNewOrderNotification = null)
    {
        $privateData = $this->getPrivateData($originalNewOrderNotification);

        $user = $this->em
            ->getRepository('User')
            ->find((int) $privateData->user);
        /* @var $user \Oddboys\UserBundle\Entity\User */

        $price = $notification->getXmlAsSimpleXMLElement()
            ->{'latest-charge-amount'};
        $user->setHasActiveSubscription(true);
        $user->setBillingAmount($price);
        $user->setLastBilledAt(new \DateTime());
        $this->em->persist($user);
        $this->em->flush();
    }

    public function onCancelledSubscription(Notification $notification, Notification $originalNewOrderNotification = null)
    {
        // Cancel the user's subscription.
        $privateData = $this->getPrivateData($originalNewOrderNotification);

        $user = $this->em
            ->getRepository('User')
            ->find((int) $privateData->user);
        /* @var $user \Oddboys\UserBundle\Entity\User */

        $user->setHasActiveSubscription(false);
        $user->setBillingAmount(null);
        $this->em->persist($user);
        $this->em->flush();
    }

    public function onStateChange(Notification $notification, Notification $originalNewOrderNotification = null)
    {
        $state = $notification->getXmlAsSimpleXMLElement()->{'new-financial-order-state'};
        $isPaymentDeclined = $state === 'PAYMENT_DECLINED';
        if ($isPaymentDeclined) {
            // Cancel the user's subscription.
            $privateData = $this->getPrivateData($originalNewOrderNotification);

            $user = $this->em
                ->getRepository('User')
                ->find((int) $privateData->user);
            /* @var $user \Oddboys\UserBundle\Entity\User */

            $user->setHasActiveSubscription(false);
            $user->setBillingAmount(null);
            $this->em->persist($user);
            $this->em->flush();
        }
    }

    private function getPrivateData(Notification $notification)
    {
        return json_decode($notification->getXmlAsSimpleXMLElement()
            ->{'shopping-cart'}
            ->{'items'}
            ->{'item'}
            ->{'merchant-private-item-data'});
    }
}
