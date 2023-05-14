# polaris-bank-api-client

[![Latest Stable Version](https://img.shields.io/github/v/release/brokeyourbike/polaris-bank-api-client-php)](https://github.com/brokeyourbike/polaris-bank-api-client-php/releases)
[![Total Downloads](https://poser.pugx.org/brokeyourbike/polaris-bank-api-client/downloads)](https://packagist.org/packages/brokeyourbike/polaris-bank-api-client)
[![Maintainability](https://api.codeclimate.com/v1/badges/41d6114333d868a1af66/maintainability)](https://codeclimate.com/github/brokeyourbike/polaris-bank-api-client-php/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/41d6114333d868a1af66/test_coverage)](https://codeclimate.com/github/brokeyourbike/polaris-bank-api-client-php/test_coverage)

Polaris Bank API Client for PHP

## Installation

```bash
composer require brokeyourbike/polaris-bank-api-client
```

## Usage

```php
use BrokeYourBike\PolarisBank\Client;
use BrokeYourBike\PolarisBank\Interfaces\ConfigInterface;

assert($config instanceof ConfigInterface);
assert($httpClient instanceof \GuzzleHttp\ClientInterface);

$apiClient = new Client($config, $httpClient);
$apiClient->disburseUSD();
```

## Authors
- [Ivan Stasiuk](https://github.com/brokeyourbike) | [Twitter](https://twitter.com/brokeyourbike) | [LinkedIn](https://www.linkedin.com/in/brokeyourbike) | [stasi.uk](https://stasi.uk)

## License
[Mozilla Public License v2.0](https://github.com/brokeyourbike/polaris-bank-api-client-php/blob/main/LICENSE)
