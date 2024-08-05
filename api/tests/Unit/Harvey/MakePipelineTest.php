<?php

namespace Tests\Unit\Harvey;

use Tests\TestCase;

class MakePipelineTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $fun = make_pipeline( function($x) { return $x * 3; },
                      function($x) { return $x + 1; },
                      function($x) { return $x / 2; } );
        $result = $fun(3); # should print 5

        $this->assertTrue($result == 5);
    }
}

function make_pipeline(...$funcs)
{
    return function($arg) use ($funcs)
    {
        $loop = true;
        while ($loop) {
            $fn = array_shift($funcs);
            if (empty($fn)) {
                $loop = false;
            } else {
                $arg = $fn($arg);
            }
        }
        return $arg;
    };
}

