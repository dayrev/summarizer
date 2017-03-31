<?php

namespace DayRev\Summarizer\Tests\Integration;

class TestCase extends \PHPUnit_Framework_TestCase
{
    protected $config = [];

    public function __construct()
    {
        $this->parseParams();
    }

    protected function parseParams()
    {
        $params = $_SERVER['argv'];
        foreach ($params as $index => $param) {
            if ($param != '-d') {
                continue;
            }

            if (!$config = $params[$index + 1] ?? false) {
                continue;
            }

            if (strpos($config, '=') === false) {
                continue;
            }

            list($key, $value) = explode('=', $config);

            $this->config[$key] = $value;
        }
    }
}
