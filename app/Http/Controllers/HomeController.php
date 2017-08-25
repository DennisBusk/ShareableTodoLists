<?php

namespace App\Http\Controllers;

use App\TodoList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      if(Auth::user()->sharedLists()->count() > 0){
        $last_updated = Auth::user()->lastUpdated();
      }
      else{
        $last_updated = null;
      }
        
        return view('home',compact('last_updated'));
    }
}
