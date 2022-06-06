@extends('new.layouts.website')
@section('app-content')
<div>
    <h4>連動完成，現在可以關閉這個視窗，並回到原網站重新整理即可。</h4>
</div>
<script>
    $.ajax({
        url: '{{ route('lineNotifyProcess') }}',
        type: 'POST',
        data: {
            '_token': '{{ csrf_token() }}',
            'code': '{{ $data->code }}'
        },
        success: function(data) {
            console.log(data);
        }
    });
</script>
@stop