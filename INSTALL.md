Netgen Layouts & Sylius eCommerce integration installation instructions
=======================================================================

Use Composer to install the integration
---------------------------------------

Run the following command to install Netgen Layouts & Sylius eCommerce
integration:

```
composer require netgen/layouts-standard netgen/layouts-sylius
```

Activating integration bundles
------------------------------

After completing standard Layouts install instructions, you also need to
activate `NetgenLayoutsSyliusBundle` and `NetgenContentBrowserSyliusBundle`.
Make sure they are activated after all other Netgen Layouts and Content Browser bundles:

```
...

$bundles[] = new Netgen\Bundle\LayoutsSyliusBundle\NetgenLayoutsSyliusBundle();
$bundles[] = new Netgen\Bundle\ContentBrowserSyliusBundle\NetgenContentBrowserSyliusBundle();

return $bundles;
```

Configure the main layout and design
------------------------------------

Due to how Netgen Layouts works, your main layout template needs to wrap the
`content` block inside a new `layout` block:

```
{% block layout %}
    {% block content %}
    {% endblock %}
{% endblock %}

```

All full view templates (those that are rendered directly by controllers), need
to extend `ngbm.layoutTemplate` instead of your original layout:

```
{% extends ngbm.layoutTemplate %}

{% block content %}

    ...

{% endblock %}
```

This allows Netgen Layouts to inject a layout resolved for the request into
your page. Since you configured all your full views to now use Netgen Layouts,
they will not fallback to your main layout template. Because of that, you need
to configure Netgen Layouts with your main layout template, so the fallback
keeps working as it should.

Add the following to your `app/config/config.yml` file to configure the layout
and specify the default design for block and layout templates:

```
netgen_layouts:
    pagelayout: '@MyShop/layout.html.twig'
    design: sylius
```

Activate ESI and fragments support
----------------------------------

Netgen Layouts requires that ESI and fragments support is activated in Symfony.
Add the following to your `app/config/config.yml` file:

```
framework:
    esi: ~
    fragments: ~
```

Update security rules for admin UI integration
----------------------------------------------

To properly integrate Netgen Layouts and Sylius admin interfaces, you need to
update admin regex parameter in your `app/config/security.yml` file to include
Netgen Layouts admin routes:

```
parameters:
    sylius.security.admin_regex: "^(/admin|/bm/app|/bm/api|/bm/admin|/cb)"
```
