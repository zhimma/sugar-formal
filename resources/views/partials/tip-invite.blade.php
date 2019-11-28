@section('tip-invite')

@if($user->engroup == 1)
<form class="m-nav__link" action="{{ route('chatpay_ec') }}" method=post>
    <input type="hidden" name="_token" value="{{ csrf_token() }}" >
    <input type="hidden" name="userId" value="{{$user->id}}">
    <input type="hidden" name="to" value="@if(isset($to)){{$to->id}}@endif">
    <button type="submit" style="background: none; border: none; padding: 0">
        <i class="m-nav__link-icon flaticon-profile"></i>
        <span class="m-nav__link-text">車馬費邀請(管道一)</span>
    </button>&nbsp;&nbsp;
</form>
<form action=<?php echo Config::get('social.payment.actionURL') ?> class="m-nav__link" method="POST" id="form1">
    <input type="hidden" name="_token" value="{{ csrf_token() }}" >
    <input type="hidden" name="userId" value="{{$user->id}}">
    <input type="hidden" name="to" value="@if(isset($to)){{$to->id}}@endif">
    <input type=hidden name="MerchantNumber" value="761404">
    <input type=hidden name="OrderNumber" value="<?php echo $orderNumber; ?>">
    <input type=hidden name="OrgOrderNumber" value="SG-車馬費({{$user->id}})">
    <input type=hidden name="ApproveFlag" value="1">
    <input type=hidden name="DepositFlag" value="1">
    <input type=hidden name="iphonepage" value="0">
    <input type=hidden name="Amount" value="1">
    <input type=hidden name="op" value="AcceptPayment">
    <input type=hidden name="checksum" value="<?php print md5("761404".$orderNumber.$code."1"); ?>">
    <input type=hidden name="ReturnURL" value="{{ route('chatpay') }}">
    <button type="submit" style="background: none; border: none; padding: 0">
        <i class="m-nav__link-icon flaticon-profile"></i>
        <span class="m-nav__link-text">車馬費邀請(管道二)</span>
    </button>
</form>
<script>
    $('#form1').submit(function() {
        $.ajax({
            type: 'POST',
            url: '{{ route('chatpayLog') }}',
            data: { user_id : "{{ $user->id }}", to_id : '@if(isset($cur)){{ $cur->id }}@endif', _token:"{{ csrf_token() }}"},
            success: function(xhr, status, error){
                console.log(xhr);
                console.log(error);
            },
            error: function(xhr, status, error){
                console.log(xhr);
                console.log(status);
                console.log(error);
            }
        });
        return true;// return false to cancel form action
    });
</script>
@endif

@show
