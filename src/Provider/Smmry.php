<?php

namespace DayRev\Summarizer\Provider;

use Curl\Curl;
use DayRev\Summarizer\Content;
use DayRev\Summarizer\Provider;

/**
 * Driver class that handles Smmry interactions.
 */
class Smmry extends Provider
{
    protected $api_key;
    protected $length = 7; // Sentences

    /**
     * Summarizes a given string of text.
     *
     * @param string $text The text to summarize.
     *
     * @return Content
     */
    public function summarize(string $text) : Content
    {
        $url  = 'http://api.smmry.com';
        $url .= '?' . http_build_query(array(
            'SM_API_KEY' => $this->api_key,
            'SM_LENGTH' => $this->length,
        ));

        $request = new Curl();
        $request->post($url, array(
            'sm_api_input' => $text,
        ));

        $response = $request->response;

        $content = new Content();
        $content->text = !empty($response->sm_api_content) ? $response->sm_api_content : $text;

        return $content;
    }
}
