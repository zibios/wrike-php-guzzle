Wrike PHP GUZZLE - Wrike API V3 & V4
====================================

Introduction
------------

**This is HTTP Client plugin for [Wrike PHP Library](https://github.com/zibios/wrike-php-library).**

* For general purpose please check [full configured Wrike PHP SDK](https://github.com/zibios/wrike-php-sdk).
* For Symfony Framework please check full configured [Wrike bundle](https://github.com/zibios/wrike-bundle).
* For none standard purposes please check [generic Wrike PHP Library](https://github.com/zibios/wrike-php-library).

Versions
--------
| Major Version                                              | Wrike API | PHP Compatibility                  | Initial release | Support                        |
|:----------------------------------------------------------:|:---------:|:----------------------------------:|:---------------:|:------------------------------:|
| [V3](https://github.com/zibios/wrike-php-guzzle/tree/v3.x) | V4        | PHP 7.1, PHP 7.2, TBD              | October, 2018   | TBD                            |
| [V2](https://github.com/zibios/wrike-php-guzzle/tree/v2.x) | V4        | PHP 5.5, PHP 5.6, PHP 7.0, PHP 7.1 | October, 2018   | Support ends on October, 2019  |
| [V1](https://github.com/zibios/wrike-php-guzzle/tree/v1.x) | V3        | PHP 5.5, PHP 5.6, PHP 7.0, PHP 7.1 | February, 2018  | Support ends on February, 2019 |

Project status
--------------

**General**

[![Packagist License](https://img.shields.io/packagist/l/zibios/wrike-php-guzzle.svg)](https://packagist.org/packages/zibios/wrike-php-guzzle)
[![Packagist Downloads](https://img.shields.io/packagist/dt/zibios/wrike-php-guzzle.svg)](https://packagist.org/packages/zibios/wrike-php-guzzle)
[![Packagist Version](https://img.shields.io/packagist/v/zibios/wrike-php-guzzle.svg)](https://packagist.org/packages/zibios/wrike-php-guzzle)
[![Packagist Version](https://img.shields.io/packagist/php-v/zibios/wrike-php-guzzle.svg)](https://packagist.org/packages/zibios/wrike-php-guzzle)
[![Libraries.io](https://img.shields.io/librariesio/github/zibios/wrike-php-guzzle.svg)](https://libraries.io/packagist/zibios%2Fwrike-php-guzzle)

[![CII Best Practices](https://bestpractices.coreinfrastructure.org/projects/1691/badge)](https://bestpractices.coreinfrastructure.org/projects/1691)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/8a8a49af-f1a6-40c9-97c6-dda145e8a75c/mini.png)](https://insight.sensiolabs.com/projects/8a8a49af-f1a6-40c9-97c6-dda145e8a75c)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/1b24d23368ad4971a0fbf47ed0457e86)](https://www.codacy.com/app/zibios/wrike-php-guzzle)
[![Code Climate Maintainability](https://api.codeclimate.com/v1/badges/8cb3af3ee1c8b8b2eb4f/maintainability)](https://codeclimate.com/github/zibios/wrike-php-guzzle/maintainability)

**Branch 'v3.x'**

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/zibios/wrike-php-guzzle/badges/quality-score.png?b=v3.x)](https://scrutinizer-ci.com/g/zibios/wrike-php-guzzle/?branch=v3.x)
[![Scrutinizer Build Status](https://scrutinizer-ci.com/g/zibios/wrike-php-guzzle/badges/build.png?b=v3.x)](https://scrutinizer-ci.com/g/zibios/wrike-php-guzzle/build-status/v3.x)
[![Scrutinizer Code Coverage](https://scrutinizer-ci.com/g/zibios/wrike-php-guzzle/badges/coverage.png?b=v3.x)](https://scrutinizer-ci.com/g/zibios/wrike-php-guzzle/?branch=v3.x)
[![Travis Build Status](https://travis-ci.org/zibios/wrike-php-guzzle.svg?branch=v3.x)](https://travis-ci.org/zibios/wrike-php-guzzle)
[![StyleCI](https://styleci.io/repos/81218835/shield?branch=v3.x)](https://styleci.io/repos/81218835)
[![Coverage Status](https://coveralls.io/repos/github/zibios/wrike-php-guzzle/badge.svg?branch=v3.x)](https://coveralls.io/github/zibios/wrike-php-guzzle?branch=v3.x)

Installation
------------
Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require zibios/wrike-php-guzzle "^3.0"
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Contribution
------------
To try it yourself clone the repository:

```bash
git clone git@github.com:zibios/wrike-php-guzzle.git
cd wrike-php-guzzle
```

and install dependencies with composer:

```bash
composer install
```

Run PHPUnit tests:

```bash
./vendor/bin/phpunit
``` 

Usage
------------

```php
/**
 * Standard usage
 */
$client = ClientFactory::create();

/**
 * @see \Zibios\WrikePhpLibrary\Enum\Api\ResponseFormatEnum
 *
 * @return string 'PsrResponse'
 */
$client->getResponseFormat();

/**
 * @param string $requestMethod GET/POST/PUT/DELETE/UPLOAD
 * @param string $path          full path to REST resource without domain, ex. 'accounts/XXXXXXXX/contacts'
 * @param array  $params        optional params for GET/POST request
 * @param string $accessToken   Access Token for Wrike access
 *
 * @see \Zibios\WrikePhpLibrary\Enum\Api\RequestMethodEnum
 * @see \Zibios\WrikePhpLibrary\Enum\Api\RequestPathFormatEnum
 *
 * @return string|\Psr\Http\Message\ResponseInterface
 */
$client->executeRequestForParams($requestMethod, $path, array $params, $accessToken);

// + all methods from \GuzzleHttp\Client
```

Reference
---------

**Internal**

Generic [Wrike PHP Library](https://github.com/zibios/wrike-php-library)

Full configured [Wrike PHP SDK](https://github.com/zibios/wrike-php-sdk)

Full configured [Symfony bundle](https://github.com/zibios/wrike-bundle) based on Wrike PHP SDK

[Response transformer plugin](https://github.com/zibios/wrike-php-jmsserializer) for Wrike PHP Library

**External**

Official [Wrike API Documentation](https://developers.wrike.com/documentation/api/overview)

[PSR Naming Conventions](http://www.php-fig.org/bylaws/psr-naming-conventions/)

License
-------

This bundle is available under the [MIT license](LICENSE).
