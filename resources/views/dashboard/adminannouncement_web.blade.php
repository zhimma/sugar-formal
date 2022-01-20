@extends('layouts.master')

@section('app-content')

<?php $icc = 1; ?>

<div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">
                    網站公告-本月封鎖名單
                </h3>
               
            </div>
        </div>
</div>
<div class="m-portlet__body">
    <div class="m-widget3">
    共{{ $users->count() }}筆資料
        <div class="m-widget3__item">
            <div class="m-widget3__header">
                <div class="m-widget3__info">
                    
                </div>
            </div>
            <div class="m-widget3__body">
            <table class='table table-bordered table-hover'>
                <tr>
                    <th>名稱</th>
                    <th>封鎖時間</th>
                    <th>封鎖原因</th>
                </tr>
                @forelse ($users as $userBanned)
                <tr>
                    <td>{{ $userBanned->name}}</td>
                    <td>{{ $userBanned->created_at }}</td>
                    <td>{{ $userBanned->reason }}</td>
                </tr>
                @empty
                <tr>
                找不到資料
                </tr>
                @endforelse
            </table>
            </div>
        </div>

    </div>
</div>

@stop
