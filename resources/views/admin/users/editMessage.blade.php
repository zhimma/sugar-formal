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
        @if(isset($data))
            <h1>將{{ $data['originalMessage'] }}取代為...</h1>
            <form action="{{ route('users/message/edit') }}" id='message' method='POST'>
                {!! csrf_field() !!}
                <input type="text" name="replace">
                <input type="hidden" value="{{ $data['admin']->id }}" name="admin_id">
                <input type="hidden" value="{{ $data['originalMessage'] }}" name="originalMessage">
                @foreach( $data['ids'] as $id )
                    <input type="hidden" value="{{ $id }}" name="msg_id[]">
                @endforeach
                <button type='submit' class='text-white btn btn-primary'>送出</button>
            </form>
        @endif
    @endif
@endif
</body>
