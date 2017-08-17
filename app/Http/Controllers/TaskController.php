<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
  
  public function create(Request $request, $user_id, $list_id)
  {
    $input = $request->all();
    
    Task::create([
        'title' => $input['title'],
        'todo_list_id' => $list_id
    ]);
    }
  
  public function update(Request $request, $user_id, $list_id, $task_id)
  {
    $input = $request->all();
    switch($input['type']){
      case 'pending':
        Task::whereId($task_id)->update(['completed'=>1]);
        break;
      case 'completed':
        Task::whereId($task_id)->update(['completed'=>0]);
        break;
    }
    return ['status'=>true];
    }
}
