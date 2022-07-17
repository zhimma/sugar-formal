@extends('admin.main')
@section('app-content')

<head>
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
        crossorigin="anonymous"></script>
</head>
<body style="padding: 15px;">
    <h1>Feature Flags Create</h1>
    <form action="/admin/global/feature_flags/create" method="POST">
        <div class="form-group">
        <label for="exampleInputEmail1">Feature</label>
        <input type="text" class="form-control" id="feature" name="feature" placeholder="feature">
        </div>
        <div class="form-group">
        <label for="exampleInputPassword1">Priority</label>
        <input type="number" class="form-control" id="priority" name="priority" placeholder="priority">
        </div>
        @csrf

        <button type="submit" class="btn btn-primary">新增</button>
    </form>
</body>
@stop
