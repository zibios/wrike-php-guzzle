Wrike PHP GUZZLE
================================

Introduction
------------

**This is HTTP Client plugin for [Wrike PHP Library](https://github.com/zibios/wrike-php-library).**

For general purpose please check [full configured Wrike PHP SDK](https://github.com/zibios/wrike-php-sdk).
For Symfony2 / Symfony3 please check full configured [Wrike bundle](https://github.com/zibios/wrike-bundle).
For none standard purposes please check [generic Wrike PHP Library](https://github.com/zibios/wrike-php-library).

Project status
--------------

[![Packagist License](https://img.shields.io/packagist/l/zibios/wrike-php-guzzle.svg)](https://packagist.org/packages/zibios/wrike-php-guzzle)
[![Packagist Downloads](https://img.shields.io/packagist/dt/zibios/wrike-php-guzzle.svg)](https://packagist.org/packages/zibios/wrike-php-guzzle)
[![Packagist Version](https://img.shields.io/packagist/v/zibios/wrike-php-guzzle.svg)](https://packagist.org/packages/zibios/wrike-php-guzzle)

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/zibios/wrike-php-guzzle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/zibios/wrike-php-guzzle/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/8a8a49af-f1a6-40c9-97c6-dda145e8a75c/mini.png)](https://insight.sensiolabs.com/projects/8a8a49af-f1a6-40c9-97c6-dda145e8a75c)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/1b24d23368ad4971a0fbf47ed0457e86)](https://www.codacy.com/app/zibios/wrike-php-guzzle)
[![Test Coverage](https://codeclimate.com/github/zibios/wrike-php-guzzle/badges/coverage.svg)](https://codeclimate.com/github/zibios/wrike-php-guzzle/coverage)
[![Build Status](https://travis-ci.org/zibios/wrike-php-guzzle.svg?branch=master)](https://travis-ci.org/zibios/wrike-php-guzzle)
[![Libraries.io](https://img.shields.io/librariesio/github/zibios/wrike-php-guzzle.svg)](https://libraries.io/packagist/zibios%2Fwrike-php-guzzle)

[All badges](docs/Badges.md)

Installation
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

[Wrike PHP Library](https://github.com/zibios/wrike-php-library)

[Wrike PHP SDK](https://github.com/zibios/wrike-php-sdk)

[Symfony bundle](https://github.com/zibios/wrike-bundle)

License
-------

This bundle is available under the [MIT license](LICENSE).
