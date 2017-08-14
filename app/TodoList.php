<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TodoList extends Model
{
  
  protected $fillable = [
      'user_id', 'title'
  ];
  
  public function user(  )
  {
    return $this->belongsTo('App\User');
    }
}
