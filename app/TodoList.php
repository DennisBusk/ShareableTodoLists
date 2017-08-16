<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Task;

class TodoList extends Model
{
  
  protected $fillable = [
      'user_id', 'title'
  ];
  
  public function user(  )
  {
    return $this->belongsTo('App\User');
    }
  
  public function tasks(  )
  {
    return $this->hasMany('App\Task');
    }
  
  public function addTask($items  )
  {
    
    $method = $items instanceof Task ? 'save' : 'saveMany';
    
    $this->tasks()->$method($items);
    
    }
  
  public function sharedWith(  )
  {
    return $this->belongsToMany('App\User','todo_list_user');
    }
  
  public function remove($items  )
  {
    if($items instanceof Task){
      $items = collect([$items]);
    }
    
    $this->tasks()->whereIn('TodoList_id',$items->pluck('id'))->update(['TodoList_id' => null]);
    }
  
  public function completedTasks(  )
  {
    return $this->tasks()->whereCompleted(1)->get();
    }
  
  public function pendingTasks(  )
  {
    return $this->tasks()->whereCompleted(0)->get();
    
    }
}
