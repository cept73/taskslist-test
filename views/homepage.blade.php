@extends('layouts.app')

@section('title', 'Table')

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
<script>
@if ($user['logged'])

function onInitpage(table)
{
    $('tr', table).on('click', function() {
        
        if ($(this).hasClass('selected')) {
            // Remove selected
            $(this).removeClass('selected');
            tableSelect(null);
        }
        else {
            // Remove old
            $('tr.selected', table).removeClass('selected');
            // Set new
            if (tableSelect($(this)))
                $(this).addClass('selected');
        }
    });
}

@else

function onInitpage(table)
{

}

@endif
</script>
<script src="public/assets/js/homepage.js"></script>
@endsection

@section('content')

    <!-- DataTale -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tasks list</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
            <table class="table table-bordered" id="tasks_list" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th class="column-mini">#</th>
                    <th class="w-25">Title</th>
                    <th class="w-75">Task</th>
                    <th><nobr>E-mail</nobr></th>
                    <th class="column-mini"><i class="fas fa-check-circle" title="Completed"></i></th>
                    <th class="column-mini"><i class="fas fa-highlighter" title="Admin edited"></i></th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            </div>
        </div>
    </div>


    <!-- Add task -->
    <div id="addTask" class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary" id="addTask__WindowCaption">Add task</h6>
        </div>
        <div class="card-body">
            <div id="alert-error" class="alert alert-danger text-center d-none" role="alert"></div>
            <div id="alert-success" class="alert alert-success text-center d-none" role="alert"></div>

            <form id="add-task" action="/task" method="POST">
                <input type="hidden" name="path" value="task">
                <input type="hidden" name="id" value="">

                <div class="form-group row">
                    <div class="col-sm-9 mb-3 mb-sm-0">
                        <input type="text" class="taskName form-control form-control-email required w-100" 
                            id="formTaskName" name="taskName"
                            value="@if (isset($taskName)){{ $taskName }}@endif"
                            placeholder="Task name"
                            tabindex="1">
                    </div>
                    <div class="form-check col-sm-3 text-right">
                        @if ($user['logged'])
                        <input type="checkbox" class="taskCompleted form-check-input"
                            id="formTaskCompleted" name="taskCompleted" onclick=false
                            @if (isset($taskCompleted) and $taskCompleted) checkbox @endif 
                            title="Completed"
                            tabindex="4">
                        <label class="form-check-label d-sm-none d-md-inline"
                            for="formTaskCompleted">Completed</label>
                        @endif
                    </div>

                </div>
                <div class="form-group">
                    <textarea class="taskText form-control required w-100" rows="5"
                        id="formTaskText" name="taskText"
                        placeholder="Describe task..."
                        tabindex="2">@if (isset($taskText)){{ $taskText }}@endif</textarea>
                </div>
                <div class="form-group row">
                    <div class="col-sm-8 mb-3 mb-sm-0">
                        <input type="email" class="taskEmail form-control form-control-email required w-100"
                            id="formTaskEmail" name="taskEmail"
                            value="@if (isset($taskEmail)) {{ $taskEmail }} @endif" 
                            placeholder="E-mail"
                            tabindex="3">
                    </div>
                    <div class="col-sm-4 text-right">
                        <input type="submit" class="btn btn-primary" value="Add task"
                            id="addTask__Submit"
                            tabindex="5">
                    </div>
                </div>
            </form>

        </div>
    </div>

@endsection
