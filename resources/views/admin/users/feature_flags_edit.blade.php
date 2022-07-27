@extends('admin.main')
@section('app-content')

<head>
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
        crossorigin="anonymous"></script>
</head>
<body style="padding: 15px;">
    <h1>Feature Flags Edit</h1>
    <form action="/admin/global/feature_flags/edit" method="POST">
        <input type="hidden" name="feature_id" value="{{$feature['id']}}" />
        <div class="form-group">
            <label for="feature">鍵值 / FEATURE KEY</label>
            <input type="text" class="form-control" id="feature" name="feature" value="{{$feature['feature']}}" placeholder="feature">

            <label for="introduction">用途介紹 / Introduction</label>
            <input type="text" class="form-control" id="introduction" name="introduction" value="{{isset(json_decode($feature['description'])->introduction) ? json_decode($feature['description'])->introduction : ''}}" placeholder="introduction">

            <label for="priority">優先級 / Priority</label>
            <input type="number" class="form-control" id="priority" name="priority" value="{{isset(json_decode($feature['description'])->priority) ? json_decode($feature['description'])->priority : ''}}" placeholder="priority">
        </div>
        @csrf

        <button type="submit" class="btn btn-primary">更新</button>
    </form>
</body>
@stop
