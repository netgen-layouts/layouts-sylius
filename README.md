# Netgen Layouts & Sylius eCommerce integration

## Installation instructions

### Use Composer

Run the following command to install Netgen Layouts & Sylius eCommerce
integration:

```bash
composer require netgen/layouts-sylius
```

Symfony Flex will automatically enable the bundle and import the routes.

### Upgrading from Netgen Layouts 1.4

Version 2.0 renamed the taxon rule target types: 1.4's `sylius_single_taxon` (exact match) is
now `sylius_taxon`, and 1.4's `sylius_taxon` (subtree match) is now `sylius_taxon_tree`.
Existing rule targets need a one-time rename, shipped as a Doctrine migration. Run it right
after upgrading, before creating or editing taxon rules in 2.0 (a 2.0 `sylius_taxon` target is
indistinguishable from an un-renamed 1.4 one):

```bash
php bin/console doctrine:migrations:migrate --configuration=vendor/netgen/layouts-sylius/migrations/doctrine.yaml
```

The migration has no runtime check of its own — the migration table decides whether it has run,
so it never runs twice. If you already renamed the targets by hand, do not run it (it would
rename the exact-match targets a second time); mark it as executed instead:

```bash
php bin/console doctrine:migrations:version 'Netgen\Layouts\Sylius\Migrations\Doctrine\Version020000' --add --configuration=vendor/netgen/layouts-sylius/migrations/doctrine.yaml
```

Fresh installations run it like any other migration: there is nothing to rename, and the
recorded version keeps later upgrades from running it again.

### Configure the main layout

Due to how Netgen Layouts works, your main layout template needs to wrap the
`content` block inside a new `layout` block:

```twig
{% block layout %}
    {% block content %}
    {% endblock %}
{% endblock %}

```

All full view templates (those that are rendered directly by controllers), need
to extend `nglayouts.layoutTemplate` instead of your original layout:

```twig
{% extends nglayouts.layoutTemplate %}

{% block content %}

    ...

{% endblock %}
```

This allows Netgen Layouts to inject a layout resolved for the request into
your page. Since you configured all your full views to now use Netgen Layouts,
they will not fallback to your main layout template. Because of that, you need
to configure Netgen Layouts with your main layout template, so the fallback
keeps working as it should.

```yaml
# config/packages/netgen_layouts.yaml
netgen_layouts:
    pagelayout: templates/shop/layout.html.twig
```

### Activate ESI and fragments support

Netgen Layouts requires that ESI and fragments support is activated in Symfony.

```yaml
# config/packages/framework.yaml
framework:
    esi: true
    fragments: true
```

### Update security rules for admin UI integration

To properly integrate Netgen Layouts and Sylius admin interfaces, you need to
redefine the `sylius.security.admin_regex` parameter to allow  access to
Netgen Layouts admin routes:

```yaml
# config/packages/security.yaml
parameters:
    sylius.security.admin_regex: "^(/%sylius_admin.path_name%|/nglayouts/app|/nglayouts/api|/nglayouts/admin|/cb)"
```
