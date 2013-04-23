<?php

namespace Amy\GoogleCheckoutBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Amy\GoogleCheckoutBundle\Entity\Notification;

class DefaultController extends Controller
{
    public function ipnAction(Request $request)
    {
        $serial = $request->get('serial-number');
        $xml = $this->get('amy_google_checkout')->getXmlBySerialNumber($serial);

        $notification = new Notification($xml);
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($notification);
        $em->flush();

        if ($notification->getType() != Notification::TYPE_NEW_ORDER) {
            $originalNewOrder = $this->getDoctrine()
                ->getRepository('AmyGoogleCheckoutBundle:Notification')
                ->findOriginalNewOrderNotification($notification);
        }

        $this->get('amy_google_checkout')->newNotification(
            $notification,
            isset($originalNewOrder) ? $originalNewOrder : null
        );

        // A '200 OK' response needs to be sent.
        return new Response(null, 200);
    }
}
