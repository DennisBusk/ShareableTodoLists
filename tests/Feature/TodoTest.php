<?php

namespace Tests\Feature;

use App\Task;
use App\TodoList;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TodoTest extends TestCase
{
  
  use DatabaseTransactions;
  
  /**
     * A basic test example.
     *
     * @return void
     */
    
/** @test  */
public function ATodoListCanAddOneTask(){
  $TodoList = factory(TodoList::class)->create();
  
  $task = factory('App\Task')->create();
  
  $TodoList->addTask($task);
  
  $this->assertEquals(1,$TodoList->tasks()->count());
  
}
    /** @test  */
    public function ATodoListCanAddManyTasks(){
    
    $TodoList = factory('App\TodoList')->create();
    
    $tasks = factory('App\Task',5)->create();
    
    $TodoList->add($tasks);
    
    $this->assertEquals(5,$TodoList->tasks()->count());
    
    }
}
