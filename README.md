OpenTransBundle
================


[![Latest Stable Version](https://poser.pugx.org/se/opentrans-bundle/v/stable.png)](https://packagist.org/packages/se/opentrans-bundle)

This bundle integrates the [opentrans] (https://github.com/sveneisenschmidt/opentrans) library into Symfony2.


#### Dev branch is master branch.

[![Build Status](https://api.travis-ci.org/sveneisenschmidt/opentrans-bundle.png?branch=master)](https://travis-ci.org/svenseisenschmidt/opentrans-bundle)


##### Table of Contents

[Installation](#installation)

[Configuration](#configuration)

[Usage](#usage)

[Tests](#tests)

<a name="installation"></a>
## Installation

The recommended way to install is through [Composer](http://getcomposer.org).

```json
{
    "require": {
        "se/opentrans-bundle": "dev-master"
    }
}
```

Add the bundle to your AppKernel.php file:

```php
// in AppKernel::registerBundles()
$bundles = array(
    // ...
    new SE\Bundle\OpenTransBundle\SEOpenTransBundle(),
    // ...
);
```

## Configuration

You can declare default documents in your app configuration.
(i.e. app/config/config.yml)

```yaml
se_open_trans:

  documents:

    my_default_order_document:
      type: order
      document:
        header:
          control_info:
            generator_info: "My Order Document"

    my_default_order_document_2:
      type: order
      document:
        header:
          control_info:
            generator_info: "My Order Document 2"
          order_info:
            custom_key: custom_var
            my_shop_id: Magento_1702
```

<a name="usage"></a>
## Usage

Your configured document builder is available as a member in `se.opentrans.document_builder_manager` service.
Retrieve it by calling `$manager->getDocumentBuilder($documentName)`.

```php
$manager = $container->get('se.opentrans.document_builder_manager');
$builder = $manager->getDocumentBuilder('my_default_order_document');
$document = $builder->getDocument();
```

The document builder is created as a service aswell. So instead calling the document builder manager you can
directly load the document builder from the container. The name is consisting of the base key `se.opentrans.document_builder.`
plus the `se_open_trans.documents` key from your configuration. (i.e. `my_default_order_document`)


```php
$builder = $container->get('se.opentrans.document_builder.my_default_order_document');
$document = $builder->getDocument();
```

<a name="tests"></a>
### Run tests
``` bash
$> vendor/bin/phpunit
```





