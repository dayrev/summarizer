<?php

namespace DayRev\Summarizer;

/**
 * Adapter class that handles Summarizer provider interactions.
 */
abstract class Provider
{
    /**
     * The max length of the summarized text (in sentences).
     *
     * @var int
     */
    protected $summary_length = 7;

    /**
     * Gets an instance of the given provider.
     *
     * @param string $provider The name of the provider to instantiate.
     * @param array $data Optional provider data.
     *
     * @return Provider|bool
     */
    public static function instance(string $provider, array $data = [])
    {
        $class = __NAMESPACE__ . '\\Provider\\' . ucfirst($provider);
        if (!class_exists($class)) {
            return false;
        }

        return new $class($data);
    }

    /**
     * Initializes the class.
     *
     * @param array $data Key value data to populate object properties.
     *
     * @return void
     */
    public function __construct(array $data = [])
    {
        $this->loadData($data);
    }

    /**
     * Attempts to map array data to object properties.
     *
     * @param array $data Key value data to populate object properties.
     *
     * @return void
     */
    protected function loadData(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Summarizes a given string of text.
     *
     * @param string $text The text to summarize.
     *
     * @return Content
     */
    abstract public function summarize(string $text): Content;
}
