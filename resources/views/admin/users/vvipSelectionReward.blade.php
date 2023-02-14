@extends('admin.main')
@section('app-content')
<style>
.table > tbody > tr > td, .table > tbody > tr > th{
    vertical-align: middle;
}
.table > tbody > tr > th{
    text-align: center;
}
.form-check-input{
    margin-left: unset !important;
}

.sortable { list-style-type: none; margin: 0; padding: 0; }
.sortable li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; }
.sortable li span { margin-left: -1.3em; }
.handle{
    margin-top: 2px;
}
</style>

<body style="padding: 15px;">
<h1>VVIP 徵選活動管理</h1>
<h5>點選「暱稱」可查詢應徵名單</h5>

<table class="display table cell-border" id="data-table" width="100%">
    <thead>
    <tr>
        <th>ID</th>
        <th>暱稱</th>
        <th>Email</th>
        <th>徵選主題</th>
        <th>徵選條件</th>
        <th>驗證方式</th>
        <th>獎金發放</th>
        <th>核定人數</th>
        <th>單人費用設定</th>
        <th>活動到期日</th>
        <th>匯款末五碼</th>
        <th>備註</th>
        <th>申請狀態</th>
        <th>通知繳費</th>
        <th>異動時間</th>
        <th>申請時間</th>
{{--        <th>管理</th>--}}
    </tr>
    </thead>
    <tbody>
    </tbody>
    <tfoot>
    <tr>
        <th>ID</th>
        <th>暱稱</th>
        <th>Email</th>
        <th>徵選主題</th>
        <th>徵選條件</th>
        <th>驗證方式</th>
        <th>獎金發放</th>
        <th>核定人數</th>
        <th>單人費用設定</th>
        <th>活動到期日</th>
        <th>匯款末五碼</th>
        <th>備註</th>
        <th>申請狀態</th>
        <th>通知繳費</th>
        <th>異動時間</th>
        <th>申請時間</th>
{{--        <th>管理</th>--}}
    </tr>
    </tfoot>
</table>
</body>

{{--<script src="https://code.jquery.com/jquery-3.6.0.js"></script>--}}
<script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
{{--<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>--}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.2/css/bootstrap.css" integrity="sha512-Fik9pU5hBUfoYn2t6ApwzFypxHnCXco3i5u+xgHcBw7WFm0LI8umZ4dcZ7XYj9b9AXCQbll9Xre4dpzKh4nvAQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.2/css/bootstrap-responsive.css" integrity="sha512-4p9BaBwuA5E3w3mOrlv7yFHn6upnXQ4QbjZebGFhqGnM/hUHAFuR1SpRymnLhqWrWv9sGwPI0B6S6CUfHUuSaw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.2/js/bootstrap.min.js" integrity="sha512-28e47INXBDaAH0F91T8tup57lcH+iIqq9Fefp6/p+6cgF7RKnqIMSmZqZKceq7WWo9upYMBLMYyMsFq7zHGlug==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap-editable/css/bootstrap-editable.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap-editable/js/bootstrap-editable.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/6.2.10/css/tempus-dominus.css" integrity="sha512-rVtAwvz6BFSgOOmHkGnBcJH6T5NsA8HG2FFQ0vWZFCyCmChs4ZNZKk0B2WDzBK/sxDG0iVeC10JquS5cH5D/xQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/6.2.10/css/tempus-dominus.css" integrity="sha512-rVtAwvz6BFSgOOmHkGnBcJH6T5NsA8HG2FFQ0vWZFCyCmChs4ZNZKk0B2WDzBK/sxDG0iVeC10JquS5cH5D/xQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<script>

    function isChat(id, is_open) {
        window.open('/admin/users/message/record/' + id + '?from_advInfo=1' );

        setTimeout(function() {
            location.reload();
        }, 1500);
    }

    $.fn.editable.defaults.mode = 'inline';

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{csrf_token()}}'
        }
    });

    $('#data-table').on( 'click', 'tbody td:not(:first-child)', function (e) {

        $('.delete_key').show();

        $('.update_status').editable({
            url: "{{ route('vvipSelectionRewardApplyUpdate') }}",
            type: $(this).data('type'),
            pk: $(this).data('pk'),
            name: $(this).data('name'),
            title: $(this).data('title'),
            // value: $(this).data('value'),
            source:[{value: 0, text: "申請中"}, {value: 1, text: "通過"}, {value: 2, text: "不通過"}, {value: 3, text: "活動結束"}, {value: 4, text: "關閉"}],
            success: function(response, newValue) {
                if(response.success==false) {
                    alert(response.msg);
                    location.reload();
                }
            }
        });
        $('.update_status2').editable({
            url: "{{ route('vvipSelectionRewardApplyUpdate') }}",
            type: $(this).data('type'),
            pk: $(this).data('pk'),
            name: $(this).data('name'),
            title: $(this).data('title'),
            // value: $(this).data('value'),
            source:[{value: 0, text: "尚未通知"}, {value: 1, text: "已通知"}],
            success: function(response, newValue) {
                if(response.success==false) {
                    alert(response.msg);
                    location.reload();
                }
            }
        });

        $('.update').editable({
            url: "{{ route('vvipSelectionRewardApplyUpdate') }}",
            type: $(this).data('type'),
            pk: $(this).data('pk'),
            name: $(this).data('name'),
            value: $(this).data('value'),
            title: $(this).data('title')
        });

        $('.delete_key').on('click', function(e){
            if(confirm('確定要刪除?')){
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '{{ route('vvipSelectionRewardApplyDeleteKey') }}',
                    data: {
                        _token: '{{csrf_token()}}',
                        pk: $(this).data('pk'),
                        name: $(this).data('name'),
                        key: $(this).data('key'),
                    },
                    success: function(xhr, status, error){
                        console.log();
                        location.reload();
                    },

                });

            }

        });

        $('.add-data-condition').on('click', function(e){
            $('.add_group').remove();
           $('.s_'+$(this).data('pk')).append('<div class="control-group add_group">' +
               '<div class="editable-input" style="position: relative;">' +
               '<input type="text" class="input-medium" id="s_'+$(this).data('pk')+'" style="padding-right: 24px;">' +
               // '<span class="editable-clear-x" style="top:40%;"></span>' +
               '</div>' +
               '<div class="editable-buttons">' +
               '<button class="btn btn-primary editable-submit-inner-add" data-id="'+$(this).data('pk')+'" data-name="condition">' +
               '<i class="icon-ok icon-white"></i>' +
               '</button>' +
               '<button type="button" class="btn add-cancel">' +
               '<i class="icon-remove"></i>' +
               '</button>' +
               '</div>' +
               '</div>')
        });

        $('.add-data-identify_method').on('click', function(e){
            $('.add_group').remove();
            $('.i_'+$(this).data('pk')).append('<div class="control-group add_group">' +
                '<div class="editable-input" style="position: relative;">' +
                '<input type="text" class="input-medium" id="i_'+$(this).data('pk')+'" style="padding-right: 24px;">' +
                // '<span class="editable-clear-x" style="top:40%;"></span>' +
                '</div>' +
                '<div class="editable-buttons">' +
                '<button type="submit" class="btn btn-primary editable-submit-inner-add" data-id="'+$(this).data('pk')+'" data-name="identify_method">' +
                '<i class="icon-ok icon-white"></i>' +
                '</button>' +
                '<button type="button" class="btn add-cancel">' +
                '<i class="icon-remove"></i>' +
                '</button>' +
                '</div>' +
                '</div>')
        });

        $('.add-data-bonus_distribution').on('click', function(e){
            $('.add_group').remove();
            $('.b_'+$(this).data('pk')).append('<div class="control-group add_group">' +
                '<div class="editable-input" style="position: relative;">' +
                '<input type="text" class="input-medium" id="b_'+$(this).data('pk')+'" style="padding-right: 24px;">' +
                // '<span class="editable-clear-x" style="top:40%;"></span>' +
                '</div>' +
                '<div class="editable-buttons">' +
                '<button type="submit" class="btn btn-primary editable-submit-inner-add" data-id="'+$(this).data('pk')+'" data-name="bonus_distribution">' +
                '<i class="icon-ok icon-white"></i>' +
                '</button>' +
                '<button type="button" class="btn add-cancel">' +
                '<i class="icon-remove"></i>' +
                '</button>' +
                '</div>' +
                '</div>')
        });

        $('.editable-submit-inner-add').on('click', function(e){
            // e.stopPropagation();
            if($('.add_group').is(":visible")){
                let value;

                if($(this).data('name')=='condition'){
                    value = $('#s_'+$(this).data('id')).val();

                }else if($(this).data('name')=='identify_method'){
                    value = $('#i_'+$(this).data('id')).val();

                }else if($(this).data('name')=='bonus_distribution'){
                    value = $('#b_'+$(this).data('id')).val();
                }

                if(value == ''){
                    alert('尚未輸入');
                    return false;
                }
                else if($(this).data('name')){
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('vvipSelectionRewardApplyAddData') }}',
                        data: {
                            _token: '{{csrf_token()}}',
                            pk: $(this).data('id'),
                            value: value,
                            name: $(this).data('name'),
                        },
                        success: function(xhr, status, error){
                            $('.add_group').remove();
                            //alert(xhr.success)
                            console.log();
                            location.reload();
                        },

                    });
                }
                $('.add_group').remove();
            }
        });

        $('.add-cancel').on('click', function(e) {
            $('.add_group').remove();
        });

        $( ".sortable" ).sortable({
            handle: ".handle",
            invertSwap: true,
            revert: 100,
            placeholder: 'placeholder',
            start: function(event, ui) {
                let start_pos = ui.item.index();
                ui.item.data('start_pos', start_pos);
            },
            update: function(event, ui) {
                let index = ui.item.index();
                let start_pos = ui.item.data('start_pos');
                $.ajax({
                    type: 'POST',
                    url: '{{ route('vvipSelectionRewardApplyKeyUpdate') }}',
                    data: {
                        _token: '{{csrf_token()}}',
                        pk: $(this).data('pk'),
                        name: $(this).data('name'),
                        start_pos: start_pos,
                        end_pos: index,
                    },
                    success: function (xhr, status, error) {
                        console.log();
                    },
                });

            }
        });

    });


</script>

<link href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://legacy.datatables.net/extras/thirdparty/ColReorderWithResize/ColReorderWithResize.js"></script>



<style>
    thead th {
        border-right: 1px;
        border-style: double;
        border-color: #dddddd;
    }
    thead th:last-child {
        border-right: unset;
    }
    thead th:first-child {
        border-left: unset;
    }
</style>
<script type="text/javascript">
    $(document).ready( function () {

        $.noConflict();

        $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            autoWidth: true,
            sDom: "Rlfrtip",
            pageLength: 25,
            ajax: '{!! route('vvipSelectionReward/list') !!}',
            columns: [
                { data: 'user_id', name: 'vvip_selection_reward.user_id' },
                {
                    data: 'name',
                    name: 'users.name',
                    render: function(data,type,row,meta) {
                        return data ? '<a href="/admin/users/vvipSelectionRewardApplyList/'+row.id+'" target="_blank">' + data + '</a> ('+row.applyCounts+')' : '';
                    }
                },
                {
                    data: 'email',
                    name: 'users.email',
                    render: function(data,type,row,meta) {
                        let class_name, status_name;
                        if(row.is_admin_chat_channel_open==1){
                            class_name = 'btn-success';
                            status_name = '對話中';
                        }else{
                            class_name = 'btn-danger';
                            status_name = '開啟會員對話';
                        }
                        return data ? '<a href="/admin/users/advInfo/' + row.user_id + '" target="_blank">' + data + '</a><br><br>' +
                            '<a class="btn btn-dark" href="/admin/users/message/to/'+ row.user_id +'" target="_blank">撰寫站長訊息</a><br><br>' +
                            '<button class="btn ' + class_name + ' message_record_btn" onclick="isChat( '+row.user_id+', '+ !row.is_admin_chat_channel_open +')">'+status_name+'</button>': '';
                    }
                },
                {
                    data: 'title',
                    name: 'vvip_selection_reward.title',
                    render: function(data,type,row,meta) {
                        return data ? '<a href="javascript:void(0);" class="update" data-name="title" data-type="text" data-pk="'+row.id+'" data-title="輸入徵選主題">'+data+'</a>':'<a href="javascript:void(0);" class="update" data-name="title" data-type="text" data-pk="'+row.id+'" data-title="輸入徵選主題"></a>';
                    }
                },
                {
                    data: 'condition',
                    name: 'condition',
                    render: function(data,type,row,meta) {

                        let str='';
                        let dd = JSON.parse(data.replace(/&quot;/g, '"'));
                        // let lastKey = Object.keys(dd).pop();

                        str = '<ul class="sortable s_'+row.id+'" data-pk="'+row.id+'" data-name="condition">';
                        $.each(dd, function (key, value) {
                            str += '<li class="ui-state-default"><span class="handle ui-icon ui-icon-arrowthick-2-n-s"></span>'+ key +'.<a href="javascript:void(0);" class="update pk_'+row.id+'" data-name="condition" data-type="text" data-pk="'+row.id+'_'+key+'" data-title="輸入徵選條件" data-value="'+value+'">'+value+'</a> ' +
                                '<a href="javascript:void(0);" class="delete_key" data-name="condition" data-pk="'+row.id+'" data-key="'+ key +'" style="display: none;"><i class="icon-remove"></i></a></li>';
                        });
                        str += '</ul>';
                        str += '<botton type="button" class="btn add-data-condition" data-pk="'+row.id+'"><i class="icon-plus"></botton>';
                        return data ? str : '';
                    }
                },
                {
                    data: 'identify_method',
                    name: 'identify_method',
                    render: function(data,type,row,meta) {

                        let str='';
                        let dd = JSON.parse(data.replace(/&quot;/g, '"'));
                        // let lastKey = Object.keys(dd).pop();

                        str = '<ul class="sortable i_'+row.id+'" data-pk="'+row.id+'" data-name="identify_method">';
                        $.each(dd, function (key, value) {
                            str += '<li class="ui-state-default"><span class="handle ui-icon ui-icon-arrowthick-2-n-s"></span>'+ key +'.<a href="javascript:void(0);" class="update pk_'+row.id+'" data-name="identify_method" data-type="text" data-pk="'+row.id+'_'+key+'" data-title="輸入驗證方式" data-value="'+value+'">'+value+'</a> ' +
                                '<a href="javascript:void(0);" class="delete_key" data-name="identify_method" data-pk="'+row.id+'" data-key="'+ key +'" style="display: none;"><i class="icon-remove"></i></a></li>';
                        });
                        str += '</ul>';
                        str += '<botton type="button" class="btn add-data-identify_method" data-pk="'+row.id+'"><i class="icon-plus"></botton>';
                        return data ? str : '';
                    }
                },
                {
                    data: 'bonus_distribution',
                    name: 'bonus_distribution',
                    render: function(data,type,row,meta) {

                        let str='';
                        let dd = JSON.parse(data.replace(/&quot;/g, '"'));
                        // let lastKey = Object.keys(dd).pop();

                        str = '<ul class="sortable b_'+row.id+'" data-pk="'+row.id+'" data-name="bonus_distribution">';
                        $.each(dd, function (key, value) {
                            str += '<li class="ui-state-default"><span class="handle ui-icon ui-icon-arrowthick-2-n-s"></span>'+ key +'.<a href="javascript:void(0);" class="update pk_'+row.id+'" data-name="bonus_distribution" data-type="text" data-pk="'+row.id+'_'+key+'" data-title="輸入獎金發放" data-value="'+value+'">'+value+'</a> ' +
                                '<a href="javascript:void(0);" class="delete_key" data-name="bonus_distribution" data-pk="'+row.id+'" data-key="'+ key +'" style="display: none;"><i class="icon-remove"></i></a></li>';
                        });
                        str += '</ul>';
                        str += '<botton type="button" class="btn add-data-bonus_distribution" data-pk="'+row.id+'"><i class="icon-plus"></botton>';
                        return data ? str : '';
                    }
                },
                {
                    data: 'limit',
                    name: 'vvip_selection_reward.limit',
                    render: function(data,type,row,meta) {
                        return data ? '<a href="javascript:void(0);" class="update" data-name="limit" data-type="number" data-pk="'+row.id+'" data-title="輸入人數上限">'+data+'</a>':'<a href="javascript:void(0);" class="update" data-name="limit" data-type="number" data-pk="'+row.id+'" data-title="輸入人數上限"></a>';
                    }
                },
                {
                    data: 'per_person_price',
                    name: 'vvip_selection_reward.per_person_price',
                    render: function(data,type,row,meta) {
                        return data ? '<a href="javascript:void(0);" class="update" data-name="per_person_price" data-type="number" data-pk="'+row.id+'" data-title="輸入">'+data+'</a>':'<a href="javascript:void(0);" class="update" data-name="per_person_price" data-type="number" data-pk="'+row.id+'" data-title="輸入"></a>';
                    }
                },
                {
                    data: 'expire_date',
                    name: 'vvip_selection_reward.expire_date',
                    render: function(data,type,row,meta) {
                        return data ? '<a href="javascript:void(0);" class="update" data-type="date" data-name="expire_date" data-viewformat="yyyy-mm-dd" data-placement="bottom" data-pk="'+row.id+'" data-title="輸入活動到期日">'+moment(data).format("YYYY-MM-DD")+'</a>':'<a href="javascript:void(0);" class="update" data-type="date" data-name="expire_date" data-viewformat="yyyy-mm-dd" data-placement="bottom" data-pk="'+row.id+'" data-title="輸入活動到期日"></a>';
                    }
                },
                {
                    data: 'user_note',
                    name: 'vvip_selection_reward.user_note',
                    // render: function(data,type,row,meta) {
                    //     // let text = data.split('<br>');
                    //     // let str='<span>';
                    //     // $.each(text, function (key, value) {
                    //     //     str += value +'\\n';
                    //     // });
                    //     // str += '</span>';
                    //     let text = row.user_note.replace('<br>', '')
                    //     return data ? text : '';
                    // }
                },
                {
                    data: 'note',
                    name: 'vvip_selection_reward.note',
                    render: function(data,type,row,meta) {
                        return data ? '<a href="javascript:void(0);" class="update" data-type="textarea" data-name="note" data-pk="'+row.id+'" data-title="輸入活動到期日" data-value="'+data+'">'+data.replace(/\n/g,'<br/>')+'</a>':'<a href="javascript:void(0);" class="update" data-type="textarea" data-name="note" data-pk="'+row.id+'" data-title="輸入活動到期日"></a>';
                    }
                },
                {
                    data: 'status',
                    name: 'vvip_selection_reward.status',
                    render: function(data,type,row,meta) {
                        let text;
                        switch(data) {
                            case 0:
                                text = '申請中';
                                break;
                            case 1:
                                text = '通過';
                                break;
                            case 2:
                                text = '不通過';
                                break;
                            case 3:
                                text = '活動結束';
                                break;
                            case 4:
                                text = '關閉';
                                break;
                        }
                        return '<a href="javascript:void(0);" class="update_status" data-type="select" data-name="status" data-pk="'+row.id+'" data-value="'+data+'" data-title="輸入">'+text+'</a>';

                    }
                },
                {
                    data: 'notice_status',
                    name: 'vvip_selection_reward.notice_status',
                    render: function(data,type,row,meta) {
                        let text;
                        switch(data) {
                            case 0:
                                text = '尚未通知';
                                break;
                            case 1:
                                text = '已通知';
                                break;
                        }
                        return '<a href="javascript:void(0);" class="update_status2" data-type="select" data-name="notice_status" data-pk="'+row.id+'" data-value="'+data+'" data-title="輸入">'+text+'</a>';

                    }
                },
                {
                    data: 'updated_at',
                    name: 'vvip_selection_reward.updated_at',
                    render: function(data,type,row,meta) {
                        return data ? moment(data).format("YYYY-MM-DD HH:mm") : '';
                    }
                },
                {
                    data: 'created_at',
                    name: 'vvip_selection_reward.created_at',
                    render: function(data,type,row,meta) {
                        // data.format("YYYY-MM-DD H:i");
                        return data ? moment(data).format("YYYY-MM-DD HH:mm") : '';
                    }
                },
                // {
                //     data: 'user_id',
                //     name: 'user_id',
                //     render: function(data,type,row,meta) {
                //         // data.format("YYYY-MM-DD H:i");
                //         return data ? '<a href="advInfo/editPic_sendMsg/'+data+'" class="text-white btn btn-primary" target="_blank">照片&發訊息</a>' : '';
                //     }
                // },

            ]
        });
    });
</script>
@stop
</html>