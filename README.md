# Educa Federation Client

[![Latest Version on Packagist](https://img.shields.io/packagist/v/teamnovu/educa-federation-client.svg?style=flat-square)](https://packagist.org/packages/teamnovu/educa-federation-client)
[![Build Status](https://img.shields.io/travis/teamnovu/educa-federation-client/master.svg?style=flat-square)](https://travis-ci.org/teamnovu/educa-federation-client)

Official client for service providers connecting to https://federation.educa.ch/.

## Example Implementation

You can find a example Project at https://github.com/teamnovu/educa-federation-client-example.

## Installation

You can install the package via composer:

```bash
composer require teamnovu/educa-federation-client
```

## Configuration

Before you can reveive SAMLResponse from the federation, you must generate a x509 certificate and a corresponding private key. Consult the table after the example to get more information about the config keys.

```php
$config = [
    'x509cert' => '',
    'privateKey' => '',
    'entityId' => 'http://localhost/saml/sp/metadata',
    'assertionConsumerService' => [
        'url' => 'http://localhost/saml/acs',
    ],
    'contactPerson' => [
        'technical' => [
            'givenName' => 'Your Name',
            'emailAddress' => 'your@email.com',
        ],
        'support' => [
            'givenName' => 'Your Name',
            'emailAddress' => 'your@email.com',
        ],
    ],
];
```

| Name                                 | Description                                                                                                                                                                              | Example                                                                                                                                                                                       |
| ------------------------------------ | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| x509cert                             | Contains certificate of service providers in x509 format without headers                                                                                                                 | MIIFdDCCA1ygAwIBAgIUPNptL10Zxoxj/AJLnVVpc2oA0KIwDQYJKoZIhvcNAQEF BQAwczELMAkGA1UEBgwCQ0gxDTALBgNVBAgMBEJlcm4xDTALBgNVBAcMBFRodW4x GDAWBgNVBAoMD0V4YW1wbGUgQ29tcGFueTEWMBQGA1UECwwNSVQgRGVw... |
| privateKey                           | Contains certificate's private key of service provider                                                                                                                                   | -----BEGIN RSA PRIVATE KEY----- MIIJKQIBAAKCAgEAxT4Lt3bww5lsdEIk4WVcQ8LqTmK+k0kV8g/6SRi1lhr1TJ/u DZILFoCFUHuuqN9Vlh...                                                                        |
| entityId                             | Usually this is the URL to the metadata of the service provider. Can be any valid URI.                                                                                                   | http://awesome-sp.ch/saml/sp/metadata                                                                                                                                                         |
| assertionConsumerService.url         | Url to which the SAMLResponse will be sent as HTTP-POST binding. Under this url you should process the response with this package. **This URL needs to be configured by the federation** | http://awesome-sp.ch/saml/sp/acs                                                                                                                                                              |
| contactPerson.technical.givenName    | Name of your technical contact                                                                                                                                                           | Hans Muster                                                                                                                                                                                   |
| contactPerson.technical.emailAddress | E-Mail of your technical contact                                                                                                                                                         | hans@muster.com                                                                                                                                                                               |
| contactPerson.support.givenName      | Name of your support contact                                                                                                                                                             | Hans Muster                                                                                                                                                                                   |
| contactPerson.support.emailAddress   | Name of your support contact                                                                                                                                                             | hans@muster.com                                                                                                                                                                               |

## Usage

### Process SAMLResponse

You can use this package to process a SAMLResponse returned by the federation.

```php

require '../vendor/autoload.php';

use  Teamnovu\SamlClient\Saml2AuthFactory;

$config = [ /* your config */ ];
$client = Saml2AuthFactory::forServiceProvider($config);
try {
    $client->acs();
} catch(\Exception $ex) {
    // handle error
}

$user = $client->getSaml2User();
$user->getNameId(); // unique identifier for the user
$user->getIntendedUrl(); // url originally passed as resource_name

```

### Generate Metadata

This package can generate the proper metadata xml code to be imported by the federation to enable the certain service provider (e.g. under a given route or to create a metadata.xml sent to the federation support).

```php

require '../vendor/autoload.php';

use  Teamnovu\SamlClient\Saml2AuthFactory;

$config = [ /* your config */ ];
$client = Saml2AuthFactory::forServiceProvider($config);
$metadata = $client->getMetadata();

echo $metadata;
```

## Generate Certificate and Key

### Openssl

To generate the needed data you can use the following command. **Please change the DN information to match your company**

    openssl req -newkey rsa:3072 -new -x509 -days 3652 -nodes -out sp.crt -keyout sp.key -subj "/C=CH/ST=Bern/L=Bern/O=Development/OU=IT Department/CN=awesome-sp.ch"

The command will generate a `sp.crt` and a `sp.key` file.

You now have the options to add the contents to the config or load them directly from the filesystem. **Don't forget to remove the header and footer from the certificate(`-----BEGIN CERTIFICATE-----` and `-----END CERTIFICATE-----`).**

### Package

TODO

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email kaufmann@novu.ch instead of using the issue tracker.

## Credits

-   [Oliver Kaufmann](https://github.com/teamnovu)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## PHP Package Boilerplate

This package was generated using the [PHP Package Boilerplate](https://laravelpackageboilerplate.com).
