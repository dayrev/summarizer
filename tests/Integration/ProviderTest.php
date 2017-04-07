<?php

namespace DayRev\Summarizer\Tests\Integration;

use DayRev\Summarizer\Content;
use DayRev\Summarizer\Provider;

class ProviderTest extends TestCase
{
    public function testDayrevSummarizesExpectedContent()
    {
        $provider = Provider::instance('dayrev', ['summary_length' => 4]);
        $content = $provider->summarize($this->getTextToSummarize());

        $this->assertInstanceOf(Content::class, $content);
        $this->assertEquals($this->getExpectedDayrevSummary()->text, $content->text);
    }

    public function testSmmrySummarizesExpectedContent()
    {
        $provider = Provider::instance(
            'smmry',
            ['api_key' => $this->config['smmry_api_key'], 'summary_length' => 4]
        );
        $content = $provider->summarize($this->getTextToSummarize());

        $this->assertInstanceOf(Content::class, $content);
        $this->assertEquals($this->getExpectedSmmrySummary()->text, $content->text);
    }

    protected function getDataFileContents(string $filename): string
    {
        return file_get_contents(__DIR__ . '/../Data/' . $filename);
    }

    protected function getExpectedDayrevSummary(): Content
    {
        $summary = new Content();
        $summary->text = $this->getDataFileContents('text-summary-dayrev.txt');

        return $summary;
    }

    protected function getExpectedSmmrySummary(): Content
    {
        $summary = new Content();
        $summary->text = $this->getDataFileContents('text-summary-smmry.txt');;

        return $summary;
    }

    protected function getTextToSummarize(): string
    {
        return $this->getDataFileContents('text-extract.txt');
    }
}
