## Install ##

* Download (or checkout) the source and put it into your bundles
  directory.

* Add `new Amy\GoogleCheckoutBundle\GoogleCheckoutBundle()` in your
  `app/AppKernel.php`'s `registerBundles`

* Review `app/console doctrine:schema:update --dump-sql`

* Run `app/console doctrine:schema:update --force` if the above was OK.
