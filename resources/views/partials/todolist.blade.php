<div class="panel panel-default list-panel {{$type_of_list}}">
  <div class="panel-heading"><span class="list-heading">{{$list->title}}</span>
    <div class="todolist-buttons">
    <span class="glyphicon todolist glyphicon-remove" data-list_id="{{$list->id}}" aria-hidden="true"></span>
    @if($list->user_id == Auth::id())
      <br>
    <span class="glyphicon todolist glyphicon-share" data-list_id="{{$list->id}}" aria-hidden="true"></span>
      @endif
    </div>
  </div>
  <div class="panel-body">
    
    <ul id="p-{{$list->id}}" class="tasks" data-list_id="{{$list->id}}">
      @foreach($list->pendingTasks() as $task)
        <li class="pending" data-task_id="{{$task->id}}"><span class="task-title">{{$task->title}}</span><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></li>
      @endforeach
      <li id="new-task-{{$list->id}}" class="new-task">
        <form action="#"><input class="col-md-12" name="new-task-input" placeholder="Add new task"></form>
      </li>
    </ul>
    <hr>
    <ul id="c-{{$list->id}}" class="tasks" data-list_id="{{$list->id}}">
      @foreach($list->completedTasks() as $task)
        <li class="completed" data-task_id="{{$task->id}}"><span class="task-title">{{$task->title}}</span><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></li>
      @endforeach
    </ul>
  </div>
</div>
