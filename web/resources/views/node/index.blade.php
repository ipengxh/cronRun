@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading" id="test">
                    Servers
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-hover table-condensed">
                        <thead>
                            <tr>
                                <th>Server name</th>
                                <th>Key</th>
                                <th>Created at</th>
                                <th>Last Active</th>
                                <th>Created by</th>
                                <th>Authority</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($nodes as $node)
                            <tr>
                                <td>{{ $node->node->name }}</td>
                                <td>
                                    {{ $node->node->key }}
                                    <button class="btn btn-default btn-xs" title="Click to copy" data-toggle="tooltip" data-placement="top">
                                        <i class="fa fa-copy"></i>
                                    </button>
                                </td>
                                <td>{{ $node->node->created_at }}</td>
                                <td>{{ Redis::get('node:last_active:'.$node->node->id) }}</td>
                                <td>{{ $node->user->name }}</td>
                                <td>{{ $node->role }}</td>
                                <td>
                                    @if ('manager' == $node->role)
                                    <button type="button" class="btn btn-primary btn-xs">
                                        <i class="fa fa-edit"></i>
                                        Edit
                                    </button>
                                    <button type="button" class="btn btn-danger btn-xs">
                                        <i class="fa fa-remove"></i>
                                        Remove
                                    </button>
                                    @endif
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
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endsection
