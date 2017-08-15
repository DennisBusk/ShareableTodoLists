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
  
  public function shared(  )
  {
    return $this->belongsToMany('App\TodoList','todo_list_user');
    }
  
  public function shareTodoListWithUser($user2, $Todolist)
  {
  DB::table('todo_list_user')->insert(['user_id' => $user2->id,
      'todo_list_id' => $Todolist->id]);
}
}
