<?php

namespace DayRev\Summarizer\Tests\Integration;

/**
 * @see http://docs.mockery.io/en/latest/reference/phpunit_integration.html
 */
class TestCase extends \Mockery\Adapter\Phpunit\MockeryTestCase
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
