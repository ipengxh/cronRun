@extends('layouts.app')

@section('content')
<div class="container">
    <div class="content">
        <div class="title">
            <p>@{{ message }}</p>
        </div>
    </div>
</div>
@endsection
@section('footer')
<script type="text/javascript">
     new Vue({
        el: '.title',
        data: {
            message: 'Hello Laravel!'
        }
    })
</script>
@endsection
