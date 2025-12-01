# Netgen Layouts & Sylius eCommerce integration installation instructions

## Use Composer to install the integration

Run the following command to install Netgen Layouts & Sylius eCommerce
integration:

```
composer require netgen/layouts-standard netgen/layouts-sylius
```

## Activating integration bundles

After completing standard Layouts install instructions, you also need to
activate `NetgenLayoutsSyliusBundle` and `NetgenContentBrowserSyliusBundle`.
Make sure they are activated after all other Netgen Layouts and Content Browser bundles:

```
...

Netgen\Bundle\LayoutsSyliusBundle\NetgenLayoutsSyliusBundle::class => ['all' => true],
Netgen\Bundle\ContentBrowserSyliusBundle\NetgenContentBrowserSyliusBundle::class => ['all' => true],
```

## Include routing configuration:

Create the `config/routes/netgen_layouts_sylius.yaml` file and add the following
routing configuration:

```yaml
netgen_layouts_sylius:
    resource: "@NetgenLayoutsSyliusBundle/Resources/config/routing.yaml"
```

## Configure the main layout

Due to how Netgen Layouts works, your main layout template needs to wrap the
`content` block inside a new `layout` block:

```
{% block layout %}
    {% block content %}
    {% endblock %}
{% endblock %}

```

All full view templates (those that are rendered directly by controllers), need
to extend `nglayouts.layoutTemplate` instead of your original layout:

```
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

Create the `config/packages/netgen_layouts.yaml` file to configure the layout:

```
netgen_layouts:
    pagelayout: 'templates/shop/layout.html.twig'
```

## Activate ESI and fragments support

Netgen Layouts requires that ESI and fragments support is activated in Symfony.
Add the following to your `config/packages/framework.yaml` file:

```
framework:
    esi: ~
    fragments: ~
```

## Update security rules for admin UI integration

To properly integrate Netgen Layouts and Sylius admin interfaces, you need to
add some access control rules to your `config/packages/security.yaml` file to allow
access to Netgen Layouts admin routes:

```
access_control:
    - { path: "%netgen_layouts.sylius.security.admin_regex%", role: ROLE_ADMINISTRATION_ACCESS }
```
