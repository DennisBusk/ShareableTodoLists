<?php

namespace Tests\Feature;

use App\TodoList;
use App\User;
use Faker\Factory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTests extends TestCase
{
  
    /**
     * A basic test example.
     *
     * @return void
     */
    
    /** @test  */
    public function aUserCanCreateATodolist()
    {
      $user = factory(User::class)->create();
      
      $Todolist = new TodoList(['title' => 'Test title']);
      $user->Todolist()->save($Todolist);
      
      $this->assertEquals($user->id,$Todolist->user_id);
      
    }
}
