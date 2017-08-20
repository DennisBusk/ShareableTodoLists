<?php

namespace App\Http\Controllers;

use App\TodoList;
use App\User;
use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TodoListController extends Controller {
  
  public function store( Request $request, $user_id )
  {
    $input = $request->all();
    try
    {
      $list = TodoList::create([ 'title'   => $input['title'],
                                 'user_id' => Auth::id(),
      ]);
      
      return [ 'status' => true,
               'html'   => view('partials.todolist', [ 'list' => $list, 'type_of_list' => 'todolist' ])->render(),
      ];
      
    } catch ( Exception $e )
    {
      return [ 'status' => false ];
    }
    
  }
  
  public function delete( $user_id, $list_id )
  {
    $list = TodoList::find($list_id);
    try
    {
      if ( $list->user_id == Auth::id() )
      {
        $list->delete();
      }
      else
      {
        DB::table('todo_list_user')->where('todo_list_id', $list->id)->where('user_id', Auth::id())->delete();
      }
      
      
      return [ 'status' => true ];
    } catch ( Exception $e )
    {
      return [ 'status' => false ];
    }
  }
  
  public function getSharedWith( $user_id, $list_id )
  {
    $list = TodoList::find($list_id);
    
$options = collect([]);
    foreach ( User::where('id','!=',Auth::id())->get() as $user )
    {
      $options->put($user->id, $user->name);
    }

    return [ 'status' => true,
             'title'=> $list->title,
             'html'   => view('partials.sharedWithSelect', [ 'list' => $list, 'options'=>$options ])->render(),
    ];
    
  }
  
  public function share( Request $request, $user_id, $list_id )
  {
    $input = $request->all();
//    print_r($input['ids']);
//    exit();
    $list  = TodoList::find($list_id);
    try
    {
      $list->sharedWith()->sync($input['ids']);
  
      return [ 'status' => true ];
    } catch ( Exception $e )
    {
      return [ 'status' => false ];
    }
  }
}