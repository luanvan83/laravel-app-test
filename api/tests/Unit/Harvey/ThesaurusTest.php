<?php

namespace Tests\Unit\Harvey;

use Tests\TestCase;

class ThesaurusTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $thesaurus = new Thesaurus(
            [
                "buy" => array("purchase"),
                "big" => array("great", "large")
            ]
        );
        
        $resultBig = $thesaurus->getSynonyms("big");
        $expectBig = json_encode([
            "word"     => "big",
            "synonyms" => ["great", "large"]
        ]);
        $this->assertEquals($expectBig, $resultBig);

        $resultAgeLast = $thesaurus->getSynonyms("agelast");
        $expectAgeLast = json_encode([
            "word"     => "agelast",
            "synonyms" => []
        ]);
        $this->assertEquals($expectAgeLast, $resultAgeLast);

        $resultNull = $thesaurus->getSynonyms('');
        $expectNull = json_encode([
            "word"     => '',
            "synonyms" => []
        ]);
        $this->assertEquals($expectNull, $resultNull);
    }
}

class Thesaurus
{
    private $thesaurus;

    function __construct(array $thesaurus)
    {
        $this->thesaurus = $thesaurus;
    }

    public function getSynonyms(string $word) : string
    {
        $response = [
            'word' => $word,
            'synonyms' => [],
        ];
        if (array_key_exists($word, $this->thesaurus)) {
            $response['synonyms'] = $this->thesaurus[$word];
        }
        return json_encode($response);
    }
}
