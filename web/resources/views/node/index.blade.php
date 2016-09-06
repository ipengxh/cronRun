@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Nodes
                    <button class="btn btn-default btn-xs pull-right hotkey-n" id="new-node-button">
                        <i class="fa fa-plus"></i>
                        <u>N</u>ew node
                    </button>
                </div>
                <div class="panel-body">
                    <form action="{{ url('/node/store') }}" method="POST" class="form-horizontal hide" role="form" id="new-node-form">
                        {{ csrf_field() }}
                        <div class="form-group form-group-sm">
                            <label for="name" class="col-sm-2 control-label">New node:</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="name" id="name">
                            </div>
                            <div class="col-sm-1">
                                <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                            </div>
                        </div>
                    </form>
                    <table class="table table-striped table-hover table-condensed">
                        <thead>
                            <tr>
                                <th>Node name</th>
                                <th>Projects</th>
                                <th>Latest active at</th>
                                <th>Status</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($nodes as $node)
                            <tr>
                                <td>
                                    <a href="{{ route('node:show', $node->id) }}">
                                        {{ $node->name }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('node:projects', $node->id) }}">
                                        {{ count($node->project) }}
                                    </a>
                                </td>
                                <td>
                                    {{ Redis::get('node:last_active:time:'.$node->id) ?? 'Never actived' }}
                                </td>
                                <td>
                                    <span class="label label-default">dead</span>
                                    <span class="label label-danger">disconnected</span>
                                    <span class="label label-warning">reconnecting</span>
                                    <span class="label label-success">connected</span>
                                </td>
                                <td>
                                    <a href="{{ route('node:show', $node->id) }}">
                                        Details
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
        $("#new-node-button").bind('click', function () {
            $("#new-node-form").toggleClass('hide');
            $("#name").focus();
        });
    });
</script>
@endsection
