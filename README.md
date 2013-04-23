## Install ##

* Download (or checkout) the source and put it into your bundles
  directory at `vendor/bundles/Amy/GoogleCheckoutBundle`.

* Add `new Amy\GoogleCheckoutBundle\GoogleCheckoutBundle()` in your
  `app/AppKernel.php`'s `registerBundles`

* Add to your app/config/routing.yml:

        amy_google_checkout:
            resource: "@AmyGoogleCheckoutBundle/Resources/config/routing.yml"
            prefix: /checkout

* Add a new notification listener service to your app/config/services.yml, for
  example:

        subscription_ipn_listener:
            class: SubscriptionNotificationListener
            arguments: [@doctrine.orm.entity_manager]
            tags:
                -  { name: amy_google_checkout.notification_listener }

  Note it must be tagged `amy_google_checkout.notification_listener`

* Create a class (same class name as in your config.yml).
  See `Samples/SampleSubscriptionNotificationListener` for a sample implementation.

* Review `app/console doctrine:schema:update --dump-sql` and run
  `app/console doctrine:schema:update --force` if the above was OK.
