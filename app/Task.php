<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
  protected $fillable = [
      'todo_list_id', 'title'
  ];
  
  public function todolist(  )
  {
    return $this->belongsTo('App\TodoList');
    }
  
  public function setCompleted($tasks  )
  {
    if($tasks instanceof Task){
      $tasks = collect([$tasks]);
    }
    $tasks->update(['completed'=>1]);
    }
  
  public function setPending($tasks  )
  {
    if($tasks instanceof Task){
      $tasks = collect([$tasks]);
    }
    $tasks->update(['completed'=>0]);
    }
  
}
