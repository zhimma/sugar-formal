@include('partials.header')
@yield("pre-javascript")
@include('partials.scripts')
@yield("javascript")
<style>
.table > tbody > tr > td, .table > tbody > tr > th{
    vertical-align: middle;
}
</style>
<body style="padding: 15px;">
@include('partials.errors')
@include('partials.message')
@if (isset($errors))
    @if ($errors->count() > 0)
    @else
        <h1>發送站長訊息給{{ $user->name }}</h1>
        <form action="{{ route('admin/send', $user->id) }}" id='message' method='POST'>
            {!! csrf_field() !!}
            <input type="hidden" value="{{ $admin->id }}" name="admin_id">
            <textarea name="msg" class="form-control" cols="80" rows="5"></textarea><br>
            <button type='submit' class='text-white btn btn-primary'>送出</button>
        </form>
    @endif
@endif
</body>
