@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="col-md-8">
          
          <div class="panel panel-default">
            <div class="panel-heading">What Todo??
              <button style="float:right;" type="button" class="btn btn-default btn-sm">
                <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Tilføj
              </button>
            </div>
            
            <div class="panel-body">
              @Auth
                @foreach(Auth::user()->todolist as $list)
                  <div class="col-md-3">
                    <div class="panel panel-default ">
                      <div class="panel-heading">{{$list->title}}</div>
                      <div class="panel-body">
      
                        <ul class="pending-tasks" data-list_id="{{$list->id}}">
                          @foreach($list->pendingTasks() as $task)
                            <li class="task pending toggle" data-task_id="{{$task->id}}">{{$task->title}}</li>
                          @endforeach
                          <li class="new-task"><input placeholder="tilføj"></li>
                        </ul>
                        <hr>
                        <ul class="completed-tasks" data-list_id="{{$list->id}}">
                          @foreach($list->completedTasks() as $task)
                            <li class="task completed toggle" data-task_id="{{$task->id}}">{{$task->title}}</li>
                          @endforeach
                        </ul>
                      </div>
                    </div>
                  </div>
                @endforeach
              @endauth
            
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="panel panel-default">
            <div class="panel-heading">Shared Todo's</div>
            
            <div class="panel-body">
              @foreach(Auth::user()->shared as $list)
                <div class="col-md-10 col-md-offset-1">
                  <div class="panel panel-default ">
                    <div class="panel-heading">{{$list->title}}</div>
                    <div class="panel-body">
                      
                      <ul class="pending-tasks" data-list_id="{{$list->id}}">
                        @foreach($list->pendingTasks() as $task)
                          <li class="task pending toggle" data-task_id="{{$task->id}}">{{$task->title}}</li>
                        @endforeach
                        <li class="new-task"><input placeholder="tilføj"></li>
                      </ul>
                      <hr>
                      <ul class="completed-tasks" data-list_id="{{$list->id}}">
                        @foreach($list->completedTasks() as $task)
                          <li class="task completed toggle" data-task_id="{{$task->id}}">{{$task->title}}</li>
                        @endforeach
                      </ul>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>

    function post_task(list_id, task_id, type, input) {
      if (task_id != '') {
        task_id = '/'.task_id;
      }
      url = document.location.origin + '/users/{{Auth::id()}}/todolist/' + list_id + '/task' + task_id;
      $.ajax({
        type: "POST",
        url: url,
        data: {title: input, type: type},
        success: function (response) {
          console.log(response);
          return (response['status']);
        }

      });
    }
    
    
    // toggle task completed / pending
    $(document).ready(function () {
      $('.toggle').on('click', function () {
        var type = '';
        console.log($(this));
        var task = $(this),
            task_id = task.data('task_id'),
            list_id = task.parent('div').data('list_id');
        if(task.hasClass('pending')){
          type = 'pending';
        }
        else if(task.hasClass('completed')){
          type = 'completed';
        }
        var url = document.location.origin + '/users/{{Auth::id()}}/todolist/' + list_id + '/task/' + task_id;
        $.ajax({
          type: "POST",
          url: url,
          data: {type: type},
          success: function (response) {
            console.log(response);
            if (response['status']) {
              var newTask = task;

              //remove task from list and add to other
              task.fadeOut().slideUp();
              if (task.hasClass('pending')) {
                task.parent('div').find('.completed-tasks li:last-child').append(newTask);
                task.remove();
                newTask.toggleClass('pending completed').fadeIn();
              }
              else {
                task.parent('div').find('.pending-tasks .new-task').prepend(newTask);
                task.remove();
                newTask.toggleClass('pending completed').fadeIn();
              }
            }
          }
        });
        });

      $('.new-task input').keypress(function (ev) {
        var input = $(this);
        var keycode = (ev.keyCode ? ev.keyCode : ev.which);
        if (keycode == '13') {
              var last_li = input.parent('li.new-task'),
              list = last_li.parent('div'),
              list_id = list.data('list_id');
          url = document.location.origin + '/users/{{Auth::id()}}/todolist/' + list_id + '/task';
          $.ajax({
            type: "POST",
            url: url,
            data: {title: input},
            success: function (response) {
              console.log(response);
              if (response['status']) {
                last_li.prepend('<li class="task pending toggle hidden-task" style="display:none;">todo_input.val()</li>');
                $('.hidden-task').fadeIn().removeClass('hidden-task');
              }
            }
          });
        }
      });

      
    })
  
  </script>
@endpush
