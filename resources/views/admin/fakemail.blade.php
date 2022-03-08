@extends('admin.main')
@section('app-content')
    <body style="padding: 15px;">
        <h1>寄送統計沖洗郵件</h1>
        <br>
        <form id='form' method='post' action="{{route('sendFakeMail')}}">
            {!! csrf_field() !!}
            <table class="table-bordered table-hover center-block text-center" style="width: 100%;" id="table">
                <tr>
                    <th class="text-center">Email</th>
                    <th class="text-center">寄送次數</th>
                    <th class="text-center">內容</th>
                </tr>
                <tr class="template">
                        <td><input name='account'></input>@<input name='net'></input></td>
                        <td><input name='repeat'></input></td>
                        <td><input name='content'></input></td>
                </tr>
            <table>
            <br>
            <button type='submit'>寄出</button>
            <br>
            <br>
            寄送次數:
            <br>
            未填寫將寄送至XXX@XXX
            <br>
            0 將寄送至XXX+0@XXX
            <br>
            1 將寄送至XXX+0@XXX , XXX+1@XXX
            <br>
            2 將寄送至XXX+0@XXX , XXX+1@XXX , XXX+2@XXX
            <br>
            以此類推
            <br>
        </form>
    </body>
@stop