<?php

namespace Summarizer\Provider;

use Porter;
use Summarizer\Provider;

/**
 * Driver class that handles local summarization of text.
 *
 * @see https://github.com/aboustayyef/TextSummarizer
 */
class Dayrev extends Provider
{
    protected $sentences = [];
    public $length = 7; // Sentences

    /**
     * Summarizes a given string of text.
     *
     * @param string $text The text to summarize.
     *
     * @return string
     */
    public function summarize($text)
    {
        $text = strip_tags($text);
        $text = html_entity_decode($text, ENT_NOQUOTES);
        $this->extractSentences($text);
        $this->scoreSentences();

        $paragraph = '';
        $index = 0;
        foreach ($this->getTopScoringSentences() as $sentence => $value) {
            $paragraph .= $sentence . ' ';
            $index += 1;

            if ($index == $this->length) {
                return rtrim($paragraph);
            }
        }
    }

    /**
     * Extracts cleaned sentences from a given string of text.
     *
     * @param string $text The text to extract sentences from.
     *
     * @return void
     */
    protected function extractSentences($text)
    {
        // Returns a collection of key phrases - split by punctuation delimiters into sentences.
        $pattern =  '/(?<=[.?!;])[^0-9A-Za-z]+/';
        $sentences = preg_split($pattern, $text);
        foreach ($sentences as $key => $sentence) {
            array_push(
                $this->sentences,
                array('sentence' => trim($this->removeWhitespace($sentence)), 'order' => $key, 'score' => 0)
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
    protected function removeWhitespace($text)
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
    protected function splitSentenceIntoWords($sentence)
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
    protected function compare($sentence1, $sentence2)
    {
        $words1 = $this->splitSentenceIntoWords(strtolower($sentence1));
        $words2 = $this->splitSentenceIntoWords(strtolower($sentence2));

        $union = array_unique(array_intersect($words1, $words2));
        $combination = array_unique(array_merge($words1, $words2));

        if ((count($words1) < 3) || (count($words2) < 3)) {
            // Ignore sentences that are too short.
            return 0;
        }

        $jaccard = count($union) / sqrt(count($combination));

        return $jaccard;
    }

    /**
     * Gets the top scoring sentences.
     *
     * @param bool $sorted Whether to sort the sentences by score. 
     *
     * @return array
     */
    protected function getTopScoringSentences($sorted = true)
    {
        $scored = [];
        foreach ($this->sentences as $key => $sentence) {
            $scored[$sentence['sentence']] = $sentence['score'];
        }

        // Sort phrases by strength.
        asort($scored);
        $scored = array_reverse($scored);
        $topscoring = array_slice($scored, 0, $this->length);

        // Sort phrases by order of occurrence.
        $inorder = [];
        foreach ($topscoring as $phrase => $score) {
            foreach ($this->sentences as $key => $sentence) {
                if ($phrase == $sentence['sentence']) {
                    $inorder[$phrase] = $sentence['order'];
                    continue;
                }
            }
        }

        asort($inorder);
        $topscored_sorted = $inorder;
        if ($sorted) {
            return $topscored_sorted;
        } else {
            return $topscoring;
        }
    }
}
