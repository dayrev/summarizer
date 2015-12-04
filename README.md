## Overview

Summarizer provides an elegant interface to summarize text using a variety of third-party tools and services.

**Supported Providers:**

 * Smmry

##Usage

    $summarizer = Summarizer\Provider::instance('smmry', array('api_key' => 'YOURKEYHERE'));
    $summary = $summarizer->summarize($text);
