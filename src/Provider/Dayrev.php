<?php

namespace DayRev\Summarizer\Provider;

use DayRev\Summarizer\Content;
use DayRev\Summarizer\Provider;
use Porter;

/**
 * Driver class that handles local summarization of text.
 *
 * @see https://github.com/aboustayyef/TextSummarizer
 */
class Dayrev extends Provider
{
    protected $sentences = [];

    /**
     * Summarizes a given string of text.
     *
     * @param string $text The text to summarize.
     *
     * @return Content
     */
    public function summarize(string $text): Content
    {
        $text = html_entity_decode(strip_tags($text), ENT_NOQUOTES);

        $this->extractSentences($text);
        $this->scoreSentences();

        $content = new Content();
        $content->text = implode(' ', $this->getTopScoringSentences());

        return $content;
    }

    /**
     * Extracts cleaned sentences from a given string of text.
     *
     * @param string $text The text to extract sentences from.
     *
     * @return void
     */
    protected function extractSentences(string $text)
    {
        // Returns a collection of key phrases - split by punctuation delimiters into sentences.
        $pattern =  '/(?<=[.?!;])[^0-9A-Za-z]+/';
        $sentences = preg_split($pattern, $text);
        foreach ($sentences as $key => $sentence) {
            array_push(
                $this->sentences,
                ['sentence' => trim($this->removeWhitespace($sentence)), 'order' => $key, 'score' => 0]
            );
        }
    }

    /**
     * Scores sentences based on word occurrences.
     *
     * @return void
     */
    protected function scoreSentences()
    {
        foreach ($this->sentences as $key1 => $sentence1) {
            $score = 0;
            foreach ($this->sentences as $key2 => $sentence2) {
                if ($sentence1['sentence'] === $sentence2['sentence']) {
                    continue;
                }

                $score += $this->compare($sentence1['sentence'], $sentence2['sentence']);
            }

            $this->sentences[$key1]['score'] = $score;
        }
    }

    /**
     * Cleans a given string of text.
     *
     * @param string $text The text to clean.
     *
     * @return string
     */
    protected function removeWhitespace(string $text): string
    {
        return preg_replace("#\\s+#um", ' ', $text);
    }

    /**
     * Splits a sentences into an array of word tokens.
     *
     * @param string $sentence The text to tokenize.
     *
     * @return array
     */
    protected function splitSentenceIntoWords(string $sentence): array
    {
        $raw = preg_split('#\s+#', $sentence);

        $result = [];
        foreach ($raw as $key => $word) {
            if (strlen(trim($word)) > 0) {
                $result[] = Porter::Stem($word);
            }
        }

        return $result;
    }

    /**
     * Compares two sentences.
     * Uses a modified version of the Jaccard Coefficient (dividing by the square root).
     *
     * @param string $sentence1 The first sentence to compare.
     * @param string $sentence2 The second sentence to compare.
     *
     * @return float
     */
    protected function compare(string $sentence1, string $sentence2): float
    {
        $words1 = $this->splitSentenceIntoWords(strtolower($sentence1));
        $words2 = $this->splitSentenceIntoWords(strtolower($sentence2));

        $union = array_unique(array_intersect($words1, $words2));
        $combination = array_unique(array_merge($words1, $words2));

        if ((count($words1) < 3) || (count($words2) < 3)) {
            // Ignore sentences that are too short.
            return 0.0;
        }

        $jaccard = count($union) / sqrt(count($combination));

        return $jaccard;
    }

    /**
     * Gets the top scoring sentences.
     *
     * @return array
     */
    protected function getTopScoringSentences(): array
    {
        $scored = [];
        foreach ($this->sentences as $key => $sentence) {
            $scored[$sentence['sentence']] = $sentence['score'];
        }

        // Sort sentences by strength.
        asort($scored);
        $scored = array_reverse($scored);
        $topscoring = array_slice($scored, 0, $this->summary_length);

        // Sort sentences by order of occurrence.
        $ordered = [];
        foreach ($topscoring as $phrase => $score) {
            foreach ($this->sentences as $key => $sentence) {
                if ($phrase == $sentence['sentence']) {
                    $ordered[$phrase] = $sentence['order'];
                    continue;
                }
            }
        }

        asort($ordered);

        return array_keys($ordered);
    }
}
