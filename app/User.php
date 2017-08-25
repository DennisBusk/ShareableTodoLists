<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
  
  public function todolist(  )
  {
    return $this->hasMany('App\TodoList');
    }
  
  public function tasks(  ) {
    return $this->hasManyThrough(Task::class, TodoList::class);
    }
  
  public function sharedTasks(  ) {
return Task::whereIn('todo_list_id',$this->sharedLists()->pluck('id'))->get();
    }
  public function addTodoList($items  )
  {
    $method = $items instanceof TodoList ? 'save' : 'saveMany';
    
    $this->todolist()->$method($items);
    }
  
  public function shared(  )
  {
    return $this->belongsToMany('App\TodoList','todo_list_user','user_id','todo_list_id');
    }
  
  public function shareTodoListWithUser($users, $Todolist)
  {
    if($users instanceof User){
      $users = collect([$users]);
    }
    foreach($users as $user)
  DB::table('todo_list_user')->insert(['user_id' => $user->id,
      'todo_list_id' => $Todolist->id]);
}
public function unshareTodoListWithUser($users, $Todolist)
  {
    if($users instanceof User){
      $users = collect([$users]);
    }
    foreach($users as $user)
  DB::table('todo_list_user')->where('user_id' , $user->id)->where('todo_list_id' , $Todolist->id)->delete();
}
  
  public function sharedLists(  )
  {
    $sharedLists = $this->shared;
    return $sharedLists->merge($this->todolist()->whereShared(1)->get());
}
  
  public function lastUpdated(  ) {
    $tasks = $this->sharedTasks();
    $sharedTasks = $this->sharedTasks();
    $sharedLists = $this->sharedLists();
    
    $merged = $tasks->merge($sharedTasks)->merge($sharedLists);
    return Carbon::parse($merged->sortByDesc(function($item){
      return $item->updated_at;
    })->first()->updated_at)->timestamp;
    
//    foreach ($this->sharedLists() as $list)
    
    
//    return Carbon::parse($this->sharedLists()->sortBy(function($todo_list){'updated_at')->first()->updated_at);
//    return Carbon::parse($this->sharedLists()->sortBy('updated_at')->first()->updated_at)->timestamp;
}
}
