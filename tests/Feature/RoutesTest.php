<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RoutesTest extends TestCase

{
  use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     *
     *
     */
    
 /** @test */
    public function aHomeRouteShowsWhatTodo()
    {
      $this->get('/')
           ->assertSee('What Todo??');
    }
}
