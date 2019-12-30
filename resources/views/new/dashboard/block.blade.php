@extends('new.layouts.website')

@section('app-content')

<div class="container matop70">
    <div class="row">
        <div class="col-sm-2 col-xs-2 col-md-2 dinone">
            @include('new.dashboard.panel')
        </div>
        <div class="col-sm-12 col-xs-12 col-md-10">
            <div class="fs_name">
                <div class="fs_title">本月封鎖名單<h2>共{{ $blocks->count() }}筆資料</h2></div>
                <div class="fs_table">
                    <table>
                        <tr class="fs_tb">
                            <th style=" border-radius:5px 0 0 5px;">名稱</th>
                            <th>封鎖原因</th>
                            <th>開始日期</th>
                            <th style=" border-radius:0 5px 5px 0;">封鎖時間</th>
                        </tr>
                        @foreach ($blocks as $block)
                            <tr>
                                <td>{{ $user[$block->blocked_id]->name or "此會員不存在"}}</td>
                                <td>{{ $block->content or "無" }}</td>
                                <td>{{ $block->created_at}}</td>
                                <td>{{ $block->days or ""}}</td>
                            </tr>
                        @endforeach
                    </table>
                    <div class="fenye">
                        <a id="prePage" href="{{ $blocks->previousPageUrl() }}">上一頁</a>
                        <a id="nextPage" href="{{ $blocks->nextPageUrl() }}">下一頁</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop