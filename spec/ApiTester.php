<?php namespace spec\Pisa\Api\Gizmo;

use Faker\Factory as Faker;
use PhpSpec\ObjectBehavior;

class ApiTester extends ObjectBehavior
{
    protected $faker;

    public function __construct()
    {
        $this->faker = Faker::create();
    }
}
