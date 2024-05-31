<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeatureTestCase extends TestCase
{
    use RefreshDatabase;
    protected $defaultHeaders = [
        'X-Requested-With' => 'XMLHttpRequest',
        'Accept' => 'application/json'
    ];
}
