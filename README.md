## Overview

Summarizer provides an elegant interface to summarize text using a variety of third-party tools and services.

**Supported Providers:**

 * Smmry

##Usage

    $summarizer = DayRev\Summarizer\Provider::instance('smmry', array('api_key' => 'YOURKEYHERE'));
    $content = $summarizer->summarize($text);

## Tests
To run the test suite, install [composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx) and then run the following commands from the root directory:

```
composer install
vendor/bin/phpunit
```
