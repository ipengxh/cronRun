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
                    <form action="{{ route('node:update', $node->id) }}" method="POST" role="form">
                        {{ csrf_field() }}
                        <legend>{{ $node->name }}</legend>
                        <div class="form-group">
                            <label for="name">New name</label>
                            <input type="text" name="name" class="form-control" id="name" placeholder="Input field" autofocus="true">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
