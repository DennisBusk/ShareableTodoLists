
{!! Form::select('sharedWithSelect[]',$options,DB::table('todo_list_user')->where('todo_list_id',$list->id)->pluck('user_id'),['class'=> 'form-control', 'required','multiple']) !!}
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
{!! Form::submit('Share',['class'=>'btn btn-primary']) !!}