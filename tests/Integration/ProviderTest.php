<?php

namespace DayRev\Summarizer\Tests\Integration;

use DayRev\Summarizer\Content;
use DayRev\Summarizer\Provider;
use Mockery;
use ReflectionProperty;
use stdClass;

class ProviderTest extends TestCase
{
    public function testDayrevSummarizesExpectedContent()
    {
        $provider = Provider::instance('dayrev', array('summary_length' => 4));
        $content = $provider->summarize($this->getTextToSummarize());

        $this->assertInstanceOf('DayRev\Summarizer\Content', $content);
        $this->assertEquals($this->getExpectedDayrevSummarizedContent()->text, $content->text);
    }

    public function testSmmrySummarizesExpectedContent()
    {
        $summarizer = Mockery::mock('DayRev\Smmry\SDK', array('api_key' => 'D74KLJ345UH9SHDF1', 'summary_length' => 4))
            ->shouldReceive('summarizeText')
            ->andReturn($this->getExpectedSmmrySummarizedContent())
            ->getMock();

        $provider = Provider::instance('smmry');
        $this->setProtectedProviderProperty($provider, 'summarizer', $summarizer);

        $content = $provider->summarize($this->getTextToSummarize());

        $this->assertInstanceOf('DayRev\Summarizer\Content', $content);
        $this->assertEquals($this->getExpectedSmmrySummarizedContent()->sm_api_content, $content->text);
    }

    protected function setProtectedProviderProperty(Provider $provider, string $property, $value)
    {
        $reflected_property = new ReflectionProperty(get_class($provider), $property);
        $reflected_property->setAccessible(true);
        $reflected_property->setValue($provider, $value);
    }

    protected function getExpectedDayrevSummarizedContent(): Content
    {
        $content = new Content();
        $content->text = file_get_contents(__DIR__ . '/../Data/summarized-text-dayrev.txt');

        return $content;
    }

    protected function getExpectedSmmrySummarizedContent(): stdClass
    {
        $summary = new stdClass();
        $summary->sm_api_content = file_get_contents(__DIR__ . '/../Data/summarized-text-smmry.txt');

        return $summary;
    }

    protected function getTextToSummarize(): string
    {
        return file_get_contents(__DIR__ . '/../Data/full-text.txt');
    }
}
