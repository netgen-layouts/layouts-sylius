Netgen Block Manager & Sylius eCommerce integration installation instructions
=============================================================================

Use Composer to install the integration
---------------------------------------

Run the following command to install Netgen Block Manager & Sylius eCommerce integration:

```
composer require netgen/block-manager-sylius:^1.0
```

Activating integration bundle
-----------------------------

After completing standard Block Manager install instructions, you also need to activate `NetgenSyliusBlockManagerBundle`. Make sure it is activated after all other Block Manager bundles.

```
...

$bundles[] = new Netgen\Bundle\BlockManagerAdminBundle\NetgenBlockManagerAdminBundle();
$bundles[] = new Netgen\Bundle\SyliusBlockManagerBundle\NetgenSyliusBlockManagerBundle();

return $bundles;
```
