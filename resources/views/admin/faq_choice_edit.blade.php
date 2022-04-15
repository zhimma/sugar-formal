@extends('admin.main')
@section('app-content')
    <link rel="stylesheet" href="{{asset('/css/faq_admin_common.css')}}">
    <style>
        .table > tbody > tr > td, .table > tbody > tr > th{
            vertical-align: middle;
        }
        h3{
            text-align: left;
        }
        .is_new_block {position:relative;top:20px;color:black;font-weight:normal;}
        .cvvs_box {margin-top:5px;}
        .ainput {width:80px;} 

        #table {width:100%;margin:10px;}
        #table th {width:20%;white-space:nowrap;text-align:center;}
        #table td {text-align:left;}
        #table td label {margin-right:20px;}
        #table th,#table td {padding:10px;}
        #faq_group_engroupvip_required_block {margin-top:-15px;}
        #faq_group_engroupvip_required_block select {
            width:0;
            height:0;
            border-color:transparent;
        }

        #is_answer_off_explain {margin-left:10px;}
    </style>
    <body style="padding: 15px;">
    <h1>FAQ修改選項</h1>
    <h2>修改「{{$service->getQuertionTitleLayout()}}」的選項</h2>
    <form action="{{ route('admin/faq_choice/save',$service->question_entry()->id) }}" method="post">
        {!! csrf_field() !!}
        <table class="table-bordered table-hover center-block text-center" id="table">
            <tr>
                <th>選項名稱</th> 
                <td>
                    <input type="text" name="name" value="{{$entry->name??null}}" required />
                </td>
            </tr>
            <tr>
                <th>正解</th>
                <td>
                    <input type="checkbox" name="is_answer" value="1" {{$service->getFormChkEditAssign(1,$entry->is_answer,request()->is_answer)}} {{$service->getIsAnswerOffEltAttr($entry->id)}} />
                    <span id="is_answer_off_explain">{{$service->getIsAnswerOffEltExplain($entry->id)}}</span>
                </td>
            </tr>
            <tr>
                <th>操作</th>
                <td>
                    
                    <input type="submit" class='text-white btn btn-success' value="送出" >
                    <input type="reset"  class='text-white btn btn-danger' value="復原">                
                    <input type="hidden" name="id" value="{{ $entry->id }}">
                </td>
            </tr>
        </table>
    </form>    
    <a href="{{ route('admin/faq_choice',$entry->faq_question->id) }}" class="text-white btn btn-primary">返回選項</a>    
    </body>
    <script>
    </script>
@stop
