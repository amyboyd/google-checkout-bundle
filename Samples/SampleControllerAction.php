<?php

$this->get('amy_google_checkout')->autoloadGoogleClasses();

$price = ...;
$userID = ...;

$item = new \GoogleItem('Subscription', '', 1, $price);
$item->SetEmailDigitalDelivery('true');
$item->SetMerchantItemId('subscription');
$item->SetMerchantPrivateItemData(json_encode(array(
    'type' => 'subscription',
    'user' => $userID,
)));

// Make it recurring monthly.
$subscription = new \GoogleSubscription('google', 'MONTHLY', $price);
$item->SetSubscription($subscription);
$subscription->SetItem($item);

$cart = $this->get('amy_google_checkout')->createCart('GBP');
/* @var $cart \GoogleCart */
$cart->AddItem($item);
$cart->SetContinueShoppingUrl($this->generateUrl('after_new_subscription', array(), true));

// Redirect to Google Checkout.
$checkoutUrl = $cart->CheckoutServer2Server(array(), $this->get('amy_google_checkout')->getSslCertificatePath(), false);
if (is_array($checkoutUrl) && $checkoutUrl[0] == 200) {
    return $this->redirect($checkoutUrl[1]);
}
else {
    throw new \Exception(print_r($checkoutUrl, true));
}
