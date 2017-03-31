<?php

namespace DayRev\Summarizer\Provider;

use DayRev\Smmry\SDK as SmmrySDK;
use DayRev\Summarizer\Content;
use DayRev\Summarizer\Provider;

/**
 * Driver class that handles Smmry interactions.
 */
class Smmry extends Provider
{
    protected $api_key;
    protected $summarizer;

    /**
     * Initializes the class.
     *
     * @param array $data Key value data to populate object properties.
     *
     * @return void
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);

        $this->summarizer = new SmmrySDK($data);
    }

    /**
     * Summarizes a given string of text.
     *
     * @param string $text The text to summarize.
     *
     * @return Content
     */
    public function summarize(string $text): Content
    {
        $response = $this->summarizer->summarizeText($text);

        $content = new Content();
        $content->text = !empty($response->sm_api_content) ? trim($response->sm_api_content) : $text;

        return $content;
    }
}
