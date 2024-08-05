<?php

namespace Tests\Unit\Harvey;

use Tests\TestCase;

class ActionStackTest extends TestCase
{
    public function test_normal(): void
    {
        $textActions = new ActionStack();
        $textActions->performAction("Write text");
        $textActions->performAction("Add an empty page");
        $textActions->performAction("Change the font color");
        $lastAction = $textActions->undoAction();
        

        // b.	$textActions has two items in the stack after running the snippet.
        $this->assertEquals(2, $textActions->countAction());

        // d.	Calling $textActions->undoAction() just after the snippet will return "Add an empty page".
        $lastAction = $textActions->undoAction();
        $this->assertEquals("Add an empty page", $lastAction);

    }
    public function test_exception(): void
    {
        $this->expectException(\Exception::class);
        $textActions = new ActionStack();
        $textActions->performAction("Write text");
        $textActions->performAction("Add an empty page");
        $textActions->performAction("Change the font color");
        $lastAction = $textActions->undoAction();
        
        // e.	Calling $textActions->undoAction() three more times just after the snippet will throw an Exception.
        $textActions->undoAction();
        $textActions->undoAction();
        $textActions->undoAction();
    }
}

class ActionStack
{
    private $stack = null;

    function __construct()
    {
        $this->stack = new \SplStack();
    }

    public function performAction($action)
    {
        if (!is_null($action)) {
            $this->stack->push($action);
        }
    }

    public function undoAction()
    {
        if ($this->stack->isEmpty()) {
            throw new \Exception("No more actions!");
        }
        return $this->stack->pop();
    }

    public function countAction()
    {
        return $this->stack->count();
    }
}
