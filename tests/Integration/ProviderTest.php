<?php

namespace DayRev\Summarizer\Tests\Integration;

use DayRev\Summarizer\Content;
use DayRev\Summarizer\Provider;

class ProviderTest extends TestCase
{
    public function testDayrevSummarizesExpectedContent()
    {
        $provider = Provider::instance('dayrev', array('summary_length' => 4));
        $content = $provider->summarize($this->getTextToSummarize());

        $this->assertInstanceOf('DayRev\Summarizer\Content', $content);
        $this->assertEquals($this->getExpectedDayrevSummary()->text, $content->text);
    }

    public function testSmmrySummarizesExpectedContent()
    {
        $provider = Provider::instance(
            'smmry',
            array('api_key' => $this->config['smmry_api_key'], 'summary_length' => 4)
        );
        $content = $provider->summarize($this->getTextToSummarize());

        $this->assertInstanceOf('DayRev\Summarizer\Content', $content);
        $this->assertEquals($this->getExpectedSmmrySummary()->text, $content->text);
    }

    protected function getExpectedDayrevSummary(): Content
    {
        $summary = new Content();
        $summary->text = file_get_contents(__DIR__ . '/../Data/summarized-text-dayrev.txt');

        return $summary;
    }

    protected function getExpectedSmmrySummary(): Content
    {
        $summary = new Content();
        $summary->text = file_get_contents(__DIR__ . '/../Data/summarized-text-smmry.txt');

        return $summary;
    }

    protected function getTextToSummarize(): string
    {
        return file_get_contents(__DIR__ . '/../Data/full-text.txt');
    }
}
