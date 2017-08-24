<?php

namespace App\Http\Controllers;

use App\TodoList;
use App\User;
use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;

class TodoListController extends Controller {
  
  public function store( Request $request, $user_id ) {
    $input = $request->all();
    try {
      $list = TodoList::create([ 'title'   => $input['title'],
                                 'user_id' => Auth::id(),
      ]);
      
      return [ 'status' => true,
               'html'   => view('partials.todolist', [ 'list' => $list, 'type_of_list' => 'todolist' ])->render(),
      ];
    } catch ( Exception $e ) {
      return [ 'status' => false ];
    }
  }
  
  public function delete( $user_id, $list_id ) {
    $list = TodoList::find($list_id);
    try {
      if ( $list->user_id == Auth::id() ) {
        $list->delete();
      }
      else {
        DB::table('todo_list_user')->where('todo_list_id', $list->id)->where('user_id', Auth::id())->delete();
      }
      
      
      return [ 'status' => true ];
    } catch ( Exception $e ) {
      return [ 'status' => false ];
    }
  }
  
  public function getSharedWith( $user_id, $list_id ) {
    $list = TodoList::find($list_id);
    
    $options = collect([]);
    foreach ( User::where('id', '!=', Auth::id())->get() as $user ) {
      $options->put($user->id, $user->name);
    }
    
    return [ 'status' => true,
             'title'  => $list->title,
             'html'   => view('partials.sharedWithSelect', [ 'list' => $list, 'options' => $options ])->render(),
    ];
  }
  
  public function share( Request $request, $user_id, $list_id ) {
    $input = $request->all();
//    print_r($input['ids']);
//    exit();
    $list = TodoList::find($list_id);
    try {
      $list->sharedWith()->sync($input['ids']);
      $list->update([ 'shared' => 1 ]);
      
      return [ 'status' => true, 'status' => 'shared' ];
    } catch ( Exception $e ) {
      return [ 'status' => false ];
    }
  }
  
  public function unshare( $user_id, $list_id ) {
    $list = TodoList::find($list_id);
    try {
      if ( $list->user_id == $user_id ) {
        $list->sharedWith()->sync([]);
        $list->update([ 'shared' => 0 ]);
      }
      else {
        DB::table('todo_list_user')->whereUserId($user_id)->whereTodoListId($list_id)->delete();
        if ( ! DB::table('todo_list_user')->whereTodoListId($list_id)->count() > 0 ) {
          TodoList::find($list_id)->update([ 'shared' => 0 ]);
        }
      }
      
      return [ 'status' => true, 'status' => 'unshared' ];
    } catch ( Exception $e ) {
      return [ 'status' => false ];
    }
  }
  
  public function last_updated() {
    $last_updated = Auth::user()->lastUpdated();
    
    if ( Input::get('last_updated') != $last_updated ) {
      $html = '';
      foreach ( Auth::user()->sharedLists() as $list ) {
        $html .= '<div class="col-md-10 col-md-offset-1" >';
        $html .= view('partials.todolist',['list'=>$list,'type_of_list'=>'shared-todolist'])->render();
        $html .= '</div>';
      }
      return ['status' => true,
          'html' => $html,
          'last_updated' => $last_updated];
  }
  else{
    return ['status' => false ];
    }
  }
}