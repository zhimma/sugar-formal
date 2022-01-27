@if(isset($_GET['status']))
    @if($_GET['status']=='false')
        <span style="color:red">資料輸入錯誤，請重新驗證</span>
    @elseif($_GET['status']=='age_failed')
        <span style="color:red">年齡未滿18歲，不得進行驗證</span>
    @endif
@endif
<form id="advance_auth_form" name="advance_auth_form" class="m-form m-form--fit" method="POST" action="/advance_auth_email_process">
    <input type="hidden" name="_token" value="{{ csrf_token() }}" >
    <input type="hidden" name="userId" value="{{$user->id}}">

    <div class="de_input mtb20">
        <div class="zybg_new02 @if(in_array('i',Session::get('error_code')??[])) has_error @endif">                                       
            <input name="email" id="email" type="text" class="xy_input xy_left wbd" placeholder="請輸入校內edu.tw網域的email"  autocomplete="off">
        </div>
        <button type="text" class="n_zybg_right btn_yz advanceAuthSubmit" onclick="tab_confirm_send();return false;">驗證</button>
    </div>
</form>
	