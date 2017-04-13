@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading" id="test">
                    Edit node
                </div>
                <div class="panel-body">
                    <form action="{{ route('node:update', $node->id) }}" method="POST" class="form-horizontal" role="form">
                        {{ csrf_field() }}
                        <div class="form-group form-group-sm">
                            <label class="control-label col-sm-2" for="name">Node name:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="name" id="name" value="{{ $node->name }}">
                            </div>
                        </div>
                        <div class="form-group form-group-sm">
                            <label class="control-label col-sm-2" for="key">Secret Key:</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="key" value="{{ $node->key }}" readonly>
                            </div>
                            <div class="col-sm-1">
                                <a class="btn btn-default btn-sm">
                                    <i class="fa fa-recycle"></i>
                                </a>
                            </div>
                        </div>
                        <div class="form-group form-group-sm">
                            <label class="control-label col-sm-2" for="manager">Managers:</label>
                            <div class="col-sm-8">
                                <select multiple class="multiple-select form-control form-control-sm">
                                    @foreach ($users as $user)
                                        @if (in_array($user->id, array_column($managers->toArray(), 'user_id')))
                                            <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                                        @else
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-10 col-sm-offset-2">
                                <button type="submit" class="btn btn-primary btn-sm">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer')
<script type="text/javascript">
    $(function () {
        $(".multiple-select").select2({
            theme: "bootstrap"
        });
    });
</script>
@endsection
