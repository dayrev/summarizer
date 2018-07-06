# Summarizer
[![Build Status](https://travis-ci.org/dayrev/summarizer.svg?branch=master)](https://travis-ci.org/dayrev/summarizer)
[![Coverage Status](https://coveralls.io/repos/github/dayrev/summarizer/badge.svg?branch=master)](https://coveralls.io/github/dayrev/summarizer?branch=master)
[![Latest Stable Version](https://poser.pugx.org/dayrev/summarizer/v/stable.png)](https://packagist.org/packages/dayrev/summarizer)

## Overview

Summarizer provides an elegant interface to summarize text using a variety of third-party providers.

**Supported Providers:**

 * DayRev (local)
 * [Smmry](https://github.com/dayrev/smmry-sdk-php)

## Installation
Run the following [composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx) command to add the package to your project:

```
composer require dayrev/summarizer
```

Alternatively, add `"dayrev/summarizer": "^1.0"` to your composer.json file.

## Usage
```php
$summarizer = DayRev\Summarizer\Provider::instance('smmry', ['api_key' => 'YOURKEYHERE']);
$content = $summarizer->summarize($text);
```

## Tests
To run the test suite, run the following commands from the root directory:

```
composer install
vendor/bin/phpunit -d smmry_api_key=YOUR_SMMRY_API_KEY
```

> **Note:** A valid Smmry API key is required when running the integration tests.
