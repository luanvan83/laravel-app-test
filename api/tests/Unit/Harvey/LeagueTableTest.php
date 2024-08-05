<?php

namespace Tests\Unit\Harvey;

use Tests\TestCase;

class LeagueTableTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        dd(LeagueTable::isPalindrome('Noel sees Leon.'));
        $table = new LeagueTable(array('Mike', 'Chris', 'Arnold'));
        $table->recordResult('Mike', 2);
        $table->recordResult('Mike', 3);
        $table->recordResult('Arnold', 5);
        $table->recordResult('Chris', 5);
        $player = $table->playerRank(1);
        $expect = 'Chris';
        $this->assertEquals($expect, $player);

        $table->recordResult('Mike', 1);
        $player = $table->playerRank(1);
        $expect = 'Mike';
        $this->assertEquals($expect, $player);

        $table->recordResult('Arnold', 1);
        $player = $table->playerRank(1);
        $expect = 'Arnold';
        $this->assertEquals($expect, $player);
    }
}

class LeagueTable
{
    protected $standings;
    public function __construct(array $players)
    {
        $this->standings = [];
        foreach($players as $index => $p) {
            $this->standings[$p] = [
                'index'        => $index,
                'games_played' => 0,
                'score'        => 0
            ];
        }
    }

    public function recordResult(string $player, int $score) : void
    {
        $this->standings[$player]['games_played']++;
        $this->standings[$player]['score'] += $score;
    }

    public function playerRank(int $rank) : string
    {
        $clonedStandings = $this->standings;
        usort($clonedStandings, function($a, $b) {
            return $b['score']   <=> $a['score']  //score desc
                ?: $a['games_played']   <=> $b['games_played']  //games_played asc
                ?: $a['index']  <=> $b['index']   //index asc
            ;
        });
        $listPlayers = array_keys($this->standings);
        $foundPlayer = $listPlayers[$clonedStandings[$rank - 1]['index']];
        return $foundPlayer;
    }

    public static function isPalindrome($str)
    {
        $newStr = str_replace(['.', ''], '', $str);
        $reveseStr = array_reverse(str_split($newStr));
        return $newStr == implode('', $reveseStr);
        //throw new Exception('Not implemented');
    }
}

