# Netgen Layouts & Sylius eCommerce integration

## Installation instructions

### Use Composer to install the integration

Run the following command to install Netgen Layouts & Sylius eCommerce
integration:

```bash
composer require netgen/layouts-sylius
```

Symfony Flex will automatically enable the bundle and import the routes.

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
    esi: ~
    fragments: ~
```

### Update security rules for admin UI integration

To properly integrate Netgen Layouts and Sylius admin interfaces, you need to
redefine the `sylius.security.admin_regex` parameter to allow  access to
Netgen Layouts admin routes:

```yaml
# config/packages/security.yaml
parameters:
    sylius.security.admin_regex: "^(/%sylius_admin.path_name%|/nglayouts/(dev/)?app|/nglayouts/api|/nglayouts/admin|/cb)"
```

## Running tests

Running tests requires that you have complete vendors installed, so run
`composer install` before running the tests.

You can run unit tests by calling `composer test` from the repo root:

```bash
$ composer test
```
