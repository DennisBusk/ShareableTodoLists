<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RoutesTests extends TestCase

{
  use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     *
     *
     */
    
 /** test */
    public function aHomeRouteShowsWhatTodo()
    {
      $this->visit('/')
           ->see('What Todo??');
    }
}
