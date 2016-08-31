@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Servers
                    <button class="btn btn-xs pull-right" id="new-server-button">
                        <i class="fa fa-plus"></i>
                        <u>N</u>ew server
                    </button>
                </div>
                <div class="panel-body">
                    <form action="{{ url('/server/store') }}" method="POST" class="form-horizontal hide" role="form" id="new-server-form">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="name" class="control-label col-sm-2">Server name</label>
                            <div class="col-sm-8">
                                <input type="text" name="" id="name" class="form-control" value="" required="required">
                            </div>
                            <div class="col-sm-2">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                    {{ Auth::user()->name }}, these are servers you managed.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
<script>
    $(function () {
        $("#new-server-button").bind('click', function () {
            $("#new-server-form").toggleClass('hide');
            $("#name").focus();
            $(this).toggleClass('btn-info');
        });
    });
</script>
@endsection
