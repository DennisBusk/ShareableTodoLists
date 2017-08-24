
$(document).ready(function () {
  var sent = false;
  var old_list_id = 0;
  var newList = '';

  // toggle task completed / pending
  function toggle() {

    $('.task-title').on('click', function () {


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

              //  to add the click event to the new li

              // to make sure the funtion works another time
              sent = false;
            }
            toggle();

          }
        });

      }
      toggle();

    });
  }

  function modalForm(list_id) {
    $('#shareModalForm').submit(function (e) {
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
    $('#unshareForm').submit(function (e) {
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

  // when submitting a new task
  $('.new-task').submit(function (e) {
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
          console.log($('#new-task-'+list_id+' input').first());
          console.log($('#new-task-'+list_id+' input').first().val());
          $('#new-task-'+list_id+' input').first().val('');
          toggle();
        }
      }
    });
  });

// delete tasks
  $('li .glyphicon-remove').click(function () {
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

// delete todolist
  $('.todolist .panel-heading .glyphicon-remove').click(function () {
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

  // unshare todolist
  $('.shared-todolist .panel-heading .glyphicon-remove').click(function () {
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


  //when submitting a new todolist
  $('.new-todo').submit(function (e) {
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
          toggle();
        }
      }
    });
  });

  //when clicking the share button
  $('.glyphicon-share').click(function () {
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


  toggle();

});