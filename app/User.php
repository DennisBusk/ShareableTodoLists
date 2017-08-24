<?php

namespace App;

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
  
  public function sharedLists(  )
  {
    $sharedLists = $this->shared;
    return $sharedLists->merge($this->todolist()->whereShared(1)->get());
}
  
  public function lastUpdated(  ) {
    return $this->sharedLists()->orderBy('updated_at','desc')->first()->updated_at;
}
}
