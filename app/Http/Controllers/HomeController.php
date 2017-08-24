<?php

namespace App\Http\Controllers;

use App\TodoList;
use Illuminate\Http\Request;

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
      $last_updated = Auth::user()->sharedLists()->orderBy('updated_at','desc')->first()->updated_at;
        return view('home');
    }
}
