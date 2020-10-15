@extends('admin.main')
@section('app-content')
<body style="padding: 15px;">
    <h1>站長審核 - 修改暱稱申請</h1>
    <table class="table-bordered table-hover center-block table" id="table">
        <thead>
            <tr>
                <th scope="col">email</th>
                <th scope="col">暱稱</th>
                <th scope="col">VIP</th>
                <th scope="col">性別</th>
                <th scope="col">被檢舉分數</th>
                <th scope="col">修改原資料</th>
                <th scope="col">修改後資料</th>
                <th scope="col">申請修改原因</th>
                <th scope="col">不通過原因</th>
                <th scope="col">審核狀態</th>
                <th scope="col">申請時間</th>
                <th scope="col">審核時間</th>
            </tr>
        </thead>
        <tbody>
        @foreach($data as $row)
            @php
                $Vip = \App\Models\Vip::vip_diamond($row->id);
            @endphp
            <tr>
                <td scope="row"><a href="users/advInfo/{{$row->user_id}}" target="_blank">{{$row->email}}</a></td>
                <td>{{$row->name}}</td>
                <td>
                    @if($row->isVip()==1)
                        @if($Vip=='diamond_black')
                            <img src="/img/diamond_black.png" style="height: 1.5rem;">
                        @else
                            @for($z = 0; $z < $Vip; $z++)
                                <img src="/img/diamond.png" style="height: 1.5rem;">
                            @endfor
                        @endif
                    @endif
                </td>
                <td>@if($row->engroup==1)男@else女@endif</td>
                <td>{{$row->WarnedScore()}}</td>
                <td>{{$row->before_change_name}}</td>
                <td>{{$row->change_name}}</td>
                <td>{{$row->reason}}</td>
                <td>
                    @if($row->status == 0)
{{--                        <a href="javascript:void(0)" id="input_reject" data-id="{{$row->id}}" data-toggle="modal" data-target="#exampleModal" class="input_reject">--}}
{{--                            <span class="reject_content_{{$row->id}}">請輸入原因</span>--}}
{{--                        </a>--}}
                        <input class="form-control" type="text" name="reject_content_{{$row->user_id}}" id="reject_content_{{$row->user_id}}" placeholder="請輸入原因">
                    @else
                        {{ $row->reject_content }}
                    @endif
                </td>
                <td>@switch($row->status)
                        @case(0)
                        <button type="button" class="btn btn-primary" onclick="checkAction({{$row->user_id}},1)" >通過</button>
                        <button type="button" class="btn btn-danger reject_button" id="reject_button" data-id="{{$row->user_id}}" >不通過</button>
                        @break
                        @case(1)
                        通過
                        @break
                        @case(2)
                        不通過
                        @break
                    @endswitch
                </td>
                <td>{{$row->created_at}}</td>
                <td>{{$row->passed_at}}</td>


            </tr>
        @endforeach
        </tbody>
    </table>

    <!-- Modal -->
{{--    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">--}}
{{--        <div class="modal-dialog" role="document">--}}
{{--            <div class="modal-content">--}}
{{--                <div class="modal-header">--}}
{{--                    <h5 class="modal-title" id="exampleModalLabel"></h5>--}}
{{--                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                        <span aria-hidden="true">&times;</span>--}}
{{--                    </button>--}}
{{--                </div>--}}
{{--                <div class="modal-body">--}}
{{--                    <form id="reject_form">--}}
{{--                        <div class="form-group">--}}
{{--                            <label for="recipient-name" class="col-form-label">請輸入原因:</label>--}}
{{--                            <textarea class="form-control" name="reject_content"></textarea>--}}
{{--                        </div>--}}
{{--                    </form>--}}
{{--                </div>--}}
{{--                <div class="modal-footer">--}}
{{--                    <button type="button" class="btn btn-secondary" data-dismiss="modal">X</button>--}}
{{--                    <button type="button" class="btn btn-danger reject_submit" data-id="" id="reject_submit">送出</button>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

</body>
    <script>
        function checkAction(id, status){
            $.ajax({
                type: 'POST',
                url: "/admin/checkNameChange",
                data:{
                    _token: '{{csrf_token()}}',
                    id: id,
                    status: status,
                },
                dataType:"json",
                success: function(res){
                    location.reload();
            }});
        }

        // $('.input_reject').on('click', function(){
        //     var id = $(this).data('id');
        //     $('.reject_submit').data('id',id); //setter
        // });
        //
        // $('#reject_submit').on('click', function(){
        //     $('.reject_content_' + $('#reject_submit').data('id')).text($('textarea[name=reject_content]').val());
        //     $('#exampleModal').modal('hide');
        // });

        $('.reject_button').on('click', function(){

            var reject_content;
            // if( $('.reject_content_'+ $(this).data('id')).text() != '請輸入原因'){
            //     reject_content = $('.reject_content_'+ $(this).data('id')).text();
            // }

            // reject_content = $('input[name=reject_content_'+ $(this).data('id') +']').val();
            reject_content = $('#reject_content_'+ $(this).data('id')).val();
            $.ajax({
                type: 'POST',
                url: "/admin/checkNameChange",
                data:{
                    _token: '{{csrf_token()}}',
                    id: $(this).data('id'),
                    status: 2,
                    reject_content: reject_content,
                },
                dataType:"json",
                success: function(res){
                    location.reload();
                }
            });
        });

    </script>
@stop
