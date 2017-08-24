@extends('layouts.app')
@push('styles')
  <link href="{{ asset('css/chosen/chosen.css') }}" rel="stylesheet">

@endpush
@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="col-md-8">
          
          <div class="panel panel-default main-panel">
            <div class="panel-heading">What Todo??
              <form class="new-todo"><input name="new-todo-input" placeholder="new TodoList"></form>
            </div>
            
            <div id="private-lists" class="panel-body">
              @Auth
                @foreach(Auth::user()->todolist()->whereShared(0)->get() as $list)
                  <div class="col-md-4">
                    @include('partials.todolist',['list'=>$list,'type_of_list'=>'todolist'])
                  </div>
                @endforeach
              @endauth
            
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class=" main-panel panel panel-default">
            <div class="panel-heading">Shared Todo's</div>
            
            <div id="shared-lists" class="panel-body">
              @foreach(Auth::user()->sharedLists() as $list)
                <div class="col-md-10 col-md-offset-1">
                  @include('partials.todolist',['list'=>$list,'type_of_list'=>'shared-todolist'])}}
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @include('partials.shareModal')
@endsection

@push('scripts')
  <script src="{{ asset('js/chosen/chosen.jquery.js') }}"></script>
  <script src="{{ asset('js/scripts.js') }}"></script>
@endpush
