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
  {{--<script src="{{ asset('js/scripts.js') }}"></script>--}}
  <script>
    var sent = false;
    var old_list_id = 0;
    var newList = '';
    var last_updated;
    
    
    // toggle task completed / pending
    function toggle() {

      $(document).on('click','.task-title', function () {


        // to prevent the event to fire multiple times
        if (sent == false) {
          sent = true;

          var type = '';
          console.log($(this));
          var task = $(this).closest('li'),
              task_id = task.data('task_id'),
              list_id = task.parent('ul').data('list_id');
          console.log(old_list_id + ' - ' + list_id);
          if (old_list_id != list_id || CList == undefined || PList == undefined) {
            old_list_id = list_id;
            var CList = $('#c-' + list_id);
            var PList = $('#p-' + list_id);
          }
          if (task.hasClass('pending')) {
            newList = CList;
            type = 'pending';
          }
          else if (task.hasClass('completed')) {
            type = 'completed';
//                var PList = $('#new-task-'+list_id);
            newList = PList;
          }
          var url = document.location.origin + '/users/{{Auth::id()}}/todolists/' + list_id + '/tasks/' + task_id;
          $.ajax({
            type: "POST",
            url: url,
            data: {type: type},
            success: function (response) {
              console.log(response);
              if (response['status']) {
                task.addClass('inTransition');

                var newTask = task;

                //remove task from list and add to other
                if (task.hasClass('pending')) {
                  newList.append(newTask[0].outerHTML);
                }
                else {
                  newList.find('#new-task-' + list_id).before(newTask[0].outerHTML);
                }
                task.remove();
                console.log(newList);
                newList.find('.inTransition').toggleClass('pending completed inTransition');

                // to make sure the funtion works another time
                sent = false;
              }
            }
          });
        }
      });
    }

    // when submitting a new task
    function SubmitNewTask() {
      $(document).on('submit','.new-task form',function (e) {
        e.preventDefault();
        var input = $(this).find('input').first().val();
        var last_li = $(this).closest('li');
        var list_id = last_li.parent('ul').data('list_id');
        var url = document.location.origin + '/users/{{Auth::id()}}/todolists/' + list_id + '/tasks';
        console.log(url);
        console.log(input);
        $.ajax({
          type: "POST",
          url: url,
          data: {title: input},
          success: function (response) {
            console.log(response);
            if (response['status']) {
              // after getting confirmation of creation
              last_li.before('<li class="pending" data-task_id="' + response['id'] + '"><span class="task-title">' + input + '</span><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></li>');
              console.log($('#new-task-' + list_id + ' input').first());
              console.log($('#new-task-' + list_id + ' input').first().val());
              $('#new-task-' + list_id + ' input').first().val('');
            }
          }
        });
      });
    }

    // Delete a Todolist
    function deleteTodoList(){
      $(document).on('click','.todolist .panel-heading .glyphicon-remove',function () {
        var glyph = $(this),
            list = glyph.closest('div.list-panel');
        list_id = glyph.data('list_id'),
            url = document.location.origin + '/users/{{Auth::id()}}/todolists/' + list_id;
        $.ajax({
          type: "DELETE",
          url: url,
          success: function (response) {
            console.log(response);
            if (response['status']) {
              // after getting confirmation of deletion
              list.remove();
            }
          }
        });


      });

    }

    // Delete a task
    function deleteATask(){
      $(document).on('click','li .glyphicon-remove',function () {
        var task = $(this).closest('li'),
            task_id = task.data('task_id'),
            list_id = task.parent('ul').data('list_id'),
            url = document.location.origin + '/users/{{Auth::id()}}/todolists/' + list_id + '/tasks/' + task_id;
        $.ajax({
          type: "DELETE",
          url: url,
          success: function (response) {
            console.log(response);
            if (response['status']) {
              // after getting confirmation of creation
              task.remove();
            }
          }
        });


      });
    }
    
    // Unshare a Shared TodoList
    function unshareTodoList(){
      // unshare todolist
      $(document).on('click','.shared-todolist .panel-heading .glyphicon-remove',function () {
        var glyph = $(this),
            list = glyph.closest('div.list-panel');
        list_id = glyph.data('list_id'),
            url = document.location.origin + '/users/{{Auth::id()}}/todolists/' + list_id+'/unshare';
        $.ajax({
          type: "get",
          url: url,
          success: function (response) {
            console.log(response);
            if (response['status']) {
              // after getting confirmation of deletion
              location.reload();
            }
          }
        });


      });
    }

    // Handle the triggers in the share / unshare Forms
    function modalForm(list_id) {
      $(document).on('submit','#shareModalForm',function (e) {
        e.preventDefault();
        var form = $(this);
        console.log(form.find('select').val());
        var url = document.location.origin + '/users/{{Auth::id()}}/todolists/' + list_id + '/share';
        $.ajax({
          type: "POST",
          url: url,
          data: {ids: form.find('select').val()},
          success: function (response) {
            console.log(response);
            location.reload();
          }
        });


      });
      $(document).on('submit','#unshareForm',function (e) {
        e.preventDefault();
        var url = document.location.origin + '/users/{{Auth::id()}}/todolists/' + list_id + '/unshare';
        $.ajax({
          type: "get",
          url: url,
          success: function (response) {
            console.log(response);
            location.reload();
          }
        });


      })
    }

    // Checking the shared lists for changes
    function checkForUpdates(){
      $.ajax({
        type:"GET",
        url:document.location.origin + '/users/{{Auth::id()}}/last_updated',
        data:{last_updated: last_updated},
        success: function(response){
          if(response['status']){
            last_updated = response['last_updated'];
            $('#shared-lists').html(response['html']);
            // activate the triggers for the new changes;

          }
        }
      });
    }
    
    // Submitting a new Todolist
    function submitANewTodoList(){
      $('.new-todo').on('submit',function (e) {
        e.preventDefault();
        var input = $(this).find('input').first().val();
        var url = document.location.origin + '/users/{{Auth::id()}}/todolists';
        console.log(url);
        console.log(input);
        $.ajax({
          type: "POST",
          url: url,
          data: {title: input},
          success: function (response) {
            console.log(response);
            if (response['status']) {
              // after getting confirmation of creation
              $('#private-lists').append('<div class="col-md-4">' + response['html'] + '</div>');
            }
          }
        });
      });

    }
    
    // clicking the share Button
    function clickTheShareButton() {
      $(document).on('click','.glyphicon-share',function () {
        var list = $(this),
            list_id = list.data('list_id'),
            url = document.location.origin + '/users/{{Auth::id()}}/todolists/' + list_id + '/get_shared_with';
        $.ajax({
          type: "GET",
          url: url,
          success: function (response) {
            console.log(response);
            if (response['status']) {
              var modal = $('#shareModal');
              modal.find('.modal-title').text(response['title']);
              modal.find('#shareModalForm').append(response['html']);
              console.log(modal);
              $('#sharedWithSelect').chosen();
              $('#sharedWithSelect').on('change', function (evt, params) {
                $(this).find('option[value="' + params['selected'] + '"]').attr('selected', true);
                $('#sharedWithSelect').trigger('chosen:updated');
              });
              modal.modal('show');
              modal.on('hidden.bs.modal', function (e) {
                modal.find('#shareModalForm').text('');
              });
              modalForm(list_id);
            }
          }
        });
      });
    }



    // Register the triggers before start and then activate them once ready
    function triggers(){

      SubmitNewTask();
      
      deleteTodoList();
      
      deleteATask();

      unshareTodoList();
      
      clickTheShareButton();

      toggle();
    }

    $(document).ready(function () {

      // registering the last_updated value;
      last_updated = $('#last_updated').val();
      
      // Activate the triggers
      triggers();

      //start auto update of shared lists.
      setInterval(checkForUpdates,10000);

      // active the submitform
      submitANewTodoList();

    });
  </script>
@endpush
