@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    @if (isset($project))
                    Task in project {{ $project->name }}
                    @else
                    Tasks
                    @endif
                    <button class="btn btn-default btn-xs pull-right hotkey-n" id="new-task-button">
                        <i class="fa fa-plus"></i>
                        <u>N</u>ew task
                    </button>
                </div>
                <div class="panel-body">
                    <form action="{{ url('/task/store') }}" method="POST" class="form-horizontal hide" role="form" id="new-task-form">
                        {{ csrf_field() }}
                        <div class="form-group form-group-sm">
                            <label for="name" class="col-sm-2 control-label">New task:</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="name" id="name">
                            </div>
                            @if (isset($project))
                            <input type="hidden" name="project_id" value="{{ $project->id }}">
                            @else
                            <div class="col-sm-2">
                                <select name="project_id" id="" class="form-control">
                                    <option value="">Select a project</option>
                                    @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <div class="col-sm-1">
                                <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                            </div>
                        </div>
                    </form>
                    <table class="table table-striped table-hover table-condensed">
                        <thead>
                            <tr>
                                <th>Task name</th>
                                <th>Latest run at</th>
                                <th>Status</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tasks as $task)
                            <tr>
                                <td>{{ $task->name }}</td>
                                <td>{{ \Redis::get('task:last_run:'.$task->id) }}</td>
                                <td>
                                    <span class="label label-default">stuck</span>
                                    <span class="label label-danger">error</span>
                                    <span class="label label-success">running</span>
                                    <span class="label label-success">waiting</span>
                                </td>
                                <td>
                                    <a type="button" class="btn btn-info btn-xs">
                                        <i class="fa fa-info"></i>
                                        More info
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
<script>
    $(function () {
        $("#new-task-button").bind('click', function () {
            $("#new-task-form").toggleClass('hide');
            $("#name").focus();
        });
    });
</script>
@endsection
