@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    View node {{ $node->name }}
                    <a href="{{ route('node:edit', $node->id) }}" class="btn btn-primary btn-xs pull-right">Edit</a>
                </div>
                <div class="panel-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Attribute</th>
                                <th>Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Name</td>
                                <td>{{ $node->name }}</td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>
                                    <span class="label label-danger">disconnected</span>
                                    <span class="label label-success">connected</span>
                                </td>
                            </tr>
                            <tr>
                                <td>Latest active at</td>
                                <td>{{ \Redis::get('node:latest_active:'.$node->id) }}</td>
                            </tr>
                            <tr>
                                <td>Secret key</td>
                                <td>
                                    {{ $node->key }}
                                </td>
                            </tr>
                            <tr>
                                <td>Created by</td>
                                <td>{{ $node->user->name }}</td>
                            </tr>
                            <tr>
                                <td>Manager</td>
                                <td>
                                    @foreach ($managers as $manager)
                                        <span class="label label-info">{{ $manager->user->name }}</span>
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td>Projects</td>
                                <td>
                                    @foreach ($projects as $project)
                                        <span class="label label-info">{{ $project->name }}</span>
                                    @endforeach
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="panel-footer">
                    &nbsp;
                    <a class="btn btn-danger btn-xs pull-right" data-toggle="modal" href='#modal-destroy'>Delete node</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-destroy">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('node:destroy', $node->id) }}" method="POST" role="form">
                {{ csrf_field() }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Delete node</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure? Could not undo delete.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancle</button>
                    <button type="submit" class="btn btn-danger">Yes, delete it</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
