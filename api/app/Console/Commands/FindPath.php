<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class FindPath extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:find-path {from} {to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find path {from} city {to} city';

    protected $lPassedCities = [];

    public $lPaths = [
        [
            'from' => 'Acoding',
            'to' => 'Atheport',
            'travel_cost' => 20,
            'transit_cost' => 0
        ],
        [
            'from' => 'Acoding',
            'to' => 'Padiff',
            'travel_cost' => 90,
            'transit_cost' => 0
        ],
        [
            'from' => 'Acoding',
            'to' => 'Panta',
            'travel_cost' => 20,
            'transit_cost' => 0
        ],
        [
            'from' => 'Andburn',
            'to' => 'Quark',
            'travel_cost' => 30,
            'transit_cost' => 0
        ],
        [
            'from' => 'Andburn',
            'to' => 'Wregate',
            'travel_cost' => 30,
            'transit_cost' => 0
        ],
        [
            'from' => 'Atheport',
            'to' => 'Kaville',
            'travel_cost' => 85,
            'transit_cost' => 0
        ],
        [
            'from' => 'Atheport',
            'to' => 'Quark',
            'travel_cost' => 50,
            'transit_cost' => 0
        ],
        [
            'from' => 'Atheport',
            'to' => 'Ziver',
            'travel_cost' => 20,
            'transit_cost' => 0
        ],
        [
            'from' => 'Equosa',
            'to' => 'Padiff',
            'travel_cost' => 40,
            'transit_cost' => 0
        ],
        [
            'from' => 'Equosa',
            'to' => 'Tempolis',
            'travel_cost' => 20,
            'transit_cost' => 0
        ],
        [
            'from' => 'Equosa',
            'to' => 'Wregate',
            'travel_cost' => 80,
            'transit_cost' => 0
        ],
        [
            'from' => 'Inasnard',
            'to' => 'Kaville',
            'travel_cost' => 10,
            'transit_cost' => 0
        ],
        [
            'from' => 'Inasnard',
            'to' => 'Qrosa',
            'travel_cost' => 50,
            'transit_cost' => 0
        ],
        [
            'from' => 'Kaville',
            'to' => 'Qrosa',
            'travel_cost' => 40,
            'transit_cost' => 0
        ],
        [
            'from' => 'Padiff',
            'to' => 'Quark',
            'travel_cost' => 90,
            'transit_cost' => 0
        ],
        [
            'from' => 'Padiff',
            'to' => 'Tempolis',
            'travel_cost' => 30,
            'transit_cost' => 0
        ],
        [
            'from' => 'Padiff',
            'to' => 'Vrogow',
            'travel_cost' => 30,
            'transit_cost' => 0
        ],
        [
            'from' => 'Panta',
            'to' => 'Vrogow',
            'travel_cost' => 30,
            'transit_cost' => 0
        ],
        [
            'from' => 'Quarc',
            'to' => 'Wregate',
            'travel_cost' => 80,
            'transit_cost' => 0
        ],
        [
            'from' => 'Qrosa',
            'to' => 'Ziver',
            'travel_cost' => 30,
            'transit_cost' => 0
        ],
        [
            'from' => 'Tempolis',
            'to' => 'Wregate',
            'travel_cost' => 40,
            'transit_cost' => 0
        ]
    ];

    public function __construct()
    {
        parent::__construct();
        // convert to collection
        $this->lPaths = collect($this->lPaths);
        $this->lPassedCities = collect([]);
    }

    protected function newNode($name, $cost = 0, $via = null, $passed = false)
    {
        return [
            'name' => $name,
            'cost' => $cost,
            'via'  => $via,
            'passed' => $passed
        ];
    }

    protected function getCalculatedNotPassedNodes($name)
    {
        return $this->lPassedCities
                ->where('name', $name)
                ->where('passed', false);
    }

    protected function calcPathCostFor($name)
    {
        Log::info('Cal for '.$name);
        $passedName = '';
        $lPossilbePaths = $this->findPossiblePaths($name);
        $baseCost = 0;
        //if ($this->departure === $name) {
        if ($this->lPassedCities->count() === 0) {
            // root node
            $node = $this->newNode($name, 0, null, true);
            $this->lPassedCities->push($node);
            $passedName = $name;
        } else {
            // nodes already calculated before
            $nodes = $this->getCalculatedNotPassedNodes($name);
            if (count($nodes) > 0) {
                $key = array_key_first($nodes->toArray());
                $data = $nodes->first();
                $data['passed'] = true;
                $this->lPassedCities->put($key, $data);
                $baseCost = $data['cost'];
            }
        }

        foreach ($lPossilbePaths as &$path) {
            $neighbourName = ($path['from'] === $name) ? $path['to'] : $path['from'];
            $thisCost = $baseCost + $path['travel_cost'] + $path['transit_cost'];

            $nodes = $this->getCalculatedNotPassedNodes($neighbourName);
            if (count($nodes) > 0) {
                // the neighbour node already calculated before
                $data = $nodes->first();
                // If the cost was greater than this route, we update the route
                if ($data['cost'] > $thisCost) {
                    Log::info(
                        'Update cost for ', 
                        [
                            'node' => $neighbourName,
                            'via' => $name,
                            'old_cost' => $data['cost'],
                            'this_cost' => $thisCost
                        ]);
                    $data['cost'] = $thisCost;
                    $data['via'] = $name;
                    $key = array_key_first($nodes->toArray());
                    $this->lPassedCities->put($key, $data);
                }
            } else {
                // new node
                $newNode = $this->newNode($neighbourName, $thisCost, $name, false);
                $this->lPassedCities->push($newNode);
            }
        }
        return $passedName;
    }

    protected function findPossiblePaths($name) : Collection
    {
        $lFoundPaths = $this->lPaths->filter(function ($city) use ($name) {
            return $city['from'] == $name || $city['to'] == $name;
        });
        // exclude the REAL passed cities
        foreach ($lFoundPaths as $k => $path) {
            $lRealPasseds = $this->lPassedCities->filter(function ($p) use ($path) {
                return ($p['passed'] == true && ($path['from'] == $p['name'] || $path['to'] == $p['name']));
            });
            if (count($lRealPasseds) > 0) {
                unset($lFoundPaths[$k]);
            }
        }
        return $lFoundPaths;
    }

    protected function checkCity($name) : bool
    {
        $lFoundPaths = $this->findPossiblePaths($name);
        return count($lFoundPaths) > 0;
    }

    /**
     * Prompt for missing input arguments using the returned questions.
     *
     * @return array<string, string>
     */
    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'from' => 'Departure city',
            'to' => 'Destination city',
        ];
    }

    protected function getNextMinNode()
    {
        // Get nodes that already calc but not passed
        $lNotPasseds = $this->lPassedCities->where('passed', '=', false);
        $min = 0;
        $next = null;
        foreach ($lNotPasseds as $notPassed) {
            if ($next == null) {
                $next = $notPassed['name'];
                $min = $notPassed['cost'];
            } else if ($notPassed['cost'] <= $min) {
                $next = $notPassed['name'];
                $min = $notPassed['cost'];
            }
        }
        return $next;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $from = $this->argument('from');
        $to = $this->argument('to');

        if ($from == $to) {
            echo 'Invalid destination'. PHP_EOL;
            return;
        }

        if (!$this->checkCity($from)) {
            echo 'Invalid departure'. PHP_EOL;
            return;
        }

        if (!$this->checkCity($to)) {
            echo 'Invalid destination'. PHP_EOL;
            return;
        }

        $tsStart = microtime(true);

        // Root node
        $this->calcPathCostFor($from);

        // Next nodes
        $nextRoot = $from;
        while ($nextRoot != $to) {
            $nextRoot = $this->getNextMinNode();
            $this->calcPathCostFor($nextRoot);
        }
  
        $lRealPassedCities = $this->lPassedCities->where('passed', true);
        $loop = true;
        $all = [];
        $prev = null;
        while ($loop) {
            if ($prev === null) {
                $prev = $to;
            }
            $step = $lRealPassedCities->where('name', $prev)->first();
            $all[] = $step;
            if ($prev == $from) {
                $loop = false;
            }
            $prev = $step['via'];
        }
        $finalRoutes = array_reverse($all);

        $tsEnd = microtime(true);
        Log::info('End ', [
            'tsStart' => $tsStart,
            'tsEnd' => $tsEnd,
            'ms' => $tsEnd - $tsStart
        ]);

        dd($finalRoutes);
    }
}
