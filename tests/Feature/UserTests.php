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
  use DatabaseTransactions;
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
      $user->todolist()->save($Todolist);
      
      $this->assertEquals($user->id,$Todolist->user_id);
      
    }
    
    /** @test  */
    public function AUserCanShareATodoListWithAnotherUser(){
    $user1 =factory(User::class)->create();
    
    $user2 = factory(User::class)->create();
  
      $Todolist = new TodoList(['title' => 'Test title']);
      $user1->todolist()->save($Todolist);
      
      $user1->shareTodoListWithUser($user2, $Todolist);
      
      $this->assertEquals($Todolist->id,$user2->shared()->first()->id);
  
    }
    
}
