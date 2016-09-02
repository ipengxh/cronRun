@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    @if (isset($node))
                    Projects on node {{ $node->name }}
                    @else
                    Projects
                    @endif
                    <button class="btn btn-default btn-xs pull-right hotkey-n" id="new-project-button">
                        <i class="fa fa-plus"></i>
                        <u>N</u>ew project
                    </button>
                </div>
                <div class="panel-body">
                    <form action="{{ url('/project/store') }}" method="POST" class="form-horizontal hide" role="form" id="new-project-form">
                        {{ csrf_field() }}
                        <div class="form-group form-group-sm">
                            <label for="name" class="col-sm-2 control-label">New project:</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="name" id="name">
                            </div>
                            @if (isset($node))
                            <input type="hidden" name="node_id" value="{{ $node->id }}">
                            @else
                            <div class="col-sm-2">
                                <select name="node_id" id="" class="form-control">
                                    <option value="">Select a node</option>
                                    @foreach ($nodes as $node)
                                    <option value="{{ $node->id }}">{{ $node->name }}</option>
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
                                <th>Project name</th>
                                <th>Tasks</th>
                                <th>Status</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($projects as $project)
                            <tr>
                                <td>{{ $project->name }}</td>
                                <td>
                                    <a href="{{ route('project:tasks', $project->node_id) }}">
                                        {{ count($project->task) }}
                                    </a>
                                </td>
                                <td>
                                    <span class="label label-default">dead</span>
                                    <span class="label label-danger">disconnected</span>
                                    <span class="label label-warning">reconnecting</span>
                                    <span class="label label-success">connected</span>
                                </td>
                                <td>
                                    <a type="button" class="btn btn-info btn-xs">
                                    <i class="fa fa-info"></i>
                                    More info
                                    </a>
                                    <a class="btn btn-primary btn-xs" href="{{ route('project:edit', $project->node_id) }}">
                                    <i class="fa fa-edit"></i>
                                    Edit
                                    </a>
                                    <button type="button" class="btn btn-danger btn-xs">
                                    <i class="fa fa-remove"></i>
                                    Remove
                                    </button>
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
        $("#new-project-button").bind('click', function () {
            $("#new-project-form").toggleClass('hide');
            $("#name").focus();
        });
    });
</script>
@endsection
