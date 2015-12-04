<?php

namespace Summarizer\Provider;

use Curl\Curl;
use Summarizer\Provider;

/**
 * Driver class that handles Smmry interactions.
 */
class Smmry extends Provider
{
    protected $api_key;
    protected $sentences;

    /**
     * Summarizes a given string of text.
     *
     * @param string $text The text to summarize.
     *
     * @return string
     */
    public function summarize($text)
    {
        $url  = 'http://api.smmry.com';
        $url .= '?' . http_build_query(array(
            'SM_API_KEY' => $this->api_key,
            'SM_LENGTH' => $this->sentences ?: 7,
        ));

        $request = new Curl();
        $request->post($url, array(
            'sm_api_input' => $text,
        ));

        $response = $request->response;

        return !empty($response->sm_api_content) ? $response->sm_api_content : $text;
    }
}
