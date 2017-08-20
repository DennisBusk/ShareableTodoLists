<?php

namespace App\Http\Controllers;

use App\Task;
use http\Exception;
use Illuminate\Http\Request;

class TaskController extends Controller {
  
  public function store( Request $request, $user_id, $list_id )
  {
    $input = $request->all();
    
    try
    {
      $task = Task::create([ 'title'        => $input['title'],
                             'todo_list_id' => $list_id,
      ]);
      
      return [ 'status' => true, 'id' => $task->id ];
    } catch ( Exception $e )
    {
      return [ 'status' => false ];
    }
  }
  
  public function update( Request $request, $user_id, $list_id, $task_id )
  {
    $input = $request->all();
    try
    {
      switch ( $input['type'] )
      {
        case 'pending':
          Task::whereId($task_id)->update([ 'completed' => 1 ]);
          break;
        case 'completed':
          Task::whereId($task_id)->update([ 'completed' => 0 ]);
          break;
      }
      
      return [ 'status' => true ];
    } catch ( Exception $e )
    {
      return [ 'status' => false ];
    }
  }
  
  public function delete($user_id, $list_id, $task_id  )
  {
    try{
    Task::delete($task_id);
  
      return [ 'status' => true ];
    } catch ( Exception $e )
    {
      return [ 'status' => false ];
    }
  }
}
